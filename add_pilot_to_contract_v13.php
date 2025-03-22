<?php
include_once "db_connect.php";

// Enable error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json');

//Check the user is logged into the system properly
// session_start();
// if (!isset($_SESSION["HeliUser"]["id"])) {
//     $response = array("success" => false, "message" => "You are not logged in properly.");
//     echo json_encode($response);
//     exit;
// }
//Added to test the session, check and change, remove when ready

// $pilot_id = $_SESSION["HeliUser"]["id"]; //The Session ID of the Pilot
$pilot_id = 64;

$contract_id = isset($_POST['contract_id']) ? intval($_POST['contract_id']) : 0; //The Post ID.
// The Basic Validation
if ($contract_id <= 0) {
    $response = ["success" => false, "message" => "Invalid contract ID."];
    echo json_encode($response);
    exit;
}
//Check that relationship are good and nothing wrong.
$checkSql = "SELECT id FROM contract_pilots WHERE pilot_id = ? AND contract_id = ?";
$checkStmt = $mysqli->prepare($checkSql);
if (!$checkStmt) {
    $response = ["success" => false, "message" => "Prepare failed: " . htmlspecialchars($mysqli->error)];
    echo json_encode($response);
    exit;
}
$checkStmt->bind_param("ii", $pilot_id, $contract_id);
$checkStmt->execute();
$checkStmt->store_result();
if ($checkStmt->num_rows > 0) {
    $response = ["success" => false, "message" => "The contract is already assigned to the pilot."];
    echo json_encode($response);
    $checkStmt->close();
    exit;
}
$checkStmt->close();

//Insert Data to new Query
$sql = "INSERT INTO contract_pilots (pilot_id, contract_id,craft_type_id) VALUES (?, ?, 34)";  // Hardcoded craft_type_id for testing
$stmt = $mysqli->prepare($sql);

if ($stmt === false) {
    $response = ["success" => false, "message" => "Prepare failed: " . htmlspecialchars($mysqli->error)];
    echo json_encode($response);
    exit;
}

$stmt->bind_param("ii", $pilot_id, $contract_id);

if ($stmt->execute()) {
    $response = ["success" => true, "message" => "Contract added successfully."];
} else {
    $response = ["success" => false, "message" => "Execute failed: " . htmlspecialchars($stmt->error)];
}

$stmt->close();
$mysqli->close();

echo json_encode($response);
?>