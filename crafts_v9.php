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
  $page = "crafts";
  include_once "header.php";
?>

<div class="container inner-md">
    <div class="row">
      <h2 class="text-black text-center">Crafts1</h2>
    </div>
    <!-- ================ BELOW - SEARCH input =================================== -->
    <div class="row" id="craftFilter">
      <div class="pull-right col-sm-4 outer-xs">
        <form class="form-inline">
          <div class="input-group">
            <input type="text" id="search_crafts" class="form-control" placeholder="Craft Registration..">
            <span class="input-group-btn">
              <input type="button" id="craftSearchGo" value="Search" class="btn btn-success">
            </span>
          </div>
        </form>
      </div>
    </div>
    <!-- ================ ABOVE  - SEARCH input =================================== -->

    <!-- ================ BELOW - craft list from craftfunctions.================== -->

    <!-- ================ ABOVE - craft list from craftfunctions.================== -->


    <div id="crafts"></div>
    <div class="row outer-top-xs">
      <h2 class="text-black text-center outer-bottom-xs">Pilots and Craft Types</h2>
      
      
      <div class="craftTypes">
        <div class="list" id="craftList">

          <!-- ================ BELOW - craft input ================== -->
          <input type="text" id="search_craft_type" name="search_craft_type" class="form-control" placeholder="Craft Type..">
          
          <!-- ================ BELOW - craft list from craftfunctions??? ================== -->
          <div id="craft_list"></div>
        </div>

        <!-- ================ BELOW - BOX FOR SOMETHING - FIGURE IT OUT LATER ================== -->
        <div id="craft_info_section">
          <div class="pull-right"><h5><a href="#addPilotsRow" id='#craftScrollBottom' class="pointer outer-right-xs">Go To Bottom</a></h5></div>
          <div id="craft_info"></div>
        </div>
      </div>
    </div>
  </div>

  <?php include_once "footer.php"; ?>