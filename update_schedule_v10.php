<?php
	// include_once "db_connect.php";
	// include_once "check_login.php";
	
	// $pk = $_POST["pk"]; //sched_date
	// $name = $_POST["name"]; //craft
	// $value = $_POST["value"]; //id
	// $pos = $_POST["pos"]; //pos
	
	// mysqli_autocommit($mysqli, TRUE);
	
	// if($value == 0){
	// 	$sql = "DELETE FROM schedule WHERE sched_date='$pk' AND pos='$pos' AND craft='$name'";
	// 	$result = $mysqli->query($sql);
	// 	if($result){
	// 		print_r(json_encode(array("name"=>($pos=="com"?"Comandante":"Piloto"), "date"=>$pk)));
	// 	}else{
	// 		print_r("false, msg: '".mysqli_error($mysqli)."'");
	// 	}
	// }else{

	// 	$query = "SELECT * FROM schedule WHERE sched_date='$pk' AND pos='$pos' AND craft='$name'";
	// 	$result = $mysqli->query($query);
	// 	if($result != FALSE){
	// 		if(mysqli_num_rows($result) != 0){
	// 			//update existing
	// 			$sql = "UPDATE schedule SET id=$value WHERE sched_date='$pk' AND pos='$pos' AND craft='$name'";
	// 		}else{
	// 			//create new entry
	// 			$sql = "INSERT INTO schedule VALUES(null, '$pk', '$name', $value, '$pos')";
	// 		}
	// 		$res = $mysqli->query($sql);
	// 		if($res){
	// 			$query = "SELECT fname, lname FROM pilot_info WHERE id=$value";
	// 			$result = $mysqli->query($query);
	// 			if($result != FALSE){
	// 				$row = mysqli_fetch_assoc($result);
	// 				$name = $row["lname"].". ".strtoupper(substr($row["fname"], 0, 1));
	// 				print_r(json_encode(array("name"=>$name, "id"=>$value)));
	// 			}else{
	// 				print_r("false, msg: ".mysqli_error($mysqli));
	// 			}
	// 		}else{
	// 			print_r("false, msg: ".mysqli_error($mysqli));
	// 		}
	// 	}else{
	// 		print_r("false, msg: '".mysqli_error($mysqli)."'");
	// 	}
	// }

	// mysqli_close($mysqli);	
?>

<?php
include_once "db_connect.php";
include_once "check_login.php";

$pk = $_POST["pk"]; // sched_date
$name = $_POST["name"]; // craft
$value = $_POST["value"]; // pilot id
$pos = $_POST["pos"]; // position (com/pil)

mysqli_autocommit($mysqli, TRUE);

if ($value == 0) {
    // Remove pilot assignment for the day
    $sql = "DELETE FROM schedule WHERE sched_date='$pk' AND pos='$pos' AND craft='$name'";
    $result = $mysqli->query($sql);
    if ($result) {
        echo json_encode(array("name" => ($pos == "com" ? "Comandante" : "Piloto"), "date" => $pk));
    } else {
        echo json_encode(array("error" => "Failed to delete assignment: " . $mysqli->error));
    }
} else {
    // Check if the pilot is already assigned for the day
    $query = "SELECT * FROM schedule WHERE sched_date='$pk' AND pos='$pos' AND craft='$name'";
    $result = $mysqli->query($query);

    if ($result != FALSE) {
        if (mysqli_num_rows($result) != 0) {
            // Update existing assignment
            $sql = "UPDATE schedule SET id=$value WHERE sched_date='$pk' AND pos='$pos' AND craft='$name'";
        } else {
            // Create new assignment
            $sql = "INSERT INTO schedule VALUES (null, '$pk', '$name', $value, '$pos')";
        }

        $res = $mysqli->query($sql);
        if ($res) {
            // Fetch pilot name
            $query = "SELECT fname, lname FROM pilot_info WHERE id=$value";
            $result = $mysqli->query($query);
            if ($result != FALSE) {
                $row = mysqli_fetch_assoc($result);
                $name = $row["lname"] . ", " . $row["fname"];
                echo json_encode(array("name" => $name, "id" => $value));
            } else {
                echo json_encode(array("error" => "Failed to fetch pilot name: " . $mysqli->error));
            }
        } else {
            echo json_encode(array("error" => "Failed to update schedule: " . $mysqli->error));
        }
    } else {
        echo json_encode(array("error" => "Failed to check schedule: " . $mysqli->error));
    }
}

mysqli_close($mysqli);
?>