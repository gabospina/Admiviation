<?php
error_reporting(E_ALL);

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// // Check if the user is logged in
// if (!isset($_SESSION["HeliUser"])) {
//     header("Location: hangarLdng.php");
//     exit();
// }

// Database connection
// include '../assets/php/db_connect.php';
include 'db_connect.php';

$lastHeliSelected = "";
if (isset($_COOKIE["lastHeliSelected"])) {
    $_SESSION["lastHeliSelected"] = $_COOKIE["lastHeliSelected"];
}
if (isset($_SESSION["lastHeliSelected"])) {
    $lastHeliSelected = $_SESSION["lastHeliSelected"];
}

// Set the page variable for header inclusion
$page = "hangar";
include_once "header.php";

// Get the current user's admin level
$adminLevel = intval($_SESSION["admin"]);

// Only show user management if the admin level allows it
if (in_array($adminLevel, array(8))) { // Check if admin level is 8
    // Fetch the user information from the 'users' table
    $user_id = $_SESSION["HeliUser"];

    // Prepared Statement
    $query = "SELECT * FROM users WHERE id = ?";
    $stmt = $mysqli->prepare($query);

    if ($stmt === false) {
        die("Error preparing statement: " . $mysqli->error);
    }

    $stmt->bind_param("i", $user_id);  // "i" indicates an integer

    if ($stmt->execute()) {
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        // Display the form for editing user details
        echo '<div class="light-bg" id="adminSection">';
        echo '<div class="container inner-sm">';
        echo '<h2>Edit User Information</h2>';

        echo '<form action="hangarLdng.php" method="POST">'; // Form submission will go to hangar.php

        echo '<label for="firstname">First Name*</label>';
        echo '<input type="text" id="firstname" name="firstname" value="' . htmlspecialchars($user['first_name']) . '" required><br>';

        echo '<label for="lastname">Last Name*</label>';
        echo '<input type="text" id="lastname" name="lastname" value="' . htmlspecialchars($user['last_name']) . '" required><br>';

        echo '<label for="email">Email</label>';
        echo '<input type="email" id="email" name="email" value="' . htmlspecialchars($user['email']) . '"><br>';

        echo '<label for="phone">Phone</label>';
        echo '<input type="text" id="phone" name="phone" value="' . htmlspecialchars($user['phone']) . '"><br>';

        echo '<label for="position">Position</label>';
        echo '<select id="position" name="position">';
        echo '<option value="pilot"' . ($user['position'] == 'pilot' ? ' selected' : '') . '>Pilot</option>';
        echo '<option value="manager"' . ($user['position'] == 'manager' ? ' selected' : '') . '>Manager</option>';
        echo '<option value="administrator"' . ($user['position'] == 'administrator' ? ' selected' : '') . '>Administrator</option>';
        echo '</select><br>';

        echo '<input type="hidden" name="user_id" value="' . $user_id . '">';
        echo '<button type="submit" name="updateUser" class="btn btn-primary">Save Changes</button>';
        echo '</form>';

        echo '</div>';
        echo '</div>';
    } else {
        echo "Error executing query: " . $stmt->error;
    }

    $stmt->close(); // Close the prepared statement
}

// Handling the form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['updateUser'])) {
    // Sanitize inputs - STILL REQUIRED, BUT PREPARED STATEMENTS ARE THE PRIMARY DEFENSE
    $firstname = mysqli_real_escape_string($mysqli, $_POST['firstname']);
    $lastname = mysqli_real_escape_string($mysqli, $_POST['lastname']);
    $email = mysqli_real_escape_string($mysqli, $_POST['email']);
    $phone = mysqli_real_escape_string($mysqli, $_POST['phone']);
    $position = mysqli_real_escape_string($mysqli, $_POST['position']);
    $user_id = intval($_POST['user_id']);

    // Prepared Statement for Update
    $update_query = "UPDATE users SET first_name=?, last_name=?, email=?, phone=?, position=? WHERE id=?";
    $stmt = $mysqli->prepare($update_query);

    if ($stmt === false) {
        die("Error preparing update statement: " . $mysqli->error);
    }

    $stmt->bind_param("sssssi", $firstname, $lastname, $email, $phone, $position, $user_id);

    if ($stmt->execute()) {
        echo "User information updated successfully!";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close(); // Close the update statement
}

// Always close the database connection when you're done
$mysqli->close(); //Close the connection here

?>