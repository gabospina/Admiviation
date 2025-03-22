<?php
include_once "db_connect.php";

// Enable error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json');

session_start(); // Start the session
// Check if the user is logged in and has the company_id
if (!isset($_SESSION["HeliUser"]) || !isset($_SESSION["company_id"])) {
    $response = ["success" => false, "message" => "You are not logged in properly or company ID not found."];
    echo json_encode($response);
    exit;
}

$pilot_id = (int)$_SESSION["HeliUser"]; // The Session ID of the Pilot
$company_id = (int)$_SESSION["company_id"]; // The company ID from the session

$contract_id = isset($_POST['contract_id']) ? intval($_POST['contract_id']) : 0; // The Post ID
$craft_type_id = isset($_POST['craft_type_id']) ? intval($_POST['craft_type_id']) : 0; // The craft_type_id from the request

// Basic validation
if ($contract_id <= 0 || $craft_type_id <= 0) {
    $response = ["success" => false, "message" => "Invalid contract ID or craft type ID. add_pilot_to_contract "];
    echo json_encode($response);
    exit;
}

// Check if the contract and craft type belong to the same company as the user
$checkOwnershipSql = "SELECT c.customer_id FROM contracts c JOIN crafts craft ON c.id = craft.contract_id WHERE c.id = ? AND craft.id = ?";
$checkOwnershipStmt = $mysqli->prepare($checkOwnershipSql);

if (!$checkOwnershipStmt) {
    $response = ["success" => false, "message" => "Prepare failed: " . htmlspecialchars($mysqli->error)];
    echo json_encode($response);
    exit;
}

$checkOwnershipStmt->bind_param("ii", $contract_id, $craft_type_id);
$checkOwnershipStmt->execute();
$checkOwnershipResult = $checkOwnershipStmt->get_result();

if ($checkOwnershipResult->num_rows === 0) {
    $response = ["success" => false, "message" => "Contract or craft type not found."];
    echo json_encode($response);
    $checkOwnershipStmt->close();
    exit;
}

$contractData = $checkOwnershipResult->fetch_assoc();
$checkOwnershipStmt->close();

// Now check if the customer (company) associated with the contract is the same as the user's company
$companySql = "SELECT company_id FROM customers WHERE id = ?";
$companyStmt = $mysqli->prepare($companySql);

if (!$companyStmt) {
    $response = ["success" => false, "message" => "Prepare failed: " . htmlspecialchars($mysqli->error)];
    echo json_encode($response);
    exit;
}

$companyStmt->bind_param("i", $contractData['customer_id']);
$companyStmt->execute();
$companyResult = $companyStmt->get_result();

if ($companyResult->num_rows === 0) {
    $response = ["success" => false, "message" => "Customer not found for the contract."];
    echo json_encode($response);
    $companyStmt->close();
    exit;
}

$customerData = $companyResult->fetch_assoc();
$companyStmt->close();

if ($customerData['company_id'] != $company_id) {
    $response = ["success" => false, "message" => "The contract does not belong to your company."];
    echo json_encode($response);
    exit;
}

// Check if the relationship already exists
$checkSql = "SELECT id FROM contract_pilots WHERE pilot_id = ? AND contract_id = ? AND craft_type_id = ?";
$checkStmt = $mysqli->prepare($checkSql);
if (!$checkStmt) {
    $response = ["success" => false, "message" => "Prepare failed: " . htmlspecialchars($mysqli->error)];
    echo json_encode($response);
    exit;
}
$checkStmt->bind_param("iii", $pilot_id, $contract_id, $craft_type_id);
$checkStmt->execute();
$checkStmt->store_result();

if ($checkStmt->num_rows > 0) {
    $response = ["success" => false, "message" => "The contract is already assigned to the pilot."];
    echo json_encode($response);
    $checkStmt->close();
    exit;
}
$checkStmt->close();

// Insert Data to new Query
$sql = "INSERT INTO contract_pilots (pilot_id, contract_id, craft_type_id) VALUES (?, ?, ?)"; // Use dynamic craft_type_id
$stmt = $mysqli->prepare($sql);

if ($stmt === false) {
    $response = ["success" => false, "message" => "Prepare failed: " . htmlspecialchars($mysqli->error)];
    echo json_encode($response);
    exit;
}

$stmt->bind_param("iii", $pilot_id, $contract_id, $craft_type_id); // Bind craft_type_id as the third parameter

if ($stmt->execute()) {
    $response = ["success" => true, "message" => "Contract added successfully."];
} else {
    $response = ["success" => false, "message" => "Execute failed: " . htmlspecialchars($stmt->error)];
}

$stmt->close();
$mysqli->close();

echo json_encode($response);
?>