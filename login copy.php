<?php
include_once "db_connect.php";

// 1. Get the username and password from the POST request
$username = $_POST['username'];
$password = $_POST['password'];

// 2. Retrieve the user's information (including company_id) from the database
//    Also, include login id
$sql = "SELECT l.id, l.admin, u.role_id, u.company_id, l.id as login_id
        FROM login l
        INNER JOIN users u ON u.id = l.id
        WHERE l.name = '$username' AND l.password = '$password'";

$result = $mysqli->query($sql);

if ($result !== FALSE && mysqli_num_rows($result) > 0) {
    $res = mysqli_fetch_assoc($result);
    $user_company_id = $res['company_id'];
    $login_id = $res['login_id'];

    // 3. User is authorized, proceed with login (No company check needed here)
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
    $id = $res["id"];
    $_SESSION['HeliUser'] = $id;
    $_SESSION["expire"] = time() + (3 * 60 * 60);
    $_SESSION['admin'] = $res['admin'];
    $_SESSION["role_id"] = $res["role_id"]; // Store role_id in session
    $_SESSION["company_id"] = $user_company_id;  // Store the user's company ID in the session for later use
    $_SESSION["login_id"] = $login_id;

        // Redirect based on role
        switch ($res["role_id"]) {
            case 1: // Pilot
                header("Location: hangar.php");
                break;
            case 2: // Schedule Manager Pilot
                header("Location: schedule_manager.php");
                break;
            case 3: // Training Manager Pilot
                header("Location: training_manager.php");
                break;
            case 4: // Manager Pilot
                header("Location: manager_pilot.php");
                break;
            case 5: // Schedule Manager
                header("Location: schedule_manager.php");
                break;
            case 6: // Training Manager
                header("Location: training_manager.php");
                break;
            case 7: // Manager
                header("Location: manager.php");
                break;
            case 8: // Admin
                header("Location: admin_dashboard.php");
                break;
            case 9: // Admin Pilot
                header("Location: admin_pilot.php");
                break;
            default:
                header("Location: hangar.php"); // Default to hangar page
                break;
        }
        exit();
    }else{
        header("Location: admviationHome.php?error=Your password is incorrect; ". $mysqli->error);
    }


mysqli_close($mysqli);
?>