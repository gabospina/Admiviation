<?php
header("Content-Security-Policy: "
. "script-src 'self' cdnjs.cloudflare.com code.jquery.com stackpath.bootstrapcdn.com cdn.jsdelivr.net 'unsafe-inline'; "
. "style-src 'self' 'unsafe-inline' cdnjs.cloudflare.com stackpath.bootstrapcdn.com;");


if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

error_reporting(E_ALL);
ini_set('display_errors', 1);

include_once "db_connect.php";

// session_start(); // Ensure session_start() is called
if (!isset($_SESSION["HeliUser"])) {
    header("Location: admviationHome.php"); // Redirect to login page
    exit(); // Stop further execution
}

// Initialize variables to avoid undefined variable errors
$accountName = ""; // Changed from $accountRes, now just holds the name
$userName = ""; // Holds the username
$firstName = ""; // Holds the first name
$lastName = ""; // Holds the last name

// 1. Retrieve Company Name
if (isset($_SESSION["company_id"]) && is_numeric($_SESSION["company_id"])) {
    $company_id = (int)$_SESSION["company_id"];

    // Retrieve company name from the companies table
    $query = $mysqli->prepare("SELECT company_name FROM companies WHERE id = ?");
    $query->bind_param("i", $company_id);

    if ($query->execute()) {
        $result = $query->get_result();
        $companyData = $result->fetch_assoc();

        if ($companyData) {
            $accountName = $companyData["company_name"];
        } else {
            error_log("Company information not found for company ID: " . $company_id);
        }

        $query->close();
    } else {
        error_log("Invalid or missing HeliUser ID in session.");
    }
} else {
    error_log("Invalid or missing company ID in session.");
}

// 2. Retrieve User Information (Username, First Name, Last Name)
$user_data = []; // Initialize an empty array to store user data
if (isset($_SESSION["HeliUser"]) && is_numeric($_SESSION["HeliUser"])) {
    $user_id = (int)$_SESSION["HeliUser"];

    $query = $mysqli->prepare("SELECT username, firstname, lastname FROM users WHERE id = ?"); // Select only the username
    $query->bind_param("i", $user_id);

    if ($query->execute()) {
        $result = $query->get_result();
        $userData = $result->fetch_assoc();

        if ($userData) {
            $userName = $userData["username"]; // Store the username
            $firstName = $userData["firstname"]; // Store the first name
            $lastName = $userData["lastname"]; // Store the last name
        } else {
            error_log("User information not found for user ID: " . $user_id);
        }

        $query->close();
    } else {
        error_log("Invalid or missing HeliUser ID in session.");
    }
} else {
    error_log("Invalid or missing HeliUser ID in session.");
}

// 3. Check if user is logged
if (!isset($_SESSION["HeliUser"])) {
	header("Location: admviationHome.php");
	exit(); 
 }

// 4. Track user activity (preventing SQL injection) - No change needed here, but keep the prepared statement
$time = time();
if (isset($_SESSION["HeliUser"]) && is_numeric($_SESSION["HeliUser"]) && isset($page)) {
    $user_id = (int)$_SESSION["HeliUser"];
    $page_safe = htmlspecialchars($page, ENT_QUOTES, 'UTF-8');

    // *** ADDED FOR DEBUGGING ***
    error_log("Page name: " . $page_safe); // Debugging line
    
	// *** ADDED FOR DEBUGGING ***

    // Construct a query to check if the column exists
    $check_column_query = "SHOW COLUMNS FROM user_tracking LIKE '$page_safe'";

    // Execute the query
    $check_column_result = $mysqli->query($check_column_query);

    // Check if the column exists
    if ($check_column_result && $check_column_result->num_rows > 0) {
        $update = $mysqli->prepare("UPDATE user_tracking SET `$page_safe` = `$page_safe` + 1, last_accessed=? WHERE id=?");

        if ($update === false) {
            error_log("Error preparing statement: " . $mysqli->error);
        } else {
            $update->bind_param("ii", $time, $user_id);

            if (!$update->execute()) {
                error_log("User tracking update failed: " . $mysqli->error);
            }
            $update->close();
        }
    } else {
        error_log("Column '$page_safe' does not exist in user_tracking table.");
    }
} else {
    error_log("Invalid or missing HeliUser ID in session or page name.");
}
?>


