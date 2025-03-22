<?php
	include_once "db_connect.php";

	$pilot = $_POST["pilot"];
	$craft = $_POST["craft"];

	$query = "SELECT crafts FROM pilot_info WHERE id=$pilot";
	$result = $mysqli->query($query);
	if($result != FALSE){
		
		$success = true;
		$row = mysqli_fetch_assoc($result);
		$craftRes = $row["crafts"];
		$craftStr = str_replace($craft.";", "", $craftRes);
		$sql = "UPDATE pilot_info SET crafts='$craftStr' WHERE id=$pilot";
		$update = $mysqli->query($sql);
		if(!$update){
			$success = false;
		}

		if($success){
			print("success");
		}else{
			print("failed update");
		}
	}else{
		print("failed query");
	}
?>