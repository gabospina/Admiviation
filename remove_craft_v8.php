<?php
include_once "db_connect.php";

$craft_id = $_POST["craft"]; // Get the craft ID

// Initialize response array
$response = array();

// Use prepared statements to prevent SQL injection
$sql = "DELETE FROM craft_pilots WHERE craft_id = ?";  //delete from the junction table
$stmt = $mysqli->prepare($sql);

if ($stmt) {
    $stmt->bind_param("i", $craft_id);
    if ($stmt->execute()) {
        $stmt->close();
    } else {
        error_log("Error deleting from craft_pilots: " . $stmt->error);
        $stmt->close();
        $response["success"] = false;
        $response["message"] = "failed_craft_pilots";
        echo json_encode($response);
        $mysqli->close();
        exit;
    }
} else {
    error_log("Error preparing statement: " . $mysqli->error);
    $response["success"] = false;
    $response["message"] = "failed_prepare_craft_pilots";
    echo json_encode($response);
    $mysqli->close();
    exit;
}

$sql = "DELETE FROM crafts WHERE id = ?";  //delete the craft
$stmt = $mysqli->prepare($sql);

if ($stmt) {
    $stmt->bind_param("i", $craft_id);  // "i" - integer
    if ($stmt->execute()) {
        $stmt->close();
        $response["success"] = true;
        $response["message"] = "success";
    } else {
        error_log("Error deleting craft: " . $stmt->error); // Log the error
        $stmt->close();
        $response["success"] = false;
        $response["message"] = "failed_delete_craft";
    }
} else {
    error_log("Error preparing statement: " . $mysqli->error);
    $response["success"] = false;
    $response["message"] = "failed_prepare_craft";
}

echo json_encode($response);
$mysqli->close();
?>
