<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION["uid"]) || $_SESSION["uid"] == null) {
    echo "false"; // User is not logged in
} else {
    echo $_SESSION["uid"]; // Return the user ID
}
?>