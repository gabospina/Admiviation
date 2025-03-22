<?php
	if (session_status() == PHP_SESSION_NONE) {
    	session_start();
	}
	include_once "db_connect.php";

	$user = $_SESSION["HeliUser"];
	$name = $_POST["name"];
	$tz = $_POST["timezone"];

	$query = "UPDATE pilot_info SET clock_name='$name', clock_tz=$tz WHERE id=$user";
	$result = $mysqli->query($query);
	if($result){
		print("success");
	}else{
		print("failed");
	}

?>