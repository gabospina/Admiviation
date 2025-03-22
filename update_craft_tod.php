
<?php
	include_once "db_connect.php";
	// include_once "check_login.php";

	$craft_id = $_POST["pk"];
	$tod = $_POST["value"];

	// Use prepared statement to prevent SQL injection
	$sql = "UPDATE crafts SET tod = ? WHERE id = ?";
	$stmt = $mysqli->prepare($sql);

	if ($stmt) {
		$stmt->bind_param("si", $tod, $craft_id);  // "si" - string, integer

		if ($stmt->execute()) {
			$stmt->close();
			echo "success";
		} else {
			error_log("Error updating craft TOD: " . $stmt->error);
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
	// $tod = $_POST["value"];
	// $sql = "UPDATE crafts SET tod='$tod' WHERE id=$craft";
	// $update = $mysqli->query($sql);
	// if($update){
	// 	print("success");
	// }else{
	// 	print("failed");
	// }
?>