<?php
require_once 'db_connect.php';

// Check if it's an AJAX request
$isAjax = isset($_SERVER['HTTP_X_REQUESTED_WITH']) &&
          strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';

// Get search and filter parameters
$search = isset($_GET['search']) ? mysqli_real_escape_string($mysqli, $_GET['search']) : '';
$showNonPilots = isset($_GET['showNonPilots']) ? $_GET['showNonPilots'] : 'f';

// Base query - to call for other functions

$query = "SELECT 
  u.id, 
  CONCAT(u.firstname, ' ', u.lastname) AS fullname,
  u.firstname,
  u.lastname,
  u.username,
  u.user_nationality,
  ur.role_name AS job_position,
  u.nal_license,
  u.for_license,
  u.email,
  u.phone,
  u.phonetwo,
  u.access_level,
  u.created_at
FROM users u
INNER JOIN users_roles ur ON u.role_id = ur.id
WHERE 1=1";

// Add search conditions
if (!empty($search)) {
    $query .= " AND (firstname LIKE '%$search%' 
                OR lastname LIKE '%$search%')";
                // OR username LIKE '%$search%')";
}

// Add access level filter
if ($showNonPilots === 'f') {
    $query .= " AND access_level = 0"; // Show only pilots
} elseif ($showNonPilots === 'm') {
    $query .= " AND access_level > 0"; // Show only managers
}

$result = $mysqli->query($query);
$pilotCount = $result->num_rows;

// Generate HTML
$html = '';
if ($pilotCount > 0) {
    while ($row = $result->fetch_assoc()) {
        $fullname = htmlspecialchars($row['fullname']);
        
        // Add highlighting only when searching
        if (!empty($search)) {
            $pattern = '/(' . preg_quote($search, '/') . ')/i';
            $fullname = preg_replace(
                $pattern,
                '<span class="highlight">$1</span>',
                $fullname
            );
        }

        // Simplified view for "Show Only Pilots"
        if ($showNonPilots === 'f') {
            $html .= '<div class="pilot-item" data-pilot-id="'.$row['id'].'">';
            $html .= '  <h4>'.$fullname.'</h4>';
            $html .= '</div>';
        } 
        // Existing detailed view for other filters
        else {
            $html .= '<div class="pilot-item" data-pilot-id="'.$row['id'].'">';
            $html .= '  <div class="pilot-header">';
            $html .= '    <h4>'.$fullname.'</h4>';
            $html .= '  </div>';
            $html .= '  <div class="pilot-details">';
            $html .= '  </div>';
            $html .= '</div>';
        }
    }
} else {
    $html = '<div class="no-results">No pilots found matching your criteria</div>';
}

if ($isAjax) {
    header('Content-Type: application/json');
    echo json_encode([
        'html' => $html,
        'count' => $pilotCount
    ]);
    exit();
}
?>

<div class="list">
<style>
    .highlight {
        background-color: #ffeb3b;
        border-radius: 3px;
        padding: 0 2px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.12);
    }
    .pilot-item h4 {
        margin: 2px 0;
        padding: 1px;
        transition: background-color 0.2s;
    }
    .pilot-item:hover h4 {
        background-color:rgb(137, 219, 246);
    }
    .autocomplete-wrapper {
    position: relative;
    margin-bottom: 15px;
  }

  .autocomplete-results {
      position: absolute;
      z-index: 1000;
      width: 100%;
      max-height: 200px;
      overflow-y: auto;
      background: white;
      border: 1px solid #ddd;
      border-top: none;
      display: none;
  }

  .autocomplete-item {
      padding: 10px;
      cursor: pointer;
      transition: background-color 0.2s;
  }

  .autocomplete-item:hover {
      background-color: #f8f9fa;
  }
  </style>
  
  <div class="autocomplete-wrapper">
    <input type="text" id="search_pilot" name="search_pilot" 
           class="form-control" placeholder="Pilot's Name-pilot_list.php"
           autocomplete="off">
    <div class="autocomplete-results"></div>
  </div>

  <!-- <input type="text" id="search_pilot" name="search_pilot" class="form-control" placeholder="Pilot's Name"> -->
  
  <select id="sortBy" class="form-control outer-top-xxs">
    <option value="creation">Creation Date</option>
    <option value="name">Name</option>
    <option value="position">Position</option>
    <option value="duty">On Duty</option>
  </select>
  <select id="craftType" class="form-control outer-top-xxs">
    <option value="all">All</option>
  </select>
  <select id="showNonPilots" class="form-control outer-top-xxs">
    <option value="f">Show Only Pilots</option>
    <option value="t">Show Managers and Pilots</option>
    <option value="m">Show Only Managers</option>
  </select>
  <div id="pilots_list"><?= $html ?></div>
</div>

<!-- <script src="./pilot-ajax.js" type="module"></script> -->
<script src="pilot-ajax.js" type="module"></script>