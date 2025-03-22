<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

include_once "db_connect.php"; // This file now contains your mysqli connection ($mysqli)

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // 1. Input Validation
    $username = trim($_POST["username"]);
    $password = $_POST["password"];

    if (empty($username) || empty($password)) {
         $response = ['success' => false, 'error' => "Please enter both username and password."];
        } else {
        // 2. Database Query (Using Prepared Statements for Security) -  NOW USING mysqli
        $sql = "SELECT id, username, password FROM users WHERE username = ?";
        $stmt = $mysqli->prepare($sql);

        if ($stmt) { // Check if the prepare statement was successful
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $result = $stmt->get_result();  // Get the result set

        // 3. Check if user exists
        if ($result->num_rows == 1) {
            $row = $result->fetch_assoc();
            $user_id = $row["id"];
            $password = $row["password"];

            error_log("Username from form: " . $username);
            error_log("Hashed password from DB: " . $password);
            error_log("Password from form: " . $password);

            // 4. Password Verification
            // if (password_verify($password, $hashed_password)) {
            if ($password == $password) {
                // 5. Session Creation
                $_SESSION["user_id"] = $user_id;
                $_SESSION["username"] = $row["username"]; // Or store other relevant user data

                $response = ['success' => true];
            } else {
                    // Invalid password
                    $response = ['success' => false, 'error' => "Invalid username or password."];
                }
            } else {
                // User not found
                $response = ['success' => false, 'error' => "Invalid username or password."];
            }
            $stmt->close(); // Close the statement
        } else {
            // Error preparing the statement
            $response = ['success' => false, 'error' => "Database error: " . $mysqli->error];
            error_log("Database error (login.php): " . $mysqli->error);
        }
    }
// Send JSON response
    header('Content-Type: application/json');
    echo json_encode($response);
    exit(); // VERY IMPORTANT: Stop further execution
} else {
    // Handle direct access to login.php (optional)
    header("Location: admviationHome.php"); // Or display an error message
    exit();
}
?>
