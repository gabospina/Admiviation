<?php
include_once "db_connect.php";

$response = array();

// Get Data
$customer_id = (int)$_POST["customer_id"]; // Validate integer

// Check if the customer ID is valid
if (empty($customer_id)) {
    $response["success"] = false;
    $response["message"] = "Customer ID is invalid.";
    echo json_encode($response);
    exit;
}

// Use prepared statement to prevent SQL injection
$sql = "DELETE FROM customers WHERE id = ?";
$stmt = $mysqli->prepare($sql);

if ($stmt) {
    $stmt->bind_param("i", $customer_id);

    if ($stmt->execute()) {
        $response["success"] = true;
        $response["message"] = "Customer deleted successfully.";
    } else {
        $response["success"] = false;
        $response["message"] = "Database error: " . $stmt->error;
    }

    $stmt->close();
} else {
    $response["success"] = false;
    $response["message"] = "Statement preparation error: " . $mysqli->error;
}

echo json_encode($response);
$mysqli->close();
?>