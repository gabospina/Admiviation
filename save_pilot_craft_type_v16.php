<?php
session_start();
include_once "db_connect.php";

// Check if the user is logged in
if (!isset($_SESSION["HeliUser"])) {
    echo json_encode(["success" => false, "message" => "User not logged in"]);
    exit();
}

$pilotId = (int)$_SESSION["HeliUser"]; // Get user ID from session and cast to integer

// Check for CSRF token if you are using it.
// if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
//     echo json_encode(["success" => false, "message" => "CSRF token validation failed"]);
//     exit();
// }

// Get the input data from the POST request
$craftTypeId = isset($_POST["craft_type_id"]) ? intval($_POST["craft_type_id"]) : 0; // Craft type ID from the database
$position = isset($_POST["position"]) ? $_POST["position"] : ""; // 'PIC' or 'SIC'

// Validate inputs
if ($craftTypeId <= 0 || !in_array($position, ["PIC", "SIC"])) {
    echo json_encode(["success" => false, "message" => "Invalid data"]);
    exit();
}

// Use prepared statements to prevent SQL injection
$sql = "INSERT INTO pilot_craft_type (pilot_id, craft_type_id, position) VALUES (?, ?, ?)";
$stmt = $mysqli->prepare($sql);

if ($stmt === false) {
    echo json_encode(["success" => false, "message" => "Prepare failed: " . htmlspecialchars($mysqli->error)]);
    exit();
}

$stmt->bind_param("iis", $pilotId, $craftTypeId, $position);

if ($stmt->execute()) {
    echo json_encode(["success" => true, "message" => "Craft type and position saved successfully."]);
} else {
    echo json_encode(["success" => false, "message" => "Execute failed: " . htmlspecialchars($stmt->error)]);
}

$stmt->close();
$mysqli->close();
?>