<?php
  if (session_status() == PHP_SESSION_NONE) {
      session_start();
  }
  if(!isset($_SESSION["HeliUser"])){
    header("Location: index.php");
  }
  // $lastHeliSelected = "";
  // if(isset($_COOKIE["lastHeliSelected"])){
  //   $_SESSION["lastHeliSelected"] = $_COOKIE["lastHeliSelected"];
  // }
  // if(isset($_SESSION["lastHeliSelected"])){
  //   $lastHeliSelected = $_SESSION["lastHeliSelected"];
  // }
  $page = "statistics";
  include_once "header.php";
  include_once "assets/php/db_connect.php";
?>
    <div class="light-bg" id="personalSection">
      <div class="inner-sm">
        <h1 class="page-header text-center">Track Your Hours</h1>
          <div class="col-md-12">
            <div class="tabs">
              <div class="tab active" data-tab-toggle="tables">Manage Statistics</div>
              <div class="tab" data-tab-toggle="graphs">Limited Times</div>
              <div class="tab" data-tab-toggle="crafts">Craft Experience</div>
            </div>
            <div class="tab-content">
              <div class="tab-pane active" data-tab="tables">
                  <h2 class="page-header inner-left-xs">Manage Hours</h2>
                  <div class="col-md-12" id="addHourContainer">
                    <div id="scroll-left-indicator"><div class="fa fa-chevron-left fa-2x"></div></div>
                    <div id="scroll-right-indicator"><div class="fa fa-chevron-right fa-2x"></div></div>
                    <div class="col-md-12 no-padding" id="addHoursSection">
                      <table class="table" id="addEntryTable">
                        <thead>
                          <tr>
                            <th class="no-border"></th>
                            <th colspan="2" class="no-border text-center">Aircraft</th>
                            <th colspan="3" class="no-border"></th>
                            <th colspan="2" class="no-border text-center">Instruments</th>
                            <th colspan="2" class="no-border text-center">Hours</th> 
                          </tr>
                          <tr>
                            <th class="text-center">Date</th>
                            <th class="text-center border-left">Model</th>
                            <th class="text-center border-right">Registration</th>
                            <th class="text-center border-right">Pilot in Command</th>
                            <th class="text-center border-right">Copilot</th>
                            <th class="text-center border-right">Route/<br/>Remarks</th>
                            <th class="text-center">IFR</th>
                            <th class="text-center border-right">Approaches</th>
                            <th class="text-center">Day</th>
                            <th class="text-center border-right">Night</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr>
                            <td class="border-bottom border-right" style="width: 175px">
                              <input type="text" id="addDate" class="form-control" placeholder="YYYY-MM-DD"/>
                            </td>
                            <td class="border-bottom" style="width: 185px">
                              <select class="form-control" id="addCraft"></select>
                            </td>
                            <td class="border-bottom border-right" style="width: 185px">
                              <select class="form-control" id="addAircraft"></select>
                            </td>
                            <td class="border-bottom border-right" style="width: 200px">
                              <input type="text" id="addCommand" class="form-control">
                            </td>
                            <td class="border-bottom border-right" style="width: 200px">
                              <input type="text" id="addCopilot" class="form-control">
                            </td>
                            <td class="border-bottom border-right" style="width: 350px">
                              <input type="text" id="addRoute" class="form-control">
                            </td>
                            <td class="border-bottom" style="width: 75px">
                              <input type="text" id="addIFRInstrument" style="min-width: 45px;" class="form-control"/>
                            </td>
                            <td class="border-right border-bottom" style="width: 75px">
                              <input type="text" id="addActualInstrument" style="min-width: 45px;" class="form-control"/>
                            </td>
                            <td class="border-bottom" style="width: 75px">
                              <input type="text" id="addDayHour" style="min-width: 45px;" class="form-control"/>
                            </td>
                            <td class="border-right border-bottom" style="width: 75px">
                              <input type="text" id="addNightHour" style="min-width: 45px;" class="form-control"/>
                            </td>
                            <td style="width: 45px">
                              <button class="btn btn-success" id="addEntry"><div class="fa fa-plus"></div></button>
                            </td>
                          </tr>
                        </tbody>
                      </table>
                    </div>
                  </div>
                  <!-- <div class="col-md-1">
                    <div class="lbl">Date</div>
                    <input type="text" id="addDate" class="form-control" placeholder="YYYY-MM-DD"/>
                  </div>
                  <div class="col-md-2">
                    <div class="lbl">Aircraft</div>
                    <div class="col-md-6 no-padding">
                      <div class="lbl">Model</div>
                      <select class="form-control" id="addCraft"></select>
                    </div>
                    <div class="col-md-6 no-padding">
                      <div class="lbl">Registration</div>
                      <select class="form-control" id="addAircraft"></select>
                    </div>
                  </div>
                  <div class="col-md-1">
                    <div class="lbl">Pilot<br/>in command</div>
                    <input type="text" id="addCommand" class="form-control" placeholder="Pilot">
                  </div>
                  <div class="col-md-1">
                    <div class="lbl">Copilot</div>
                    <input type="text" id="addCopilot" class="form-control" placeholder="Pilot">
                  </div>
                  <div class="col-md-1">
                    <div class="lbl">Route/<br/>Remarks</div>
                    <textarea rows="4" id="addRoute" class="form-control"></textarea>
                  </div>
                  <div class="col-md-2">
                    <div class="lbl">Instruments</div>
                    <div class="lbl">Actual</div>
                    <input type="text" id="addActualInstrument" class="form-control" placeholder="Actual"/>
                    <div class="lbl">IFR Approches</div>
                    <input type="text" id="addIFRInstrument" class="form-control" placeholder="IFR Approaches"/>
                  </div>
                  <div class="col-md-1">
                    <div class="lbl">Hours</div>
                    <div class="lbl">Day</div>
                    <input type="text" id="addNightHour" class="form-control" placeholder="Night"/>
                    <div class="lbl">Night</div>
                    <input type="text" id="addNightHour" class="form-control" placeholder="Night"/>
                  </div>
                  <div class="col-md-1">
                    <button class="btn btn-success" id="addEntry"><div class="fa fa-plus"></div></button>
                  </div> -->

                  <h2 class="page-header inner-left-xs inner-right-xs no-margin">Log Book <div class="pull-right"><button class="btn btn-primary" data-toggle="modal" data-target="#viewLogbookModal">View Logbook</button><button class="btn btn-primary outer-left-xxs" data-toggle="modal" data-target="#viewLogModal">Monthly Report</button></div></h2>
                  <div class="col-md-12 no-float outer-bottom-xxs">
                    <div class="col-md-2">
                      <div class="lbl">Log book begining from:</div>
                      <input type="text" id="log-date" class="form-control" placeholder="Select Starting Date"/>
                    </div>
                    <div class="col-md-4 col-md-offset-6" id="page-container">
                      <div class="lbl">Page</div>
                      <div class="pages"></div>
                    </div>
                  </div>
                  <div id="manageHoursSection"></div>
              </div>
              <div class="tab-pane" data-tab="graphs">
                <div class="col-md-12">
                  <h2 class="page-header">Hour Statistics <button class="btn btn-primary pull-right" data-toggle="modal" data-target="#limitationsModal">Crew Time Limitations</button></h2>
                  <div class="col-md-12">
                    <div class="col-md-5">
                      <div class="lbl">Select View</div>
                      <div class="btn-group">
                        <button class="btn btn-default view-change" data-view="past7">Last 7 days</button>
                        <button class="btn btn-default view-change" data-view="past28">Last 28 days</button>
                        <!-- <button class="btn btn-default view-change" data-view="week">Week</button> -->
                        <button class="btn btn-default view-change" data-view="month">Month</button>
                        <button class="btn btn-default view-change" data-view="year">Year</button>
                      </div>
                    </div>
                    <div class="col-md-2">
                      <div class="lbl">Select Date</div>
                      <input type="text" class="form-control" id="graphStartDate" placeholder="YYYY-MM-DD"/>
                    </div>
                    <div class="col-md-4 col-md-offset-1 inner-top-xs"><b>Total: </b> <span id="totalHours"></span></div>
                  </div>
                  <div class="col-md-12" id="graphSection">

                  </div>
                </div>
              </div>
              <div class="tab-pane" data-tab="crafts">
                <div class="col-md-12">
                  <h2 class="page-header">Craft Experience</h2>
                  <div class="col-md-8 center-block">
                    <h4>Add Craft Experience</h4>
                    <table class='table table-bordered no-shadow'>
                      <thead>
                        <th>Aircraft</th>
                        <th>Position</th>
                        <th>Hours</th>
                      </thead>
                      <tbody>
                        <td>
                          <input type='text' class='form-control' id='addExperienceCraft' placeholder='Aircraft'/>
                        </td>
                        <td>
                        	<select class='form-control' id="addExperiencePos">
                        		<option value="com">Command</option>
                        		<option value="pil">Copilot</option>
                        	</select>
                        </td>
                        <td>
                          <input type='text' class='form-control' id='addExperienceHours' placeholder='Hours'>
                        </td>
                        <td>
                          <button class='btn btn-success' id="addExperience"><div class='fa fa-plus'></div></button>
                        </td>
                      </tbody>
                    </table>
                    <h4>View Craft Experience</h4>
                    <div id="experience-section"></div>
                  </div>
                </div>
              </div>
            </div>  
      </div>
      <div class="container">
        <!-- MODAL FOR LOG -->
        <div class="modal" id="viewLogModal" tabindex="-1" role="dialog" aria-labelledby="viewLog" aria-hidden="true">
          <div class="modal-dialog" style="width: 50%">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
              <h1 class="modal-title text-center">Report Details</h1>
            </div>
            <div class="modal-body">
              <div class="container">
                <h3 class="page-header">Please select a date range</h3>
                <div class="col-md-12 no-padding">
                  <div class="col-md-6">
                    <div class="lbl">Start Date</div>
                    <input type="text" class="form-control" id="logStartDate">
                  </div>
                  <div class="col-md-6">
                    <div class="lbl">End Date</div>
                    <input type="text" class="form-control" id="logEndDate">
                  </div>
                </div>
              </div><!-- ENDS CONTAINER -->
           </div>
           <div class="modal-footer">
            <button class="btn btn-success" id="launchLog">View Report</button>
            <button class="btn" data-dismiss="modal">Cancel</button>
           </div>
          </div>
          </div>
        </div>

        <div class="modal" id="viewLogbookModal" tabindex="-1" role="dialog" aria-labelledby="viewLog" aria-hidden="true">
          <div class="modal-dialog" style="width: 50%">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
              <h1 class="modal-title text-center">Log Details</h1>
            </div>
            <div class="modal-body">
              <div class="container">
                <h3 class="page-header">Please select a date range</h3>
                <div class="col-md-12 no-padding">
                  <div class="col-md-6">
                    <div class="lbl">Start Date</div>
                    <input type="text" class="form-control" id="logbookStartDate">
                  </div>
                  <div class="col-md-6">
                    <div class="lbl">End Date</div>
                    <input type="text" class="form-control" id="logbookEndDate">
                  </div>
                  <div class="col-md-6">
                    <div class="lbl">Output Format</div>
                    <select class="form-control" id="logbookFormat">
                      <option value="xlsx">Excel</option>
                      <option value="pdf">PDF</option>
                    </select>
                  </div>
                </div>
              </div><!-- ENDS CONTAINER -->
           </div>
           <div class="modal-footer">
            <button class="btn btn-success" id="printLog">View Log</button>
            <button class="btn" data-dismiss="modal">Cancel</button>
           </div>
          </div>
          </div>
        </div>

        <div class="modal" id="limitationsModal" tabindex="-1" role="dialog" aria-labelledby="viewLog" aria-hidden="true">
          <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
              <h1 class="modal-title text-center">Crew Time Limitations</h1>
            </div>
            <div class="modal-body">
              <div class="col-md-12 no-padding no-float">
                <div class="lbl">Maximum Flying hours in 1 Day</div>
                <?php echo $mysqli->query("SELECT max_in_day FROM account_info WHERE id=$_SESSION[account]")->fetch_assoc()["max_in_day"]; ?>    
                <div class="lbl">Maximum Flying hours in 7 Days</div>
                <?php echo $mysqli->query("SELECT max_last_7 FROM account_info WHERE id=$_SESSION[account]")->fetch_assoc()["max_last_7"]; ?>
                <div class="lbl">Maximum Flying hours in 28 Days</div>
                <?php echo $mysqli->query("SELECT max_last_28 FROM account_info WHERE id=$_SESSION[account]")->fetch_assoc()["max_last_28"]; ?>
                <div class="lbl">Maximum Flying hours in 365 Days</div>
                <?php echo $mysqli->query("SELECT max_last_365 FROM account_info WHERE id=$_SESSION[account]")->fetch_assoc()["max_last_365"]; ?>
                <div class="lbl">Maximum Flying Days in a Row</div>
                <?php echo $mysqli->query("SELECT max_days_in_row FROM account_info WHERE id=$_SESSION[account]")->fetch_assoc()["max_days_in_row"]; ?>
                <div class="lbl">Maximum Duty hours in 1 Day</div>
                <?php echo $mysqli->query("SELECT max_duty_in_day FROM account_info WHERE id=$_SESSION[account]")->fetch_assoc()["max_duty_in_day"]; ?>   
                <div class="lbl">Maximum Duty hours in 7 Days</div>
                <?php echo $mysqli->query("SELECT max_duty_7 FROM account_info WHERE id=$_SESSION[account]")->fetch_assoc()["max_duty_7"]; ?>   
                <div class="lbl">Maximum Duty hours in 28 Days</div>
                <?php echo $mysqli->query("SELECT max_duty_28 FROM account_info WHERE id=$_SESSION[account]")->fetch_assoc()["max_duty_28"]; ?> 
                <div class="lbl">Maximum Duty hours in 365 Days</div>
                <?php echo $mysqli->query("SELECT max_duty_365 FROM account_info WHERE id=$_SESSION[account]")->fetch_assoc()["max_duty_365"]; ?> 
              </div>
           </div>
           <div class="modal-footer">
            <button class="btn" data-dismiss="modal">Close</button>
           </div>
          </div>
          </div>
        </div>

      </div>
  <?php include_once "footer.php";?>