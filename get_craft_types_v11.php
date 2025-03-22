<?php
// session_start();
// include_once "db_connect.php";

// if (!isset($_SESSION["HeliUser"])) {
//     echo json_encode(["success" => false, "message" => "User not logged in"]);
//     exit();
// }

// $userId = $_SESSION["HeliUser"]["id"];

// // Fetch the user's craft types
// $sql = "SELECT id, type, is_pic, is_sic FROM craft_types WHERE user_id = ?";
// $stmt = $mysqli->prepare($sql);
// $stmt->bind_param("i", $userId);
// $stmt->execute();
// $result = $stmt->get_result();

// $craftTypes = [];
// while ($row = $result->fetch_assoc()) {
//     $craftTypes[] = $row;
// }

// echo json_encode(["success" => true, "craft_types" => $craftTypes]);

// $stmt->close();
// $mysqli->close();
?>

<?php
session_start();
include_once "db_connect.php";

// Check if the user is logged in
if (!isset($_SESSION["HeliUser"])) {
    echo json_encode(["success" => false, "message" => "User not logged in"]);
    exit();
}

$userId = $_SESSION["HeliUser"]["id"]; // Get the logged-in user's ID

// Fetch the user's craft types and positions from the pilot_craft_type table
$sql = "
    SELECT 
        c.id AS craft_type_id, 
        c.craft_type, 
        pct.position 
    FROM 
        pilot_craft_type pct
    JOIN 
        crafts c ON pct.craft_type_id = c.id
    WHERE 
        pct.pilot_id = ?
";
$stmt = $mysqli->prepare($sql);
$stmt->bind_param("i", $userId); // Bind the user ID to the query
$stmt->execute();
$result = $stmt->get_result();

$craftTypes = [];
while ($row = $result->fetch_assoc()) {
    $craftTypes[] = [
        "id" => $row["craft_type_id"],
        "craft_type" => $row["craft_type"],
        "position" => $row["position"], // PIC or SIC
    ];
}

// Return the data as JSON
echo json_encode(["success" => true, "craft_types" => $craftTypes]);

$stmt->close();
$mysqli->close();
?>