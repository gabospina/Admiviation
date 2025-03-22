<?php
	include_once "db_connect.php";

	$date = $_POST["date"];
	$craft = $_POST["craft"];
	$pos = $_POST["pos"];
	$pilot = $_POST["pilot"];
	$in = $_POST["in"];
	$out = $_POST["out"];
	$daily = $_POST["daily"];
	$flown = $_POST["flown"];
	$landings = $_POST["landings"];
	if($_POST["recordID"] == "new"){
		$sql = "INSERT INTO scala_records VALUES(null, '$date', '$craft', $pilot, '$pos', '$in', '$out', $daily, $flown, $landings)";
		if($mysqli->query($sql)){
			print("success");
		}else{
			print("failed: ".$mysqli->error);
		}
	}else{
		$id = $_POST["recordID"];
		$update = "UPDATE scala_records SET `date`='$date', `craft`='$craft', `pilot_id`=$pilot, `punch_in`='$in', `punch_out`='$out', `daily`=$daily, `flown`=$flown, `landings`=$landings WHERE id=$id";
		if($mysqli->query($update)){
			print("success");
		}else{
			print($mysqli->error);
		}
	}
		
?>