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

$craft_type = sanitizeString($_POST["craft"]);  // Use craft instead of craft_type
$registration = sanitizeString($_POST["registration"]);
$tod = sanitizeString($_POST["tod"]);
$alive = (int)$_POST["alive"];

// Initialize response array
$response = array();

// Check for required fields
if (empty($craft_type) || empty($registration)) {  // Use craft_type
    $response["success"] = false;
    $response["message"] = "Craft Type and Registration cannot be empty.";  // Updated message
    echo json_encode($response);
    exit;
}

// Use prepared statement for security
// Correct column names
$sql = "INSERT INTO crafts (craft_type, registration, tod, alive, contract_id) VALUES (?, ?, ?, ?, 0)"; // Changed craft_type and added contract_id
$stmt = $mysqli->prepare($sql);

if ($stmt) {
    $stmt->bind_param("sssi",  $craft_type, $registration, $tod, $alive);

    if ($stmt->execute()) {
        $craft_id = $stmt->insert_id;
        $response["success"] = true;
        $response["message"] = "Craft added successfully.";
        $response["craft_id"] = $craft_id;
    } else {
        // Log the error
        error_log("Error inserting craft: " . $stmt->error);
        $response["success"] = false;
        $response["message"] = "Database error: " . $stmt->error;
    }

    $stmt->close();
} else {
    // Log the error
    error_log("Error preparing statement: " . $mysqli->error);
    $response["success"] = false;
    $response["message"] = "Statement preparation error: " . $mysqli->error;
}

echo json_encode($response);  // Return JSON response
$mysqli->close();
?>