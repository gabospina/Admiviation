     
<?php
// pilot_list.php
require_once 'db_connect.php';

// Check if it's an AJAX request
$isAjax = isset($_SERVER['HTTP_X_REQUESTED_WITH']) && 
          strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';

// Get search and filter parameters
$search = isset($_GET['search']) ? mysqli_real_escape_string($mysqli, $_GET['search']) : '';
$sort = isset($_GET['sort']) ? $_GET['sort'] : 'creation';
$craft = isset($_GET['craft']) ? $_GET['craft'] : 'all';
$showNonPilots = isset($_GET['showNonPilots']) ? $_GET['showNonPilots'] : 'f';

// Base query
$query = "SELECT 
            id, 
            CONCAT(firstname, ' ', lastname) AS fullname,
            firstname,
            lastname,
            username,
            user_nationality,
            job_position,
            nal_license,
            for_license,
            email,
            phone,
            phonetwo,
            access_level,
            created_at
          FROM users 
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

// Add sorting
switch ($sort) {
    case 'name':
        $query .= " ORDER BY firstname, lastname";
        break;
    case 'position':
        $query .= " ORDER BY job_position DESC";
        break;
    case 'duty':
        $query .= " ORDER BY (SELECT MAX(end_date) FROM duty_schedule WHERE user_id = users.id) DESC";
        break;
    default:
        $query .= " ORDER BY created_at DESC";
}

$result = $mysqli->query($query);
$pilotCount = $result->num_rows;

// Generate HTML
$html = '';
if ($pilotCount > 0) {
    while ($row = $result->fetch_assoc()) {
        $html .= '<div class="pilot-item" data-pilot-id="'.$row['id'].'">';
        $html .= '  <div class="pilot-header">';
        $html .= '    <h4>'.$row['fullname'].'</h4>';
        $html .= '    <span class="badge">'.($row['access_level'] > 0 ? 'Manager' : 'Pilot').'</span>';
        $html .= '  </div>';
        $html .= '  <div class="pilot-details">';
        $html .= '    <p>Username: '.$row['username'].'</p>';
        $html .= '    <p>Position: '.$row['job_position'].'</p>';
        $html .= '    <p>Joined: '.date('M Y', strtotime($row['created_at'])).'</p>';
        $html .= '  </div>';
        $html .= '</div>';
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
  <input type="text" id="search_pilot" name="search_pilot" class="form-control" placeholder="Pilot's Name, Craft or Contract">
  <div id="pilots_list"><?= $html ?></div>
</div>

<div class="list">
  <input type="text" id="search_pilot" name="search_pilot" class="form-control" placeholder="Pilot's Name, Craft or Contract">
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
</div>