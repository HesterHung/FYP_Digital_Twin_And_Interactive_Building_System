<?php
require_once 'config.php';

// Columns to add
$columns = [
    "ADD COLUMN texture_repeat_x FLOAT DEFAULT 1",
    "ADD COLUMN texture_repeat_y FLOAT DEFAULT 1",
    "ADD COLUMN texture_offset_x FLOAT DEFAULT 0",
    "ADD COLUMN texture_offset_y FLOAT DEFAULT 0",
    "ADD COLUMN texture_rotation FLOAT DEFAULT 0"
];

foreach ($columns as $col) {
    try {
        $sql = "ALTER TABLE scene_objects $col";
        if ($conn->query($sql) === TRUE) {
            echo "Successfully executed: $sql <br>";
        } else {
            // Check if error is "Duplicate column name"
            if (strpos($conn->error, "Duplicate column name") !== false) {
                 echo "Column already exists (Skipped): $col <br>";
            } else {
                 echo "Error executing $sql: " . $conn->error . "<br>";
            }
        }
    } catch (Exception $e) {
        echo "Exception: " . $e->getMessage() . "<br>";
    }
}

echo "Database update complete.";
?>