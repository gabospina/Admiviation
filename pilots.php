<?php
error_reporting(E_ALL); //Use E_ALL for reporting all errors
ini_set('display_errors', 1);
// Include 'IsLogged.php', but exclude it for now to simplify debugging
// include 'IsLogged.php';
session_start();

// Ensure session is started
// if (session_status() == PHP_SESSION_NONE) {
//     session_start();
// }

$lastHeliSelected = "";
if (isset($_COOKIE["lastHeliSelected"])) {
    $_SESSION["lastHeliSelected"] = $_COOKIE["lastHeliSelected"];
}

if (isset($_SESSION["lastHeliSelected"])) {
    $lastHeliSelected = $_SESSION["lastHeliSelected"];
}

// // Use json_encode for safer JavaScript variable injection
// echo '<script>
//     var lastHeliSelected = ' . json_encode($lastHeliSelected) . ';
//     var myPilotID = ' . json_encode($_SESSION['HeliUser'] ?? null) . '; // Handle unset session
// </script>';

$page = "pilots";
include_once "header.php";

// Check if $user_data is available, otherwise, provide a default value
$user_data = isset($user_data) ? $user_data : [];

// Output the user ID to a JavaScript variable
echo "<script>";
echo "var phpHeliUser = '" . (isset($_SESSION["HeliUser"]) ? htmlspecialchars($_SESSION["HeliUser"], ENT_QUOTES, 'UTF-8') : '') . "';";
echo "</script>";

// Get pilot ID from request or session
// $pilotId = $_GET['pilot_id'] ?? $_SESSION['pilot_id'] ?? null;
?>

<!-- <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User Details</title>
    <style>
        .outer-container { display: flex; gap: 5px; }
        .pilot-list, .pilot-details { flex: 1; }
    </style>
</head>
<body>
<div class="outer-bottom outer-top-md inner-left-sm inner-right-sm">
    <div id="notifications"></div>
    <div class="pilots">
        php include 'pilot_list.php';
        <div id="pilot_info_section">
            <h2 class="page-header text-center">Pilot Information-pilot.php</h2>
            <div id="pilot_info">
            <h5> Select a pilot to get Pilot Information-pilot.php</h5>
            </div>
        </div>
    </div>
</div>
</body>
</html> -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User Details</title>
        <!-- <script src="js_v8/pilot-main.js" type="module"></script>
		<script src="js_v8/pilot-ajax.js" type="module"></script>
		<script src="js_v8/pilot-sorting.js" type="module"></script>
		<script src="js_v8/pilot-filtering.js" type="module"></script>
		<script src="js_v8/pilot-ui.js" type="module"></script>
		<script src="js_v8/pilot-events.js" type="module"></script>
		<script src="js_v8/pilot-admin.js" type="module"></script>
		<script src="js_v8/pilot-profile-picture.js" type="module"></script> -->
		<!-- <script src="pilot-utils.js" type="module"></script> -->
    <style>
        .outer-container {
            display: flex;
            gap: 5px;
        }
        .pilot-list, .pilot-details {
            flex: 1;
        }
        #pilot_info {
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
            margin-top: 20px;
            font-family: Arial, sans-serif;
        }

        .pilot-card {
            margin-bottom: 20px;
        }

        .pilot-header {
            background-color: #f0f0f0;
            padding: 10px;
            border-bottom: 1px solid #ddd;
            font-weight: bold;
        }

        .detail-group {
            margin-bottom: 15px;
        }

        .detail-group h4 {
            font-size: 1.2em;
            margin-bottom: 5px;
        }

        .detail-group p {
            margin: 5px 0;
        }

        .badge {
            display: inline-block;
            padding: 5px 10px;
            font-size: 0.8em;
            font-weight: bold;
            line-height: 1;
            color: #fff;
            text-align: center;
            white-space: nowrap;
            vertical-align: baseline;
            border-radius: 0.25rem;
        }

        .badge.manager {
            background-color: #5bc0de; /* Example color for managers */
        }

        .badge.pilot {
            background-color: #5cb85c; /* Example color for pilots */
        }

        /* Style the validity table */
        .table-condensed {
            font-size: 0.9em;
        }

        .table-bordered th,
        .table-bordered td {
            border: 1px solid #ddd !important;
        }

        .text-success {
            color: #5cb85c;
        }

        .text-danger {
            color: #d9534f;
        }

        #validityTable {
            width: 100%;
        }

        #validityTable th,
        #validityTable td {
            padding: 8px;
            text-align: left;
        }
    </style>
