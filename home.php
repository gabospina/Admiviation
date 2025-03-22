<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

if(!isset($_SESSION["HeliUser"])){
  // echo "Session not set. Redirecting to index.php...";
  echo "Session not set. Redirecting to admviationHome.php...";
  // header("Location: dashboard.php");
  header("Location: admviationHome.php"); // Redirect to login page
  exit(); // Important: Stop further execution after redirection
}

$firstName = $_SESSION['firstName'] ?? '';
$lastName = $_SESSION['lastName'] ?? '';
$accountName = $_SESSION['accountName'] ?? '';

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
// include_once "header.php";
include "header.php";

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
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                        <h3 class="modal-title">Your future schedule</h3>
                    </div>
                    <div class="modal-body">
                        <ul id="userOnOff">
                            <!-- Availability dates will be loaded here by JavaScript -->
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

        <!-- =========== BELOW - COMPLETED ================== -->
        <div class="col-md-4 outer-bottom-xs">
          <h4 class="text-center">Contract:</br></h4>
          <select class="form-control" id="contract-select">
            <option value="any" selected>Any</option>
          </select>
          
          <h4 class="text-center">Aircraft:</br></h4>
          <select class="form-control" id="craft-select"></select>
          <?php if($adminLevel > 0 && $adminLevel != 2 && $adminLevel != 5) echo '<button class="btn btn-success form-control outer-top-xs" id="sendUpdateSMS">Send Update Notifications</button>'; ?>
        </div>
        <!-- =========== ABOVE - COMPLETED ================== -->
      </div>
    </div>

  	 <div class="inner-bottom" style="width: 100%;">
      <div class="col-md-3 center-block" id="fullScheduleLegend">
        <h3 class="text-center page-header">Legend</h3>
        <ul id="legendList">
          <li>No aircrafts listed</li>
        </ul>
      </div>
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

<!-- ================================ NEW SCHEDULE DESIGN IS ON home_v9 ======================================================================= -->

<div class="pilot">
    <table>
        <caption>EC225 Aircraft Registrations</caption>
        <thead>
            <tr>
                <th scope="rowgroup">Week</th>
                <th scope="col">C-QWER</th>
                <th scope="col">C-GOLF</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <th scope="row" rowspan="1">Monday</th>
                <td>
                    <select name="comandante" class="mon comandante">
                        <option value="">Comandante</option>
                    </select><br>
                    <select name="piloto" class="mon piloto">
                        <option value="">Piloto</option>
                    </select>
                </td>
                <td>
                    <select name="comandante" class="mon comandante">
                        <option value="">Comandante</option>
                    </select><br>
                    <select name="piloto" class="mon piloto">
                        <option value="">Piloto</option>
                    </select>
                </td>
            </tr>
            <tr>
                <th scope="row" rowspan="1">Tuesday</th>
                <td>
                    <select name="comandante" class="mon comandante">
                        <option value="">Comandante</option>
                    </select><br>
                    <select name="piloto" class="mon piloto">
                        <option value="">Piloto</option>
                    </select>
                </td>
                <td>
                    <select name="comandante" class="mon comandante">
                        <option value="">Comandante</option>
                    </select><br>
                    <select name="piloto" class="mon piloto">
                        <option value="">Piloto</option>
                    </select>
                </td>
            </tr>
            <tr>
                <th scope="row" rowspan="1">Wednesday</th>
                <td>
                    <select name="comandante" class="mon comandante">
                        <option value="">Comandante</option>
                    </select><br>
                    <select name="piloto" class="mon piloto">
                        <option value="">Piloto</option>
                    </select>
                </td>
                <td>
                    <select name="comandante" class="mon comandante">
                        <option value="">Comandante</option>
                    </select><br>
                    <select name="piloto" class="mon piloto">
                        <option value="">Piloto</option>
                    </select>
                </td>
            </tr>
            <tr>
                <th scope="row" rowspan="1">Thursday</th>
                <td>
                    <select name="comandante" class="mon comandante">
                        <option>Comandante</option>
                    </select><br>
                    <select name="piloto" class="mon piloto">
                        <option>Piloto</option>
                    </select>
                </td>
                <td>
                    <select name="comandante" class="mon comandante">
                        <option>Comandante</option>
                    </select><br>
                    <select name="piloto" class="mon piloto">
                        <option>Piloto</option>
                    </select>
                </td>
            </tr>
            <tr>
                <th scope="row" rowspan="1">Friday</th>
                <td>
                    <select name="comandante" class="mon comandante">
                        <option>Comandante</option>
                    </select><br>
                    <select name="piloto" class="mon piloto">
                        <option>Piloto</option>
                    </select>
                </td>
                <td>
                    <select name="comandante" class="mon comandante">
                        <option>Comandante</option>
                    </select><br>
                    <select name="piloto" class="mon piloto">
                        <option>Piloto</option>
                    </select>
                </td>
            </tr>
            <tr>
                <th scope="row" rowspan="1">Saturday</th>
                <td>
                    <select name="comandante" class="mon comandante">
                        <option>Comandante</option>
                    </select><br>
                    <select name="piloto" class="mon piloto">
                        <option>Piloto</option>
                    </select>
                </td>
                <td>
                    <select name="comandante" class="mon comandante">
                        <option>Comandante</option>
                    </select><br>
                    <select name="piloto" class="mon piloto">
                        <option>Piloto</option>
                    </select>
                </td>
            </tr>
            <tr>
                <th scope="row" rowspan="1">Sunday</th>
                <td>
                    <select name="comandante" class="mon comandante">
                        <option>Comandante</option>
                    </select><br>
                    <select name="piloto" class="mon piloto">
                        <option>Piloto</option>
                    </select>
                </td>
                <td>
                    <select name="comandante" class="mon comandante">
                        <option>Comandante</option>
                    </select><br>
                    <select name="piloto" class="mon piloto">
                        <option>Piloto</option>
                    </select>
                </td>
            </tr>
            <!-- Repeat for other days -->
        </tbody>
    </table>
