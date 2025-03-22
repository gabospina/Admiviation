
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