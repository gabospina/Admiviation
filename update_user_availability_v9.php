<?php
session_start();

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Set the Content-Type header to application/json
header('Content-Type: application/json');

// Check if the user is logged in
if (!isset($_SESSION["HeliUser"])) {
    echo json_encode(["error" => "Not logged in"]);
    exit();
}

include_once "db_connect.php";

// Function to sanitize input
function sanitizeInput($input) {
    global $mysqli;
    return htmlspecialchars(mysqli_real_escape_string($mysqli, $input));
}

// Sanitize inputs
$user_id = intval($_SESSION["HeliUser"]);  // Get user_id from session
$on = sanitizeInput($_POST["on"]);
$off = sanitizeInput($_POST["off"]);
$action = $_POST["action"];  // To determine whether to insert or delete

// Validate user ID
if ($user_id <= 0) {
    echo json_encode(["error" => "Invalid user ID"]);
    exit;
}

// Validate date format (YYYY-MM-DD)
if (!preg_match("/^\d{4}-\d{2}-\d{2}$/", $on) || !preg_match("/^\d{4}-\d{2}-\d{2}$/", $off)) {
    echo json_encode(["error" => "Invalid date format. Use YYYY-MM-DD."]);
    exit;
}

// Validate that off_date is after on_date
if ($off <= $on) {
    echo json_encode(["error" => "Off date must be after on date."]);
    exit;
}

// Determine whether to insert or delete
if ($action === "insert") {
    try {
        $sql = "INSERT INTO user_availability (user_id, on_date, off_date) VALUES (?, ?, ?)";
        $stmt = $mysqli->prepare($sql);
        
        if(!$stmt){
            error_log("SQL prepare error: " . $mysqli->error);
            echo json_encode(["error" => "Database error: " .  $mysqli->error]);
            exit();
        }

        $stmt->bind_param("iss", $user_id, $on, $off);

        if ($stmt->execute()) {
            echo json_encode(["success" => true, "message" => "Date inserted successfully."]);
        } else {
            echo json_encode(["error" => "Failed to insert new entry: ". $stmt->error]);
        }

        $stmt->close();
    } catch (Exception $e) {
        error_log("Exception: " . $e->getMessage()); // Log the full exception message
        echo json_encode(["error" => "Database error: " . $e->getMessage()]);
    }
} elseif ($action === "delete") {
    try {
        $sql = "DELETE FROM user_availability WHERE user_id = ? AND on_date = ? AND off_date = ?";
        $stmt = $mysqli->prepare($sql);

        if (!$stmt) {
            error_log("SQL prepare error: " . $mysqli->error);
            echo json_encode(["error" => "Database error: " .  $mysqli->error]);
            exit();
        }

        $stmt->bind_param("iss", $user_id, $on_date, $off_date);

        if ($stmt->execute()) {
            echo json_encode(["success" => true, "message" => "Date removed successfully."]);
        } else {
            echo json_encode(["error" => "Failed to delete entry: " . $stmt->error]);
        }

        $stmt->close();
    } catch (Exception $e) {
        error_log("Exception: " . $e->getMessage()); // Log the full exception message
        echo json_encode(["error" => "Database error: " . $e->getMessage()]);
    }
} else {
    echo json_encode(["error" => "Invalid action"]);
    exit;
}

finally {
    if (isset($mysqli)) {
        $mysqli->close();
    }
    exit();
}
?>