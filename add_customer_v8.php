<?php
include_once "db_connect.php";

// Function to sanitize data (prevent SQL injection and other issues)
function sanitizeString($str) {
    global $mysqli;
    $str = strip_tags($str);
    $str = htmlentities($str);
    $str = stripslashes($str);
    return $mysqli->real_escape_string($str);  // MOST IMPORTANT: Escape for MySQL
}
// Get Data
$customer_name = sanitizeString($_POST["customer_name"]);

$response = array();
// Check for required fields
if (empty($customer_name)) {
    $response["success"] = false;
    $response["message"] = "Customer name cannot be empty.";
    echo json_encode($response);
    exit;
}

// Use prepared statement to prevent SQL injection
$sql = "INSERT INTO customers (customer_name) VALUES (?)";
$stmt = $mysqli->prepare($sql);

if ($stmt) {
    $stmt->bind_param("s", $customer_name);

    if ($stmt->execute()) {
        $response["success"] = true;
        $response["message"] = "Customer added successfully.";
        // $response["customer_id"] = $mysqli->insert_id;    Removed from this side to prevent a double response
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