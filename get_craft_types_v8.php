<?php
session_start();
include_once "db_connect.php";

if (!isset($_SESSION["HeliUser"])) {
    echo json_encode(["success" => false, "message" => "User not logged in"]);
    exit();
}

$userId = $_SESSION["HeliUser"]["id"];

// Fetch the user's craft types
$sql = "SELECT id, type, is_pic, is_sic FROM craft_types WHERE user_id = ?";
$stmt = $mysqli->prepare($sql);
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();

$craftTypes = [];
while ($row = $result->fetch_assoc()) {
    $craftTypes[] = $row;
}

echo json_encode(["success" => true, "craft_types" => $craftTypes]);

$stmt->close();
$mysqli->close();
?>