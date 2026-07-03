<?php
require_once 'config.php';
require_once 'auth.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['room_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Missing room_id']);
    exit;
}

$room_id = intval($data['room_id']);
$user_id = $_SESSION['user_id'] ?? 1; // Default to testuser if session issue, matching save_room logic

// 1. Check ownership
$stmt = $conn->prepare("SELECT id FROM room_sessions WHERE id = ? AND (owner_id = ? OR user_id = ?)");
$stmt->bind_param("iii", $room_id, $user_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo json_encode(['status' => 'error', 'message' => 'Room not found or permission denied']);
    exit;
}

// --- 2. Delete Associated Textures ---
$textureQuery = "SELECT ut.id, ut.filepath 
                 FROM user_textures ut
                 JOIN scene_objects so ON so.texture_id = ut.id
                 WHERE so.room_id = ?";

if ($texStmt = $conn->prepare($textureQuery)) {
    $texStmt->bind_param("i", $room_id);
    $texStmt->execute();
    $texResult = $texStmt->get_result();
    
    // Buffer results to avoid connection sync issues
    $texturesToDelete = [];
    while ($row = $texResult->fetch_assoc()) {
         $texturesToDelete[] = $row;
    }
    $texStmt->close();

    foreach ($texturesToDelete as $row) {
        // Remove from DB (prepare new stmt)
        // We still need to remove DB entries for textures
        $delTex = $conn->prepare("DELETE FROM user_textures WHERE id = ?");
        if ($delTex) {
            $delTex->bind_param("i", $row['id']);
            $delTex->execute();
            $delTex->close();
        }
        
        // Note: Individual file deletion is skipped here in favor of folder deletion below,
        // UNLESS the texture was legacy (not in the room folder).
        // Let's try to delete it anyway to be safe for legacy files.
        $filepath = $row['filepath'];
        $localPath = str_replace('api/', '', $filepath);
        // If it is NOT in uploads/rooms/$room_id/, we delete it manually.
        // If it IS in the folder, the recursive delete will catch it.
        // Simple check: does path contain "rooms/$room_id"?
        if (strpos($localPath, "rooms/$room_id") === false) {
             if (file_exists($localPath)) unlink($localPath);
        }
    }
}

// --- 3. Delete Images (Preview) ---
// Just get the path to confirm if we need to clean up legacy preview images
$imgQuery = $conn->prepare("SELECT preview_image FROM room_sessions WHERE id = ?");
$imgQuery->bind_param("i", $room_id);
$imgQuery->execute();
$imgRes = $imgQuery->get_result();

if ($row = $imgRes->fetch_assoc()) {
    $img = $row['preview_image'];
    if (!empty($img)) {
        $relPath = str_replace('api/', '', $img);
        // Legacy cleanup only
        if (strpos($relPath, "rooms/$room_id") === false) {
            if (file_exists($relPath)) unlink($relPath);
        }
    }
}
$imgQuery->close();

// --- 4. RECURSIVE DELETE OF ROOM FOLDER ---
// The path must be relative to THIS file (api/). 
// "uploads/rooms/" works if "uploads" is in "api/".
$roomFolder = "uploads/rooms/" . $room_id;

if (is_dir($roomFolder)) {
    $it = new RecursiveDirectoryIterator($roomFolder, RecursiveDirectoryIterator::SKIP_DOTS);
    $files = new RecursiveIteratorIterator($it, RecursiveIteratorIterator::CHILD_FIRST);
    foreach($files as $file) {
        if ($file->isDir()){
            rmdir($file->getRealPath());
        } else {
            unlink($file->getRealPath());
        }
    }
    rmdir($roomFolder);
}

// 2. Delete objects in the room
$stmt = $conn->prepare("DELETE FROM scene_objects WHERE room_id = ?");
$stmt->bind_param("i", $room_id);
if (!$stmt->execute()) {
    echo json_encode(['status' => 'error', 'message' => 'Failed to clean up room objects']);
    exit;
}
$stmt->close();

// 3. Delete the room session
$stmt = $conn->prepare("DELETE FROM room_sessions WHERE id = ?");
$stmt->bind_param("i", $room_id);

if ($stmt->execute()) {
    echo json_encode(['status' => 'success', 'message' => 'Room deleted successfully']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Failed to delete room']);
}
$stmt->close();
$conn->close();
?>