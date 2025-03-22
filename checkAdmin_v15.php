<?php
include_once "check_login.php"; // Ensure the user is logged in

// Check if the user is an admin
if (isset($_SESSION["admin"]) && $_SESSION["admin"] >= 7) { // Assuming level 7 is admin
    echo "true"; // User is an admin
} else {
    echo "false"; // User is not an admin
}
?>