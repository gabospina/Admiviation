
<?php
include_once "db_connect.php";

$response = array();
$response['success'] = false;
$response['message'] = '';
$response['crafts'] = array();

try {
    // Query to fetch all crafts from the crafts table
    $sql = "SELECT id, craft_type, registration, tod, alive, company_id FROM crafts";
    $result = $mysqli->query($sql);

    if ($result) {
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                // Add each craft to the crafts array
                $response['crafts'][] = $row;
            }
            $response['success'] = true;
        } else {
            $response['message'] = "No crafts found.";
        }
        $result->free(); // Free result set
    } else {
        throw new Exception("Error fetching crafts: " . $mysqli->error);
    }
} catch (Exception $e) {
    $response['message'] = $e->getMessage();
}

// Ensure the response is always JSON
header('Content-Type: application/json');
echo json_encode($response);

$mysqli->close();
?>
