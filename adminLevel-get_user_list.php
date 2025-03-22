<?php
include_once "db_connect.php";

$user = $_SESSION["HeliUser"];
$account = $_SESSION["account"];

// Fetch users and their roles
$query = "
    SELECT p.id, CONCAT(p.fname,' ',p.lname) AS name, p.comandante, p.contracts, p.crafts, p.training, r.role_name 
    FROM pilot_info p 
    INNER JOIN login l ON l.id = p.id 
    INNER JOIN users_roles r ON l.role_id = r.id 
    WHERE p.account = $account AND p.id != $user 
    ORDER BY p.fname
";
$result = $mysqli->query($query);
$res = array();

if ($result != false) {
    while ($row = $result->fetch_assoc()) {
        $row["permissions"] = $row["role_name"]; // Use the role_name from users_roles
        $row["training"] = returnTraining($row["training"]);
        $row["position"] = ($row["comandante"] == 1 ? "PIC" : "SIC");
        array_push($res, $row);
    }
}

print(json_encode($res));

function returnTraining($training) {
    switch ($training) {
        case 0: return ""; break;
        case 1: return "tri"; break;
        case 2: return "tre"; break;
    }
}
?>