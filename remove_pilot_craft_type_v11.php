<?php
// session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include_once "db_connect.php";

// Function to sanitize data (prevent SQL injection and other issues)
function sanitizeString($str) {
    global $mysqli;
    return $mysqli->real_escape_string(trim($str));
}

$pilot_id = 64; // Set to a user for testing purposes
$id = isset($_POST['id']) ? intval($_POST['id']) : 0; // Get ID from post

error_log("Attempting to remove craft type with ID: " . $id); // add line in php

// Use prepared statements to prevent SQL injection
$sql = "DELETE FROM pilot_craft_type WHERE id = ?";
$stmt = $mysqli->prepare($sql);

if ($stmt === false) {
    $response = ["success" => false, "message" => "Prepare failed: " . htmlspecialchars($mysqli->error)];
    header('Content-Type: application/json');
    echo json_encode($response, JSON_PRETTY_PRINT);
    exit();
}

$stmt->bind_param("i", $id);

// Execute the statement
if ($stmt->execute()) {
    // Check if any rows were affected
    if ($stmt->affected_rows > 0) {
        $response = ["success" => true, "message" => "Craft type removed successfully."];
    } else {
        $response = ["success" => false, "message" => "Craft type not found or could not be removed."];
    }
} else {
    $response = ["success" => false, "message" => "Execute failed: " . htmlspecialchars($stmt->error)];
}

// Close statement and connection
$stmt->close();
$mysqli->close();

// Send JSON response
header('Content-Type: application/json');
echo json_encode($response, JSON_PRETTY_PRINT);
?>