<?php
include_once "db_connect.php";

$contract_id = $_POST["contract_id"]; // Get the contract ID

// Initialize response array
$response = array();
$response["success"] = false;
$response["message"] = "";

// Start a transaction for atomicity
$mysqli->begin_transaction();

try {
    // 1. Delete or update dependent rows in the `crafts` table
    $sql_update_crafts = "UPDATE crafts SET contract_id = NULL WHERE contract_id = ?";
    $stmt_update_crafts = $mysqli->prepare($sql_update_crafts);

    if (!$stmt_update_crafts) {
        throw new Exception("Failed to prepare crafts update statement: " . $mysqli->error);
    }

    $stmt_update_crafts->bind_param("i", $contract_id);

    if (!$stmt_update_crafts->execute()) {
        throw new Exception("Failed to execute crafts update statement: " . $stmt_update_crafts->error);
    }

    $stmt_update_crafts->close();

    // 2. Delete from `contract_crafts` table
    $sql_delete_contract_crafts = "DELETE FROM contract_crafts WHERE contract_id = ?";
    $stmt_delete_contract_crafts = $mysqli->prepare($sql_delete_contract_crafts);

    if (!$stmt_delete_contract_crafts) {
        throw new Exception("Failed to prepare contract_crafts delete statement: " . $mysqli->error);
    }

    $stmt_delete_contract_crafts->bind_param("i", $contract_id);

    if (!$stmt_delete_contract_crafts->execute()) {
        throw new Exception("Failed to execute contract_crafts delete statement: " . $stmt_delete_contract_crafts->error);
    }

    $stmt_delete_contract_crafts->close();

    // 3. Delete from `contract_pilots` table
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

    // 4. Delete from `contracts` table
    $sql_delete_contract = "DELETE FROM contracts WHERE id = ?";
    $stmt_delete_contract = $mysqli->prepare($sql_delete_contract);

    if (!$stmt_delete_contract) {
        throw new Exception("Failed to prepare contract delete statement: " . $mysqli->error);
    }

    $stmt_delete_contract->bind_param("i", $contract_id);

    if (!$stmt_delete_contract->execute()) {
        throw new Exception("Failed to execute contract delete statement: " . $stmt_delete_contract->error);
    }

    $stmt_delete_contract->close();

    // Commit the transaction
    $mysqli->commit();

    // Success message
    $response["success"] = true;
    $response["message"] = "Contract deleted successfully!";
} catch (Exception $e) {
    // Rollback the transaction on error
    $mysqli->rollback();

    // Error message
    $response["message"] = "Error deleting contract: " . $e->getMessage();
}

// Close the database connection
$mysqli->close();

// Send the JSON response
header('Content-Type: application/json');
echo json_encode($response);
?>
