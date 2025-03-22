<?php
	include_once "db_connect.php";
	include_once "check_login.php";
	
	$id = $_POST["id"];
	$on = $_POST["on"];
	$off = $_POST["off"];

	$sql = "DELETE FROM available WHERE id=$id AND `on`='$on' AND `off`='$off'";
	$result = $mysqli->query($sql);
	if($result){
		print("success");
	}else{
		print(mysqli_error($mysqli));
	}

	$mysqli->close();
?>