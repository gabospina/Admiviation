
<?php
include_once "db_connect.php";

$sql = "SELECT id, CONCAT(firstname, ' ', lastname) AS fullname FROM users WHERE access_level = 0";
$result = $mysqli->query($sql);

$pilots = [];
while ($row = $result->fetch_assoc()) {
    $pilots[] = $row;
}

echo json_encode($pilots);
?>

<?php
// include_once "db_connect.php";

// $response = array();
// $response['success'] = false;
// $response['message'] = '';
// $response['pilots'] = array();

// // Query to fetch all pilots
// $sql = "SELECT id, CONCAT(fname, ' ', lname) AS fullname FROM users WHERE access_level = 0";  //<----- MODIFIED THE QUERY
// $result = $mysqli->query($sql);

// if ($result) {
//     $pilots = array();
//     while ($row = $result->fetch_assoc()) {
//         $pilots[] = $row;
//     }
//     $response["success"] = true;
//     $response["pilots"] = $pilots;
// } else {
//     $response["success"] = false;
//     $response["message"] = "Error fetching pilots: " . $mysqli->error;
//     error_log("Error fetching pilots: " . $mysqli->error);
// }

// header('Content-Type: application/json');
// echo json_encode($response);

// $mysqli->close();
?>

<?php
// include_once "db_connect.php";

// // Updated SQL query with INNER JOIN to get role_name
// $sql = "SELECT 
//             u.id, 
//             u.firstname, 
//             u.lastname, 
//             CONCAT(u.firstname, ' ', u.lastname) AS fullname,
//             u.username, 
//             u.user_nationality,
//             ur.role_name AS job_position,  -- get name from table
//             u.nal_license, 
//             u.for_license, 
//             u.email, 
//             u.phone, 
//             u.phonetwo, 
//             u.access_level
//         FROM users u
//         INNER JOIN users_roles ur ON u.role_id = ur.id   -- Use INNER JOIN
//         WHERE u.is_active = 1";

// $result = $mysqli->query($sql);

// $pilots = [];
// while ($row = $result->fetch_assoc()) {
//     $pilots[] = $row;
// }

// echo json_encode($pilots);
// $mysqli->close();
?>