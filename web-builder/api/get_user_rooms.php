<?php
header("Access-Control-Allow-Origin: http://localhost:5500");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

require_once 'config.php';

// session_start(); // Config.php handles this
$user_id = $_SESSION['user_id'] ?? 1; // Default to testuser

// AUTO-MIGRATION: Ensure columns exist
try {
    // 1. Check for room_code
    $checkSchemaCode = $conn->query("SHOW COLUMNS FROM room_sessions LIKE 'room_code'");
    if ($checkSchemaCode->num_rows == 0) {
        $conn->query("ALTER TABLE room_sessions ADD COLUMN room_code VARCHAR(8) NOT NULL DEFAULT '000000'");
        $conn->query("UPDATE room_sessions SET room_code = SUBSTRING(MD5(RAND()), 1, 8) WHERE room_code = '000000'");
    }

    // 2. Check for is_public (Separate check to ensure it's added even if room_code exists)
    $checkSchemaPublic = $conn->query("SHOW COLUMNS FROM room_sessions LIKE 'is_public'");
    if ($checkSchemaPublic->num_rows == 0) {
        $conn->query("ALTER TABLE room_sessions ADD COLUMN is_public TINYINT(1) NOT NULL DEFAULT 0");
    }

    // 3. Check for description
    $checkSchemaDesc = $conn->query("SHOW COLUMNS FROM room_sessions LIKE 'description'");
    if ($checkSchemaDesc->num_rows == 0) {
        $conn->query("ALTER TABLE room_sessions ADD COLUMN description TEXT DEFAULT NULL");
    }

    // 4. Check for preview_image
    $checkSchemaImg = $conn->query("SHOW COLUMNS FROM room_sessions LIKE 'preview_image'");
    if ($checkSchemaImg->num_rows == 0) {
        $conn->query("ALTER TABLE room_sessions ADD COLUMN preview_image VARCHAR(255) DEFAULT NULL");
    }

    $checkSchemaPoster = $conn->query("SHOW COLUMNS FROM room_sessions LIKE 'poster_image'");
    if ($checkSchemaPoster->num_rows == 0) {
        $conn->query("ALTER TABLE room_sessions ADD COLUMN poster_image VARCHAR(255) DEFAULT NULL");
    }

    // 5. Check for is_shared
    $checkSchemaShared = $conn->query("SHOW COLUMNS FROM room_sessions LIKE 'is_shared'");
    if ($checkSchemaShared->num_rows == 0) {
        $conn->query("ALTER TABLE room_sessions ADD COLUMN is_shared TINYINT(1) NOT NULL DEFAULT 1"); 
    }

} catch(Exception $e) { /* Continue silently */ }

// Query: Get all rooms owned by this user
$sql = "SELECT r.id, r.name, r.room_code, r.is_public, r.is_shared, r.description, r.preview_image, r.poster_image, r.created_at, r.category_id, r.public_start, r.public_end,
        (SELECT GROUP_CONCAT(category_id SEPARATOR ',') FROM room_category_relations rcr WHERE rcr.room_id = r.id) as category_ids
        FROM room_sessions r WHERE r.owner_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$rooms = [];
while ($row = $result->fetch_assoc()) {
    $cat_ids = $row['category_ids'];
    if (empty($cat_ids) && !empty($row['category_id'])) {
        $cat_ids = $row['category_id'];
    }

    $rooms[] = [
        'id' => $row['id'],
        'name' => $row['name'] ? $row['name'] : 'Unnamed Room',
        'code' => $row['room_code'],
        'is_public' => (bool)$row['is_public'],
        // Handle is_shared nullability
        'is_shared' => isset($row['is_shared']) ? (bool)$row['is_shared'] : true,
        'description' => $row['description'] ?? '',
        'preview_image' => $row['preview_image'] ?? null,
        'poster_image' => $row['poster_image'] ?? null,
        'created_at' => $row['created_at'],
        'category_id' => $row['category_id'] ?? null,
        'category_ids' => $cat_ids ? explode(',', $cat_ids) : [],
        'public_start' => $row['public_start'] ?? null,
        'public_end' => $row['public_end'] ?? null
    ];
}

echo json_encode(["status" => "success", "rooms" => $rooms]);
?>