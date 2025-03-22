<?php
include_once "db_connect.php";

// Enable error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json');

// Check if the user is logged in and has a pilot ID in the session
// Get pilotId from session
// session_start();
//  if (!isset($_SESSION["HeliUser"]["id"])) {
//     $response = array("success" => false, "message" => "Not properly logged in.");
//     echo json_encode($response);
//     exit;
// }
//$pilotId = $_SESSION["HeliUser"]["id"];
$pilotId = 64;

$response = array("success" => false, "message" => "", "contracts" => array());
//Get craft_type to the proper ID in SQL

$sql = "SELECT c.id AS contract_id, c.contract_name, cust.customer_name
		FROM contract_pilots AS ct
		JOIN contracts AS c
		ON ct.contract_id = c.id
		JOIN customers AS cust
		ON c.customer_id = cust.id
		WHERE ct.pilot_id = ?";
// sql proper for loading with names
$stmt = $mysqli->prepare($sql);
if ($stmt === false) {
    $response["message"] = "Prepare failed: " . htmlspecialchars($mysqli->error);
    echo json_encode($response);
    exit;
}

$stmt->bind_param("i", $pilotId);

if (!$stmt->execute()) {
    $response["message"] = "Execute failed: " . htmlspecialchars($stmt->error);
    echo json_encode($response);
    exit;
}

$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $contracts = array(); // Initialize the array
    while ($row = $result->fetch_assoc()) {
        $contracts[] = array(
            "id" => $row['contract_id'],
            "contract_name" => $row['contract_name'],
            "customer_name" => $row['customer_name']
        );
    }
    $response["success"] = true;
    $response["contracts"] = $contracts;
}

$stmt->close();
$mysqli->close();

echo json_encode($response);
?>
