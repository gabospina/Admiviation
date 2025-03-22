<?php
include_once "db_connect.php";
// include_once "check_login.php"; //Consider to remove this. Not used.

// Start the session if it's not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Function to safely fetch pilot information
function getPilotInfo($mysqli, $pilid) {
    $query = "SELECT 
                users.username, 
                users.email, 
                users.phone,
                users.user_nationality,
                pilot_info.fname, 
                pilot_info.lname, 
                pilot_info.ang_license, 
                pilot_info.for_license,
                pilot_info.comandante,
                pilot_info.profile_picture, 
                users.phonetwo, 
                pilot_info.id
            FROM 
                pilot_info 
            INNER JOIN 
                users ON users.id = pilot_info.user_id 
            WHERE 
                users.id = ?";

    $prep = $mysqli->prepare($query);

    if ($prep === false) {
        error_log("get_pilot.php: Prepare failed: " . $mysqli->error);
        return null;
    }

    $prep->bind_param("i", $pilid);

    if ($prep->execute()) {
        $result = $prep->get_result();

        if ($result === false) {
            error_log("get_pilot.php: Get result failed: " . $prep->error);
            return null;
        }

        $row = $result->fetch_assoc();
        $prep->close();
        return $row;
    } else {
        error_log("get_pilot.php: Execute failed: " . $prep->error);
        $prep->close();
        return null;
    }
}

// Function to safely fetch pilot schedule
function getPilotSchedule($mysqli, $pilid) {
    $onOffAr = array();

    $on_off_query = "SELECT on_duty AS `on`, off_duty AS `off`, in_schedule AS inSched FROM pilot_schedule WHERE pilot_id = ?";
    $on_off_prep = $mysqli->prepare($on_off_query);

    if ($on_off_prep === false) {
        error_log("get_pilot.php: Prepare failed for pilot schedule: " . $mysqli->error);
        return $onOffAr;
    }

    $on_off_prep->bind_param("i", $pilid);

    if ($on_off_prep->execute()) {
        $on_off_result = $on_off_prep->get_result();

        if ($on_off_result === false) {
            error_log("get_pilot.php: Get result failed for pilot schedule: " . $on_off_prep->error);
            $on_off_prep->close();
            return $onOffAr;
        }

        while ($on_off_row = $on_off_result->fetch_assoc()) {
            $on = $on_off_row["on"];
            $off = $on_off_row["off"];

            if (strtotime($on) < strtotime(date("Y-m-d"))) {
                $checkOn = date("Y-m-d");
            } else {
                $checkOn = $on;
            }

            $inSchedSql = "SELECT DISTINCT id FROM schedule WHERE id=? AND sched_date>='$checkOn' AND sched_date<='$off'";
            $inSchedPrep = $mysqli->prepare($inSchedSql);
            $inSchedPrep->bind_param("i",$pilid);

            if ($inSchedPrep->execute()){
              $inSchedRes = $inSchedPrep->get_result();
              $numrows = $inSchedRes->num_rows;

              if ($inSchedRes != FALSE &&  $numrows > 0){
                $inSched = true;
              }else{
                $inSched = false;
              }
              $inSchedPrep->close();
            } else {
              error_log("get_pilot.php: Execute failed for pilot schedule: " . $inSchedPrep->error);
            }
            $tempAr = array("on" => $on, "off" => $off, "inSched" => $inSched);
            array_push($onOffAr, $tempAr);
        }

        $on_off_prep->close();
    } else {
        error_log("get_pilot.php: Execute failed for pilot schedule: " . $on_off_prep->error);
        $on_off_prep->close();
    }

    return $onOffAr;
}

// Main script execution
if (isset($_POST["id"])) {
    $pilid = ($_POST["id"] == "user") ? $_SESSION["HeliUser"] : $_POST["id"];

    if (!is_numeric($pilid)) {
        error_log("get_pilot.php: Invalid pilot ID: " . $pilid);
        echo json_encode(array("success" => false, "msg" => "Invalid pilot ID"));
        $mysqli->close();
        exit;
    }

    $pilid = intval($pilid); // Sanitize to integer

    $pilotInfo = getPilotInfo($mysqli, $pilid);

    if ($pilotInfo === null) {
        echo json_encode(array("success" => false, "msg" => "Couldn't access pilot info"));
        $mysqli->close();
        exit;
    }

    $res = array(
        "username" => array($pilotInfo["username"]),
        "email" => array($pilotInfo["email"]),
        "phone" => array($pilotInfo["phone"]),
        "user_nationality" => array($pilotInfo["user_nationality"]),
        "fname" => array($pilotInfo["fname"]),
        "lname" => array($pilotInfo["lname"]),
        "ang_license" => array($pilotInfo["ang_license"]),
        "for_license" => array($pilotInfo["for_license"]),
        "comandante" => array($pilotInfo["comandante"]),
        "profile_picture" => array($pilotInfo["profile_picture"]),
        "phonetwo" => array($pilotInfo["phonetwo"]),
        "id" => array($pilotInfo["id"]),
    );

    $onOffAr = getPilotSchedule($mysqli, $pilid);

    $res["onOff"] = array($onOffAr);

    print_r(json_encode($res));
} else {
    echo json_encode(array("success" => false, "msg" => "No pilot ID provided"));
}

$mysqli->close();
?>