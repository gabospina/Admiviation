<?php
include_once "db_connect.php";

$type = $_GET["type"]; // "com" or "pil"
$date = $_GET["date"]; // Selected date (YYYY-MM-DD)
$contract = $_GET["contract"]; // Contract name
$craft = $_GET["craft"]; // Craft class
$tod = $_GET["tod"]; // Time of day (day/night)

// Fetch pilots who are not already assigned for the selected day
$sql = "
    SELECT pi.id, pi.fname, pi.lname 
    FROM pilot_info pi
    WHERE pi.id NOT IN (
        SELECT s.user_id 
        FROM schedule s
        WHERE s.sched_date = '$date' AND s.pos = '$type'
    )
    AND pi.id IN (
        SELECT p.user_id 
        FROM contracts c
        JOIN contract_crafts cc ON c.id = cc.contract_id
        JOIN crafts cr ON cc.craft_id = cr.id
        JOIN pilots p ON cr.id = p.craft_id
        WHERE c.contract_name = '$contract' AND cr.craft_type = '$craft' AND cr.tod = '$tod'
    )
";

$result = $mysqli->query($sql);

if ($result) {
    $pilots = array();
    while ($row = $result->fetch_assoc()) {
        $pilots[] = array(
            "value" => $row["id"],
            "text" => $row["lname"] . ", " . $row["fname"]
        );
    }
    echo json_encode($pilots);
} else {
    echo json_encode(array("error" => "Failed to fetch pilots: " . $mysqli->error));
}

$mysqli->close();
?>