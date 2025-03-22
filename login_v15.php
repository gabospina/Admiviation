<?php
include_once "db_connect.php";

// 1. Get the username and password from the POST request
$username = $_POST['username'];
$password = $_POST['password'];

// 2. Retrieve the user's information from the users table
$sql = "SELECT id, company_id, role_id, admin, password 
        FROM users 
        WHERE username = ?";

$stmt = $mysqli->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result !== FALSE && mysqli_num_rows($result) > 0) {
    $res = mysqli_fetch_assoc($result);

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

        // Return JSON response for success
        echo json_encode(['success' => true]);
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
mysqli_close($mysqli);
?>

<?php
include_once "db_connect.php";

// 1. Get the username and password from the POST request
$username = $_POST['username'];
$password = $_POST['password'];

// 2. Retrieve the user's information from the users table
$sql = "SELECT id, company_id, role_id, admin, password 
        FROM users 
        WHERE username = ?";

$stmt = $mysqli->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result !== FALSE && mysqli_num_rows($result) > 0) {
    $res = mysqli_fetch_assoc($result);

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
        // Return JSON response for success
        echo json_encode(['success' => true]);
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
mysqli_close($mysqli);
?>