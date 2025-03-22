<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

// if (session_status() == PHP_SESSION_NONE) {
//     session_start();
// }
// if(!isset($_SESSION["HeliUser"])){
//   header("Location: index.php");
// }
// if(!isset($_SESSION["admin"]) || intval($_SESSION["admin"]) == 0){
//     header("Location: index.php");
// }
$page = "contracts";
include_once "header.php";
?>
<div class="container inner-md">
    <div class="row">
        <h2 class="text-black text-center">Contracts</h2>
    </div>

    <!-- New Customer and New Contract Buttons -->
    <div class="row">
        <div class="col-sm-6 outer-xs">
            <button class="btn btn-primary form-control" data-toggle="modal" data-target=".addNewCustomerModal">New Customer</button>
        </div>
        <div class="col-sm-6 outer-xs">
            <button class="btn btn-primary form-control" data-toggle="modal" data-target=".addNewContractModal">New Contract</button>
        </div>
    </div>

    <!-- Search and Filter (Moved Below Buttons) -->
    <div class="row outer-top-md" id="contractFilter">
        <div class="col-sm-offset-5 col-sm-4 outer-xs">
            <form class="form-inline">
                <div class="input-group">
                    <input type="text" id="search_contracts" class="form-control" placeholder="Contract Name..">
                    <span class="input-group-btn">
                        <input type="button" id="contractSearchGo" value="Search" class="btn btn-success">
                    </span>
                </div>
            </form>
            <div class="outer-top-xs">
                <button class="btn btn-primary" id="expandAccordion">Expand All</button>
                <button class="btn btn-primary" id="printContracts">Print</button>
            </div>
        </div>
    </div>

    <!-- Customer and Contract Display -->
    <div class="row outer-top-md">
        <div class="col-md-6">
            <h3>Existing Customers</h3>
            <div id="customerList">
                <!-- Customers will be loaded here -->
            </div>
        </div>
        <div class="col-md-6">
        <h3>Existing Contracts</h3>
        <div id="contracts">
            <!-- Contracts will be loaded here -->
        </div>
    </div>
    </div>

    <!-- ================ BELOW - Add New Customer modal ================================  -->
     <div class="modal addNewCustomerModal" tabindex="-1" role="dialog" aria-labelledby="newCustomer" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h3 class="modal-title">Add a New Customer</h3>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-10 col-md-offset-1">
                            <div class="form-group">
                                <label for="newCustomerName">Customer Name:</label>
                                <input type="text" class="form-control" id="newCustomerName" placeholder="Enter customer name" required/>
                            </div>
                            <button type="submit" class="btn btn-success" id="submitNewCustomerBtn">Add Customer</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ================ BELOW - contract modal ================================  -->

    <div class="modal addNewContractModal" tabindex="-1" role="dialog" aria-labelledby="newContract" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            <h3 class="modal-title">Add a new Contract</h3>
          </div>
          <div class="modal-body">
            <div class="row">
              <div class="col-md-5">
                <div class="lbl">
                  Contract Name
                </div>
                <input class="form-control" type="text" id="newContractName" placeholder="Enter name of contract"/>
              </div>
               <!-- Customer Select Box -->
                
              <div class="col-md-5">
                  <div class="lbl">
                      Select Customer
                  </div>
                  <select class="form-control" id="newContractCustomerSelect">
                      <!-- Options will be loaded here by Javascript -->
                  </select>
              </div>

              <div class="col-md-5">
                <div class="lbl">
                  Select Crafts <small>(Minimum one)</small>
                </div>
                <select size='6' multiple class="form-control" id="newContractCraftSelect">
                </select>
              </div>

              <div class="col-md-5">
                <div class="lbl">
                  Select Pilots
                </div>
                <select size='6' multiple class="form-control" id="newContractPilotSelect">
                </select>
              </div>

              <div class="col-md-4">
                <div class="lbl outer-top-xs">
                  Contract Color
                </div>
                <input type="color" id="newContractColor">
              </div>
              
              <div class="col-md-4 no-float center-block">
                <button class="btn btn-success form-control outer-top-sm" id="submitNewContractBtn">Submit Contract</button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>  
  </div>
  <!-- ================ ABOVE  - contract modal ================================  -->
   
  <div class="modal" id="loadingModal" tabindex="-1" role="dialog" aria-labelledby="loadingModal" aria-hidden="true">
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

  <?php include_once "footer.php"; ?>
  <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/spectrum/1.8.1/spectrum.min.css" integrity="sha512-LbtS+5D/aH9K99eNPaCJVqP1K+QK8Pz2V+K63tEgzDyzz74V47LJX2l4cIEefUe1EivZtKkn0I3wTr9MVKWew==" crossorigin="anonymous" referrerpolicy="no-referrer" /> -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/spectrum/1.8.1/spectrum.min.js" integrity="sha512-1aNp9qKP+hKU/VJwCtYqJP9tdZWbMDN5pEEXXoXT0pTAxZq1HHZhNBR/dtTNSrHO4U1FsFGGILbqG1O9nl8Mdg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
				