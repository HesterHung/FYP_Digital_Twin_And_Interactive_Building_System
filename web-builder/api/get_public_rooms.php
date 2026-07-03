<?php
header("Access-Control-Allow-Origin: http://localhost:5500");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

require_once 'config.php';

// session_start();
$user_id = $_SESSION['user_id'] ?? 1; // Default to testuser

// AUTO-MIGRATION: Ensure columns exist (Just in case user loads this first)
try {
    // 1. Ensure room_categories table exists
    $checkTable = $conn->query("SHOW TABLES LIKE 'room_categories'");
    if ($checkTable->num_rows == 0) {
        $conn->query("CREATE TABLE IF NOT EXISTS room_categories (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(255) NOT NULL UNIQUE,
            created_by INT,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP
        )");
    }

    // 2. Ensure columns in room_sessions
    $checkSchemaPublic = $conn->query("SHOW COLUMNS FROM room_sessions LIKE 'is_public'");
    if ($checkSchemaPublic->num_rows == 0) {
        $conn->query("ALTER TABLE room_sessions ADD COLUMN is_public TINYINT(1) NOT NULL DEFAULT 0");
    }
    
    $checkSchemaCode = $conn->query("SHOW COLUMNS FROM room_sessions LIKE 'room_code'");
    if ($checkSchemaCode->num_rows == 0) {
        $conn->query("ALTER TABLE room_sessions ADD COLUMN room_code VARCHAR(8) NOT NULL DEFAULT '000000'");
    }

    $checkSchemaDesc = $conn->query("SHOW COLUMNS FROM room_sessions LIKE 'description'");
    if ($checkSchemaDesc->num_rows == 0) {
        $conn->query("ALTER TABLE room_sessions ADD COLUMN description TEXT DEFAULT NULL");
    }

    $checkSchemaImg = $conn->query("SHOW COLUMNS FROM room_sessions LIKE 'preview_image'");
    if ($checkSchemaImg->num_rows == 0) {
        $conn->query("ALTER TABLE room_sessions ADD COLUMN preview_image VARCHAR(255) DEFAULT NULL");
    }

    $checkSchemaPoster = $conn->query("SHOW COLUMNS FROM room_sessions LIKE 'poster_image'");
    if ($checkSchemaPoster->num_rows == 0) {
        $conn->query("ALTER TABLE room_sessions ADD COLUMN poster_image VARCHAR(255) DEFAULT NULL");
    }

    $checkSchemaCat = $conn->query("SHOW COLUMNS FROM room_sessions LIKE 'category_id'");
    if ($checkSchemaCat->num_rows == 0) {
        $conn->query("ALTER TABLE room_sessions ADD COLUMN category_id INT DEFAULT NULL");
    }

    $checkSchemaStart = $conn->query("SHOW COLUMNS FROM room_sessions LIKE 'public_start'");
    if ($checkSchemaStart->num_rows == 0) {
        $conn->query("ALTER TABLE room_sessions ADD COLUMN public_start DATETIME DEFAULT NULL");
    }

    $checkSchemaEnd = $conn->query("SHOW COLUMNS FROM room_sessions LIKE 'public_end'");
    if ($checkSchemaEnd->num_rows == 0) {
        $conn->query("ALTER TABLE room_sessions ADD COLUMN public_end DATETIME DEFAULT NULL");
    }

} catch (Exception $e) { /* Ignore */ }

// Ensure room_category_relations exists
try {
    $checkTable = $conn->query("SHOW TABLES LIKE 'room_category_relations'");
    if ($checkTable->num_rows == 0) {
        $conn->query("CREATE TABLE room_category_relations (
            room_id INT,
            category_id INT,
            PRIMARY KEY(room_id, category_id)
        )");
        $conn->query("INSERT IGNORE INTO room_category_relations (room_id, category_id) 
                      SELECT id, category_id FROM room_sessions WHERE category_id IS NOT NULL");
    }
} catch (Exception $e) {}

// Query: Get all rooms where is_public = 1
$sql = "SELECT r.*, IFNULL(u.username, CONCAT('User ', r.owner_id)) as owner_name,
        (SELECT GROUP_CONCAT(c.name SEPARATOR ', ') 
         FROM room_category_relations rcr 
         JOIN room_categories c ON rcr.category_id = c.id 
         WHERE rcr.room_id = r.id) as category_names,
        (SELECT GROUP_CONCAT(rcr.category_id SEPARATOR ',') 
         FROM room_category_relations rcr 
         WHERE rcr.room_id = r.id) as category_ids
        FROM room_sessions r
        LEFT JOIN users u ON r.owner_id = u.id
        WHERE r.is_public = 1
        ORDER BY r.created_at DESC";

$result = $conn->query($sql);

$rooms = [];
if ($result) {
    while ($row = $result->fetch_assoc()) {
        // Fallback for older single category
        $cat_names = $row['category_names'];
        $cat_ids = $row['category_ids'];
        
        // If relations table is empty but old column exists
        if (empty($cat_names) && !empty($row['category_id'])) {
            $catSql = "SELECT name FROM room_categories WHERE id=" . intval($row['category_id']);
            $catRes = $conn->query($catSql);
            if ($catRes && $cName = $catRes->fetch_assoc()) {
                $cat_names = $cName['name'];
                $cat_ids = $row['category_id'];
            }
        }
    
        $rooms[] = [
            'id' => $row['id'],
            'name' => htmlspecialchars($row['name']),
            'description' => htmlspecialchars($row['description'] ?? ''),
            'preview_image' => (!empty($row['preview_image'])) ? $row['preview_image'] : null,
            'poster_image' => (!empty($row['poster_image'])) ? $row['poster_image'] : null,
            'code' => $row['room_code'],
            'owner_name' => htmlspecialchars($row['owner_name']),
            'category_name' => $cat_names,
            'category_names' => $cat_names ? explode(', ', $cat_names) : [],
            'category_ids' => $cat_ids ? explode(',', $cat_ids) : [],
            'public_start' => $row['public_start'] ?? null,
            'public_end' => $row['public_end'] ?? null,
            'owner_id' => $row['owner_id'],
            'created_at' => $row['created_at']
        ];
    }
}

echo json_encode(["status" => "success", "rooms" => $rooms]);
?>