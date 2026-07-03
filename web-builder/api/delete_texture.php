<?php
header("Content-Type: application/json");
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405); // Method Not Allowed
    echo json_encode(['error' => 'Method Not Allowed']);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);

if (!isset($input['texture_id']) && !isset($input['room_id'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Missing texture_id or room_id']);
    exit;
}

// Case 1: Delete a specific texture (when an object is deleted or texture removed from object)
if (isset($input['texture_id'])) {
    $texture_id = intval($input['texture_id']);
    
    // 1. Get filepath first to delete file
    $stmt = $conn->prepare("SELECT filepath FROM user_textures WHERE id = ?");
    $stmt->bind_param("i", $texture_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($row = $result->fetch_assoc()) {
        $filepath = $row['filepath'];
        
        // Remove 'api/' prefix if present to get local path relative to this script
        $localPath = str_replace('api/', '', $filepath);
        
        // Ensure we remove the texture file
        if (file_exists($localPath)) {
            unlink($localPath);
        } elseif (file_exists(__DIR__ . '/' . $localPath)) {
             unlink(__DIR__ . '/' . $localPath);
        }
        
        // 2. Delete from DB
        $delStmt = $conn->prepare("DELETE FROM user_textures WHERE id = ?");
        $delStmt->bind_param("i", $texture_id);
        if ($delStmt->execute()) {
             echo json_encode(['success' => true, 'message' => 'Texture deleted']);
        } else {
             echo json_encode(['error' => 'Database deletion failed']);
        }
    } else {
        echo json_encode(['error' => 'Texture not found']);
    }
} 
// Case 2: Delete all textures for a room (when room is deleted)
else if (isset($input['room_id'])) {
    // This requires that textures are linked to a user/room? 
    // Currently `user_textures` table schema is unknown. Assuming it has `user_id`.
    // If textures are shared across rooms, we shouldn't delete them.
    // If unique to objects/rooms, we need to know which ones.
    // Objects table `scene_objects` links object -> texture_id.
    
    // Strategy: Find all textures used by objects in this room
    $room_id = intval($input['room_id']);
    
    $query = "SELECT t.id, t.filepath 
              FROM user_textures t
              JOIN scene_objects so ON so.texture_id = t.id
              WHERE so.room_id = ?";
              
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $room_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $deletedCount = 0;
    while ($row = $result->fetch_assoc()) {
        $filepath = $row['filepath'];
        if (file_exists($filepath)) {
            unlink($filepath);
        }
        
        // Delete record
        // We can do this in bulk later or one by one
        $delNode = $conn->prepare("DELETE FROM user_textures WHERE id = ?");
        $delNode->bind_param("i", $row['id']);
        $delNode->execute();
        $deletedCount++;
    }
    
    echo json_encode(['success' => true, 'count' => $deletedCount]);
}
?>