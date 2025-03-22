<?php
	// include_once "db_connect.php";
	// include_once "check_login.php";
	
	// $field = $_POST["name"];
	// if($field != "comandante" && $field != "training"){
	// 	$value = "\"".$_POST["value"]."\"";
	// }else{
	// 	$value = $_POST["value"];
	// }
	
	// $id = $_POST["pk"];

	// if($field == "username"){
	// 	$sql = "UPDATE login SET name=$value WHERE id=$id";
	// 	$update = $mysqli->query($sql);
	// 	if($update){
	// 		$success = true;
	// 	}else{
	// 		$success = false;
	// 	}
	// }else{
	// 	$sql = "UPDATE pilot_info SET $field=$value WHERE id=$id";
	// 	$result = $mysqli->query($sql);


	// 	if($result != FALSE){
	// 		print_r("success");
	// 	}else{
	// 		print_r($mysqli->error);
	// 	}
	// }
?>

<?php
include_once "db_connect.php";
include_once "check_login.php";

$field = $_POST["name"];
$value = $_POST["value"];
$id = $_POST["pk"];

if ($field == "username") {
    $sql = "UPDATE login SET name = ? WHERE id = ?";
} else {
    $sql = "UPDATE users SET $field = ? WHERE id = ?";
}

$stmt = $mysqli->prepare($sql);
$stmt->bind_param("si", $value, $id);

if ($stmt->execute()) {
    echo "success";
} else {
    echo $mysqli->error;
}

$stmt->close();
$mysqli->close();
?>