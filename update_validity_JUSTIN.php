<?php
	include_once "db_connect.php";
	include_once "check_login.php";
	
	$test = $_POST["name"];
	$value = $_POST["value"];
	if($_POST["pk"] !== "holder"){
		$id = $_POST["pk"];
	}else{
		$id = $_SESSION["HeliUser"];
	}
	
	// switch($test){
	// 	case "for_lic":
	// 		if($value != ""){
	// 			$value = "'".date("Y-m-d", strtotime($value." + 24 months"))."'";
	// 		}else{
	// 			$value = "null";
	// 		}
	// 	break;
	// 	case "ang_lic":
	// 		if($value != ""){
	// 				$value = "'".date("Y-m-d", strtotime($value." + 6 months"))."'";
	// 			}else{
	// 				$value = "null";
	// 			}
	// 	break;
	// 	case "passport":
	// 		if($value != ""){
	// 				$value = "'".date("Y-m-d", strtotime($value." + 48 months"))."'";
	// 			}else{
	// 				$value = "null";
	// 			}
	// 	break;
	// 	case "ang_visa":
	// 		if($value != ""){
	// 				$value = "'".date("Y-m-d", strtotime($value." + 12 months"))."'";
	// 			}else{
	// 				$value = "null";
	// 			}
	// 	break;
	// 	case "us_visa":
	// 			if($value != ""){
	// 				$value = "'".date("Y-m-d", strtotime($value." + 12 months"))."'";
	// 			}else{
	// 				$value = "null";
	// 			}
	// 	break;
	// 	case "instruments":
	// 			if($value != ""){
	// 				$value = "'".date("Y-m-d", strtotime($value." + 12 months"))."'";
	// 			}else{
	// 				$value = "null";
	// 			}
	// 	break;
	// 	case "med":
	// 			if($value != ""){
	// 				$value = "'".date("Y-m-d", strtotime($value." + 6 months"))."'";
	// 			}else{
	// 				$value = "null";
	// 			}
	// 	break;
	// 	case "booklet":
	// 			if($value != ""){
	// 				$value = "'".date("Y-m-d", strtotime($value." + 12 months"))."'";
	// 			}else{
	// 				$value = "null";
	// 			}
	// 	break;
	// 	case "sim":
	// 			if($value != ""){
	// 				$value = "'".date("Y-m-d", strtotime($value." + 12 months"))."'";
	// 			}else{
	// 				$value = "null";
	// 			}
	// 	break;
	// 	case "train_rec":
	// 		if($value != ""){
	// 				$value = "'".date("Y-m-d", strtotime($value." + 12 months"))."'";
	// 			}else{
	// 				$value = "null";
	// 			}
	// 	break;
	// 	case "flight_train":
	// 			if($value != ""){
	// 				$value = "'".date("Y-m-d", strtotime($value." + 12 months"))."'";
	// 			}else{
	// 				$value = "null";
	// 			}
	// 	break;
	// 	case "base_check":
	// 			if($value != ""){
	// 				$value = "'".date("Y-m-d", strtotime($value." + 6 months"))."'";
	// 			}else{
	// 				$value = "null";
	// 			}
	// 	break;
	// 	case "night_cur":
	// 			if($value != ""){
	// 				$value = "'".date("Y-m-d", strtotime($value." + 3 months"))."'";
	// 			}else{
	// 				$value = "null";
	// 			}
	// 	break;
	// 	case "night_check":
	// 			if($value != ""){
	// 				$value = "'".date("Y-m-d", strtotime($value." + 6 months"))."'";
	// 			}else{
	// 				$value = "null";
	// 			}
	// 	break;
	// 	case "ifr_cur":
	// 			if($value != ""){
	// 				$value = "'".date("Y-m-d", strtotime($value." + 3 months"))."'";
	// 			}else{
	// 				$value = "null";
	// 			}
	// 	break;
	// 	case "ifr_check":
	// 			if($value != ""){
	// 				$value = "'".date("Y-m-d", strtotime($value." + 6 months"))."'";
	// 			}else{
	// 				$value = "null";
	// 			}
	// 	break;
	// 	case "line_check":
	// 			if($value != ""){
	// 				$value = "'".date("Y-m-d", strtotime($value." + 12 months"))."'";
	// 			}else{
	// 				$value = "null";
	// 			}
	// 	break;
	// 	case "hoist_check":
	// 			if($value != ""){
	// 				$value = "'".date("Y-m-d", strtotime($value." + 6 months"))."'";
	// 			}else{
	// 				$value = "null";
	// 			}
	// 	break;
	// 	case "hoist_cur":
	// 			if($value != ""){
	// 				$value = "'".date("Y-m-d", strtotime($value." + 3 months"))."'";
	// 			}else{
	// 				$value = "null";
	// 			}
	// 	break;
	// 	case "crm":
	// 			if($value != ""){
	// 				$value = "'".date("Y-m-d", strtotime($value." + 12 months"))."'";
	// 			}else{
	// 				$value = "null";
	// 			}
	// 	break;
	// 	case "hook":
	// 			if($value != ""){
	// 				$value = "'".date("Y-m-d", strtotime($value." + 6 months"))."'";
	// 			}else{
	// 				$value = "null";
	// 			}
	// 	break;
	// 	case "herds":
	// 			if($value != ""){
	// 				$value = "'".date("Y-m-d", strtotime($value." + 12 months"))."'";
	// 			}else{
	// 				$value = "null";
	// 			}
	// 	break;
	// 	case "dang_good":
	// 			if($value != ""){
	// 				$value = "'".date("Y-m-d", strtotime($value." + 24 months"))."'";
	// 			}else{
	// 				$value = "null";
	// 			}
	// 	break;
	// 	case "huet":
	// 			if($value != ""){
	// 				$value = "'".date("Y-m-d", strtotime($value." + 36 months"))."'";
	// 			}else{
	// 				$value = "null";
	// 			}
	// 	break;
	// 	case "english":
	// 			if($value != ""){
	// 				$value = "'".date("Y-m-d", strtotime($value." + 84 months"))."'";
	// 			}else{
	// 				$value = "null";
	// 			}
	// 	break;
	// 	case "faids":
	// 			if($value != ""){
	// 				$value = "'".date("Y-m-d", strtotime($value." + 60 months"))."'";
	// 			}else{
	// 				$value = "null";
	// 			}
	// 	break;
	// 	case "fire":
	// 			if($value != ""){
	// 				$value = "'".date("Y-m-d", strtotime($value." + 36 months"))."'";
	// 			}else{
	// 				$value = "null";
	// 			}
	// 	break;
	// }

	if($value != ""){
		$value = "'".$value."'";
	}else{
		$value = "null";
	}
	$sql = "UPDATE validity SET $test=$value WHERE id=$id";
	$result = $mysqli->query($sql);
	if($result != FALSE){
		print_r($value);
	}else{
		print_r(mysqli_error($mysqli));
	}

	mysqli_close($mysqli);
?>