<?php
header("Access-Control-Allow-Origin: http://localhost:5500");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

require_once 'config.php';

// Get JSON input
$data = json_decode(file_get_contents("php://input"));

if (!$data) {
    echo json_encode(["status" => "error", "message" => "No data provided."]);
    exit;
}

// Ensure session user
// session_start(); // Already in config.php
$user_id = $_SESSION['user_id'] ?? 1; // Default to testuser if not set

// FIX: Check if User Exists (Auto-Heal for Test User)
if ($user_id === 1) {
    $checkU = $conn->query("SELECT id FROM users WHERE id = 1");
    if ($checkU->num_rows === 0) {
        $conn->query("INSERT INTO users (id, username, email, password_hash, is_confirmed) VALUES (1, 'testuser', 'test@example.com', 'dummyhash', 1)");
    }
}

// AUTO-MIGRATION: Ensure texture columns exist
try {
    // 1. Scene Objects: Texture Props
    $checkSchema = $conn->query("SHOW COLUMNS FROM scene_objects LIKE 'texture_repeat_x'");
    if ($checkSchema->num_rows == 0) {
        $conn->query("ALTER TABLE scene_objects 
            ADD COLUMN texture_repeat_x FLOAT DEFAULT 1,
            ADD COLUMN texture_repeat_y FLOAT DEFAULT 1,
            ADD COLUMN texture_offset_x FLOAT DEFAULT 0,
            ADD COLUMN texture_offset_y FLOAT DEFAULT 0,
            ADD COLUMN texture_rotation FLOAT DEFAULT 0");
    }

    // 2. Room Sessions: Public & Code
    $checkSchemaRoom = $conn->query("SHOW COLUMNS FROM room_sessions LIKE 'is_public'");
    if ($checkSchemaRoom->num_rows == 0) {
        $conn->query("ALTER TABLE room_sessions ADD COLUMN is_public TINYINT(1) NOT NULL DEFAULT 0");
    }
    
    $checkSchemaCode = $conn->query("SHOW COLUMNS FROM room_sessions LIKE 'room_code'");
    if ($checkSchemaCode->num_rows == 0) {
        $conn->query("ALTER TABLE room_sessions ADD COLUMN room_code VARCHAR(8) NOT NULL DEFAULT '000000'");
        // Generate codes for existing?
        $conn->query("UPDATE room_sessions SET room_code = SUBSTRING(MD5(RAND()), 1, 8) WHERE room_code = '000000'");
    }

    // 3. Room Sessions: Preview & Description
    // Check for 'template_model' column
    $checkTemplate = $conn->query("SHOW COLUMNS FROM room_sessions LIKE 'template_model'");
    if ($checkTemplate->num_rows == 0) {
        $conn->query("ALTER TABLE room_sessions ADD COLUMN template_model VARCHAR(255) DEFAULT './src/assets/podium_assets/podiumv4.glb'");
    }

    $checkSchemaPrev = $conn->query("SHOW COLUMNS FROM room_sessions LIKE 'preview_image'");
    if ($checkSchemaPrev->num_rows == 0) {
        $conn->query("ALTER TABLE room_sessions 
            ADD COLUMN preview_image VARCHAR(255) DEFAULT '',
            ADD COLUMN description TEXT DEFAULT NULL");
    }

    // 4. Room Sessions: Updated At (Scene Builder HUD)
    $checkSchemaUpdated = $conn->query("SHOW COLUMNS FROM room_sessions LIKE 'updated_at'");
    if ($checkSchemaUpdated->num_rows == 0) {
        $conn->query("ALTER TABLE room_sessions ADD COLUMN updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP");
    }

} catch(Exception $e) { /* Continue silently */ }

try {
    // 1. Determine Mode: Create New or Overwrite
    $room_id = $data->room_id ?? null;
    $room_name = $data->name ?? 'Untitled Room';
    $template_model = $data->template_model ?? './src/assets/podium_assets/podiumv4.glb';

    if ($room_id) {
        // --- OVERWRITE MODE ---
        // Verify ownership
        $checkOwner = $conn->prepare("SELECT id FROM room_sessions WHERE id = ? AND owner_id = ? LIMIT 1");
        $checkOwner->bind_param("ii", $room_id, $user_id);
        $checkOwner->execute();
        if ($checkOwner->get_result()->num_rows === 0) {
             throw new Exception("Room not found or you don't have permission to modify it.");
        }
        
        // Update Name if provided
        $updateName = $conn->prepare("UPDATE room_sessions SET name = ?, template_model = ?, updated_at = NOW() WHERE id = ?");
        $updateName->bind_param("ssi", $room_name, $template_model, $room_id);
        $updateName->execute();

    } else {
        // --- CREATE NEW MODE ---
// 1. Check Limit (Max 100)
        $countQuery = $conn->prepare("SELECT COUNT(*) as cnt FROM room_sessions WHERE owner_id = ?");
        $countQuery->bind_param("i", $user_id);
        $countQuery->execute();
        $countResult = $countQuery->get_result()->fetch_assoc();

        if ($countResult['cnt'] >= 100) {
            throw new Exception("Room limit reached. You can only have 100 saved rooms. Please overwrite an existing one.");
        }
        
        // 2. Create Room
        $room_code = substr(md5(uniqid(rand(), true)), 0, 8);
        $createRoom = $conn->prepare("INSERT INTO room_sessions (owner_id, name, room_code, template_model, is_public) VALUES (?, ?, ?, ?, 0)");
        $createRoom->bind_param("isss", $user_id, $room_name, $room_code, $template_model);
        
        if ($createRoom->execute()) {
            $room_id = $conn->insert_id;
        } else {
            throw new Exception("Failed to create room: " . $conn->error);
        }
    }

    // 2. Clear existing objects for this room (Overwrite behavior)
// Because of ON DELETE CASCADE, if we deleted the room, objects would go.
// But we want to keep the room ID. So we delete objects WHERE room_id = ?
$deleteObjs = $conn->prepare("DELETE FROM scene_objects WHERE room_id = ?");
$deleteObjs->bind_param("i", $room_id);
if (!$deleteObjs->execute()) {
    throw new Exception("Failed to clear old objects: " . $conn->error);
}

// 3. Insert new objects
$objects = $data->objects; // Array of objects from frontend

// ------ Handle Screenshot Override if provided ------
if (!empty($data->screenshot_base64)) {
    // Process base64
    $imgData = $data->screenshot_base64;
    // Expected format: data:image/jpeg;base64,.... or image/png
    $parts = explode(',', $imgData);
    if (count($parts) === 2) {
        $meta = $parts[0];
        $imgData = $parts[1];
        
        $ext = 'png'; // default
        if (strpos($meta, 'image/jpeg') !== false) {
            $ext = 'jpg';
        }
        
        $decoded = base64_decode($imgData);
        if ($decoded) {
            // New structure: uploads/rooms/{id}/preview.ext
            $uploadDir = 'uploads/rooms/' . $room_id . '/';
            if (!file_exists($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }
            
            // Delete Old Preview First to maintain cleaner FS
            $oldQ = $conn->prepare("SELECT preview_image FROM room_sessions WHERE id = ?");
            if ($oldQ) {
                $oldQ->bind_param("i", $room_id);
                $oldQ->execute();
                $res = $oldQ->get_result();
                if ($row = $res->fetch_assoc()) {
                    $oldPath = $row['preview_image'];
                    if (!empty($oldPath)) {
                        $localPath = str_replace('api/', '', $oldPath);
                        if (file_exists($localPath)) {
                            unlink($localPath);
                        }
                    }
                }
                $oldQ->close();
            }

            // Filename can be simpler now that it is in a folder, but robust for caching
            $fileName = "preview_" . time() . "." . $ext;
            $targetPath = $uploadDir . $fileName;
            if (file_put_contents($targetPath, $decoded)) {
                $dbPath = "api/" . $targetPath;
                $updatePrev = $conn->prepare("UPDATE room_sessions SET preview_image = ? WHERE id = ?");
                $updatePrev->bind_param("si", $dbPath, $room_id);
                $updatePrev->execute();
            }
        }
    }
}
// --------------------------------------------------

if (!empty($objects)) {
    $sql = "INSERT INTO scene_objects (room_id, object_type, model_path, position_x, position_y, position_z, rotation_x, rotation_y, rotation_z, scale_x, scale_y, scale_z, texture_id, texture_repeat_x, texture_repeat_y, texture_offset_x, texture_offset_y, texture_rotation) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    
    if (!$stmt) {
        throw new Exception("Prepare failed: " . $conn->error);
    }

    foreach ($objects as $obj) {
        // Sanitize and prepare variables
        $type = $obj->type ?? 'cube';
        $model = $obj->model ?? null;
        
        $px = $obj->position->x ?? 0;
        $py = $obj->position->y ?? 0;
        $pz = $obj->position->z ?? 0;
        
        $rx = $obj->rotation->x ?? 0;
        $ry = $obj->rotation->y ?? 0;
        $rz = $obj->rotation->z ?? 0;
        
        $sx = $obj->scale->x ?? 1;
        $sy = $obj->scale->y ?? 1;
        $sz = $obj->scale->z ?? 1;
        
        $texId = $obj->texture_id ?? null;
        if (empty($texId) || $texId == 0) {
            $texId = null; 
        }

        // Texture Transforms
        $t_rep_x = $obj->texture_repeat->x ?? 1.0;
        $t_rep_y = $obj->texture_repeat->y ?? 1.0;
        $t_off_x = $obj->texture_offset->x ?? 0.0;
        $t_off_y = $obj->texture_offset->y ?? 0.0;
        $t_rot   = $obj->texture_rotation ?? 0.0;
        
        // s = string, i = integer, d = double, b = blob
        $stmt->bind_param("issdddddddddiddddd", $room_id, $type, $model, $px, $py, $pz, $rx, $ry, $rz, $sx, $sy, $sz, $texId, $t_rep_x, $t_rep_y, $t_off_x, $t_off_y, $t_rot);
        
        if (!$stmt->execute()) {
             throw new Exception("Failed to insert object: " . $stmt->error);
        }
    }
}

// ------------------------------------------------------------------
// NEW: Move textures from Cache to Room Folder
// ------------------------------------------------------------------
if (!empty($objects)) {
    // 1. Collect all unique texture IDs being saved
    $textureIdsToMove = [];
    foreach ($objects as $obj) {
        $tid = $obj->texture_id ?? 0;
        if (!empty($tid) && $tid > 0) {
            $textureIdsToMove[] = intval($tid);
        }
    }
    $textureIdsToMove = array_unique($textureIdsToMove);
    
    // 2. Process each texture
    if (!empty($textureIdsToMove)) {
        $idsStr = implode(',', $textureIdsToMove);
        $res = $conn->query("SELECT id, filepath FROM user_textures WHERE id IN ($idsStr)");
        
        $roomTexDir  = "uploads/rooms/" . $room_id . "/textures/";
        if (!file_exists($roomTexDir)) {
            mkdir($roomTexDir, 0777, true);
        }
        
        while ($res && $row = $res->fetch_assoc()) {
            $currentWebPath = $row['filepath']; 
            
            // Check if it is in cache
            if (strpos($currentWebPath, 'texture_cache') !== false) {
                
                $localCurrentPath = str_replace('api/', '', $currentWebPath);
                
                // Also check if file exists (it might have been moved already or path is wrong)
                // If the path starts with 'api/', strip it to get fs path
                $fsPath = $localCurrentPath;
                if (!file_exists($fsPath) && file_exists('api/' . $fsPath)) { $fsPath = 'api/' . $fsPath; }
                
                if (file_exists($fsPath)) {
                    $filename = basename($fsPath);
                    $newLocalPath = $roomTexDir . $filename;
                    $newWebPath = "api/" . $roomTexDir . $filename;
                    
                    if (rename($fsPath, $newLocalPath)) {
                        $updtex = $conn->query("UPDATE user_textures SET filepath = '$newWebPath' WHERE id = " . $row['id']);
                    }
                }
            }
        }
    }
}
// ------------------------------------------------------------------

// Prepare statement once outside loop if possible, but the original code had bind_param inside loop which is tricky with replace_string.
// Let's just modify the end of the file where echo json_encode is.

// Refetch code if not set (overwrite case)
if (!isset($room_code)) {
    $roomIdSafe = intval($room_id);
    $getCode = $conn->query("SELECT room_code FROM room_sessions WHERE id = $roomIdSafe");
    if ($getCode && $row = $getCode->fetch_assoc()) {
        $room_code = $row['room_code'];
    }
}

// Get updated_at
$updated_at = date('Y-m-d H:i:s'); // Approximation
// Or fetch from DB to be precise
$getUpdated = $conn->query("SELECT updated_at FROM room_sessions WHERE id = " . intval($room_id));
if ($getUpdated && $uRow = $getUpdated->fetch_assoc()) {
    $updated_at = $uRow['updated_at'];
}

echo json_encode(["status" => "success", "message" => "Room saved successfully.", "room_id" => $room_id, "room_code" => $room_code, "updated_at" => $updated_at]);

} catch (Exception $e) {
    echo json_encode(["status" => "error", "message" => $e->getMessage()]);
}
?>
