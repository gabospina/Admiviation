<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include_once "db_connect.php";

$response = array();

if (isset($_POST['customer_id']) && !empty($_POST['customer_id'])) {
    $customerId = intval($_POST['customer_id']);

    // Check if there are any contracts associated with the customer
    $query = "SELECT COUNT(*) AS contract_count FROM contracts WHERE customer_id = ?";
    $stmt = $mysqli->prepare($query);

    if ($stmt) {
        $stmt->bind_param("i", $customerId);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $contractCount = intval($row['contract_count']);
        $stmt->close();

        $response['has_contracts'] = ($contractCount > 0);  // True if customer has contracts
    } else {
        $response['has_contracts'] = true; // Assume true to prevent deletion on error
        $response['message'] = "Error preparing statement: " . $mysqli->error;
        error_log("Error preparing statement: " . $mysqli->error);
    }
} else {
    $response['has_contracts'] = true; // Assume true to prevent deletion on error
    $response['message'] = "Invalid customer ID.";
}

header('Content-Type: application/json');
echo json_encode($response);
$mysqli->close();
?>