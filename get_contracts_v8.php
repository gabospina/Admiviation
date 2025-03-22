<?php
// session_start(); // Ensure session is started
// include_once "db_connect.php"; // Include database connection

// // Initialize variables
// $contracts = [];
// $error_message = "";

// // Check if user is logged in
// if (!isset($_SESSION["HeliUser"])) {
//     $error_message = "Session not set. Redirecting to login page...";
//     header("Location: admviationHome.php");
//     exit();
// }

// // Fetch contracts from the database
// $sql = "SELECT id, name, start_date, end_date FROM contracts WHERE company_id = ?";
// $stmt = $mysqli->prepare($sql);
// $stmt->bind_param("i", $_SESSION["company_id"]);

// if ($stmt->execute()) {
//     $result = $stmt->get_result();
//     while ($row = $result->fetch_assoc()) {
//         $contracts[] = $row;
//     }
//     $stmt->close();
// } else {
//     $error_message = "Error fetching contracts: " . $mysqli->error;
// }

// // Output contracts as JSON
// echo json_encode($contracts);
?>



<?php
session_start();

include_once "db_connect.php";
$account = $_SESSION["account"];

$query = "SELECT DISTINCT id, name, color FROM contract_info WHERE account=$account ORDER BY `order`";
$result = $mysqli->query($query);
if($result != FALSE){
	$i = 0;
	$res = array();
	while($row = mysqli_fetch_assoc($result)){
		$res["id"][$i] = $row["id"];
		$res["name"][$i] = $row["name"];
		$res["color"][$i] = $row["color"];
		$i++;
	}
	// print_r(json_encode($res));
	echo json_encode($res);
}else{
	print("false");
}
?>