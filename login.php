<?php
// include_once "db_connect.php";

// // 1. Get the username and password from the POST request
// $username = $_POST['username'];
// $password = $_POST['password'];

// // 2. Retrieve the user's information from the users table
// $sql = "SELECT id, company_id, role_id, admin, password 
//         FROM users 
//         WHERE username = ?";

// $stmt = $mysqli->prepare($sql);
// $stmt->bind_param("s", $username);
// $stmt->execute();
// $result = $stmt->get_result();

// if ($result !== FALSE && mysqli_num_rows($result) > 0) {
//     $res = mysqli_fetch_assoc($result);

//     // Verify the password using password_verify() if passwords are hashed
//     if (password_verify($password, $res['password'])) {
//         // 3. User is authorized, proceed with login
//         if (session_status() == PHP_SESSION_NONE) {
//             session_start();
//         }
//         $_SESSION['HeliUser'] = $res["id"];
//         $_SESSION["expire"] = time() + (3 * 60 * 60);
//         $_SESSION['admin'] = $res['admin'];
//         $_SESSION["role_id"] = $res["role_id"]; // Store role_id in session
//         $_SESSION["company_id"] = $res["company_id"];  // Store the user's company ID in the session

//         // Return JSON response for success
//         echo json_encode(['success' => true]);
//         exit();
//     } else {
//         // Password is incorrect
//         echo json_encode(['success' => false, 'error' => 'Your password is incorrect']);
//         exit();
//     }
// } else {
//     // User not found
//     echo json_encode(['success' => false, 'error' => 'Invalid username or password']);
//     exit();
// }

// $stmt->close();
// mysqli_close($mysqli);
?>

<?php
include_once "db_connect.php";

// 1. Get the username and password from the POST request
$username = $_POST['username'];
$password = $_POST['password'];

// 2. Retrieve the user's information from the users table, using prepared statements
$sql = "SELECT id, company_id, role_id, admin, password 
        FROM users 
        WHERE username = ?";

$stmt = $mysqli->prepare($sql);

if ($stmt === false) {
    error_log("Prepare failed: " . $mysqli->error);
    echo json_encode(['success' => false, 'error' => 'Database error. Please try again.']);
    exit();
}

$stmt->bind_param("s", $username);
if (!$stmt->execute()) {
    error_log("Execute failed: " . $stmt->error);
    echo json_encode(['success' => false, 'error' => 'Database error. Please try again.']);
    exit();
}

$result = $stmt->get_result();

if ($result->num_rows > 0) {  // Changed from mysqli_num_rows for consistency
    $res = $result->fetch_assoc();

    // Verify the password using password_verify() if passwords are hashed
    if (password_verify($password, $res['password'])) {
        // 3. User is authorized, proceed with login
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        $_SESSION['HeliUser'] = $res["id"];
        $_SESSION["expire"] = time() + (3 * 60 * 60);
        $_SESSION['admin'] = $res['admin'];
        $_SESSION["role_id"] = $res["role_id"]; // Store role_id in session
        $_SESSION["company_id"] = $res["company_id"];  // Store the user's company ID in the session
        $_SESSION["login_id"] = $res["id"]; // Store user id in login id
        // Determine redirect URL based on role
        $redirect_url = '';
        switch ($res["role_id"]) {
            case 1: // Pilot
                $redirect_url = "hangar.php";
                break;
            case 2: // Schedule Manager Pilot
                $redirect_url = "schedule_manager.php";
                break;
            case 3: // Training Manager Pilot
                $redirect_url = "training_manager.php";
                break;
            case 4: // Manager Pilot
                $redirect_url = "manager_pilot.php";
                break;
            case 5: // Schedule Manager
                $redirect_url = "schedule_manager.php";
                break;
            case 6: // Training Manager
                $redirect_url = "training_manager.php";
                break;
            case 7: // Manager
                $redirect_url = "manager.php";
                break;
            case 8: // Admin
                $redirect_url = "admin_dashboard.php";
                break;
            case 9: // Admin Pilot
                $redirect_url = "admin_pilot.php";
                break;
            default:
                $redirect_url = "hangar.php"; // Default to hangar page
                break;
        }

        // Return JSON response for success AND the redirect URL
        echo json_encode(['success' => true, 'redirect' => $redirect_url]);
        exit();
    } else {
        // Password is incorrect
        echo json_encode(['success' => false, 'error' => 'Your password is incorrect']);
        exit();
    }
} else {
    // User not found
    echo json_encode(['success' => false, 'error' => 'Invalid username or password']);
    exit();
}

$stmt->close();
$mysqli->close();
?>