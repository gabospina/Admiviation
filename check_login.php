<?php
// Start the session if it's not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Check if the user is logged in and the session hasn't expired
if (isset($_SESSION["HeliUser"]) && isset($_SESSION["expire"]) && $_SESSION["expire"] >= time()) {
    // Extend the session expiration time (e.g., 3 hours)
    $_SESSION["expire"] = time() + (3 * 60 * 60);
} else {
    // Redirect to the login page if not logged in or session expired
    header("Location: admviation/home.php");
    exit(); // Stop further code execution
}
?>