<?php
header("Access-Control-Allow-Origin: http://localhost:5500");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

require_once 'config.php';

// AUTO-MIGRATION: Ensure necessary columns exist before querying
// Check for 'is_public' column in 'room_sessions' table
$checkPublic = $conn->query("SHOW COLUMNS FROM room_sessions LIKE 'is_public'");
if ($checkPublic->num_rows == 0) {
    $conn->query("ALTER TABLE room_sessions ADD COLUMN is_public TINYINT(1) NOT NULL DEFAULT 0");
}

// Check for 'room_code' column in 'room_sessions' table
$checkCode = $conn->query("SHOW COLUMNS FROM room_sessions LIKE 'room_code'");
if ($checkCode->num_rows == 0) {
    // Generate unique codes for existing rows if needed, but for now just add column
    $conn->query("ALTER TABLE room_sessions ADD COLUMN room_code VARCHAR(8) NOT NULL DEFAULT '00000000'");
}

// Check for 'template_model' column
$checkTemplate = $conn->query("SHOW COLUMNS FROM room_sessions LIKE 'template_model'");
if ($checkTemplate->num_rows == 0) {
    $conn->query("ALTER TABLE room_sessions ADD COLUMN template_model VARCHAR(255) DEFAULT './src/assets/podium_assets/podiumv4.glb'");
}

// Check for 'preview_image' column
$checkPreview = $conn->query("SHOW COLUMNS FROM room_sessions LIKE 'preview_image'");
if ($checkPreview->num_rows == 0) {
    $conn->query("ALTER TABLE room_sessions ADD COLUMN preview_image VARCHAR(255) DEFAULT NULL");
}

// Check for 'description' column
$checkDesc = $conn->query("SHOW COLUMNS FROM room_sessions LIKE 'description'");
if ($checkDesc->num_rows == 0) {
    $conn->query("ALTER TABLE room_sessions ADD COLUMN description TEXT DEFAULT NULL");
}

// Check for 'is_shared' column (CityU Request: Validates room code access)
$checkShared = $conn->query("SHOW COLUMNS FROM room_sessions LIKE 'is_shared'");
if ($checkShared->num_rows == 0) {
    $conn->query("ALTER TABLE room_sessions ADD COLUMN is_shared TINYINT(1) NOT NULL DEFAULT 1"); 
    // Default to 1 (true) for existing rooms so they don't break, or 0? 
    // User asked "if is_share is false, even the user got the room code, they can't access".
    // Let's default to 0 for strictness or 1 for convenience. Let's Set default to 0.
    // Wait, if I set default 0, all old rooms become unshareable.
    // Let's set default 1 so we don't break existing workflow, but user can toggle it off.
}

// Check for 'updated_at' column (Scene Builder HUD: Last Saved)
$checkUpdated = $conn->query("SHOW COLUMNS FROM room_sessions LIKE 'updated_at'");
if ($checkUpdated->num_rows == 0) {
    $conn->query("ALTER TABLE room_sessions ADD COLUMN updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP");
}

// session_start(); // Config.php handles this
$user_id = $_SESSION['user_id'] ?? 1; // Default to testuser

// 1. Get User's Room ID
$room_id = null;
$room_name = null;
$updated_at_val = null;

