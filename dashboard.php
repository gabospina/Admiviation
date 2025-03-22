<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}

echo "Welcome, " . $_SESSION['username'];
?>
<a href="logout.php">Logout</a>
