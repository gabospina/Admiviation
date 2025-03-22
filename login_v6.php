<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

include_once "db_connect.php";

// Check if the form was submitted AND if this is an AJAX request
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["ajax"])) {

    // Get the username and password from the form
    $username = $_POST["user"];
    $password = $_POST["pass"];

    // Validate the input
    if (empty($username) || empty($password)) {
        echo "Please enter your username and password.";
        exit();
    }

    // --- Enhanced Validation ---
    $username = trim($username); // Remove leading/trailing whitespace
    $password = trim($password);

    if (strlen($username) > 50 || !preg_match("/^[a-zA-Z0-9_]+$/", $username)) {
        echo "Invalid username format.";
        exit();
    }

    if (strlen($password) > 72) { // bcrypt max length
        echo "Invalid password.";
        exit();
    }
    // --- End Enhanced Validation ---

    // Use prepared statements to prevent SQL injection
    $sql = "SELECT id, password, access_level, company_id
            FROM users
            WHERE username = ?";

    $stmt = $mysqli->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $res = $result->fetch_assoc();
            $hashed_password = $res["password"];

            if (password_verify($password, $hashed_password)) {
                // Password matches, so regenerate session ID and create the session
                session_regenerate_id(true); // Prevent session fixation
                $id = $res["id"];
                $_SESSION["HeliUser"] = $id;
                $_SESSION["admin"] = $res["access_level"];
                $_SESSION["company_id"] = $res["company_id"];

                // Correct session expire time.
                $_SESSION["expire"] = (time() + (3 * 60 * 60));

                // Set cookies if "keep me signed in" is checked - I suggest to check the right logic and make the test if that works, for now, this is just a string on the air.
                if (isset($_POST["keepSignedIn"]) && $_POST["keepSignedIn"] == "Yes") {
                    $cookie_expiry = time() + 36000000; // Approximately 1 year
                    setcookie("HeliUser", $id, $cookie_expiry, "/", "", true, true); // secure and httponly
                    setcookie("admin", $res["access_level"], $cookie_expiry, "/", "", true, true); // secure and httponly
                }
                // Send success message back to JavaScript
                echo "success";
                exit();
            } else {
                // Incorrect password
                echo "Incorrect username or password.";
                exit();
            }
        } else {
            // User not found
            echo "Incorrect username or password.";
            exit();
        }

        $stmt->close();
    } else {
        // Prepare statement failed
        echo "Database error: " . htmlspecialchars($mysqli->error); // HTML encode!
        error_log("Database error (login.php): " . $mysqli->error); // Log the error
        exit();
    }

    $mysqli->close();
} else {
    // If the form was not submitted, or not an AJAX request, redirect to the login page
    header("Location: admviationHome.php");
    exit();
}
?>