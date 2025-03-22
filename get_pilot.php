<?php
session_start();
require_once 'db_connect.php';

header('Content-Type: application/json');

try {
    if (!isset($_GET['id'])) {
        throw new Exception('Pilot ID is required');
    }

    $pilotId = $_GET['id'];
    $query = "SELECT * FROM pilots WHERE id = ?";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param('i', $pilotId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        throw new Exception('Pilot not found');
    }

    $pilotData = $result->fetch_assoc();

    // Fetch validity dates
    $validityQuery = "SELECT * FROM validity WHERE pilot_id = ?";
    $validityStmt = $mysqli->prepare($validityQuery);
    $validityStmt->bind_param('i', $pilotId);
    $validityStmt->execute();
    $validityResult = $validityStmt->get_result();

    $validityData = [];
    while ($row = $validityResult->fetch_assoc()) {
        $validityData[$row['field']] = $row['value'];
    }

    // Merge pilot data with validity data
    $pilotData = array_merge($pilotData, $validityData);

    echo json_encode(['success' => true, 'data' => $pilotData]);

} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
?>

	<!-- // include_once "db_connect.php";
	// include_once "check_login.php";

	// // if($_SESSION["admin"] == 1){
	// 	if($_POST["id"] == "user"){
	// 		$pilid = $_SESSION["HeliUser"];
	// 	}else{
	// 		$pilid = $_POST["id"];
	// 	}
	// 	$query = "SELECT p.*, l.name, l.admin FROM pilot_info p INNER JOIN login l ON l.id=p.id WHERE p.id=$pilid";
	// 	$result = $mysqli->query($query);
	// 	if($result != FALSE){
	// 		$res = array();
	// 		$i = 0;
	// 		while($row = mysqli_fetch_assoc($result)){
	// 			$res["id"][$i] = $row["id"];
	// 			$res["admin"][$i] = $row["admin"];
	// 			$res["fname"][$i] = $row["fname"];
	// 			$res["lname"][$i] = $row["lname"];
	// 			$res["username"][$i] = $row["name"];
	// 			$res["comandante"][$i] = $row["comandante"];
	// 			$res["nationality"][$i] = $row["nationality"];
	// 			$res["nal_license"][$i] = $row["nal_license"];
	// 			$res["for_license"][$i] = $row["for_license"];
	// 			$res["email"][$i] = $row["email"];
	// 			$res["phone"][$i] = $row["phone"];
	// 			$res["phonetwo"][$i] = $row["phonetwo"];
	// 			$res["contracts"][$i] = $row["contracts"];
	// 			$res["crafts"][$i] = $row["crafts"];
	// 			$res["training"][$i] = $row["training"];
	// 			$res["profile_picture"][$i] = $row["profile_picture"];
	// 			$i++;
	// 		}
	// 		$cur = date_create(date("Y-m-d"));
	// 		for($i = 0; $i < count($res["id"]); $i++){
	// 			$id = $res["id"][$i];
	// 			$query = "SELECT * FROM validity WHERE id=$id";
	// 			$valresult = $mysqli->query($query);
				
	// 			$allValid = true;
	// 			$monthValid = true;
	// 			if($valresult != FALSE){
	// 				$row = mysqli_fetch_assoc($valresult);
	// 				foreach($row as $key => $value){
	// 					if($row[$key] != null && $key != "id"){
	// 						$check = date_create(date("Y-m-d", strtotime($value)));
	// 						$interval = date_diff($cur, $check, FALSE);
	// 						$diff = (int) $interval->format('%r%a');
	// 						if(intval($diff) < 0){
	// 							$allValid = false;
	// 						}elseif(intval($diff) < 30){
	// 							$monthValid = false;
	// 						}
	// 					}/*elseif($key != "id"){
	// 						$allValid = false;
	// 					}*/
	// 				}
	// 				$res["allValid"][$i] = $allValid;
	// 				$res["monthValid"][$i] = $monthValid;
	// 				$res["validity"][$i] = $row;
	// 			}
	// 		}
			
	// 		for($i = 0; $i < count($res["id"]); $i++){
	// 			$id = $res["id"][$i];
	// 			$query = "SELECT `on`, `off` FROM available WHERE id=$id";
	// 			$result = $mysqli->query($query);
	// 			$onOffAr = array();
	// 			if($result != FALSE){
	// 				while($row = mysqli_fetch_assoc($result)){
	// 					$on = $row["on"];
	// 					$off = $row["off"];
	// 					if(strtotime($on) < strtotime(date("Y-m-d"))){
	// 						$checkOn = date("Y-m-d");
	// 					}else{
	// 						$checkOn = $on;
	// 					}
						
	// 					$inSchedSql = "SELECT DISTINCT id FROM schedule WHERE id=$id AND sched_date>='$checkOn' AND sched_date<='$off'";
	// 					$inSchedRes = $mysqli->query($inSchedSql);
	// 					$numrows = mysqli_num_rows($inSchedRes);

	// 					if($inSchedRes != FALSE &&  $numrows > 0){
	// 						$inSched = true;
	// 					}else{
	// 						$inSched = false;
	// 					}
	// 					$tempAr = array("on"=>$on, "off"=>$off, "inSched"=>$inSched);
	// 					array_push($onOffAr, $tempAr);
	// 				}
	// 			}
	// 			$res["onOff"][$i] = $onOffAr;
	// 		}
	// 		print_r(json_encode($res));
	// 	}else{
	// 		print_r(json_encode(array("success"=>false, "msg"=>"Couldn't access pilot info")));
	// 	}
	// // }else{
	// // 	print_r(json_encode(array("success"=>false, "msg"=>"Not Admin")));
	// // }

	// $mysqli->close(); -->
?>