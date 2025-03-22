<?php
// session_start();
// include_once "db_connect.php";

// if (!isset($_SESSION["HeliUser"])) {
//     echo json_encode(["success" => false, "message" => "User not logged in"]);
//     exit();
// }

// $craftTypeId = $_POST["craft_type_id"];
// $position = $_POST["position"];
// $isChecked = $_POST["is_checked"];

// // Update the user's PIC/SIC selection in the craft_types table
// $column = $position === "PIC" ? "PIC" : "SIC";
// $sql = "UPDATE pilot_craft_type SET $column = ? WHERE id = ?";
// $stmt = $mysqli->prepare($sql);
// $stmt->bind_param("ii", $isChecked, $craftTypeId);

// if ($stmt->execute()) {
//     echo json_encode(["success" => true]);
// } else {
//     echo json_encode(["success" => false, "message" => $stmt->error]);
// }

// $stmt->close();
// $mysqli->close();
?>

<?php
// Database connection
include_once "db_connect.php";

// Get the input data
$data = json_decode(file_get_contents('php://input'), true);
$pilot_id = $data['pilot_id'];
$craft_type = $data['craft_type'];
$position = $data['position'];

// Look up the craft_type_id based on the craft_type name
$sql = "SELECT id FROM crafts WHERE craft_type = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('s', $craft_type);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $craft_type_id = $row['id'];

    // Insert into pilot_craft_type table
    $sql = "INSERT INTO pilot_craft_type (pilot_id, craft_type_id, position) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('iis', $pilot_id, $craft_type_id, $position);

    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error saving craft type.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Craft type not found.']);
}

$stmt->close();
$conn->close();
?>
