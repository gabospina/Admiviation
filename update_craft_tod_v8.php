<?php
	include_once "db_connect.php";
	include_once "check_login.php";

	$craft = $_POST["pk"];
	$tod = $_POST["value"];
	$sql = "UPDATE crafts SET tod='$tod' WHERE id=$craft";
	$update = $mysqli->query($sql);
	if($update){
		print("success");
	}else{
		print("failed");
	}
?>