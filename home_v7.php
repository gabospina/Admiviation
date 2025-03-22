<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

// session_start();
// var_dump($_SESSION);
// echo "Welcome!";

// if (session_status() == PHP_SESSION_NONE) {
//     session_start();
// }

if(!isset($_SESSION["HeliUser"])){
  // echo "Session not set. Redirecting to index.php...";
  echo "Session not set. Redirecting to admviationHome.php...";
  // header("Location: dashboard.php");
  header("Location: home.php");
  // exit(); // Important: Stop further execution after redirection
}

$lastHeliSelected = "";
if(isset($_COOKIE["lastHeliSelected"])){
  $_SESSION["lastHeliSelected"] = $_COOKIE["lastHeliSelected"];
}
if(isset($_SESSION["lastHeliSelected"])){
  $lastHeliSelected = $_SESSION["lastHeliSelected"];
}
$page = "home";
echo '<script>
  var lastHeliSelected = "' . $lastHeliSelected .'";
</script>';
include_once "header.php";

?>
 	<!-- ENDS NAV ===================================== -->
  <?php
      $adminLevel = intval($_SESSION["admin"]);
      if($adminLevel == 8 || $adminLevel < 4){
        echo '
           	<div class="light-bg">
          	<div class="container" id="topContainer">
          		<div class="row outer-xs ">
                <div id="notifications"></div>
        <!--         <div id="addPilotHeader"></div> -->
          			<div id="onOffHeader" class="page-header outer-top-sm text-center">
          				<h2>On Duty: <small id="onOffHeaderText"></small><div class="btn btn-primary outer-left-sm" data-toggle="modal" data-target=".future_sched">View future dates</div></h2>
          				<span class="divider"></span>
          			</div>
          		</div>
          	</div>
          	</div>

          <!-- FUTURE DATES MODAL ============== -->

              <div class="modal future_sched" tabindex="-1" role="dialog" aria-labelledby="schedmodal" aria-hidden="true">
                <div class="modal-dialog">
                <div class="modal-content">
                  <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h3 class="modal-title">Your future schedule</h3>
                  </div>
                  <div class="modal-body">
                    <ul id="userOnOff">
                    </ul>
                  </div>
                </div>
                </div>
              </div>

            <!-- ============================================== SCHEDULE SECTION ============================================= -->
            
            <div class="container">
              <div class="row usercenter-block outer-bottom-sm outer-top-xs" id="myScheduleSection">
                <h2>My Schedule</h2>
                <table class="mysched dark-bg">
                  <thead>
                    <th id="0">Monday</th>
                    <th id="1">Tuesday</th>
                    <th id="2">Wednesday</th>
                    <th id="3">Thursday</th>
                    <th id="4">Friday</th>
                    <th id="5">Saturday</th>
                    <th id="6">Sunday</th>
                  </thead>
                  <tr id="userSched">
                  </tr>
                </table>
              </div>';
      }else{
        echo '<div class="container">';
      }
      ?>   		

  		<!-- ======================= FULL SCHEDULE SECTION ======================= -->
       
  		<div class="row outer-top-xs" id="advancedSelect">
  			<h2 class="page-header text-center">Full Schedule</h2>
        <div class="col-md-4" id="fullScheduleLegend">
          <h3 class="text-center page-header">Legend</h3>
          <ul id="legendList">
            <li>No aircrafts listed</li>
          </ul>
        </div>
    		<div class="col-md-4 outer-bottom-xs">	
    			<h4 class="text-center">Select Week:</br></h4>
    			<input type="text" class="form-control" id="sched_week">
          <h4 class="text-center">Use strict search <i class="fa fa-info-circle" data-toggle="tooltip" data-placement="right" title="Required validity is SIM, Medical, and Angolan license."></i></h4>
          <select class="form-control" id="strict-search">
            <option value="2">Check for ALL Validity</option>
            <option value="1">Check for REQUIRED Validity</option>
            <option value="0" selected>Don't Check for Validity</option>
          </select>
          <?php if($adminLevel > 0 && $adminLevel != 2 && $adminLevel != 5) echo '<button class="btn btn-danger form-control outer-top-xs" id="clearWeekBtn">Clear Week For These Crafts</button>'; ?>
        </div>
        <div class="col-md-4 outer-bottom-xs">
          <h4 class="text-center">Contract:</br></h4>
          <select class="form-control" id="contract-select">
            <option value="any" selected>Any</option>
          </select>
          <h4 class="text-center">Aircraft:</br></h4>
          <select class="form-control" id="craft-select">
          </select>
          <?php if($adminLevel > 0 && $adminLevel != 2 && $adminLevel != 5) echo '<button class="btn btn-success form-control outer-top-xs" id="sendUpdateSMS">Send Update Notifications</button>'; ?>
        </div>
      </div>
    </div>

  	 <div class="inner-bottom" style="width: 100%;">
      <!-- <div class="col-md-3 center-block" id="fullScheduleLegend">
        <h3 class="text-center page-header">Legend</h3>
        <ul id="legendList">
          <li>No aircrafts listed</li>
        </ul>
      </div> -->
  			<table class="fullsched table-responsive" style="width: 100%;">
  				<thead>
  					<tr>
  						<th scope="rowgroup">Week</th>
  						<th scope="col" class="day0">Monday<br/><span class="date"></span></th>
  						<th scope="col" class="day1">Tuesday<br/><span class="date"></span></th>
  						<th scope="col" class="day2">Wednesday<br/><span class="date"></span></th>
  						<th scope="col" class="day3">Thursday<br/><span class="date"></span></th>
  						<th scope="col" class="day4">Friday<br/><span class="date"></span></th>
  						<th scope="col" class="day5">Saturday<br/><span class="date"></span></th>			
              <th scope="col" class="day6">Sunday<br/><span class="date"></span></th>	
  					</tr>
  				</thead>
          <tbody>
          </tbody>	
  			</table>
        <div class="col-md-12 text-center outer-top-xs" id="printRow"><button class="btn btn-primary btn-lg" id="printFullSched">Print</button></div>
  		</div>
  	<!-- ====================================== ENDS SCHEDULE SECTION ================================================================= -->

  		</div>
  	</div>
    <div class="container" id="thoughtsContainer">
      <div class="row outer-left">
        <div class="col-md-5">
          <h3 class="page-header">Let Us Know Your Thoughts</h3>
          <div class="form-group">
            <input type="text" class="form-control" id='thoughtsEmail' placeholder="Email (Optional)">
          </div>
          <div class="form-group">
            <input type="text" class="form-control" id='thoughtsName' placeholder="Name (Optional)">
          </div>
          <textarea id="thoughtsMessage"></textarea>
          <button class="btn btn-default form-control outer-bottom-xs" id='submitThoughts'>Send</button>
        </div>
      </div>
    </div>
  <?php include_once "footer.php";?>