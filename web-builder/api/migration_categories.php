<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

require_once 'config.php';

$response = ["success" => true, "messages" => []];

try {
    // 1. Create room_categories table
    $sql = "CREATE TABLE IF NOT EXISTS room_categories (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(255) NOT NULL UNIQUE,
        created_by INT,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP
    )";
    if ($conn->query($sql) === TRUE) {
        $response["messages"][] = "Table room_categories created or exists.";
    } else {
        throw new Exception("Error creating room_categories: " . $conn->error);
    }

    // 2. Add category_id, public_start, public_end to room_sessions
    $columns = [
        "category_id" => "INT DEFAULT NULL",
        "public_start" => "DATETIME DEFAULT NULL",
        "public_end" => "DATETIME DEFAULT NULL"
    ];

    foreach ($columns as $col => $def) {
        $check = $conn->query("SHOW COLUMNS FROM room_sessions LIKE '$col'");
        if ($check->num_rows == 0) {
            if ($conn->query("ALTER TABLE room_sessions ADD COLUMN $col $def") === TRUE) {
                $response["messages"][] = "Column $col added to room_sessions.";
            } else {
                throw new Exception("Error adding column $col: " . $conn->error);
            }
        }
    }

    // 3. Add role to users table
    $checkRole = $conn->query("SHOW COLUMNS FROM users LIKE 'role'");
    if ($checkRole->num_rows == 0) {
        if ($conn->query("ALTER TABLE users ADD COLUMN role VARCHAR(50) DEFAULT 'user'") === TRUE) {
            $response["messages"][] = "Column role added to users.";
            // Set user 1 as admin for testing
            $conn->query("UPDATE users SET role = 'admin' WHERE id = 1");
        } else {
            throw new Exception("Error adding column role: " . $conn->error);
        }
    }

    // Add foreign key if not exists (handling simple add)
    // Note: This might fail if data integrity is violated, so wrapping in try-catch effectively
    try {
        $conn->query("ALTER TABLE room_sessions ADD CONSTRAINT fk_room_category FOREIGN KEY (category_id) REFERENCES room_categories(id) ON DELETE SET NULL");
    } catch (Exception $e) {
        // Ignore if FK already exists or fails
    }

} catch (Exception $e) {
    $response["success"] = false;
    $response["error"] = $e->getMessage();
}

echo json_encode($response);
?>
