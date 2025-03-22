<?php
	include_once "db_connect.php";
	include_once "check_login.php";

	$craft = $_POST["pk"];
	$alive = $_POST["value"];
	$sql = "UPDATE crafts SET alive=$alive WHERE id=$craft";
	$update = $mysqli->query($sql);
	if($update){
		print("success");
	}else{
		print("failed");
	}
?>