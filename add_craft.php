<?php
session_start();

ob_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

include_once "db_connect.php";

// Check if the user is logged in and has the company_id
if (!isset($_SESSION["HeliUser"]) || !isset($_SESSION["company_id"])) {
    echo json_encode(["success" => false, "message" => "You are not logged in properly or company ID not found."]);
    exit();
}

$user_id = (int)$_SESSION["HeliUser"];
$company_id = (int)$_SESSION["company_id"];

if ($mysqli->connect_error) {
    echo json_encode(["success" => false, "message" => "Connection failed: " . $mysqli->connect_error]);
    exit();
}

$mysqli->set_charset("utf8");

// Get the POST data
$craft = isset($_POST['craft']) ? trim($_POST['craft']) : null;
$registration = isset($_POST['registration']) ? trim($_POST['registration']) : null;
$tod = isset($_POST['tod']) ? trim($_POST['tod']) : null;
$alive = isset($_POST['alive']) ? (int)$_POST['alive'] : null;

// Validate input
if (empty($craft) || empty($registration) || empty($tod) || $alive === null) {
    echo json_encode(["success" => false, "message" => "All fields are required."]);
    exit();
}

// Prepare the SQL query
$sql = "INSERT INTO crafts (craft_type, registration, tod, alive, company_id) VALUES (?, ?, ?, ?, ?)";
$stmt = $mysqli->prepare($sql);

if (!$stmt) {
    echo json_encode(["success" => false, "message" => "Failed to prepare statement: " . $mysqli->error]);
    exit();
}

$stmt->bind_param("sssii", $craft, $registration, $tod, $alive, $company_id);

if (!$stmt->execute()) {
    echo json_encode(["success" => false, "message" => "Failed to execute statement: " . $stmt->error]);
    exit();
}

// Get the ID of the newly inserted craft
$craft_id = $mysqli->insert_id;

$stmt->close();
$mysqli->close();

// Return success response
echo json_encode(["success" => true, "message" => "Craft added successfully!", "craft_id" => $craft_id]);
?>