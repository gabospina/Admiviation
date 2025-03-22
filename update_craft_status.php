
<?php
	include_once "db_connect.php";
	// include_once "check_login.php";

	$craft_id = $_POST["craft"];
	$alive = $_POST["value"];

	// Use prepared statement to prevent SQL injection
	$sql = "UPDATE crafts SET alive = ? WHERE id = ?";
	$stmt = $mysqli->prepare($sql);

	if ($stmt) {
		$stmt->bind_param("ii", $alive, $craft_id);  // "ii" - integer, integer

		if ($stmt->execute()) {
			$stmt->close();
			echo "success";
		} else {
			error_log("Error updating craft status: " . $stmt->error);
			$stmt->close();
			echo "failed";
		}
	} else {
		error_log("Error preparing statement: " . $mysqli->error);
		echo "failed";
	}

	$mysqli->close();
?>

<?php
	// include_once "db_connect.php";
	// include_once "check_login.php";

	// $craft = $_POST["pk"];
	// $alive = $_POST["value"];
	// $sql = "UPDATE crafts SET alive=$alive WHERE id=$craft";
	// $update = $mysqli->query($sql);
	// if($update){
	// 	print("success");
	// }else{
	// 	print("failed");
	// }
?>