<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

include_once "db_connect.php";

// Function to return a JSON response
function returnJson($status, $message) {
    header('Content-Type: application/json');
    echo json_encode(array("status" => $status, "message" => $message));
    exit();
}

// Check if the form was submitted AND if this is an AJAX request
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the username and password from the form
    $username = $_POST["username"];
    $password = $_POST["password"];

    // Validate the input (add more validation as needed)
    if (empty($username) || empty($password)) {
        echo "Please enter your username and password."; // Send error message
        exit();
    }
        // Hash the password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    // Use prepared statements to prevent SQL injection
    $sql = "INSERT INTO users (username, password, access_level, created_at, updated_at) VALUES (?, ?, 1, NOW(), NOW())";
    $stmt = $mysqli->prepare($sql);

        if ($stmt) {
             $stmt->bind_param("ss", $username, $hashed_password);

                if ($stmt->execute()) {
               echo "success"; // Send success message back to JavaScript
               exit();
              } else {
                echo "Signup failed: " . $stmt->error; // Send error message
              }

       $stmt->close();
    }
}
?>