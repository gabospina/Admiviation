<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include_once "db_connect.php";

$response = array();

// Contract query with JOIN to fetch customer name
$contractQuery = "SELECT
                    c.id AS contractid,
                    c.contract_name AS contract,
                    cust.customer_name AS customer_name,  -- Customer name instead of ID
                    c.color AS color
                FROM
                    `contracts` c
                LEFT JOIN
                    `customers` cust ON c.customer_id = cust.id
                ORDER BY
                    c.contract_order";

$contractResult = $mysqli->query($contractQuery);

if ($contractResult) {
    $contracts = array();
    while ($row = mysqli_fetch_assoc($contractResult)) {

        $contracts[] = array(
            "contractid" => $row["contractid"],
            "contract" => $row["contract"],
            "customer_name" => $row["customer_name"], // Customer name
            "color" => $row["color"]
        );
    }
    $response["success"] = true;
    $response["contracts"] = $contracts;
} else {
    $response["success"] = false;
    $response["message"] = "Error fetching contracts: " . $mysqli->error;
    error_log("Error fetching contracts: " . $mysqli->error);
}

header('Content-Type: application/json');
echo json_encode($response);
$mysqli->close();
?>