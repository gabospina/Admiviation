
<?php
session_start();
require_once 'db_connect.php';

header('Content-Type: application/json');

try {
    if (!isset($_GET['id'])) {
        throw new Exception('Pilot ID is required');
    }

    $pilotId = $_GET['id'];

    // Fetch pilot data from the users table
    $query = "SELECT u.*, v.* 
              FROM users u 
              LEFT JOIN validity v ON u.id = v.pilot_id 
              WHERE u.id = ?";

    $stmt = $mysqli->prepare($query);
    if (!$stmt) {
        throw new Exception("Prepare failed: " . $mysqli->error);
    }

    $stmt->bind_param('i', $pilotId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        throw new Exception('Pilot not found');
    }

    $pilotData = $result->fetch_assoc();

    if (!$pilotData) {
        throw new Exception('Error fetching pilot data');
    }

    echo json_encode(['success' => true, 'data' => $pilotData]);

} catch (Exception $e) {
    error_log('Error: ' . $e->getMessage());
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}

finally {
    if ($stmt) $stmt->close();
    if ($mysqli) $mysqli->close();
}
?>