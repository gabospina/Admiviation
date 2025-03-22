<?php
session_start();
require_once 'db_connect.php';

header('Content-Type: application/json');

try {
     // **HARDCODED PILOT ID FOR TESTING - REMOVE BEFORE DEPLOYMENT!**
     $userId = 64;

     // **COMMENT OUT THESE LINES FOR PRODUCTION**
     // if (!isset($_SESSION["HeliUser"])) {
     //    throw new Exception('Authentication required');
     // }
     // $userId = $_SESSION["HeliUser"];

     // Prepare the SQL query
     $stmt = $pdo->prepare("
          SELECT *
          FROM validity
          WHERE pilot_id = ?
     ");

     // Execute the query with the user ID
     $stmt->execute([$userId]);

     // Fetch the data into an associative array
     $validityData = $stmt->fetch(PDO::FETCH_ASSOC);

     // If no data is found, create an empty array to avoid errors later
     if (!$validityData) {
          $validityData = [];
     }

     echo json_encode(['success' => true, 'data' => $validityData]);

} catch (PDOException $e) {
     error_log("Database error: " . $e->getMessage());
     echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
?>