<?php
// login.php

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include database connection
include '../assets/php/db_connect.php'; // Adjust the path if needed

// Start session
session_start();

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve and sanitize form data
    $username = mysqli_real_escape_string($mysqli, $_POST['username']);
    $password = mysqli_real_escape_string($mysqli, $_POST['password']);

    // Debugging: Log the username and password entered
    error_log("Attempting login with Username: $username and Password: $password");

    // Query to fetch user details from 'users' table
    $sql = "SELECT id, password, admin_level FROM users WHERE username = ?";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->bind_result($user_id, $db_password, $admin_level);

    if ($stmt->fetch()) {
        // Debugging: Log the fetched password
        error_log("Fetched Password from DB: $db_password");
        
        // Verify the password
        // if (password_verify($password, $hashed_password)) {
        if ($password === $db_password) {
            // Password is correct, set session variables
            $_SESSION['HeliUser'] = $user_id;
            $_SESSION['admin'] = $admin_level;

            // Set cookies if "keepSignedIn" is checked
            // if (isset($_POST["keepSignedIn"]) && $_POST["keepSignedIn"] == "Yes") {
            //     setcookie("HeliUser", $user_id, time() + 36000000, "/");
            //     setcookie("admin", $admin_level, time() + 36000000, "/");
            // }

            // Redirect to dashboard
            header("Location: hangarLdng.php");
            exit();
        } else {
            // Incorrect password
            header("Location: indexLdng.php?error=Incorrect password.");
            exit();
        }
    } else {
        // Username not found
        header("Location: indexLdng.php?error=User does not exist.");
        exit();
    }
}
?>

<!-- HTML Form for Login -->
<form action="loginLdng.php" method="post">
    <input type="text" name="username" placeholder="Username" required>
    <input type="password" name="password" placeholder="Password" required>
    <button type="submit">Login</button>
</form>

<!-- <?php
// loginTest.php

// Enable error reporting for debugging
// error_reporting(E_ALL);
// ini_set('display_errors', 1);

// // Include database connection
// include 'db_connect.php';

// // Start session
// session_start();

// // Check if form is submitted
// if ($_SERVER['REQUEST_METHOD'] == 'POST') {
//     // Retrieve and sanitize form data
//     $username = mysqli_real_escape_string($mysqli, $_POST['username']);
//     $password = mysqli_real_escape_string($mysqli, $_POST['password']);

//     // Query to fetch user details from 'login' table
//     $sql = "SELECT login_id, password, access_level FROM login WHERE username = ?";
//     $stmt = $mysqli->prepare($sql);
//     $stmt->bind_param("s", $username);
//     $stmt->execute();
//     $stmt->bind_result($login_id, $hashed_password, $access_level);

//     if ($stmt->fetch()) {
//         // Verify the password
//         if (password_verify($password, $hashed_password)) {
//             // Password is correct, set session variables
//             $_SESSION['HeliUser'] = $login_id;
//             $_SESSION['admin'] = $access_level;

//             // Fetch account_id from 'pilot_info' table
//             $stmt->close();
//             $sql_account = "SELECT account_id FROM pilot_info WHERE user_id = ? LIMIT 1";
//             $stmt_account = $mysqli->prepare($sql_account);
//             $stmt_account->bind_param("i", $login_id);
//             $stmt_account->execute();
//             $stmt_account->bind_result($account_id);
//             if ($stmt_account->fetch()) {
//                 $_SESSION['account'] = $account_id;
//             } else {
//                 $_SESSION['account'] = null; // Handle if no account found
//             }
//             $stmt_account->close();

//             // Update user tracking
//             $sql_tracking = "SELECT id FROM user_tracking WHERE id = ?";
//             $stmt_tracking = $mysqli->prepare($sql_tracking);
//             $stmt_tracking->bind_param("i", $login_id);
//             $stmt_tracking->execute();
//             $stmt_tracking->store_result();

//             if ($stmt_tracking->num_rows == 0) {
//                 // Insert new tracking record
//                 $current_time = time();
//                 $stmt_tracking->close();
//                 $sql_insert_tracking = "INSERT INTO user_tracking (id, logins, last_login_time) VALUES (?, 1, ?)";
//                 $stmt_insert_tracking = $mysqli->prepare($sql_insert_tracking);
//                 $stmt_insert_tracking->bind_param("ii", $login_id, $current_time);
//                 $stmt_insert_tracking->execute();
//                 $stmt_insert_tracking->close();
//             } else {
//                 // Update existing tracking record
//                 $stmt_tracking->close();
//                 $current_time = time();
//                 $sql_update_tracking = "UPDATE user_tracking SET logins = logins + 1, last_login_time = ? WHERE id = ?";
//                 $stmt_update_tracking = $mysqli->prepare($sql_update_tracking);
//                 $stmt_update_tracking->bind_param("ii", $current_time, $login_id);
//                 $stmt_update_tracking->execute();
//                 $stmt_update_tracking->close();
//             }

//             // Set cookies if "keepSignedIn" is checked
//             if (isset($_POST["keepSignedIn"]) && $_POST["keepSignedIn"] == "Yes") {
//                 setcookie("HeliUser", $login_id, time() + 36000000, "/");
//                 setcookie("admin", $access_level, time() + 36000000, "/");
//                 setcookie("account", $account_id, time() + 36000000, "/");
//             }

//             // Redirect to dashboard
//             header("Location: ../../dashboard.php");
//             exit();
//         } else {
//             // Incorrect password
//             header("Location: ../../landing/index.php?error=Incorrect password.");
//             exit();
//         }
//     } else {
//         // Username not found
//         header("Location: ../../landing/index.php?error=User does not exist.");
//         exit();
//     }
// }
?> -->


