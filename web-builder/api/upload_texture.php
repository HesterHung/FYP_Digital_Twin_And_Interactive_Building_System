<?php
header("Access-Control-Allow-Origin: http://localhost:5500"); // Adjust this to your frontend URL
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

require_once 'config.php'; // Use your existing database connection

// Check if a file was actually sent
if (isset($_FILES['textureImage']) && $_FILES['textureImage']['error'] === UPLOAD_ERR_OK) {
    
    $fileTmpPath = $_FILES['textureImage']['tmp_name'];
    $fileName = $_FILES['textureImage']['name'];
    $fileSize = $_FILES['textureImage']['size'];
    $fileType = $_FILES['textureImage']['type'];
    
    // Get the extension
    $fileNameCmps = explode(".", $fileName);
    $fileExtension = strtolower(end($fileNameCmps));
    
    // Sanitize name
    $newFileName = md5(time() . $fileName) . '.' . $fileExtension;
    
    // Directory where you want to save the files.
    // CHANGED: Use a temporary cache folder. The save_room.php script will move this to
    // uploads/rooms/{id}/textures/ when the room is saved.
    $uploadFileDir = './uploads/texture_cache/';
    $webPathDir = "api/uploads/texture_cache/";
    
    // Create directory if it doesn't exist
    if (!file_exists($uploadFileDir)) {
        mkdir($uploadFileDir, 0777, true);
    }
    
    $dest_path = $uploadFileDir . $newFileName;
    
    // Allowed file extensions
    $allowedfileExtensions = array('jpg', 'gif', 'png', 'jpeg');
    
    if (in_array($fileExtension, $allowedfileExtensions)) {
        if(move_uploaded_file($fileTmpPath, $dest_path)) {
            
            // --- DATABASE INSERTION (using your new structure) ---
            // We assume the user is logged in. If not, we might need to handle that.
            // session_start(); // Already started in config.php
            $uploader_id = $_SESSION['user_id'] ?? 1; // Default to 1 (testuser) if not logged in
            
            // Insert into user_textures table
            $sql = "INSERT INTO user_textures (uploader_id, filename, filepath) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($sql);
            
            // The path we send back to the frontend (relative to web root)
            // CHANGED: Update web path to match new directory
            $webPath = $webPathDir . $newFileName;
            
            $stmt->bind_param("iss", $uploader_id, $fileName, $webPath);
            
            if($stmt->execute()) {
                $texture_id = $conn->insert_id;
                
                echo json_encode([
                    "status" => "success", 
                    "message" => "File uploaded successfully",
                    "asset_URL" => $webPath,
                    "texture_id" => $texture_id
                ]);
            } else {
                echo json_encode([
                    "status" => "error", 
                    "message" => "Database error: " . $stmt->error
                ]);
            }
            $stmt->close();
            
        } else {
            echo json_encode(["status" => "error", "message" => "There was an error moving the file to the upload directory."]);
        }
    } else {
        echo json_encode(["status" => "error", "message" => "Upload failed. Allowed file types: " . implode(',', $allowedfileExtensions)]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "No file uploaded or upload error."]);
}
?>