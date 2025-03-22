<?php
// update_personal_info.php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION["HeliUser"])) {
    echo json_encode(["error" => "Not logged in"]);
    exit();
}

include_once "db_connect.php";

$user_id = intval($_SESSION["HeliUser"]);

// error_log("POST data: " . print_r($_POST, true));
// $firstname = sanitizeInput($_POST["fname"]);
// error_log("Sanitized firstname: " . $firstname);

// Function to sanitize input
function sanitizeInput($input) {
    global $mysqli;
    return htmlspecialchars(mysqli_real_escape_string($mysqli, $input));
}

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Log raw POST data for debugging
error_log("Raw POST data: " . json_encode($_POST));

// Sanitize inputs
$firstname = sanitizeInput($_POST["firstname"]);
$lastname = sanitizeInput($_POST["lastname"]);
$username = sanitizeInput($_POST["username"]);
$user_nationality = sanitizeInput($_POST["user_nationality"]);
$job_position = sanitizeInput($_POST["comandante"]); // No need to intval if using ENUM
$nal_license = sanitizeInput($_POST["nal_license"]);
$for_license = sanitizeInput($_POST["for_license"]);
$email = sanitizeInput($_POST["email"]);
$phone = sanitizeInput($_POST["phone"]);
$phonetwo = sanitizeInput($_POST["phonetwo"]);

// Prepare the SQL query
$sql = "UPDATE users SET
        firstname = ?,
        lastname = ?,
        username = ?,
        user_nationality = ?,
        job_position = ?,
        nal_license = ?,
        for_license = ?,
        email = ?,
        phone = ?,
        phonetwo = ?
        WHERE id = ?";

$stmt = $mysqli->prepare($sql);

if (!$stmt) {
    error_log("SQL prepare error: " . $mysqli->error);
    echo json_encode(["error" => "Database error: " . $mysqli->error]);
    exit();
}

$stmt->bind_param("ssssssssssi", $firstname, $lastname, $username, $user_nationality, $job_position, $nal_license, $for_license, $email, $phone, $phonetwo, $user_id);

if ($stmt->execute()) {
    echo json_encode(["success" => "true"]);
} else {
	error_log("Execute error: " . $stmt->error);
    echo json_encode(["error" => "Failed to update personal information: " . $stmt->error]);
}

$stmt->close();
$mysqli->close();

?>