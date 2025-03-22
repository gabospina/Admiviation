<?php
// session_start(); // Remove this line

include_once "db_connect.php";

// Enable error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json');

// Function to sanitize data
function sanitizeString($str)
{
    global $mysqli;
    return $mysqli->real_escape_string(trim($str));
}

// Bypassing session requirement for development (DO NOT USE IN PRODUCTION)
// $pilotId = $_SESSION["HeliUser"]["id"];  // Temporarily remove this

// Temporarily hardcoding pilotId (DO NOT USE IN PRODUCTION)
$pilotId = 64;  // Replace 1 with a valid pilot ID from your 'users' table

// Get data from POST
$craftType = isset($_POST["craft_type"]) ? sanitizeString($_POST["craft_type"]) : "";
$position = isset($_POST["position"]) ? sanitizeString($_POST["position"]) : "";

if (empty($craftType) || empty($position)) {
    $response = ["success" => false, "message" => "Craft Type and Position are required."];
    echo json_encode($response);
    exit();
}

// Validate position
if ($position !== 'PIC' && $position !== 'SIC') {
    $response = ["success" => false, "message" => "Invalid position selected."];
    echo json_encode($response);
    exit();
}

// Lookup the craft_id from the crafts table
$craftId = null;
$craftLookupSql = "SELECT id FROM crafts WHERE craft_type = ?";
$craftLookupStmt = $mysqli->prepare($craftLookupSql);

if (!$craftLookupStmt) {
    $response = ["success" => false, "message" => "Failed to prepare craft lookup statement: " . $mysqli->error];
    echo json_encode($response);
    exit;
}

$craftLookupStmt->bind_param("s", $craftType);
$craftLookupStmt->execute();
$craftLookupResult = $craftLookupStmt->get_result();

if ($craftLookupResult->num_rows > 0) {
    $craftRow = $craftLookupResult->fetch_assoc();
    $craftId = $craftRow["id"];
} else {
    $response = ["success" => false, "message" => "Craft type not found in the crafts table."];
    echo json_encode($response);
    $craftLookupStmt->close();
    exit;
}

$craftLookupStmt->close();

// Check if the relationship already exists
$checkSql = "SELECT id FROM pilot_craft_type WHERE pilot_id = ? AND craft_type_id = ? AND position = ?";
$checkStmt = $mysqli->prepare($checkSql);

if (!$checkStmt) {
    $response = ["success" => false, "message" => "Failed to prepare check statement: " . $mysqli->error];
    echo json_encode($response);
    exit;
}

$checkStmt->bind_param("iis", $pilotId, $craftId, $position);  // Use $pilotId here
$checkStmt->execute();
$checkStmt->store_result();

if ($checkStmt->num_rows > 0) {
    $response = ["success" => false, "message" => "This pilot and craft type combination already exists."];
    echo json_encode($response);
    $checkStmt->close();
    exit;
}

$checkStmt->close();

// Insert the new record
$insertSql = "INSERT INTO pilot_craft_type (pilot_id, craft_type_id, position) VALUES (?, ?, ?)";
$insertStmt = $mysqli->prepare($insertSql);

if (!$insertStmt) {
    $response = ["success" => false, "message" => "Failed to prepare insert statement: " . $mysqli->error];
    echo json_encode($response);
    exit;
}

$insertStmt->bind_param("iis", $pilotId, $craftId, $position); //Use $pilotId here

if ($insertStmt->execute()) {
    $response = ["success" => true, "message" => "Craft type and position added successfully!"];
} else {
    $response = ["success" => false, "message" => "Failed to add craft type and position: " . $insertStmt->error];
}

echo json_encode($response);
$insertStmt->close();
$mysqli->close();
?>