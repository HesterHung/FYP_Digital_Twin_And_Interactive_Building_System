<?php
header("Access-Control-Allow-Origin: http://localhost:5500");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

require_once 'config.php';

// Check User Session
$user_id = $_SESSION['user_id'] ?? 1;

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(["status" => "error", "message" => "Method Not Allowed"]);
    exit;
}

$room_id = $_POST['room_id'] ?? null;
$description = $_POST['description'] ?? '';
// is_public could be boolean true/false or '1'/'0'
$is_public = (isset($_POST['is_public']) && ($_POST['is_public'] == '1' || $_POST['is_public'] === 'true')) ? 1 : 0;
// is_shared toggle
$is_shared = (isset($_POST['is_shared']) && ($_POST['is_shared'] == '1' || $_POST['is_shared'] === 'true')) ? 1 : 0;

$category_ids = isset($_POST['category_ids']) ? $_POST['category_ids'] : [];
$legacy_category_id = (!empty($category_ids)) ? intval($category_ids[0]) : ((isset($_POST['category_id']) && $_POST['category_id'] !== '') ? intval($_POST['category_id']) : null);
if (empty($category_ids) && $legacy_category_id) {
    // Falback if frontend still sends single 'category_id'
    $category_ids = [$legacy_category_id];
}
$public_start = (isset($_POST['public_start']) && $_POST['public_start'] !== '') ? $_POST['public_start'] : null;
$public_end = (isset($_POST['public_end']) && $_POST['public_end'] !== '') ? $_POST['public_end'] : null;


if (!$room_id) {
    echo json_encode(["status" => "error", "message" => "Room ID missing."]);
    exit;
}

// 1. Verify Ownership & Get Current Images
$check = $conn->prepare("SELECT id, preview_image, poster_image FROM room_sessions WHERE id = ? AND owner_id = ?");
$check->bind_param("ii", $room_id, $user_id);
$check->execute();
$currentData = $check->get_result()->fetch_assoc();

if (!$currentData) {
    echo json_encode(["status" => "error", "message" => "Not authorized."]);
    exit;
}

// Prepare to cleanup overridden files
$filesToDelete = [];

// 2. Handle File Upload (Preview Image)
$preview_path = null;
$store_path = null;

