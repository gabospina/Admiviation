<?php
// assets/php/get_user_onoff.php
session_start();

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Set the Content-Type header to application/json
header('Content-Type: application/json');

// Check if the user is logged in
if (!isset($_SESSION["HeliUser"])) {
    // User is not logged in.  Return an error message as JSON.
    echo json_encode(["error" => "Not logged in"]);
    exit(); // IMPORTANT: Stop further execution
}

include_once "db_connect.php";

// Sanitize user input - VERY IMPORTANT
$id = isset($_SESSION["HeliUser"]) ? intval($_SESSION["HeliUser"]) : 0; // Ensure it's an integer

if ($id <= 0) {
    // Invalid user ID. Return an error message as JSON.
    echo json_encode(["error" => "Invalid user ID"]);
    exit(); // IMPORTANT: Stop further execution
}

$cur = date("Y-m-d"); // Get the current date

try {
    // Prepare the SQL query (use prepared statements to prevent SQL injection)
    $query = "SELECT `on_date`, `off_date`, `on_duty`, `off_duty` FROM user_availability WHERE user_id=? AND `off_date`>=? ORDER BY `on_date` ASC";
    $stmt = $mysqli->prepare($query);

    if ($stmt === false) {
        // Handle preparation error
        error_log("SQL prepare error: " . $mysqli->error);
        echo json_encode(["error" => "Database error"]);
        exit(); // IMPORTANT: Stop further execution
    }

    // Bind the parameters
    $stmt->bind_param("is", $id, $cur);

    // Execute the query
    $stmt->execute();

    // Get the result
    $result = $stmt->get_result();

    if ($result === false) {
        error_log("SQL execution error: " . $mysqli->error);
        echo json_encode(["error" => "Database query error"]);
        exit();
    }

    // Process the results
    $res = []; // Initialize as an empty array
    $availability = []; // Use a more descriptive variable name

    while ($row = $result->fetch_assoc()) {
        $availability[] = [
            "on_date" => $row["on_date"],
            "off_date" => $row["off_date"],
            "on_duty" => $row["on_duty"],
            "off_duty" => $row["off_duty"]
        ];
    }

    // Construct the JSON response
    $res["availability"] = $availability; // Changed keys

    // Free result set
    $result->free();

    // Close the statement
    $stmt->close();

    // Output the JSON response
    echo json_encode($res);

} catch (Exception $e) {
    // Handle database errors
    error_log("Database exception: " . $e->getMessage());
    echo json_encode(["error" => "Database error"]);
} finally {
    // Close the database connection (if it's not closed automatically)
    if (isset($mysqli)) {
        $mysqli->close();
    }
    exit(); // VERY IMPORTANT: Stop further execution
}

// VERY IMPORTANT: Stop further execution (shouldn't reach here)
exit();
?>