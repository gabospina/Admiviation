<?php
session_start();

// **DANGER:  NEVER DO THIS IN A PRODUCTION ENVIRONMENT!**
// **This is ONLY for TEMPORARY testing/debugging.**

// Simulate successful authentication (replace with your actual authentication logic)
$username = "gabo"; // Replace with your desired username
$password = "1234"; // Replace with your desired password

// **INSECURE:** For demonstration purposes only.  Never store passwords in plain text.
if ($username == "gabo" && $password == "1234") {
    // **Only set session variables AFTER authentication!**
    // $_SESSION["HeliUser"] = 64; // Replace with the actual user ID from your database
    // $_SESSION["username"] = $username; // Replace with the actual username
    // $_SESSION["admin"] = 0; // Replace with the actual access level

    // Redirect to the logged-in page
    header("Location: home.php");
    exit();
} else {
    echo "Authentication failed.";
}
?>