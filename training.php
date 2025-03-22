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
  $page = "training";
  include_once "header.php";
?>
  <div id="eventInfoPopover"></div>
    <div class="light-bg" id="calendarSection">
      <div class="inner-md">
        <div class="col-md-12 no-float">
          <div class="tabs">
            <div class="tab" data-tab-toggle="trainer">Trainers' Duty Schedule</div>
            <div class="tab active" data-tab-toggle="trainee">Trainees and Trainers</div>
          </div>
          <div class="tab-content"> 
          <?php
            $adminLevel = intval($_SESSION["admin"]);
            if($adminLevel > 0 && $adminLevel != 1 && $adminLevel != 4){
              echo '<button class="btn btn-primary pull-right outer-right-xs outer-top-xxs" data-toggle="modal" data-target="#printModal">Print</button>';
            }
          ?>
            <div class="tab-pane inner-right-sm inner-left-sm" data-tab="trainer">
              <h1 class="page-header text-center">Manage Trainer Schedule</h1>
              <div class="row">
                <div class="col-md-12 no-float outer-bottom-xs">
                  <div id="trainer-calendar"></div>
                </div>
              </div>
            </div>
            <div class="tab-pane active inner-right-sm inner-left-sm" data-tab="trainee">
              <h1 class="page-header text-center">Manage Trainee Schedule</h1>
              <div class="row">
      <!--           <div class="col-md-7 center-block">
                  <div id="trainingDateSection">
                    <table class="table" id="trainingDateTable">
                      <thead>
                        <th>Availability Start</th>
                        <th>Availability End</th>
                      </thead>
                      <tbody>
                        <tr id="insertAvailabilityRow">
                          <td>
                            <input type="text" class='dp form-control' id="date-start">
                          </td>
                          <td>
                            <input type="text" class='dp form-control' id="date-end">
                          </td>
                          <th>
                            <button class="btn btn-primary" id="add-avail-date"><div class="fa fa-plus"></div></button>
                          </th>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                </div> -->
                <div class="col-md-12 no-float outer-bottom-xs">
                  <div class="col-md-7 display-inline outer-bottom-xxs">
                    <div class="lbl display-inline outer-right-xxs">View As:</div>
                    <button class="btn btn-default view-as-btn display-inline active" data-value="trainee">Trainee</button>
                    <button class="btn btn-default view-as-btn display-inline" data-value="trainer">Trainer</button>
                  </div>
                  <div class="col-md-4 display-inline">
                    <div class="lbl display-inline outer-right-xxs">Search</div>
                    <input type= "text" class="form-control display-inline" id="search_trainees"/>
                  </div>
                  <div id="calendar"></div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <?php
      $adminLevel = intval($_SESSION["admin"]);
      if($adminLevel > 0 && $adminLevel != 1 && $adminLevel != 4){
        echo '<div class="modal" id="editEvent" tabindex="-1" role="dialog" aria-labelledby="editEventCal" aria-hidden="true">
                <div class="modal-dialog" style="width: 50%;">
                <div class="modal-content">
                  <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h1 class="modal-title">Edit Selection</h1>
                  </div>
                  <div class="modal-body">
                    <div class="container">
                      <h4 class="page-header">Current Selection: <span id="currentEventSelection"></span></h4>
                      <div class="col-md-12 outer-bottom-xxs">
                        <h3 class="page-header">Change Selection</h3>
                        <div class="col-md-12 no-padding center-block outer-bottom-xxs">
                          <div class="col-md-12 no-padding no-margin">
                            <div class="col-md-4 outer-bottom-xxs display-inline">
                              <div class="lbl">Start Date</div>
                              <input type="text" class="form-control" id="editStartDate" />
                            </div>
                            <div class="col-md-4 outer-bottom-xxs display-inline">
                              <div class="lbl">End Date</div>
                              <input type="text" class="form-control" id="editEndDate" />
                            </div>
                          </div>
                          <div class="col-md-4">
                            <div class="lbl">TRI (Select Up to Two)</div>
                            <div id="triList" class="list"></div>
                          </div>
                          <div class="col-md-4">
                            <div class="lbl">Trainees (Select Up to Four)</div>
                            <div id="traineeList" class="list"></div>
                          </div>
                          <div class="col-md-4">
                            <div class="lbl">TRE</div>
                            <div id="treList" class="list"></div>
                          </div>
                        </div>
                        <div class="text-right col-md-12 outer-top-xxs" >
                          <button class="btn btn-success btn-lg" id="confirmChange">Change</button>
                        </div>
                      </div>
                      <div class="col-md-12" style="border-top: 1px solid #ccc;">
                        <h3 class="text-center">Would you like to remove <span id="removePilotName"></span> on <span id="removePilotDates"></span></h3>
                      </div>
                    </div><!-- ENDS CONTAINER -->
                  </div>
                  <div class="modal-footer inner-right-md">
                    <button class="btn btn-lg btn-danger" id="confirmRemoval">Remove</button>
                    <button class="btn btn-lg btn-default" data-dismiss="modal" aria-hidden="true">Cancel</button>
                  </div>
                </div>
                </div>
              </div>

              <div class="modal" id="eventModal" tabindex="-1" role="dialog" aria-labelledby="editEventCal" aria-hidden="true">
                <div class="modal-dialog modal-lg" style="width: 50%;">
                  <div class="modal-content">
                    <div class="modal-header">
                      <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                      <h1 class="modal-title text-center">Flight Simulator Schedule</h1>
                    </div>
                    <div class="modal-body">
                      <div class="container">
                        <div class="col-md-12 outer-bottom-sm eventAvailSection">
                          <h3 class="text-center">
                            <span id="availabilityHeader"></span><br><br>
                            <span class="text-center startEndDates"></span>
                          </h3>
                          <div class="col-md-12 text-center">
                            <button class="btn btn-lg btn-success" id="confirmEnableAvailability">Enable</button>
                            <button class="btn btn-lg btn-danger" id="confirmDisableAvailability">Disable</button>  
                          </div>
                        </div>
                        <div class="col-md-12 eventDetailsSection">
                          <h3 class="page-header">Select Craft and Pilots</h3>
                          <div class="row outer-top-xxs">
                            <div class="col-md-4 outer-bottom-xxs display-inline">
                              <div class="lbl">Start Date</div>
                              <input type="text" class="form-control" id="addStartDate" />
                            </div>
                            <div class="col-md-4 outer-bottom-xxs display-inline">
                              <div class="lbl">End Date</div>
                              <input type="text" class="form-control" id="addEndDate" />
                            </div>
                            <div class="col-md-12 outer-bottom-xxs" style="padding-left: 0px;">
                              <div class="col-md-6">
                                <div class="lbl">Aircraft</div>
                                <select class="form-control" id="addEventCraft"></select>
                              </div>
                            </div>
                            <div class="col-md-12 outer-bottom-xxs" style="padding-left: 0px;">
                              <div class="col-md-4">
                                <div class="lbl">TRI (0-2)</div>
                                <div class="list" id="triListSection"></div>
                              </div>
                              <div class="col-md-4">
                                <div class="lbl">Trainees (1-4)</div>
                                <div class="list pilotListSection" id="TRIpilotListSection"></div>
                              </div>
                              <div class="col-md-4">
                                <div class="lbl">TRE (0-1)</div>
                                <div class="list" id="treListSection"></div>
                              </div>
                              <!-- <div class="col-md-12 outer-top-xs text-right">
                                <button class="btn btn-lg btn-danger" id="confirmAddTRI">Add TRI Selection</button>
                                <button class="btn btn-lg btn-default" data-dismiss="modal" aria-hidden="true">Cancel</button>
                              </div> -->
                            </div>
                            <!-- <div class="col-md-12 outer-bottom-xxs" style="padding-left: 0px; border-top: 1px solid #ccc;">
                              <div class="col-md-6">
                                <div class="lbl">TRE</div>
                                <div class="list" id="treListSection"></div>
                              </div>
                              <div class="col-md-6">
                                <div class="lbl">Trainee</div>
                                <div class="list pilotListSection" id="TREpilotListSection"></div>
                              </div>
                            </div> -->
                          </div>
                        </div>
                      </div><!-- ENDS CONTAINER -->
                    </div>
                    <div class="modal-footer inner-right-md">
                      <button class="btn btn-lg btn-danger" id="confirmAdd">Add Selection</button>
                      <button class="btn btn-lg btn-default" data-dismiss="modal" aria-hidden="true">Cancel</button>
                    </div>
                  </div>
                </div>
              </div>
              <!-- TRAINER MODALS ============================================ -->
              <div class="modal" id="trainerEventModal" tabindex="-1" role="dialog" aria-labelledby="editEventCal" aria-hidden="true">
                <div class="modal-dialog modal-lg" style="width: 50%;">
                  <div class="modal-content">
                    <div class="modal-header">
                      <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                      <h1 class="modal-title text-center">Flight Simulator Schedule</h1>
                    </div>
                    <div class="modal-body">
                      <div class="container">
                        <div class="col-md-12 outer-bottom-sm eventAvailSection">
                          <h3 class="text-center">
                            <span id="availabilityHeader"></span><br><br>
                            <span class="text-center startEndDates"></span>
                          </h3>
                          <div class="col-md-12 text-center">
                            <button class="btn btn-lg btn-success" id="confirmEnableAvailabilityTrainer">Enable</button>
                            <button class="btn btn-lg btn-danger" id="confirmDisableAvailabilityTrainer">Disable</button>  
                          </div>
                        </div>
                        <div class="col-md-12 eventDetailsSection">
                          <h3 class="page-header">Select Trainer And Position</h3>
                          <div class="row outer-top-xxs">
                            <div class="col-md-12 outer-bottom-xxs" style="padding-left: 0px;">
                              <div class="col-md-6">
                                <div class="lbl">Trainers</div>
                                <div class="list" id="addTrainerList"></div>
                              </div>
                              <div class="col-md-4">
                                <div class="lbl">Position</div>
                                <select id="addTrainerPosition" class="form-control">
                                  <option value="tri">TRI</option>
                                  <option value="tre">TRE</option>
                                  <option value="tri/tre">TRI/TRE</option>
                                </select>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div><!-- ENDS CONTAINER -->
                    </div>
                    <div class="modal-footer inner-right-md">
                      <button class="btn btn-lg btn-danger" id="confirmAddTrainer">Add Trainer</button>
                      <button class="btn btn-lg btn-default" data-dismiss="modal" aria-hidden="true">Cancel</button>
                    </div>
                  </div>
                </div>
              </div>


              <div class="modal" id="editTrainerEvent" tabindex="-1" role="dialog" aria-labelledby="editEventCal" aria-hidden="true">
                <div class="modal-dialog" style="width: 50%;">
                <div class="modal-content">
                  <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h1 class="modal-title">Edit Trainer Selection</h1>
                  </div>
                  <div class="modal-body">
                    <div class="container">
                      <h4 class="page-header">Current Selection: <span id="currentEventSelectionTrainer"></span></h4>
                      <div class="col-md-12 outer-bottom-xxs">
                        <h3 class="page-header">Change Selection</h3>
                        <div class="col-md-12 no-padding center-block outer-bottom-xxs">
                          <div class="col-md-6">
                            <div class="lbl">Trainer</div>
                            <div id="changeTrainerList" class="list"></div>
                          </div>
                          <div class="col-md-4">
                            <div class="lbl">Position</div>
                            <select id="changeTrainerPosition" class="form-control">
                              <option value="tri">TRI</option>
                              <option value="tre">TRE</option>
                              <option value="tri/tre">TRI/TRE</option>
                            </select>
                            <div class="lbl">Duty Start</div>
                            <input type="text" class="form-control" id="changeTrainerStart" />
                            <div class="lbl">Duty End</div>
                            <input type="text" class="form-control" id="changeTrainerEnd" />
                            <div class="text-right col-md-12 no-padding outer-top-sm">
                              <button class="btn btn-success btn-lg col-md-12" id="confirmTrainerChange">Change</button>
                            </div>
                          </div>
                        </div>
                        <div class="text-right col-md-12 outer-top-xxs" >
                          &nbsp;
                        </div>
                      </div>
                      <div class="col-md-12" style="border-top: 1px solid #ccc;">
                        <h3 class="text-center">Would you like to remove <span id="removeTrainerName"></span> on <span id="removeTrainerDates"></span></h3>
                      </div>
                    </div><!-- ENDS CONTAINER -->
                  </div>
                  <div class="modal-footer inner-right-md">
                    <button class="btn btn-lg btn-danger" id="confirmTrainerRemoval">Remove</button>
                    <button class="btn btn-lg btn-default" data-dismiss="modal" aria-hidden="true">Cancel</button>
                  </div>
                </div>
                </div>
              </div>

              <!-- PRINT MODAL =============================================================== -->
              <div class="modal" id="printModal" tabindex="-1" role="dialog" aria-labelledby="printmod" aria-hidden="true">
                <div class="modal-dialog">
                  <div class="modal-content">
                    <div class="modal-header">
                      <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                      <h1 class="modal-title text-center">Print Schedules</h1>
                    </div>
                    <div class="modal-body">
                      <div class="col-md-12 no-float outer-bottom-xxs">
                        <div class="lbl">Type</div>
                        <select class="form-control" id="printType">
                          <option selected disabled>Select Type</option>
                          <option value="trainerDuty">Trainer Duty Schedule</option>
                          <option value="training">Training Schedule</option>
                        </select>
                        <div class="printDates">
                          <div class="lbl">Start</div>
                          <input type="text" class="form-control" id="printStart"/>
                          <div class="lbl">End</div>
                          <input type="text" class="form-control" id="printEnd"/>
                        </div>
                        <div class="lbl">Output Format</div>
                        <div class="col-md-4">
                          <select class="form-control" id="outputFormat">
                            <option value="xlsx">Excel</option>
                            <option value="pdf">PDF</option>
                          </select>
                        </div>
                      </div>
                    </div>
                    <div class="modal-footer inner-right-md">
                      <button class="btn btn-lg btn-primary" id="printBtn">Print</button>
                      <button class="btn btn-lg btn-default" data-dismiss="modal" aria-hidden="true">Cancel</button>
                    </div>
                  </div>
                </div>
              </div>';
      }
    ?>
              
    
    <div class="modal" id="yearLoadingModal" tabindex="-1" role="dialog" aria-labelledby="editEventCal" aria-hidden="true">
      <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-body">
          <div class="container">
            <h2 class="text-center">Loading</h2>
          </div><!-- ENDS CONTAINER -->
       </div>
      </div>
      </div>
    </div>

              
  <?php include_once "footer.php";?>