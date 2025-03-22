<?php
// Start the session if it's not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Check if the user is logged in and the session hasn't expired
if (isset($_SESSION["HeliUser"]) && isset($_SESSION["expire"]) && $_SESSION["expire"] >= time()) {
    // Extend the session expiration time
    $_SESSION["expire"] = (time() + (3 * 60 * 60));
} else {
    // Redirect to the login page
    header("Location: admviation/home.php");
    exit(); // Important: Stop further code execution
}
?>