<?php
include_once "db_connect.php";

$pilot_id = $_POST["pilot_id"];
$craft_name = $_POST["craft_name"];

// First, get the craft ID based on the craft name
$sql_get_craft_id = "SELECT id FROM crafts WHERE craft = ?";
$stmt_get_craft_id = $mysqli->prepare($sql_get_craft_id);

if ($stmt_get_craft_id) {
    $stmt_get_craft_id->bind_param("s", $craft_name);
    $stmt_get_craft_id->execute();
    $result_craft_id = $stmt_get_craft_id->get_result();

    if ($result_craft_id->num_rows > 0) {
        $row_craft_id = $result_craft_id->fetch_assoc();
        $craft_id = $row_craft_id["id"];
        $stmt_get_craft_id->close();

        // Now check if the pilot is assigned to this craft in craft_pilots table
        $sql_check_assignment = "SELECT COUNT(*) FROM craft_pilots WHERE craft_id = ? AND pilot_id = ?";
        $stmt_check_assignment = $mysqli->prepare($sql_check_assignment);

        if ($stmt_check_assignment) {
            $stmt_check_assignment->bind_param("ii", $craft_id, $pilot_id);
            $stmt_check_assignment->execute();
            $stmt_check_assignment->bind_result($count);
            $stmt_check_assignment->fetch();
            $stmt_check_assignment->close();

            if ($count > 0) {
                echo "assigned";
            } else {
                echo "not_assigned";
            }
        } else {
            error_log("Error preparing check assignment statement: " . $mysqli->error);
            echo "error";
        }
    } else {
        $stmt_get_craft_id->close();
        error_log("Craft not found: " . $craft_name);
        echo "error";
    }
} else {
    error_log("Error preparing statement to get craft ID: " . $mysqli->error);
    echo "error";
}

$mysqli->close();
?>