if (isset($_FILES['preview_image']) && $_FILES['preview_image']['error'] === UPLOAD_ERR_OK) {
    // ... [existing upload logic] ...
    $uploadDir = 'uploads/room_previews/';
    if (!file_exists($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }
    
    $fileInfo = pathinfo($_FILES['preview_image']['name']);
    $ext = strtolower($fileInfo['extension']);
    
    // Validate Extension
    $allowed = ['jpg', 'jpeg', 'png', 'gif'];
    if (!in_array($ext, $allowed)) {
         echo json_encode(["status" => "error", "message" => "Invalid image format for preview image."]);
         exit;
    }
    
    // Generate Name: preview_roomID_timestamp.ext
    $newFileName = "preview_{$room_id}_" . time() . ".{$ext}";
    $targetPath = $uploadDir . $newFileName;
    
    if (move_uploaded_file($_FILES['preview_image']['tmp_name'], $targetPath)) {
        $store_path = 'api/' . $targetPath;
        $preview_path = $store_path;
        // Mark old for deletion
        if (!empty($currentData['preview_image'])) $filesToDelete[] = $currentData['preview_image'];
    } else {
         echo json_encode(["status" => "error", "message" => "Failed to move uploaded preview file."]);
         exit;
    }
}

// 2.5 Handle File Upload (Poster Image)
$poster_store_path = null;
$poster_path = null;

if (isset($_FILES['poster_image']) && $_FILES['poster_image']['error'] === UPLOAD_ERR_OK) {
    $uploadDir = 'uploads/room_posters/';
    if (!file_exists($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }
    
    $fileInfo = pathinfo($_FILES['poster_image']['name']);
    $ext = strtolower($fileInfo['extension']);
    
    $allowed = ['jpg', 'jpeg', 'png', 'gif'];
    if (!in_array($ext, $allowed)) {
         echo json_encode(["status" => "error", "message" => "Invalid image format for poster image."]);
         exit;
    }
    
    // Generate Name: poster_roomID_timestamp.ext
    $newFileName = "poster_{$room_id}_" . time() . ".{$ext}";
    $targetPath = $uploadDir . $newFileName;
    
    if (move_uploaded_file($_FILES['poster_image']['tmp_name'], $targetPath)) {
        $poster_store_path = 'api/' . $targetPath;
        $poster_path = $poster_store_path;
        // Mark old for deletion
        if (!empty($currentData['poster_image'])) $filesToDelete[] = $currentData['poster_image'];
    } else {
         echo json_encode(["status" => "error", "message" => "Failed to move uploaded poster file."]);
         exit;
    }
} else if (isset($_POST['delete_poster']) && ($_POST['delete_poster'] == '1' || $_POST['delete_poster'] === 'true')) {
    // Explicit Delete: Mark old for deletion
    if (!empty($currentData['poster_image'])) $filesToDelete[] = $currentData['poster_image'];
}

// 3. Update Database
try {
    // Ensure columns exist (Auto-Migration for new fields)
    $cols = [
        'is_shared' => 'TINYINT(1) NOT NULL DEFAULT 0',
        'category_id' => 'INT DEFAULT NULL',
        'public_start' => 'DATETIME DEFAULT NULL',
        'public_end' => 'DATETIME DEFAULT NULL',
        'poster_image' => 'VARCHAR(255) DEFAULT NULL'
    ];
    foreach ($cols as $col => $def) {
         $checkCol = $conn->query("SHOW COLUMNS FROM room_sessions LIKE '$col'");
         if ($checkCol->num_rows == 0) {
             $conn->query("ALTER TABLE room_sessions ADD COLUMN $col $def");
         }
    }

    // Ensure room_category_relations table exists
    $checkTable = $conn->query("SHOW TABLES LIKE 'room_category_relations'");
    if ($checkTable->num_rows == 0) {
        $conn->query("CREATE TABLE room_category_relations (
            room_id INT,
            category_id INT,
            PRIMARY KEY(room_id, category_id)
        )");
        // Backfill
        $conn->query("INSERT IGNORE INTO room_category_relations (room_id, category_id) 
                      SELECT id, category_id FROM room_sessions WHERE category_id IS NOT NULL");
    }

    // Build UPDATE query dynamically to handle either, both, or neither image
    $updateFields = ["is_public = ?", "is_shared = ?", "description = ?", "category_id = ?", "public_start = ?", "public_end = ?"];
    $bindTypes = "iisiss";
    $bindValues = [$is_public, $is_shared, $description, $legacy_category_id, $public_start, $public_end];

    if ($store_path) {
        $updateFields[] = "preview_image = ?";
        $bindTypes .= "s";
        $bindValues[] = $store_path;
    }
    if ($poster_store_path) {
        $updateFields[] = "poster_image = ?";
        $bindTypes .= "s";
        $bindValues[] = $poster_store_path;
    } elseif (isset($_POST['delete_poster']) && ($_POST['delete_poster'] == '1' || $_POST['delete_poster'] === 'true')) {
        $updateFields[] = "poster_image = NULL";
    }

    $sql = "UPDATE room_sessions SET " . implode(", ", $updateFields) . " WHERE id = ?";
    $bindTypes .= "i";
    $bindValues[] = $room_id;

    $stmt = $conn->prepare($sql);
    $stmt->bind_param($bindTypes, ...$bindValues);

    if ($stmt->execute()) {
        // Update category relations
        // Clear existing
        $del = $conn->prepare("DELETE FROM room_category_relations WHERE room_id = ?");
        $del->bind_param("i", $room_id);
        $del->execute();

        // Insert new relations
        if (!empty($category_ids)) {
            $ins = $conn->prepare("INSERT IGNORE INTO room_category_relations (room_id, category_id) VALUES (?, ?)");
            foreach ($category_ids as $cid) {
                $cid_int = intval($cid);
                $ins->bind_param("ii", $room_id, $cid_int);
                $ins->execute();
            }
        }
        
        // Clean Up Old Files
        foreach ($filesToDelete as $oldFile) {
            $local = str_replace('api/', '', $oldFile);
            if (file_exists($local)) unlink($local);
        }

        echo json_encode(["status" => "success", "message" => "Public settings updated.", "preview_path" => $store_path, "poster_path" => $poster_store_path]);
    } else {
        throw new Exception($stmt->error);
    }
} catch (Exception $e) {
    echo json_encode(["status" => "error", "message" => "DB Error: " . $e->getMessage()]);
}
?>