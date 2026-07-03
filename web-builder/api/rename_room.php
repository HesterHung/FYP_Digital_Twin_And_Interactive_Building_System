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

$data = json_decode(file_get_contents("php://input"), true);
$room_id = $data['room_id'] ?? null;
$new_name = $data['name'] ?? null;

if (!$room_id || empty($new_name)) {
    echo json_encode(["status" => "error", "message" => "Room ID and new name are required."]);
    exit;
}

// Update Room Name
$stmt = $conn->prepare("UPDATE room_sessions SET name = ? WHERE id = ? AND owner_id = ?");
$stmt->bind_param("sii", $new_name, $room_id, $user_id);

if ($stmt->execute() && $stmt->affected_rows > 0) {
    echo json_encode(["status" => "success", "message" => "Room renamed successfully."]);
} else {
    // Check if room exists but rename failed (maybe same name or not owner)
    $check = $conn->prepare("SELECT id FROM room_sessions WHERE id = ? AND owner_id = ?");
    $check->bind_param("ii", $room_id, $user_id);
    $check->execute();
    if ($check->get_result()->num_rows === 0) {
        echo json_encode(["status" => "error", "message" => "Room not found or unauthorized."]);
    } else {
        echo json_encode(["status" => "success", "message" => "Name updated (or same name provided)."]);
    }
}
?>