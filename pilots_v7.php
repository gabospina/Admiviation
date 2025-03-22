<?php
// Include 'IsLogged.php', but exclude it for now to simplify debugging
//include 'IsLogged.php';
session_start();

// if (session_status() == PHP_SESSION_NONE) {
//     session_start();
// }

$lastHeliSelected = "";
if(isset($_COOKIE["lastHeliSelected"])){
    $_SESSION["lastHeliSelected"] = $_COOKIE["lastHeliSelected"];
}
if(isset($_SESSION["lastHeliSelected"])){
    $lastHeliSelected = $_SESSION["lastHeliSelected"];
}
// Use json_encode for safer JavaScript variable injection
echo '<script>
    var lastHeliSelected = ' . json_encode($lastHeliSelected) . ';
    var myPilotID = ' . json_encode($_SESSION['HeliUser'] ?? null) . '; // Handle unset session
</script>';

$page = "pilots";
include_once "header.php";


?>

<!-- Pilots Info and Hangar -->
<div class="outer-bottom outer-top-md inner-left-sm inner-right-sm">
    <div id="notifications"></div>
    <div class="pilots">
        <?php include 'pilot_list.php'; ?>
        <div id="pilot_info_section">
            <h2 class="page-header text-center">Pilot1 Information</h2>
            <div id="pilot_info">
                <!-- Add this line to include the hangar section-->
            </div>
        </div>
        <div id="pilot_hangar">
            <!-- Add this line to include the hangar section-->
        </div> 
    </div>
</div>



<?php include_once "footer.php"; ?>

