<?php

// if (session_status() == PHP_SESSION_NONE) {
//     session_start();
// }
// if (!isset($_SESSION["HeliUser"])) {
//     // header("Location: index.php");
//     header("Location: admviationHome.php");
//     exit();
// }

include_once "db_connect.php";

// Enable error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json'); // Set the correct Content-Type header

// Check if the user is logged in and retrieve pilot_id from session
// if (!isset($_SESSION["HeliUser"]["id"])) { //Adjust $_SESSION["HeliUser"]["id"] with your own name for the session id
//     $response = array("success" => false, "message" => "User not logged in or pilot ID not found in session.");
//     echo json_encode($response);
//     exit;
// }

// $pilot_id = $_SESSION["HeliUser"]["id"]; // Get pilotId from session

$pilot_id = 64; // Set to a user for testing purposes

$response = array("success" => false, "message" => "", "craftTypes" => array());

// Modified SQL query to join with the crafts table
$sql = "SELECT pct.id, c.craft_type, pct.position
        FROM pilot_craft_type AS pct
        INNER JOIN crafts AS c ON pct.craft_type_id = c.id
        WHERE pct.pilot_id = ?";

$stmt = $mysqli->prepare($sql);

if ($stmt === false) {
    $response["message"] = "Prepare failed: " . htmlspecialchars($mysqli->error);
    echo json_encode($response);
    exit;
}

$stmt->bind_param("i", $pilot_id);

if (!$stmt->execute()) {
    $response["message"] = "Execute failed: " . htmlspecialchars($stmt->error);
    echo json_encode($response);
    exit;
}

$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $craftTypes = array(); // Initialize the array
    while ($row = $result->fetch_assoc()) {
        $craftTypes[] = array(
            "id" => $row['id'],
            "craft_type" => $row['craft_type'],
            "position" => $row['position']
        );
    }
    $response["success"] = true;
    $response["craftTypes"] = $craftTypes;
} else {
    $response["message"] = "No craft types found for this pilot.";
}

$stmt->close();
$mysqli->close();

echo json_encode($response);
exit;
?>
Use code with caution.
PHP
Revised remove_craft_type.php (Temporary Bypass)

<?php
// session_start(); // Temporarily remove

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include_once "db_connect.php";

// Function to sanitize data (prevent SQL injection and other issues)
function sanitizeString($str) {
    global $mysqli;
    return $mysqli->real_escape_string(trim($str));
}

// Get the craft type ID from the POST request
$id = isset($_POST['id']) ? intval($_POST['id']) : 0;

// Check if the ID is valid
if ($id <= 0) {
    $response = ["success" => false, "message" => "Invalid craft type ID."];
    header('Content-Type: application/json');
    echo json_encode($response, JSON_PRETTY_PRINT);
    exit();
}

// Use prepared statements to prevent SQL injection
$sql = "DELETE FROM pilot_craft_type WHERE id = ?";
$stmt = $mysqli->prepare($sql);

if ($stmt === false) {
    $response = ["success" => false, "message" => "Prepare failed: " . htmlspecialchars($mysqli->error)];
    header('Content-Type: application/json');
    echo json_encode($response, JSON_PRETTY_PRINT);
    exit();
}

$stmt->bind_param("i", $id);

// Execute the statement
if ($stmt->execute()) {
    // Check if any rows were affected
    if ($stmt->affected_rows > 0) {
        $response = ["success" => true, "message" => "Craft type removed successfully."];
    } else {
        $response = ["success" => false, "message" => "Craft type not found or could not be removed."];
    }
} else {
    $response = ["success" => false, "message" => "Execute failed: " . htmlspecialchars($stmt->error)];
}

// Close statement and connection
$stmt->close();
$mysqli->close();

// Send JSON response
header('Content-Type: application/json');
echo json_encode($response, JSON_PRETTY_PRINT);
?>