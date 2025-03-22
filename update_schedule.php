<?php
include_once "db_connect.php";

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
            $sql = "UPDATE schedule SET user_id=$value WHERE sched_date='$pk' AND pos='$pos' AND craft='$name'";
        } else {
            // Create new entry
            $sql = "INSERT INTO schedule VALUES (null, $value, '$pk', '$name', '$pos', NULL, NULL, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP)";
        }

        $res = $mysqli->query($sql);
        if ($res) {
            // Fetch pilot name
            $query = "SELECT CONCAT(fname, ' ', lname) AS fullname FROM pilot_info WHERE id=$value";
            $result = $mysqli->query($query);
            if ($result != FALSE) {
                $row = mysqli_fetch_assoc($result);
                $name = $row["fullname"];
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