</head>
<body>
<div class="outer-bottom outer-top-md inner-left-sm inner-right-sm">
    <div id="notifications"></div>
    <div class="outer-container">
        <div class="pilot-list">
            <?php include 'pilot_list.php'; ?>
        </div>
        <div id="pilot_info_section">
            <h2 class="page-header text-center">Pilot Information-pilots.php</h2>
            <div id="pilot_info">
                <!--Pilot information display from validity table-->
                <?php
                // Establish database connection
                // $mysqli = new mysqli("localhost", "root", "", "Admviation");
                $mysqli = new mysqli("localhost", "root", "", "Heli_Offshore");

                // Check connection
                if ($mysqli->connect_error) {
                    die("Connection failed: " . $mysqli->connect_error);
                }
        
                // Fetch user data based on the selected pilot
                if (isset($_GET['pilotId'])) {
                $pilotId = $_GET['pilotId'];
        
                // Fetch pilot details using the ID
                $query = "SELECT u.firstname, u.lastname, u.username, u.user_nationality, u.job_position, u.nal_license, u.for_license, u.email, u.phone, u.phonetwo,u.access_level, u.passport, u.nal_visa, u.us_visa, u.instruments, u.booklet, u.train_rec, u.flight_train, u.base_check, u.night_cur, u.night_check, u.ifr_cur, u.ifr_check, u.line_check, u.hoist_check, u.hoist_cur, u.crm, u.hook, u.herds, u.dang_good, u.huet, u.english, u.faids, u.fire, u.avsec FROM users u LEFT JOIN pilot_info p ON u.id = p.user_id WHERE u.id = $pilotId";
                $result = $mysqli->query($query);
        
                if ($result && $result->num_rows > 0) {
                    $user_data = $result->fetch_assoc();

                        if ($user_data) {
                           // Display pilot information
                           echo "<div class='pilot-card'>";
                           echo "<div class='pilot-header'>";
                           echo "<h3>" . htmlspecialchars($user_data['firstname'] . ' ' . $user_data['lastname']) . "</h3>";
                           echo "<span class='badge " . ($user_data['access_level'] > 0 ? 'manager' : 'pilot') . "'>";
                           echo ($user_data['access_level'] > 0 ? 'Manager' : 'Pilot') . "</span>";
                           echo "</div>";

                           echo "<div class='pilot-info'>";
                           echo "<p><strong>Username:</strong> " . htmlspecialchars($user_data['username']) . "</p>";
                           echo "<p><strong>Nationality:</strong> " . htmlspecialchars($user_data['user_nationality']) . "</p>";
                           echo "<p><strong>Position:</strong> " . htmlspecialchars($user_data['job_position']) . "</p>";
                           echo "<p><strong>National License:</strong> " . htmlspecialchars($user_data['nal_license']) . "</p>";
                           echo "<p><strong>Foreign License:</strong> " . htmlspecialchars($user_data['for_license']) . "</p>";
                           echo "<p><strong>Email:</strong> <a href='mailto:" . htmlspecialchars($user_data['email']) . "'>" . htmlspecialchars($user_data['email']) . "</a></p>";
                           echo "<p><strong>Phone:</strong> " . htmlspecialchars($user_data['phone']) . "</p>";

                           if (!empty($user_data['phonetwo'])) {
                             echo "<p><strong>Secondary Phone:</strong> " . htmlspecialchars($user_data['phonetwo']) . "</p>";
                            }
                          echo "</div>";
                        echo "</div>";

                        // =======================Validity Table:Display  values from database===============

                        echo "<div class='panel panel-default'>";
                        echo "<div class='panel-heading'>";
                        echo "<h3 class='panel-title'>Certification Validity</h3>";
                        echo "</div>";
                        echo "<div class='panel-body'>";
                        echo "<table class='table table-condensed table-bordered no-shadow'>";
                        echo "<thead>";
                        echo "<tr>";
                        echo "<th>Certification</th>";
                        echo "<th>Expiry Date</th>";
                        echo "<th>Status</th>";
                        echo "</tr>";
                        echo "</thead>";
                        echo "<tbody>";

                        $validityFields = [
                            'for_lic' => 'Foreign License',
                            'passport' => 'Passport',
                            'nal_visa' => 'National Visa',
                            'us_visa' => 'USA Visa',
                            'instruments' => 'Instrument Rating',
                            'booklet' => 'Flight Log Book',
                            'train_rec' => 'Training Records',
                            'flight_train' => 'Flight Training',
                            'base_check' => 'Base Check',
                            'night_cur' => 'Night Currency',
                            'night_check' => 'Night Check',
                            'ifr_cur' => 'IFR Currency',
                            'ifr_check' => 'IFR Check',
                            'line_check' => 'Line Check',
                            'hoist_check' => 'Hoist Check',
                            'hoist_cur' => 'Hoist Currency',
                            'crm' => 'CRM Certification',
                            'hook' => 'Hook Operation',
                            'herds' => 'HERDS Training',
                            'dang_good' => 'Dangerous Goods',
                            'huet' => 'HUET Certification',
                            'english' => 'English Proficiency',
                            'faids' => 'First Aid',
                            'fire' => 'Fire Fighting',
                            'avsec' => 'AVSEC Certification'
                        ];

                        foreach ($validityFields as $field => $label):
                            $expiryDate = isset($user_data[$field]) ? htmlspecialchars($user_data[$field]) : 'N/A';
                            $statusClass = ($expiryDate != 'N/A' && strtotime($expiryDate) >= strtotime(date('Y-m-d'))) ? 'text-success' : 'text-danger';
                            $statusText = ($expiryDate != 'N/A' && strtotime($expiryDate) >= strtotime(date('Y-m-d'))) ? 'Valid' : 'Expired';

                            echo "<tr>";
                            echo "<td>" . htmlspecialchars($label) . "</td>";
                            echo "<td>" . $expiryDate . "</td>";
                            echo "<td class='" . $statusClass . "'>" . $statusText . "</td>";
                            echo "</tr>";
                        endforeach;

                        echo "</tbody>";
                        echo "</table>";
                        echo "</div>";
                        echo "</div>";
                    } else {
                        echo "<p>Pilot data not found.</p>";
                    }
                } else {
                    echo "<p>Please select a pilot.</p>";
                }
        } else {
            echo "<p>No pilot selected</p>";
        }
// Close database connection
$mysqli->close();
?>

    
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

<?php include_once "footer.php"; ?>

<!-- <script src="pilot-ajax.js" type="module"></script> -->

<!-- <script src="assets/lib/moment.min.js"></script> -->
<!-- <script src="assets/lib/dropzone.js"></script> -->
<!-- <script src="universalfunctions.js"></script> -->
<!-- <script src="loginfunctions.js"></script> -->