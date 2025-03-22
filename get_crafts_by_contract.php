<?php
session_start();

include_once "db_connect.php";

// Enable error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json');

// Check for login and company ID
if (!isset($_SESSION["HeliUser"]) || !isset($_SESSION["company_id"])) {
    $response = ["success" => false, "message" => "Not logged in or company ID missing."];
    echo json_encode($response);
    exit();
}

$companyId = (int)$_SESSION["company_id"];

// Get data from GET request
$contractId = isset($_GET["contract_id"]) ? (int)$_GET["contract_id"] : 0;

// Validate input data
if ($contractId <= 0) {
    $response = ["success" => false, "message" => "Invalid contract ID."];
    echo json_encode($response);
    exit();
}

// Check if the contract belongs to the company
$contractCheckSql = "SELECT id FROM contracts WHERE id = ? AND company_id = ?";
$contractCheckStmt = $mysqli->prepare($contractCheckSql);
if (!$contractCheckStmt) {
    $response = ["success" => false, "message" => "Prepare failed (contract check): " . $mysqli->error];
    echo json_encode($response);
    exit();
}
$contractCheckStmt->bind_param("ii", $contractId, $companyId);
$contractCheckStmt->execute();
$contractCheckStmt->store_result();

if ($contractCheckStmt->num_rows == 0) {
    $response = ["success" => false, "message" => "Contract not found or does not belong to your company."];
    $contractCheckStmt->close();
    echo json_encode($response);
    exit();
}
$contractCheckStmt->close();

//Get crafts with the contract id and company id
$sql = "SELECT id, craft_type, registration FROM crafts WHERE company_id = ? AND contract_id = ?";
$stmt = $mysqli->prepare($sql);
if (!$stmt) {
    $response = ["success" => false, "message" => "Prepare failed (get crafts): " . $mysqli->error];
    echo json_encode($response);
    exit();
}

$stmt->bind_param("ii", $companyId, $contractId);
$stmt->execute();
$result = $stmt->get_result();

$crafts = [];
while ($row = $result->fetch_assoc()) {
    $crafts[] = $row;
}

$stmt->close();

$response["success"] = true;
$response["crafts"] = $crafts;
echo json_encode($response);

$mysqli->close();
?>