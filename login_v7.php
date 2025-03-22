<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

include_once "db_connect.php"; // This file now contains your mysqli connection ($mysqli)

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // 1. Input Validation
    $username = trim($_POST["username"]); // Or "email" based on your system
    $password = $_POST["password"];

    if (empty($username) || empty($password)) {
        $error_message = "Please enter both username/email and password.";
    } else {
        // 2. Database Query (Using Prepared Statements for Security) -  NOW USING mysqli
        $sql = "SELECT id, username, password FROM users WHERE username = ?"; // Or WHERE email = ?
        $stmt = $mysqli->prepare($sql);  // Use $mysqli (from db_connect.php)

        if ($stmt) { // Check if the prepare statement was successful
            $stmt->bind_param("s", $username); //Or email
            $stmt->execute();
            $result = $stmt->get_result();  // Get the result set

            // 3. Check if user exists
            if ($result->num_rows == 1) {
                $row = $result->fetch_assoc();
                $user_id = $row["id"];
                $hashed_password = $row["password"];

                // 4. Password Verification
                if (password_verify($password, $hashed_password)) {
                    // 5. Session Creation
                    $_SESSION["user_id"] = $user_id;
                    $_SESSION["username"] = $row["username"]; // Or store other relevant user data

                    // 6. Redirection
                    // header("Location: dashboard.php");
                    header("Location: home.php"); // Replace with your logged-in page
                    // die("Login Redirect Test");
                    // exit();
                } else {
                    // Invalid password
                    $error_message = "Invalid username/email or password.";
                }
            } else {
                // User not found
                $error_message = "Invalid username/email or password.";
            }
            $stmt->close(); // Close the statement
        } else {
            // Error preparing the statement
            $error_message = "Database error: " . $mysqli->error;
            error_log("Database error (login.php): " . $mysqli->error);
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
</head>
<body>
    <h2>Login</h2>
    <?php if (isset($error_message)): ?>
        <p style="color: red;"><?php echo $error_message; ?></p>
    <?php endif; ?>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <label for="username">Username/Email:</label>
        <input type="text" id="username" name="username" required><br><br>

        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required><br><br>

        <button type="submit">Login</button>
    </form>
</body>
</html>