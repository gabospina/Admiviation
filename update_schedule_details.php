<?php
	include_once "db_connect.php";
	if($_POST["value"] != "" && $_POST["value"] != " " && $_POST["value"] != "&nbsp;"){
		if($_POST["pk"] == "dne"){
			//insert
			$date = $_POST["date"];
			$craft = $_POST["name"];
			$value = $_POST["value"];
			$sql = "INSERT INTO schedule_details VALUES(null, '$date', '$craft', '$value')";
			if($mysqli->query($sql)){
				print("success ".$mysqli->insert_id);
			}else{
				print($mysqli->error);
			}
		}else{
			//update
			$sql = "UPDATE schedule_details SET value='".$_POST["value"]."' WHERE id=".$_POST["pk"];
			if($mysqli->query($sql)){
				print("success");
			}else{
				print($mysqli->error);
			}
		}
	}
?>