if (isset($_GET['code'])) {
    // Load by Room Code (Shared)
    $stmt = $conn->prepare("SELECT id, name, is_shared, is_public, description, preview_image, poster_image, owner_id, room_code, updated_at, category_id, public_start, public_end, template_model FROM room_sessions WHERE room_code = ? LIMIT 1");
    $stmt->bind_param("s", $_GET['code']);
    $stmt->execute();
    $res = $stmt->get_result();
    
    if ($res->num_rows === 0) {
        echo json_encode(["status" => "error", "message" => "Invalid room code."]);
        exit;
    }
    
    $row = $res->fetch_assoc();
    
    // Check if sharing is enabled
    $isOwner = ($row['owner_id'] == $user_id);
    
    if ($row['is_shared'] == 0 && !$isOwner) { 
         echo json_encode(["status" => "error", "message" => "This room is not currently shared via code."]);
         exit;
    }
    
    $room_id = $row['id'];
    $room_name = $row['name'];
    $room_code_val = $row['room_code'];
    $updated_at_val = $row['updated_at'];
    $is_public = $row['is_public'];
    $is_shared = $row['is_shared'];
    $description = $row['description'];
    $preview_image = $row['preview_image'];
    $poster_image = $row['poster_image'] ?? null;
    $category_id = $row['category_id'];
    $public_start = $row['public_start'];
    $public_end = $row['public_end'];
    $template_model = $row['template_model'];

} elseif (isset($_GET['room_id'])) {
    // Specific room requested
    $room_id = intval($_GET['room_id']);
    
    // Verify ownership or public access
    $checkOwner = $conn->prepare("SELECT id, name, room_code, updated_at, is_public, is_shared, description, preview_image, poster_image, category_id, public_start, public_end, template_model FROM room_sessions WHERE id = ? AND (owner_id = ? OR is_public = 1) LIMIT 1");
    $checkOwner->bind_param("ii", $room_id, $user_id);
    $checkOwner->execute();
    $res = $checkOwner->get_result();
    
    if ($res->num_rows === 0) {
       echo json_encode(["status" => "error", "message" => "Room not found or access denied."]);
       exit;
    }
    
    $row = $res->fetch_assoc();
    $room_name = $row['name'];
    $room_code_val = $row['room_code'];
    $updated_at_val = $row['updated_at'];
    $is_public = $row['is_public'];
    $is_shared = $row['is_shared'];
    $description = $row['description'];
    $preview_image = $row['preview_image'];
    $poster_image = $row['poster_image'] ?? null;
    $category_id = $row['category_id'];
    $public_start = $row['public_start'];
    $public_end = $row['public_end'];
    $template_model = $row['template_model'];
    
} else {
    // Fallback: Get most recent room
    $checkRoom = $conn->prepare("SELECT id, name, room_code, updated_at, is_public, is_shared, description, preview_image, poster_image, category_id, public_start, public_end, template_model FROM room_sessions WHERE owner_id = ? ORDER BY id DESC LIMIT 1");
    $checkRoom->bind_param("i", $user_id);
    $checkRoom->execute();
    $roomResult = $checkRoom->get_result();

    if ($roomResult->num_rows === 0) {
        echo json_encode(["status" => "success", "objects" => []]); // No room = empty room
        exit;
    }
    $roomRow = $roomResult->fetch_assoc();
    $room_id = $roomRow['id'];
    $room_name = $roomRow['name'];
    $room_code_val = $roomRow['room_code'];
    $updated_at_val = $roomRow['updated_at'];
    $is_public = $roomRow['is_public'];
    $is_shared = $roomRow['is_shared'];
    $description = $roomRow['description'];
    $preview_image = $roomRow['preview_image'];
    $poster_image = $roomRow['poster_image'] ?? null;
    $category_id = $roomRow['category_id'];
    $public_start = $roomRow['public_start'];
    $public_end = $roomRow['public_end'];
    $template_model = $roomRow['template_model'];
}

// 2. Fetch Objects with Texture Info (JOIN)
// We LEFT JOIN user_textures so we get the object even if texture_id is NULL  
$sql = "SELECT
            so.*,
            ut.filepath as texture_url
        FROM scene_objects so
        LEFT JOIN user_textures ut ON so.texture_id = ut.id
        WHERE so.room_id = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $room_id);
$stmt->execute();
$result = $stmt->get_result();

$objects = [];
while ($row = $result->fetch_assoc()) {
    // Format for frontend
    $objects[] = [
        'type' => $row['object_type'],
        'model' => $row['model_path'],
        'position' => [
            'x' => (float)$row['position_x'],
            'y' => (float)$row['position_y'],
            'z' => (float)$row['position_z']
        ],
        'rotation' => [
            'x' => (float)$row['rotation_x'],
            'y' => (float)$row['rotation_y'],
            'z' => (float)$row['rotation_z']
        ],
        'scale' => [
            'x' => (float)$row['scale_x'],
            'y' => (float)$row['scale_y'],
            'z' => (float)$row['scale_z']
        ],
        'texture_id' => $row['texture_id'], // Keep ID for future updates
        'texture_url' => $row['texture_url'], // URL to load
        'texture_repeat' => [
            'x' => (float)($row['texture_repeat_x'] ?? 1.0),
            'y' => (float)($row['texture_repeat_y'] ?? 1.0)
        ],
        'texture_offset' => [
            'x' => (float)($row['texture_offset_x'] ?? 0.0),
            'y' => (float)($row['texture_offset_y'] ?? 0.0)
        ],
        'texture_rotation' => (float)($row['texture_rotation'] ?? 0.0)
    ];
}

// Fetch multiple categories
$category_ids = [];
if (!empty($category_id)) {
    $category_ids[] = $category_id;
}
if ($room_id) {
    try {
        $cres = $conn->query("SELECT category_id FROM room_category_relations WHERE room_id = " . intval($room_id));
        if ($cres && $cres->num_rows > 0) {
            $category_ids = [];
            while ($crow = $cres->fetch_assoc()) {
                $category_ids[] = $crow['category_id'];
            }
        }
    } catch(Exception $e) {}
}

echo json_encode([
    "status" => "success", 
    "objects" => $objects,
    "room_id" => $room_id,
    "room_name" => $room_name,
    "room_code" => $room_code_val,
    "updated_at" => $updated_at_val,
    "is_public" => $is_public,
    "is_shared" => $is_shared,
    "description" => $description,
    "preview_image" => $preview_image,
    "poster_image" => $poster_image,
    "category_id" => $category_id,
    "category_ids" => $category_ids,
    "public_start" => $public_start,
    "public_end" => $public_end,
    "template_model" => $template_model
]);
?>
