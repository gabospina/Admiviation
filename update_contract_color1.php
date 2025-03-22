<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include_once "db_connect.php";

$response = array();

if (isset($_POST['contract_id']) && !empty($_POST['contract_id']) && isset($_POST['color']) && !empty($_POST['color'])) {
    $contractId = intval($_POST['contract_id']);
    $color = trim($_POST['color']);

    // Validate the color
    if (!preg_match('/^#[a-f0-9]{6}$/i', $color)) {
        $response['success'] = false;
        $response['message'] = "Invalid color format. Use #RRGGBB.";
        echo json_encode($response);
        exit;
    }

    $query = "UPDATE contracts SET color = ? WHERE id = ?";
    $stmt = $mysqli->prepare($query);

    if ($stmt) {
        $stmt->bind_param("si", $color, $contractId);
        if ($stmt->execute()) {
            $response['success'] = true;
            $response['message'] = "Contract color updated successfully.";
        } else {
            $response['success'] = false;
            $response['message'] = "Error updating contract color: " . $stmt->error;
            error_log("Error updating contract color: " . $stmt->error);
        }
        $stmt->close();
    } else {
        $response['success'] = false;
        $response['message'] = "Error preparing statement: " . $mysqli->error;
        error_log("Error preparing statement: " . $mysqli->error);
    }
} else {
    $response['success'] = false;
    $response['message'] = "Invalid data received.";
}

header('Content-Type: application/json');
echo json_encode($response);
$mysqli->close();
?>