<?php
session_start();

include_once "db_connect.php";

// Check if session is active
if (session_status() === PHP_SESSION_NONE) {
    echo "Session is not active.  Please ensure session_start() is called at the beginning of your script.";
    exit;
}

// Sanitize inputs
$uid = isset($_SESSION["user_id"]) ? (int)$_SESSION["user_id"] : null;  //Get user ID
$newpass = isset($_POST["pass"]) ? $_POST["pass"] : null; // Sanitize
$old = isset($_POST["old"]) ? $_POST["old"] : null; // Sanitize

if ($uid === null || $newpass === null || $old === null) {
    echo "Missing required parameters (uid, pass, or old).";
    exit;
}

// SERVER-SIDE VALIDATION (Numbers and Length)
if (!preg_match("/^[0-9]{1,4}$/", $newpass)) {
    echo "Your new password must contain only numbers and be a maximum of 4 digits.";
    exit;
}

// 1. Get the password from the database
$query = "SELECT password FROM users WHERE id = ?"; //Selecting in users table
$stmt = $mysqli->prepare($query);

if (!$stmt) {
    error_log("Prepare failed: " . $mysqli->error);
    echo "failed"; // Or a more informative error message
    exit;
}

$stmt->bind_param("i", $uid);  // Bind parameters

if ($stmt->execute()) {
    $result = $stmt->get_result();

    if ($result && $result->num_rows == 1) { // Check for successful query and row count
        $row = $result->fetch_assoc();
        $hashed_password_from_db = $row["password"];

        // 2. Verify the old password
        if (password_verify($old, $hashed_password_from_db)) {
            // 3. Hash the new password
            $hashed_new_password = password_hash($newpass, PASSWORD_DEFAULT);

            // 4. Update the password in the database
            $sql = "UPDATE users SET password = ? WHERE id = ?"; //Updating in users table.
            $stmt2 = $mysqli->prepare($sql);

            if (!$stmt2) {
                error_log("Prepare failed: " . $mysqli->error);
                echo "failed";
                exit;
            }

            $stmt2->bind_param("si", $hashed_new_password, $uid);

            if ($stmt2->execute()) {
                echo "success";
            } else {
                error_log("Update failed: " . $stmt2->error);
                echo "failed";
            }
            $stmt2->close();
        } else {
            echo "Your old password is incorrect. Please try again."; // More user-friendly
        }
    } else {
        echo "Your old password is incorrect. Please try again."; // More user-friendly
    }
    $stmt->close();
} else {
    error_log("Execute failed: " . $stmt->error);
    echo "failed";
}

$mysqli->close(); // Close the connection
?>