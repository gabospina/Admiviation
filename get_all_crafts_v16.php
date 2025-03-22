<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

include_once "db_connect.php";

// Check if the user is logged in and has the company_id
if (!isset($_SESSION["HeliUser"]) || !isset($_SESSION["company_id"])) {
    $response = ["success" => false, "message" => "You are not logged in properly or company ID not found."];
    echo json_encode($response);
    exit;
}

$company_id = (int)$_SESSION["company_id"];

$response = array();

// Craft query with company_id verification
$craftQuery = "SELECT
    c.id,
    c.craft_type,
    c.registration,
    c.tod,
    c.alive,
    c.company_id
FROM
    crafts c
WHERE
    c.company_id = ?
ORDER BY
    c.craft_type";

$stmt = $mysqli->prepare($craftQuery); // Use prepared statement
if ($stmt === false) {
    $response["success"] = false;
    $response["message"] = "Prepare failed: " . htmlspecialchars($mysqli->error);
    error_log("Prepare failed: " . $mysqli->error);
    header('Content-Type: application/json');
    echo json_encode($response);
    exit();
}

// Bind the company_id parameter
$stmt->bind_param("i", $company_id);

// Execute the query
if (!$stmt->execute()) {
    $response["success"] = false;
    $response["message"] = "Execute failed: " . htmlspecialchars($stmt->error);
    error_log("Execute failed: " . $stmt->error);
    header('Content-Type: application/json');
    echo json_encode($response);
    exit();
}

// Fetch the result
$craftResult = $stmt->get_result();

if ($craftResult) {
    $crafts = array();
    while ($row = $craftResult->fetch_assoc()) {
        $crafts[] = array(
            "id" => $row["id"],
            "craft_type" => $row["craft_type"],
            "registration" => $row["registration"],
            "tod" => $row["tod"],
            "alive" => (bool)$row["alive"],
            "company_id" => $row["company_id"]
        );
    }
    $response["success"] = true;
    $response["crafts"] = $crafts;
} else {
    $response["success"] = false;
    $response["message"] = "Error fetching crafts: " . $mysqli->error;
    error_log("Error fetching crafts: " . $mysqli->error);
}

header('Content-Type: application/json');
echo json_encode($response);

$stmt->close();
$mysqli->close();
?>

<?php
// error_reporting(E_ALL);
// ini_set('display_errors', 1);

// session_start();  //Start session

// include_once "db_connect.php";

// // Check if the user is logged in and has the company_id
// if (!isset($_SESSION["HeliUser"]) || !isset($_SESSION["company_id"])) {
//     $response = ["success" => false, "message" => "You are not logged in properly or company ID not found."];
//     echo json_encode($response);
//     exit;
// }

// $company_id = (int)$_SESSION["company_id"];  //Get company id

// $response = array();

// // Contract query with JOIN to fetch customer name
// $craftQuery = "SELECT *
//                 FROM
//                     crafts
//                 ORDER BY
//                     craft_type";

// $craftResult = $mysqli->query($craftQuery);

// if ($craftResult) {
//     $crafts = array();
//     while ($row = mysqli_fetch_assoc($craftResult)) {

//         $crafts[] = array(
//             "id" => $row["id"],
//             "craft_type" => $row["craft_type"],
//             "registration" => $row["registration"],
//             "tod" => $row["tod"],
//             "alive" => (bool)$row["alive"],
//             "contract_id" => $row["contract_id"]
//         );
//     }
//     $response["success"] = true;
//     $response["crafts"] = $crafts;
// } else {
//     $response["success"] = false;
//     $response["message"] = "Error fetching crafts: " . $mysqli->error;
//     error_log("Error fetching crafts: " . $mysqli->error);
// }

// header('Content-Type: application/json');
// echo json_encode($response);
// $mysqli->close();
?>