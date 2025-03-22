<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

include_once "db_connect.php";

// Check if the form was submitted AND if this is an AJAX request
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["ajax"])) {
    // Get the form data
    $companyName = $_POST['companyName'];
    $companyNationality = $_POST['companyNationality'];
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $userNationality = $_POST['userNationality'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Insert company data
    $company_sql = "INSERT INTO companies (company_name, operation_nationality) VALUES (?, ?)";
    $company_stmt = $mysqli->prepare($company_sql);

    if ($company_stmt) {
        $company_stmt->bind_param("ss", $companyName, $companyNationality);

        if ($company_stmt->execute()) {
            $company_id = $company_stmt->insert_id;
            error_log("Company inserted successfully with ID: " . $company_id);

            // Insert user data
            $user_sql = "INSERT INTO users (company_id, firstname, lastname, user_nationality, email, phone, username, password)
                         VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
            $user_stmt = $mysqli->prepare($user_sql);

            if ($user_stmt) {
                $user_stmt->bind_param("isssssss", $company_id, $firstname, $lastname, $userNationality, $email, $phone, $username, $hashed_password);

                if ($user_stmt->execute()) {
                    // After successful signup, create session and redirect
                    $user_id = $user_stmt->insert_id;
                    $_SESSION["HeliUser"] = $user_id;
                    $_SESSION["admin"] = 0;
                    $_SESSION["company_id"] = $company_id;

                    // Automatically log in the user
                    session_regenerate_id(true); // Prevent session fixation
                    $_SESSION["HeliUser"] = $user_id;
                    $_SESSION["admin"] = 0;
                    $_SESSION["company_id"] = $company_id;

                    echo "success";
                    exit();
                } else {
                    echo "Signup failed: " . htmlspecialchars($user_stmt->error);  // HTML encode!
                    error_log("Signup failed: " . $user_stmt->error); // Log the error
                    exit();
                }

                $user_stmt->close();
            } else {
                echo "Database error: " . htmlspecialchars($mysqli->error); // HTML encode!
                error_log("Database error (signup.php): " . $mysqli->error); // Log the error
                exit();
            }
        } else {
            echo "Company insertion failed: " . htmlspecialchars($company_stmt->error);  // HTML encode!
            error_log("Company insertion failed: " . $company_stmt->error); // Log the error
            exit();
        }

        $company_stmt->close();
    } else {
        echo "Database error: " . htmlspecialchars($mysqli->error); // HTML encode!
        error_log("Database error (signup.php): " . $mysqli->error); // Log the error
        exit();
    }

    $mysqli->close();
} else {
    // If the form was not submitted, or not an AJAX request, redirect to the signup page
    header("Location: admviationHome.php");
    exit();
}
?>
