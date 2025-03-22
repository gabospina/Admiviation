
<?php
include_once "db_connect.php";

$pilot_id = $_POST["pilot"];
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

        // Prepare the SQL statement for deleting from craft_pilots
        $sql_delete = "DELETE FROM craft_pilots WHERE craft_id = ? AND pilot_id = ?";
        $stmt_delete = $mysqli->prepare($sql_delete);

        if ($stmt_delete) {
            $stmt_delete->bind_param("ii", $craft_id, $pilot_id);

            if ($stmt_delete->execute()) {
                echo "success";
            } else {
                error_log("Error deleting pilot from craft_pilots: " . $stmt_delete->error);
                echo "failed delete";
            }
            $stmt_delete->close();
        } else {
            error_log("Error preparing delete statement: " . $mysqli->error);
            echo "failed delete";
        }
    } else {
        $stmt_get_craft_id->close();
        error_log("Craft not found: " . $craft_name);
        echo "failed craft";
    }
} else {
    error_log("Error preparing statement to get craft ID: " . $mysqli->error);
    echo "failed craft";
}

$mysqli->close();
?>

<?php
	// include_once "db_connect.php";

	// $pilot = $_POST["pilot"];
	// $craft = $_POST["craft"];

	// $query = "SELECT crafts FROM pilot_info WHERE id=$pilot";
	// $result = $mysqli->query($query);
	// if($result != FALSE){
		
	// 	$success = true;
	// 	$row = mysqli_fetch_assoc($result);
	// 	$craftRes = $row["crafts"];
	// 	$craftStr = str_replace($craft.";", "", $craftRes);
	// 	$sql = "UPDATE pilot_info SET crafts='$craftStr' WHERE id=$pilot";
	// 	$update = $mysqli->query($sql);
	// 	if(!$update){
	// 		$success = false;
	// 	}

	// 	if($success){
	// 		print("success");
	// 	}else{
	// 		print("failed update");
	// 	}
	// }else{
	// 	print("failed query");
	// }
?>