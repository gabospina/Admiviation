<?php
session_start();

ob_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

include_once "db_connect.php";

// Check if the user is logged in and has the company_id
if (!isset($_SESSION["HeliUser"]) || !isset($_SESSION["company_id"])) {
    $response = ["success" => false, "message" => "You are not logged in properly or company ID not found."];
    echo json_encode($response);
    exit();
}

$user_id = (int)$_SESSION["HeliUser"];

if ($mysqli->connect_error) {
    $error_message = "Connection failed: " . $mysqli->connect_error;
    error_log($error_message);
    echo json_encode(["success" => false, "message" => $error_message], JSON_PRETTY_PRINT);
    exit();
}

$mysqli->set_charset("utf8");

function sanitizeString($str) {
    global $mysqli;
    $str = strip_tags($str);
    $str = htmlentities($str);
    $str = stripslashes($str);
    return $mysqli->real_escape_string($str);
}

error_log("======================");
error_log("POST Data:");
error_log(print_r($_POST, true));
error_log("======================");

$contract_name = isset($_POST["contract_name"]) ? sanitizeString($_POST["contract_name"]) : "";
$customer_id = isset($_POST["customer_id"]) ? intval($_POST["customer_id"]) : 0;
$craft_ids = isset($_POST["craft_ids"]) ? $_POST["craft_ids"] : [];
$pilot_ids = isset($_POST["pilot_ids"]) ? $_POST["pilot_ids"] : [];
$color = isset($_POST["color"]) ? sanitizeString($_POST["color"]) : "#000000";

$response = array();
$response["success"] = false;
$response["message"] = "";

if (!preg_match('/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/', $color)) {
    $response["message"] = "Invalid hex color code";
    error_log($response["message"]);
    echo json_encode($response, JSON_PRETTY_PRINT);
    exit();
}

if (empty($contract_name) || empty($customer_id) || empty($craft_ids) || !is_array($craft_ids)) {
    $response["message"] = "Contract Name, Customer, and Crafts are required.";
    error_log($response["message"]);
    echo json_encode($response, JSON_PRETTY_PRINT);
    exit();
}

if (!$mysqli->begin_transaction()) {
    $response["message"] = "Failed to start transaction: " . $mysqli->error;
    error_log($response["message"]);
    echo json_encode($response, JSON_PRETTY_PRINT);
    exit();
}

try {
    // 1. Validate customer_id exists in the customers table 
    $sql_validate_customer = "SELECT id FROM customers WHERE id = ?";
    $stmt_validate_customer = $mysqli->prepare($sql_validate_customer);

    if (!$stmt_validate_customer) {
        throw new Exception("Failed to prepare customer validation statement: " . $mysqli->error);
    }

    $stmt_validate_customer->bind_param("i", $customer_id);

    if (!$stmt_validate_customer->execute()) {
        throw new Exception("Failed to execute customer validation statement: " . $stmt_validate_customer->error);
    }

    $result_validate_customer = $stmt_validate_customer->get_result();

    if ($result_validate_customer->num_rows === 0) {
        throw new Exception("Invalid customer_id: The customer does not exist.");
    }

    $stmt_validate_customer->close();

    // 2. Insert into contracts table
    $sql_contracts = "INSERT INTO contracts (contract_name, customer_id, color) VALUES (?, ?, ?)";
    $stmt_contracts = $mysqli->prepare($sql_contracts);

    if (!$stmt_contracts) {
        throw new Exception("Failed to prepare contract statement: " . $mysqli->error);
    }

    $stmt_contracts->bind_param("sis", $contract_name, $customer_id, $color);

    if (!$stmt_contracts->execute()) {
        throw new Exception("Failed to execute contract statement: " . $stmt_contracts->error);
    }

    $contract_id = $mysqli->insert_id;
    $stmt_contracts->close();

    // 3. Insert into contract_crafts table
    $sql_contract_crafts = "INSERT INTO contract_crafts (contract_id, craft_type_id) VALUES (?, ?)";
    $stmt_contract_crafts = $mysqli->prepare($sql_contract_crafts);

    if (!$stmt_contract_crafts) {
        throw new Exception("Failed to prepare contract_craft statement: " . $mysqli->error);
    }

    foreach ($craft_ids as $craft_type_id) {
        $craft_type_id = intval($craft_type_id);
        $stmt_contract_crafts->bind_param("ii", $contract_id, $craft_type_id);
        if (!$stmt_contract_crafts->execute()) {
            throw new Exception("Failed to execute contract_craft statement: " . $stmt_contract_crafts->error);
        }
    }

    $stmt_contract_crafts->close();

    // 4. Insert into contract_pilots table
    if (!empty($pilot_ids)) {
        // Delete existing entries for this contract to avoid duplicates
        $sql_delete_contract_pilots = "DELETE FROM contract_pilots WHERE contract_id = ?";
        $stmt_delete_contract_pilots = $mysqli->prepare($sql_delete_contract_pilots);

        if (!$stmt_delete_contract_pilots) {
            throw new Exception("Failed to prepare contract_pilots delete statement: " . $mysqli->error);
        }

        $stmt_delete_contract_pilots->bind_param("i", $contract_id);

        if (!$stmt_delete_contract_pilots->execute()) {
            throw new Exception("Failed to execute contract_pilots delete statement: " . $stmt_delete_contract_pilots->error);
        }

        $stmt_delete_contract_pilots->close();

        // Insert pilots with the correct craft_type_id
        $sql_contract_pilots = "INSERT INTO contract_pilots (contract_id, pilot_id, craft_type_id) VALUES (?, ?, ?)";
        $stmt_contract_pilots = $mysqli->prepare($sql_contract_pilots);

        if (!$stmt_contract_pilots) {
            throw new Exception("Failed to prepare contract_pilot statement: " . $mysqli->error);
        }

        foreach ($pilot_ids as $pilot_id) {
            $pilot_id = intval($pilot_id);
            // Assign the first craft_type_id to all pilots (or modify logic as needed)
            $craft_type_id = $craft_ids[0]; // Use the first craft_type_id for all pilots
            $stmt_contract_pilots->bind_param("iii", $contract_id, $pilot_id, $craft_type_id);
            if (!$stmt_contract_pilots->execute()) {
                throw new Exception("Failed to execute contract_pilot statement: " . $stmt_contract_pilots->error);
            }
        }

        $stmt_contract_pilots->close();
    }

    if (!$mysqli->commit()) {
        throw new Exception("Transaction commit failed: " . $mysqli->error);
    }

    $response["success"] = true;
    $response["message"] = "Contract added successfully!";
} catch (Exception $e) {
    $mysqli->rollback();
    $response["message"] = "Transaction failed: " . $e->getMessage();
    error_log($response["message"]);
} finally {
    $mysqli->close();
    header('Content-Type: application/json');
    echo json_encode($response, JSON_PRETTY_PRINT);
    ob_end_flush();
}
?>