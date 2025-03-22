<?php
include_once "db_connect.php";
session_start();  // Start the session

// Retrieve user_id and company_id from session
$user_id = isset($_SESSION["HeliUser"]) ? (int)$_SESSION["HeliUser"] : 0;
$company_id = isset($_SESSION["company_id"]) ? (int)$_SESSION["company_id"] : 0;

if ($user_id <= 0 || $company_id <= 0) {
    error_log("User or company ID not found in session.");
    echo "failed";
    exit;
}

$pilots = json_decode($_POST["pilots"], true);
$craft_name = $_POST["craft"];

// First, get the craft ID based on the craft name AND company_id
$sql_get_craft_id = "SELECT c.id 
                      FROM crafts c
                      INNER JOIN contracts_crafts cc ON c.id = cc.craft_type_id
                      INNER JOIN contracts con ON cc.contract_id = con.id
                      INNER JOIN customers cust ON con.customer_id = cust.id
                      WHERE c.craft_type = ? AND cust.company_id = ?";  //Ensure only craft of this company is used
$stmt_get_craft_id = $mysqli->prepare($sql_get_craft_id);

if ($stmt_get_craft_id) {
    $stmt_get_craft_id->bind_param("si", $craft_name, $company_id);
    $stmt_get_craft_id->execute();
    $result_craft_id = $stmt_get_craft_id->get_result();

    if ($result_craft_id->num_rows > 0) {
        $row_craft_id = $result_craft_id->fetch_assoc();
        $craft_id = $row_craft_id["id"];
        $stmt_get_craft_id->close();

        // Prepare the SQL statement for inserting into craft_pilots
        $sql_insert = "INSERT INTO craft_pilots (craft_id, pilot_id) VALUES (?, ?)";
        $stmt_insert = $mysqli->prepare($sql_insert);

        if ($stmt_insert) {
            $stmt_insert->bind_param("ii", $craft_id, $pilot_id);
            $pilotSuccess = true;

            // Loop through the pilots and insert into craft_pilots
            foreach ($pilots as $pilot_id) {

                // Check if the pilot belongs to the same company.  This is a CRITICAL SECURITY CHECK
                $sql_check_pilot = "SELECT id FROM users WHERE id = ? AND company_id = ?";
                $stmt_check_pilot = $mysqli->prepare($sql_check_pilot);

                if ($stmt_check_pilot === false) {
                    error_log("Prepare failed: " . htmlspecialchars($mysqli->error));
                    $pilotSuccess = false;
                    break; // Stop processing this pilot or any further pilots
                }

                $stmt_check_pilot->bind_param("ii", $pilot_id, $company_id);

                if ($stmt_check_pilot->execute()) {
                    $result_check_pilot = $stmt_check_pilot->get_result();

                    if ($result_check_pilot->num_rows == 0) {
                        // The pilot is NOT part of the company.  Reject it.
                        error_log("Security violation: Pilot " . $pilot_id . " does not belong to company " . $company_id);
                        $pilotSuccess = false;
                        break; // Stop processing this pilot or any further pilots
                    }
                } else {
                    error_log("Execute failed: " . htmlspecialchars($stmt_check_pilot->error));
                    $pilotSuccess = false;
                    break;  // Stop processing this pilot or any further pilots
                }
                $stmt_check_pilot->close();

                if (!$pilotSuccess) {
                    break;  // Break out of the pilot loop if any check fails.
                }


                if (!$stmt_insert->execute()) {
                    $pilotSuccess = false;
                    error_log("Error inserting pilot into craft_pilots: " . $stmt_insert->error);
                    break; // Exit the loop on failure
                }
            }
            $stmt_insert->close();

            if ($pilotSuccess) {
                echo "success";
            } else {
                echo "failed";
            }
        } else {
            error_log("Error preparing insert statement: " . $mysqli->error);
            echo "failed";
        }
    } else {
        $stmt_get_craft_id->close();
        error_log("Craft not found: " . $craft_name . " for company " . $company_id);
        echo "failed";
    }
} else {
    error_log("Error preparing statement to get craft ID: " . $mysqli->error);
    echo "failed";
}

$mysqli->close();
?>