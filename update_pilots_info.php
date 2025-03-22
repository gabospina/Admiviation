<?php
	include_once "db_connect.php";
	include_once "check_login.php";
	
	$field = $_POST["name"];
	if($field != "comandante" && $field != "training"){
		$value = "\"".$_POST["value"]."\"";
	}else{
		$value = $_POST["value"];
	}
	
	$id = $_POST["pk"];

	if($field == "username"){
		$sql = "UPDATE login SET name=$value WHERE id=$id";
		$update = $mysqli->query($sql);
		if($update){
			print("success");
		}else{
			print($mysqli->error);
		}
	}else{
		$sql = "UPDATE pilot_info SET $field=$value WHERE id=$id";
		$result = $mysqli->query($sql);

		if($result != FALSE){
			print_r("success");
		}else{
			print_r($mysqli->error);
		}
	}
?>