
<?php
include_once "db_connect.php";

$pilots = json_decode($_POST["pilots"], true);
$craft_name = $_POST["craft"];

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

        // Prepare the SQL statement for inserting into craft_pilots
        $sql_insert = "INSERT INTO craft_pilots (craft_id, pilot_id) VALUES (?, ?)";
        $stmt_insert = $mysqli->prepare($sql_insert);

        if ($stmt_insert) {
            $stmt_insert->bind_param("ii", $craft_id, $pilot_id);
            $pilotSuccess = true;

            // Loop through the pilots and insert into craft_pilots
            foreach ($pilots as $pilot_id) {
                if (!$stmt_insert->execute()) {
                    $pilotSuccess = false;
                    error_log("Error inserting pilot into craft_pilots: " . $stmt_insert->error);
                    break; // Exit the loop on failure
                }
            }
            $stmt_insert->close();

            if ($pilotSuccess) {
                echo "success";
            } else {
                echo "failed";
            }
        } else {
            error_log("Error preparing insert statement: " . $mysqli->error);
            echo "failed";
        }
    } else {
        $stmt_get_craft_id->close();
        error_log("Craft not found: " . $craft_name);
        echo "failed";
    }
} else {
    error_log("Error preparing statement to get craft ID: " . $mysqli->error);
    echo "failed";
}

$mysqli->close();
?>


<?php
	// include_once "db_connect.php";

	// $pilots = json_decode($_POST["pilots"], true);
	// $craft = $_POST["craft"];

	// $pilotSuccess = true;
	// for($i = 0; $i < count($pilots); $i++){
	// 	$pilot = $pilots[$i];
	// 	$sql = "UPDATE pilot_info SET crafts = CONCAT(crafts, '$craft;') WHERE id=$pilot";
	// 	$update = $mysqli->query($sql);
	// 	if(!$update){
	// 		$pilotSuccess = false;
	// 	}
	// }
	// if($pilotSuccess){
	// 	print("success");
	// }else{
	// 	print("failed");
	// }
?>