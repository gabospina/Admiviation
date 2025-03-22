<?php
session_start();
include_once "db_connect.php";

if (!isset($_SESSION["HeliUser"])) {
    echo json_encode(["success" => false, "message" => "User not logged in"]);
    exit();
}

$craftTypeId = $_POST["craft_type_id"];

// Delete the craft type from the database
$sql = "DELETE FROM craft_types WHERE id = ?";
$stmt = $mysqli->prepare($sql);
$stmt->bind_param("i", $craftTypeId);

if ($stmt->execute()) {
    echo json_encode(["success" => true]);
} else {
    echo json_encode(["success" => false, "message" => $stmt->error]);
}

$stmt->close();
$mysqli->close();
?>