<!DOCTYPE html>
<html>
<head>
	<title>Helicopter Offshore</title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="description" content="Helicopter Offshore Scheduling website">
	<meta name="keywords" content="helicopter, offshore, pilot, schedule, scheduler">

	<link rel="icon" href="favicon.ico">
	<!-- CSS ========================== -->
	<link rel="stylesheet" type="text/css" href="assets/css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="assets/css/jquery-ui-1.10.4.min.css">
	<link rel="stylesheet" type="text/css" href="assets/css/select2.css">
	<link rel="stylesheet" type="text/css" href="assets/css/datepicker3.css">
	<link rel="stylesheet" type="text/css" href="assets/css/bootstrap-editable.css">
	<link rel="stylesheet" type="text/css" href="assets/css/font-awesome.min.css">
	<link rel="stylesheet" type="text/css" href="assets/css/stylesheet.css">

	<!-- SCRIPTS =========================-->
	<!-- // <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?v=3"></script> -->
	<!-- <script src="assets/lib/jquery-1.10.2.js"></script> -->
	<script src="js/jquery-3.7.1.min.js"></script>
    <script src="js/jquery-migrate-3.5.2.min.js"></script>
	<script src="assets/lib/bootstrap.js"></script>
	<script src="assets/lib/jquery-ui-1.10.4.min.js"></script>
	<script src="assets/lib/select2.min.js"></script>
	<script src="assets/lib/bootstrap-datepicker.js"></script>
	<script src="assets/lib/bootstrap-editable.js"></script>
	<script src="assets/lib/noty/packaged/jquery.noty.packaged.js"></script>
	<script src="assets/js/sha512.js"></script>
	
	<?php

	switch($page){
		case "home":
			echo'
				<script src="assets/lib/moment.min.js"></script>
				
				<script src="contractfunctions.js"></script>
				<script src="hangarfunctions.js"></script>
				<script src="homefunctions.js"></script>
				<script src="scalafunctions.js"></script>
				
				<script src="craftfunctions.js"></script>
				<script src="loginfunctions.js"></script>
			';

		break;
		case "pilots":
			echo'
				<script src="assets/lib/dropzone.js"></script>
				<script src="assets/lib/moment.min.js"></script>
				<script src="hangarfunctions.js"></script>
				<script src="hpilotfunctions.js"></script>
			';

		break;
		case "contracts":
			echo'
				<script src="loginfunctions.js"></script>
				<script src="contractfunctions.js"></script>

				<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
				<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
				<script src=" https://cdn.jsdelivr.net/npm/@simonwep/pickr@1.9.1/dist/pickr.min.js"></script>

				<script src="https://cdnjs.cloudflare.com/ajax/libs/spectrum/1.8.1/spectrum.min.js" integrity="sha512-1aNp9qKP+hKU/VJwCtYqJP9tdZWbMDN5pEEXXoXT0pTAxZq1HHZhNBR/dtTNSrHO4U1FsFGGILbqG1O9nl8Mdg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
				<link type="text/css" rel="stylesheet" href="assets/css/spectrum.css">
				
				
				
			';

		break;
		case "hangar":
			echo'
				<script src="loginfunctions.js"></script>
				<script src="assets/lib/dropzone.js"></script>
				<script src="assets/lib/moment.min.js"></script>
				<script src="hangarfunctions.js"></script>
			';

		break;
		case "crafts":
			echo'
				<script src="loginfunctions.js"></script>
				<script src="craftfunctions.js"></script>
			';
			
		break;
		case "statistics":
			$maxDay = 0;  // Provide a default value
			$maxSeven = 0; // Provide a default value
			$max28 = 0;  // Provide a default value

			// Fetch values safely (example, adapt to your needs)
			if (isset($_SESSION["account"]) && is_numeric($_SESSION["account"])) {
				$account_id = (int)$_SESSION["account"]; // Sanitize

				$stats_query = $mysqli->prepare("SELECT max_in_day, max_last_7, max_last_28 FROM account_info WHERE id = ?");
				$stats_query->bind_param("i", $account_id);

				if ($stats_query->execute()) {
					$stats_result = $stats_query->get_result();
					$stats_data = $stats_result->fetch_assoc();

					if ($stats_data) {
						$maxDay = (int)$stats_data["max_in_day"];   // Sanitize as integer
						$maxSeven = (int)$stats_data["max_last_7"]; // Sanitize as integer
						$max28 = (int)$stats_data["max_last_28"];  // Sanitize as integer
					} else {
						error_log("Statistics data not found for account ID: " . $account_id);
					}
					$stats_query->close();
				} else {
					error_log("Error fetching statistics data: " . $mysqli->error);
				}
			}

			echo'<script src="assets/lib/jquery.flot.min.js"></script>
				<script src="assets/lib/jquery.flot.pie.min.js"></script>
				<script src="assets/lib/jquery.flot.categories.min.js"></script>
				<script src="assets/lib/jquery.flot.resize.js"></script>
				<script src="assets/lib/moment.min.js"></script>
				<script src="universalfunctions.js"></script>
				<script src="loginfunctions.js"></script>
				<script>var maxDay = '.htmlspecialchars($maxDay, ENT_QUOTES, 'UTF-8').';
				var maxSeven = '.htmlspecialchars($maxSeven, ENT_QUOTES, 'UTF-8').';
				var max28 = '.htmlspecialchars($max28, ENT_QUOTES, 'UTF-8').';
				</script>

				<script src="statsfunctions.js"></script>';

		break;
		case "training":
			echo'<script src="assets/lib/moment.min.js"></script>
				<script src="assets/lib/moment-range.min.js"></script>
				<script src="assets/lib/fullcalendar.min.js"></script>
				<link type="text/css" rel="stylesheet" href="assets/css/fullcalendar.min.css">
				<script src="universalfunctions.js"></script>
				<script src="loginfunctions.js"></script>
				<script src="trainingfunctions.js"></script>';
		break;
		case "scala":
			echo'<script src="assets/lib/moment.min.js"></script>
			<script src="assets/lib/bootstrap-timepicker.min.js"></script>
			<link type="text/css" rel="stylesheet" href="assets/css/bootstrap-timepicker.min.css"/>
			<script src="universalfunctions.js"></script>
			<script src="assets/js/scalafunctions.js"></script>
			<script src="loginfunctions.js"></script>
			';
		break;
		case "docufile":
			echo '<script src="assets/lib/moment.min.js"></script>
				<script src="assets/js/universalfunctions.js"></script>
				<script src="assets/js/loginfunctions.js"></script>
				<script src="assets/lib/dropzone.js"></script>
				<!-- <script src="assets/lib/crocodoc.viewer.min.js"></script>
				<link type="text/css" rel="stylesheet" href="assets/css/crocodoc.viewer.min.css"> -->
				<script src="assets/js/documentfunctions.js"></script>';
		break;
		case "account":
			echo '<script src="assets/js/universalfunctions.js"></script>
				<script src="assets/js/accountfunctions.js"></script>';
		break;
		case "news":
			echo '<script src="assets/lib/jquery.shapeshift.min.js"></script>
				<script src="assets/lib/moment.min.js"></script>
				<script src="assets/js/universalfunctions.js"></script>
				<script src="assets/js/newsfunctions.js"></script>';
		break;
		case "store":
			echo '<script src="assets/js/universalfunctions.js"></script>';
		break;
		case "posts":
			echo '<script src="assets/js/universalfunctions.js"></script>';
		break;
		case "messaging":
			echo '<script src="assets/lib/moment.min.js"></script>
				<script src="assets/js/universalfunctions.js"></script>
				<script src="assets/lib/dropzone.js"></script>
				<script src="assets/js/messagingfunctions.js"></script>';
		break;
		case "admin":
			echo '<script src="assets/js/universalfunctions.js"></script>
				<script src="assets/lib/dropzone.js"></script>
				<script src="assets/js/adminfunctions.js"></script>';
		break;
		case "map":
			echo '<script src="assets/js/universalfunctions.js"></script>
				<script src="https://maps.googleapis.com/maps/api/js?v=3.exp&libraries=weather"></script>
				<script src="assets/js/mapfunctions.js"></script>';
		break;
	}
	?>

