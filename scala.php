<?php
  if (session_status() == PHP_SESSION_NONE) {
      session_start();
  }
  if(!isset($_SESSION["HeliUser"])){
    header("Location: index.php");
  }
  $lastHeliSelected = "";
  if(isset($_COOKIE["lastHeliSelected"])){
    $_SESSION["lastHeliSelected"] = $_COOKIE["lastHeliSelected"];
  }
  if(isset($_SESSION["lastHeliSelected"])){
    $lastHeliSelected = $_SESSION["lastHeliSelected"];
  }
  $admin = intval($_SESSION["admin"]);
  echo '<script>var lastHeliSelected = "'.$lastHeliSelected.'",
          ADMIN = '.$admin.';
        </script>';
  $page = "scala";
  include_once "header.php";

?>
    <div class="light-bg">
      <div class="inner-sm">
        <h1 class="page-header text-center">Daily Management</h1>
        <div class="row" style="width: 100%;">
          <div class="col-md-12 outer-left-xxs">
            <div class="tab-container">
              <div class="tabs">
                <div class="tab" data-tab-toggle="notify">Schedule</div>
                <?php
                  if($admin > 0 && $admin != 2 && $admin != 5){
                    echo '<div class="tab" data-tab-toggle="messages">Message Log</div>
                          <div class="tab" data-tab-toggle="hours">Records</div>';
                  }
                ?>
                          
              </div>
              <div class="tab-content">
                <div class="tab-pane" data-tab="notify">
                   <h3 class="page-header text-center">Manage Daily Schedule and Notifications</h3>
                   <div class="col-md-12">
                    <div class="col-md-4" id="fullScheduleLegend">
                      <h3 class="text-center page-header">Legend</h3>
                      <ul id="legendList">
                        <li>No aircrafts listed</li>
                      </ul>
                    </div>
                    <div class="col-md-4 outer-bottom-xs">  
                      <h4 class="text-center">Select Day:</br></h4>
                      <input type="text" class="form-control" id="sched_day">
                      <h4 class="text-center">Use strict search <i class="fa fa-info-circle" data-toggle="tooltip" data-placement="right" title="Required validity is SIM, Medical, and Angolan license."></i></h4>
                      <select class="form-control" id="strict-search">
                        <option value="2">Check for ALL Validity</option>
                        <option value="1">Check for REQUIRED Validity</option>
                        <option value="0" selected>Don't Check for Validity</option>
                      </select>
                    </div>
                    <div class="col-md-4 outer-bottom-xs">
                      <h4 class="text-center">Contract:</br></h4>
                      <select class="form-control" id="contract-select">
                        <option value="any" selected>Any</option>
                      </select>
                      <h4 class="text-center">Aircraft:</br></h4>
                      <select class="form-control" id="craft-select">
                      </select>
                      <?php if($admin > 0 && $admin != 2 && $admin != 5) echo '<button class="btn btn-success form-control outer-top-xs" id="sendNotiSMS">Send Notifications</button>'; ?>
                    </div>
                   </div>
                   <div class="col-md-12">
                    <table class="fullsched table-responsive no-shadow" style="width: 100%;">
                      <thead>
                        <tr>
                          <?php
                            if($admin > 0 && $admin != 2 && $admin != 5){
                            echo '<th style="width: 4%;">
                            Notify<br/><div class="checkbox"><label><input type="checkbox" class="notify-checkbox" id="check-all"/> All</label></div>
                          </th>';
                          }
                          ?>
                          <th>Class</th>
                          <th id="day"></th>
                          <th colspan="2">Details</th>
                          <th class="past-header">On Duty</th>
                          <th class="past-header">Off Duty</th>
                          <th class="past-header">Duty</th>
                          <th class="past-header">Flight</th>
                          <th class="past-header">Landings</th>
                        </tr>
                      </thead>
                      <tbody>
                      </tbody>  
                    </table>
                  </div>
                </div>
              <?php
                  if($admin > 0 && $admin != 2 && $admin != 5){
                    echo'
                <div class="tab-pane" data-tab="messages">
                  <h3 class="page-header text-center">Review Sent Notifications</h3>
                  <div class="col-md-12">
                    <div class="col-md-2">
                      <div class="lbl">&nbsp;</div>
                      <button class="btn btn-danger form-control" id="deleteMessages">Delete Selected Messages</button>
                    </div>
                    <div class="col-md-3">
                      <div class="lbl">Select Date</div>
                      <input type="text" class="form-control" id="message_day"/>
                    </div>
                    <div class="col-md-3">
                      <div class="lbl">Filter Status, Pilots, Contracts or Crafts</div>
                      <input type="text" class="form-control" id="search_messages" placeholder="Search.."/>
                    </div>
                    <div class="col-md-1">
                      <div class="lbl">&nbsp;</div>
                      <button class="btn btn-primary form-control" onclick="updateMessages()">Refresh</button>
                    </div>
                  </div>
                  <div class="col-md-12 outer-top-xs">
                    <table class="table table-bordered table-striped no-shadow" id="messageTable">
                      <thead>
                        <th style="width: 4%;">
                          <div class="checkbox"><label><input type="checkbox" class="sms-checkbox" id="sms-check-all"/> All</label></div>
                        </th>
                        <th>Schedule Date</th>
                        <th>Craft</th>
                        <th>Pilot</th>
                        <th>Date Sent</th>
                        <th>Message Status</th>
                      </thead>
                      <tbody></tbody>
                    </table>
                  </div>
                </div>
                <div class="tab-pane" data-tab="hours">
                 <!-- <h3 class="page-header text-center">Records and Statistics</h3>
                  <div class="col-md-12 no-float">
                    <div class="col-md-3">
                      <div class="lbl">Select Date</div>
                      <input type="text" class="form-control" id="record_day" />
                    </div>
                    <div class="col-md-3">
                      <div class="lbl">Select Aircraft</div>
                      <select class="form-control" id="pilot-record-aircraft">
                        <option disabled selected value="default">Select an Aircraft</option>
                      </select>
                    </div>
                    <div class="col-md-3 col-md-offset-3 outer-top-xxs">
                      <h3 id="record-contract"></h3>
                    </div>
                  </div>
                  <div class="col-md-9 center-block no-float outer-top-xs"> -->
                    <!--Commandante-->
                    <!--<table class="table no-shadow vertical-align-table">
                      <thead>
                        <th class="no-border"></th>
                        <th class="text-center">Punch-In</th>
                        <th class="text-center">Punch-Out</th>
                        <th class="text-center">Daily</th>
                        <th class="text-center">Flown</th>-->
                        <!-- <th class="text-center"></th>
                        <th class="text-center"></th> -->
                       <!-- <th class="text-center">Landings</th>
                      </thead>
                      <tbody>
                        <tr>
                          <th class="no-border text-center" style="width: 21%;" >Comandante</th>
                          <td class="border" rowspan="2"><input type="text" class="form-control" id="com-punch-in" /></td>
                          <td class="border" rowspan="2"><input type="text" class="form-control" id="com-punch-out" /></td>
                          <td class="border" rowspan="2"><input type="text" class="form-control" id="com-daily-hrs" /></td> --> <!--Daily Hours-->
                          <!--<td class="border" rowspan="2"><input type="text" class="form-control" id="com-flown-hrs" /></td> --><!--Flown Hours-->
                          <!-- <td class="border" rowspan="2"></td> --> <!--Empty-->
                          <!-- <td class="border" rowspan="2"></td> --> <!--Empty-->
                         <!-- <td class="border" rowspan="2"><input type="text" class="form-control" id="com-landings" /></td> --><!--landings-->
                       <!-- </tr>
                        <tr>
                          <td><select class="form-control" id="record-comandante"><option selected disabled value="default">Select Pilot</option></select></td>
                        </tr>
                      </tbody>
                    </table> -->
                    <!--Piloto-->
                   <!-- <table class="table no-shadow vertical-align-table">
                      <thead>
                        <th class="no-border"></th>
                        <th class="text-center">Punch-In</th>
                        <th class="text-center">Punch-Out</th>
                        <th class="text-center">Daily</th>
                        <th class="text-center">Flown</th>-->
                        <!-- <th class="text-center"></th>
                        <th class="text-center"></th> -->
                       <!-- <th class="text-center">Landings</th>
                      </thead>
                      <tbody>
                        <tr>
                          <th class="no-border text-center" style="width: 21%;" >Piloto</th>
                          <td class="border" rowspan="2"><input type="text" class="form-control" id="pil-punch-in" /></td>
                          <td class="border" rowspan="2"><input type="text" class="form-control" id="pil-punch-out" /></td>
                          <td class="border" rowspan="2"><input type="text" class="form-control" id="pil-daily-hrs" /></td> --><!--Daily Hours-->
                          <!--<td class="border" rowspan="2"><input type="text" class="form-control" id="pil-flown-hrs" /></td> --><!--Flown Hours-->
                          <!-- <td class="border" rowspan="2"></td> --> <!--Empty-->
                          <!-- <td class="border" rowspan="2"></td> --> <!--Empty-->
                         <!-- <td class="border" rowspan="2"><input type="text" class="form-control" id="pil-landings" /></td> --> <!--landings-->
                        <!--</tr>
                        <tr>
                          <td><select class="form-control" id="record-piloto"><option selected disabled value="default">Select Pilot</option></select></td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                  <div class="col-md-12 no-float inner-right">
                    <div class="col-md-2 pull-right">
                      <button class="btn btn-success" id="submitRecord">Submit Record</button>
                    </div>
                  </div> -->
                  <div class="col-md-12 no-float">
                    <h3 class="page-header text-center">Review Records <span id="recordTitleSpan"></span><!--<button class="btn btn-primary pull-right" data-toggle="modal" data-target="#recordFormsModal">Print Record Forms</button> --></h3>
                    <div id="recordSection">
                      <table class="table table-striped no-shadow" id="recordTable">
                        <thead>
                          <tr>
                            <th colspan="8"></th>
                            <th  class="text-center" colspan="2">Limit Exceeded</th>
                          </tr>
                          <tr>
                            <th class="text-center">Craft</th>
                            <th class="text-center">Pilot</th>
                            <th class="text-center">Position</th>
                            <th class="text-center">On Duty</th>
                            <th class="text-center">Off Duty</th>
                            <th class="text-center">Duty</th>
                            <th class="text-center">Flight</th>
                            <th class="text-center">Landings</th>
                            <th class="text-center">Duty</th>
                            <th class="text-center">Flight</th>
                          </tr>
                        </thead>
                        <tbody class="text-center"></tbody>
                      </table>
                    </div>
                  </div>
                </div>'; }?>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <?php
      if($admin > 0 && $admin != 2 && $admin != 5){
        echo '
    <div class="modal" id="recordFormsModal" tabindex="-1" role="dialog" aria-labelledby="viewLog" aria-hidden="true">
      <div class="modal-dialog" style="width: 50%">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
          <h1 class="modal-title text-center">Record Forms</h1>
        </div>
        <div class="modal-body">
          <div class="container">
            <h3 class="page-header">Please select aircrafts and the date</h3>
            <div class="col-md-12 no-padding no-float">
              <div class="col-md-6 center-block">
                <div class="lbl">Start Date</div>
                <input type="text" class="form-control" id="recordFormsDate">
              </div>
            </div>
            <div class="col-md-10 outer-top-xs center-block no-float">
              <div id="recordCraftList">
                <div class=\'col-md-3 no-padding no-margin\'><div class=\'check-box selected recordCraftChecks\' data-value=\'none\'>All<div class=\'check-mark\'><div class=\'fa fa-check-square-o\'></div></div></div></div>
              </div>
            </div>
          </div><!-- ENDS CONTAINER -->
       </div>
       <div class="modal-footer">
        <button class="btn btn-success" id="getRecordForms">View Report</button>
        <button class="btn" data-dismiss="modal">Cancel</button>
       </div>
      </div>
      </div>
    </div>';
      }
    ?>
  <?php include_once "footer.php";?>