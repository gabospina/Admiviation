<?php
session_start();

include_once "db_connect.php";

// Check for POST request
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    error_log("delete_contract.php: Invalid request method");
    echo json_encode(["success" => false, "message" => "Invalid request method"]);
    exit;
}

// Get contract ID from POST data
$contract_id = isset($_POST['contract_id']) ? intval($_POST['contract_id']) : 0;

// Validate contract ID
if ($contract_id <= 0) {
    error_log("delete_contract.php: Invalid contract ID: " . $contract_id);
    echo json_encode(["success" => false, "message" => "Invalid contract ID"]);
    exit;
}

// Initialize response array
$response = array();
$response["success"] = false;
$response["message"] = "";

// Start a transaction for atomicity
$mysqli->begin_transaction();

try {
    // 1. Delete from `contract_pilots` table
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
    error_log("delete_contract.php: Deleted from contract_pilots for contract ID: " . $contract_id . ". Affected rows: " . $mysqli->affected_rows);

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
    error_log("delete_contract.php: Deleted from contract_crafts for contract ID: " . $contract_id . ". Affected rows: " . $mysqli->affected_rows);

    // 3. Update `crafts` table
    $sql_update_crafts = "UPDATE crafts SET company_id = NULL WHERE company_id = ?";
    $stmt_update_crafts = $mysqli->prepare($sql_update_crafts);

    if (!$stmt_update_crafts) {
        throw new Exception("Failed to prepare crafts update statement: " . $mysqli->error);
    }

    $stmt_update_crafts->bind_param("i", $contract_id);

    if (!$stmt_update_crafts->execute()) {
        throw new Exception("Failed to execute crafts update statement: " . $stmt_update_crafts->error);
    }

    $stmt_update_crafts->close();
    error_log("delete_contract.php: Updated crafts table for contract ID: " . $contract_id . ". Affected rows: " . $mysqli->affected_rows);

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
    error_log("delete_contract.php: Deleted from contracts table for contract ID: " . $contract_id . ". Affected rows: " . $mysqli->affected_rows);

    // Commit the transaction
    $mysqli->commit();

    // Success message
    $response["success"] = true;
    $response["message"] = "Contract deleted successfully!";
    error_log("delete_contract.php: Contract deleted successfully. Contract ID: " . $contract_id);
} catch (Exception $e) {
    // Rollback the transaction on error
    $mysqli->rollback();

    // Error message
    $response["message"] = "Error deleting contract: " . $e->getMessage();
    error_log("delete_contract.php: Error deleting contract: " . $e->getMessage());
}

// Close the database connection
$mysqli->close();

// Send the JSON response
header('Content-Type: application/json');
echo json_encode($response);
?>