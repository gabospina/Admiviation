<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

include_once "db_connect.php";

if (!isset($_SESSION["HeliUser"]) || !isset($_SESSION["company_id"])) {
    $response = ["success" => false, "message" => "You are not logged in properly or company ID not found."];
    echo json_encode($response);
    exit;
}

$company_id = (int)$_SESSION["company_id"];

$response = array();

// $response = array();

// Contract query with JOIN to fetch customer name with company id verification
$craftQuery = "SELECT
    c.id,
    c.craft_type,
    c.registration,
    c.tod,
    c.alive,
    c.company_id
FROM
    crafts c
ORDER BY
    craft_type";

$stmt = $mysqli->prepare($craftQuery); // Use prepared statement
if ($stmt === false) {
    $response["success"] = false;
    $response["message"] = "Prepare failed: " . htmlspecialchars($mysqli->error);
    error_log("Prepare failed: " . $mysqli->error);
    header('Content-Type: application/json');
    echo json_encode($response);
    exit;
}

$stmt->execute();
$craftResult = $stmt->get_result();

if ($craftResult) {
    $crafts = array();
    while ($row = mysqli_fetch_assoc($craftResult)) {
        $crafts[] = array(
            "id" => $row["id"],
            "craft_type" => $row["craft_type"],
            "registration" => $row["registration"],
            "tod" => $row["tod"],
            "alive" => (bool)$row["alive"],
            "company_id" => $row["company_id"]
        );
    }
    $response["success"] = true;
    $response["crafts"] = $crafts;
} else {
    $response["success"] = false;
    $response["message"] = "Error fetching crafts: " . $mysqli->error;
    error_log("Error fetching crafts: " . $mysqli->error);
}

header('Content-Type: application/json');
echo json_encode($response);
$mysqli->close();
?>