<script type="text/javascript">
	$(document).ready(function(){
		var qStr = window.location.search.substring(1);
		if(qStr == "Welcome"){
			var welcomeHtml = '<div class="modal" id="welcomeModal" tabindex="-1" role="dialog" aria-labelledby="viewLog" aria-hidden="true"><div class="modal-dialog">'
				welcomeHtml += '<div class="modal-content"><div class="modal-header"><button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>'
				welcomeHtml += '<h2 class="modal-title text-center">Welcome to Helicopters Offshore!</h2></div><div class="modal-body">'
				welcomeHtml += '<h4 class="page-header">We\'d like to thank you for signing up to Helicopters Offshore.<br/>Enjoy the next 3 months on us!</h4>';
				welcomeHtml += '<p>To get started, check out the <strong>My Hangar</strong> to get your information and settings all fixed up.</p>';
				welcomeHtml += '<p>After that you can add pilots to your account in the <strong>Pilots</strong> page, <strong>Crafts</strong> and <strong>Contracts</strong> on their pages, and let the tracking begin!</p>';
				welcomeHtml += '</div><div class="modal-footer"><button class="btn btn-success" data-dismiss="modal">Got It!</button></div></div></div></div>';
			$("body").append(welcomeHtml);
			$("#welcomeModal").modal("show");
		}
	})
