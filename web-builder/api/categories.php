<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

require_once 'config.php';

$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'OPTIONS') {
    http_response_code(200);
    exit;
}

if ($method === 'GET') {
    $action = isset($_GET['action']) ? $_GET['action'] : '';

    if ($action === 'count') {
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        if (!$id) {
            echo json_encode(['success' => false, 'message' => 'Category ID required']);
            exit;
        }

        // Count rooms linked to this category
        $countQuery = $conn->query("
            SELECT COUNT(DISTINCT room_id) as c 
            FROM (
                SELECT id as room_id FROM room_sessions WHERE category_id = $id
                UNION
                SELECT room_id FROM room_category_relations WHERE category_id = $id
            ) as tmp
        ");
        
        $count = 0;
        if ($countQuery && $row = $countQuery->fetch_assoc()) {
            $count = (int)$row['c'];
        }
        
        echo json_encode(['success' => true, 'count' => $count]);
        exit;
    }

    // Auto-migrate if table missing
    $checkTable = $conn->query("SHOW TABLES LIKE 'room_categories'");
    if ($checkTable->num_rows == 0) {
        $conn->query("CREATE TABLE IF NOT EXISTS room_categories (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(255) NOT NULL UNIQUE,
            created_by INT,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP
        )");
    }

    $sql = "SELECT * FROM room_categories ORDER BY created_at DESC";
    $result = $conn->query($sql);
    $categories = [];
    if($result) {
        while ($row = $result->fetch_assoc()) {
            $categories[] = $row;
        }
    }
    echo json_encode(['success' => true, 'categories' => $categories]);
    exit;
}

if ($method === 'POST') {
    $data = json_decode(file_get_contents("php://input"));
    
    if (!isset($data->name)) {
        echo json_encode(['success' => false, 'message' => 'Category name is required']);
        exit;
    }

    $name = $conn->real_escape_string(trim($data->name));
    
    // Check duplication
    $check = $conn->query("SELECT id FROM room_categories WHERE name = '$name'");
    if ($check->num_rows > 0) {
        echo json_encode(['success' => false, 'message' => 'Category already exists']);
        exit;
    }

    // Insert
    $sql = "INSERT INTO room_categories (name) VALUES ('$name')";
    if ($conn->query($sql) === TRUE) {
        echo json_encode(['success' => true, 'category' => ['id' => $conn->insert_id, 'name' => $name]]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error: ' . $conn->error]);
    }
    exit;
}

if ($method === 'DELETE') {
    $data = json_decode(file_get_contents("php://input"));
    $id = isset($data->id) ? (int)$data->id : (isset($_GET['id']) ? (int)$_GET['id'] : 0);

    if (!$id) {
        echo json_encode(['success' => false, 'message' => 'Category ID required']);
        exit;
    }

    // Unset category in room_sessions (if not handled by ON DELETE SET NULL)
    $conn->query("UPDATE room_sessions SET category_id = NULL WHERE category_id = $id");

    // Remove relations
    $checkRelTable = $conn->query("SHOW TABLES LIKE 'room_category_relations'");
    if ($checkRelTable && $checkRelTable->num_rows > 0) {
        $conn->query("DELETE FROM room_category_relations WHERE category_id = $id");
    }

    // Remove category
    if ($conn->query("DELETE FROM room_categories WHERE id = $id")) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error: ' . $conn->error]);
    }
    exit;
}
?>