</div>



    <!-- =========================================  Thoughts ======================================================================== -->

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

    <script>
        $(document).ready(function() {
            // Initialize any JavaScript functions or event listeners here
            updateDailySchedule();
            updateMessages();
            updateRecordList();

            // Example of initializing datepicker for sched_week
            $("#sched_week").datepicker({
                autoclose: true,
                weekStart: 1,
                format: "yyyy-mm-dd"
            }).on("changeDate", function(e) {
                updateDailySchedule();
                updateMessages();
                updateRecordList();
            });

            // Example of handling contract-select change event
            $("#contract-select").change(function() {
                updateDailySchedule();
            });

            // Example of handling craft-select change event
            $("#craft-select").change(function() {
                updateDailySchedule();
            });

            // Example of handling clearWeekBtn click event
            $("#clearWeekBtn").click(function() {
                // Add your clear week logic here
            });

            // Example of handling sendUpdateSMS click event
            $("#sendUpdateSMS").click(function() {
                // Add your send update notifications logic here
            });
        });
    </script>

<script>
        $(document).ready(function () {
            $("comandante", "piloto").each(function () {
                if ($(this).hasClass("mon")) {
                    $(this).hide();
                }
            });

            // $("#nav").navPlugin({
            //     'itemWidth': 150,
            //     'itemHeight': 30,
            //     'navEffect': "slide",
            //     'speed': 250
            // });

            // Initialize any JavaScript functions or event listeners here
            updateDailySchedule();
            updateMessages();
            updateRecordList();

            // Example of initializing datepicker for sched_week
            $("#sched_week").datepicker({
                autoclose: true,
                weekStart: 1,
                format: "yyyy-mm-dd"
            }).on("changeDate", function (e) {
                updateDailySchedule();
                updateMessages();
                updateRecordList();
            });

            // Example of handling contract-select change event
            $("#contract-select").change(function () {
                updateDailySchedule();
            });

            // Example of handling craft-select change event
            $("#craft-select").change(function () {
                updateDailySchedule();
            });

            // Example of handling clearWeekBtn click event
            $("#clearWeekBtn").click(function () {
                // Add your clear week logic here
            });

            // Example of handling sendUpdateSMS click event
            $("#sendUpdateSMS").click(function () {
                // Add your send update notifications logic here
            });
        });
    </script>

  <?php include_once "footer.php";?>

  <!-- 1. jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.js"></script>

<!-- 2. noty CSS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/noty/3.1.4/noty.css" integrity="sha512-NXUhxhkDgZYOMjaIgd89zF2w51Mub53Ru3zCNp5LTlEzMbNNAjTjDbpURYGS5Mop2cU4b7re1nOIucsVlrx9fA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<!-- noty JavaScript FATAL ERROR -->
<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/noty/3.1.4/noty.min.js" integrity="sha512-lOrm9FgT1LKOJRUXF3tp6QaMorJftUjowOWiDcG5GFZ/q7ukof19V0HKx/GWzXCdt9zYju3/KhBNdCLzK8b90Q==" crossorigin="anonymous" referrerpolicy="no-referrer"></script> -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/noty/3.1.4/noty.js" integrity="sha512-mgZL3SZ/vIooDg2mU2amX6NysMlthFl/jDbscSRgF/k3zmICLe6muAs7YbITZ+61FeUoo1plofYAocoR5Sa1rQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<!-- jQuery UI JavaScript -->
<!-- <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script> -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.14.1/jquery-ui.min.js" integrity="sha512-MSOo1aY+3pXCOCdGAYoBZ6YGI0aragoQsg1mKKBHXCYPIWxamwOE7Drh+N5CPgGI5SA9IEKJiPjdfqWFWmZtRA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<!-- jQuery UI CSS old link-->
<!-- <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css"> -->
<!-- new link -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.14.1/themes/base/jquery-ui.min.css" integrity="sha512-TFee0335YRJoyiqz8hA8KV3P0tXa5CpRBSoM0Wnkn7JoJx1kaq1yXL/rb8YFpWXkMOjRcv5txv+C6UluttluCQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />

<!-- Bootstrap CSS -->
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css" integrity="sha384-HSMxcRTRxnN+Bdg0JdbxYKrThecOKuH5zCYotlSAcp1+c8xmyTe9GYg1l9a69psu" crossorigin="anonymous">
<!-- Bootstrap JavaScript -->
<script src="https://stackpath.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js" integrity="sha384-aJ21OjlMXNL5UyIl/XNwTMqvzeRMZH2w8c5cRVpzpU8Y5bApTppSuUkhZXN0VxHd" crossorigin="anonymous"></script>

<!-- X-Editable CSS -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/x-editable/1.5.1/bootstrap3-editable/css/bootstrap-editable.css" rel="stylesheet">
<!-- X-Editable JavaScript -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/x-editable/1.5.1/bootstrap3-editable/js/bootstrap-editable.min.js"></script>


<script src="assets/lib/moment.min.js"></script>
<script src="assets/lib/dropzone.js"></script>
<script src="universalfunctions.js"></script>
<script src="loginfunctions.js"></script>