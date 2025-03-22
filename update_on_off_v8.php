
<?php
// assets/php/update_on_off.php
session_start();

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Set the Content-Type header to application/json
header('Content-Type: application/json');

// Check if the user is logged in
if (!isset($_SESSION["HeliUser"])) {
    echo json_encode(["error" => "Not logged in"]);
    exit();
}

include_once "db_connect.php";
// include_once "check_login.php";

// Function to sanitize input
function sanitizeInput($input) {
    global $mysqli;
    return htmlspecialchars(mysqli_real_escape_string($mysqli, $input));
}

// Sanitize inputs
$field = sanitizeInput($_POST["name"]);
$id = intval($_POST["pk"]);
$value = sanitizeInput($_POST["value"]);
$on = sanitizeInput($_POST["on"]);
$off = sanitizeInput($_POST["off"]);

// Check if the id is valid
if ($id <= 0) {
    echo json_encode(["error" => "Invalid ID"]);
    exit;
}

// Validate field names, must match the database
$allowedFields = ['on_date', 'off_date'];
if (!in_array($field, $allowedFields)) {
    echo json_encode(["error" => "Invalid field"]);
    exit;
}

// Perform field-specific validation, must be a date.
if ($field == "on_date" || $field == "off_date") {
    // Validate date format (YYYY-MM-DD)
    if (!preg_match("/^\d{4}-\d{2}-\d{2}$/", $value)) {
        echo json_encode(["error" => "Invalid date format. Use YYYY-MM-DD."]);
        exit;
    }
}

try {
    if ($field == "on_date") {
        $checkField = "off_date";
        $checkVal = $off;

        $query = "SELECT `on_date` FROM user_availability WHERE user_id=? AND `$checkField`=?";
        $stmt = $mysqli->prepare($query);
        $stmt->bind_param("is", $id, $checkVal);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result && $result->num_rows > 0) {
            $ond = $result->fetch_assoc()["on_date"];
            // if the on time is being moved up in time, delete from the schedule the difference
            if (strtotime($ond) < strtotime($value)) {
                $delete = "DELETE FROM schedule WHERE sched_date>='$ond' AND sched_date<'$value' AND user_id=$id";
                $mysqli->query($delete); //No prepare statement needed here, since it is not user input involved.
            }
        }
    } else {
        $checkField = "on_date";
        $checkVal = $on;

        $query = "SELECT `off_date` FROM user_availability WHERE user_id=? AND `$checkField`=?";
        $stmt = $mysqli->prepare($query);
        $stmt->bind_param("is", $id, $checkVal);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result && $result->num_rows > 0) {
            $offd = $result->fetch_assoc()["off_date"];
            // if the off time is being moved backwards, delete from the schedule the difference
            if (strtotime($offd) > strtotime($value)) {
                $delete = "DELETE FROM schedule WHERE sched_date>'$value' AND sched_date <='$offd' AND user_id=$id";
                 $mysqli->query($delete);//No prepare statement needed here, since it is not user input involved.
            }
        }
    }

    $sql = "UPDATE user_availability SET `$field`=? WHERE user_id=? AND `$checkField`=?";
    $stmt = $mysqli->prepare($sql);
        //Check if the prepare statement was valid, because is possible the column not found or SQL is invalid.
    if(!$stmt){
        error_log("SQL prepare error: " . $mysqli->error);
        echo json_encode(["error" => "Database error: " .  $mysqli->error]);
        exit(); // IMPORTANT: Stop further execution
    }
    $stmt->bind_param("sis", $value, $id, $checkVal);
    if ($stmt->execute()) {
        echo json_encode(["success" => true]);
    } else {
        echo json_encode(["error" => "Failed to update the date: ". $stmt->error]);
    }
    $stmt->close();

} catch (Exception $e) {
    echo json_encode(["error" => "Database error: " . $e->getMessage()]);
} finally {
    if (isset($mysqli)) {
        $mysqli->close();
    }
    exit();
}
?>