</script>
</head>
<body>

	<!-- Helicopters Offshore - hangar.php - NAV Horizontal line display===================================== -->

	<!-- <form class="form-inline" id="logoutform" action="assets/php/logout.php" method="post"></form> -->
	<form class="form-inline" id="logoutform" action="admviationHome.php" method="post"></form>
	<div class="dark-bg" id="navcontainer">
    	<div class="btn btn-primary mnav"><div class="fa fa-bars"></div></div>
		<!-- PROBLEM WITH VARIABLE $accountRes - line 198 -->
		<a href="home.php" id="brand">Helicopters Offshore - <?php echo isset($accountName) ? htmlspecialchars($accountName, ENT_QUOTES, 'UTF-8') : 'Account Name'; ?></a>
		<div id="user-dropdown">
			<div class="dropdown-header">
				<!-- Display User's First Name and Last Name -->
				<!-- <b>Welcome, <br/><span id='username'><?php 
					echo htmlspecialchars($firstName . ' ' . $lastName ?? "User Name", ENT_QUOTES, 'UTF-8'); 
				?></span></b> -->
				<b>Welcome, <br/><span id='username'><?php 
					$fullName = (isset($firstName) ? $firstName . ' ' : '') . (isset($lastName) ? $lastName : 'User Name');
					echo htmlspecialchars($fullName, ENT_QUOTES, 'UTF-8'); 
				?></span></b>

				<div class="fa fa-sort-down"></div>
			</div>
			<ul class="dropdown-content">
				<a href="hangar.php"><li><i class="fa fa-cogs"></i> My Hangar</li></a>
				<li onclick="$('#logoutform')[0].submit()"><i class="fa fa-power-off"></i> Log Out</li>
			</ul>
		</div>
		<div id="clock-dropdown">
			<div class="dropdown-header">
				<div class="fa fa-3x fa-clock-o"></div>
			</div>
			<div class="dropdown-content">
				<div class="col-md-6 text-center time" id="localTime"><div class="lbl">Local</div><span class='timeVal'></span></div>
				<div class="col-md-6 text-center time" id="setTime"><div class="lbl">Home</div><span class='timeVal'>12:00:00</span></div>
			</div>
		</div>
		<div id="notification-dropdown">
			<div class="dropdown-header">
				<div id="notification-head-wrapper">
					<div class="fa fa-3x fa-bell-o" id="notification-bell"></div>
					<div id="notification-number"></div>
				</div>
			</div>
			<div class="dropdown-content"></div>
		</div>
  	</div>

	<!-- Helicopters Offshore - header_v8.php - NAV Vertical display ===================================== -->

  <div class="sidebar">
    <ul class="sidebar-list">

      <a href="home.php">
        <li class="sidebar-item"><i class="fa fa-lg fa-calendar"></i> Dashboard/home</li>
      </a>
	  
      <?php
      echo'<a href="training.php">
	        <li class="sidebar-item"><i class="fa fa-lg fa-calendar"></i> Training Schedule</li>
	      </a>
	      <a href="scala.php">
	        <li class="sidebar-item"><i class="fa fa-lg fa-pencil"></i> Daily Management</li>
	      </a>';
  	  ?>
	  <?php
      
		//   if(intval($_SESSION["admin"]) > 0){
		echo '<a href="contracts.php">
				<li class="sidebar-item"><i class="fa fa-lg fa-book"></i> Contracts</li>
				</a>
				<a href="crafts.php">
				<li class="sidebar-item"><i class="fa fa-lg fa-book"></i> Crafts</li>
				</a>';
    	//   }
      echo '<a href="docufile.php">
        <li class="sidebar-item"><i class="fa fa-lg fa-file-text-o"></i> Documents</li>
      </a>';
      //if the user is a pilot
    	//   if(intval($_SESSION["admin"]) != 7 && intval($_SESSION["admin"]) != 6 && intval($_SESSION["admin"]) != 5 && intval($_SESSION["admin"]) != 4){
      	echo '<a href="statistics.php">
		        <li class="sidebar-item"><i class="fa fa-lg fa-bar-chart-o"></i> My Statistics</li>
		      </a>';
    	//   }
      ?>
	  
      <a href="pilots.php">
        <li class="sidebar-item"><i class="fa fa-lg fa-users"></i> Pilots</li>
      </a>
	  
	  <a href="hangar.php">
        <li class="sidebar-item"><i class="fa fa-lg fa-cogs"></i> My Hangar/hangar(Header)</li>
      </a>
  	  
	  <li class="sidebar-item">
  		<i class="fa fa-lg fa-users"></i>Community
  		<ul class='sidebar-dropdown-list'>
  			<a href="messaging.php"><li class="sidebar-item"><i class="fa fa-lg fa-envelope-o"></i>Message Center</li></a>
  			<a href="news.php"><li class="sidebar-item"><i class="fa fa-lg fa-newspaper-o"></i>News</li></a>
  			<a href="store.php"><li class="sidebar-item"><i class="fa fa-lg fa-money"></i>Store</li></a>
  			<!-- <a href="posts.php"><li class="sidebar-item"><i class="fa fa-lg fa-comments-o"></i>Posts</li></a> -->
  		</ul>
  	  </li>

  	  <!-- </li> -->

    </ul>
  </div>
  <div class="content">