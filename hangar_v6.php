<?php
error_reporting(-1);
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION["HeliUser"])) {
    // header("Location: index.php");
    header("Location: admviationHome.php");
}
$lastHeliSelected = "";
if (isset($_COOKIE["lastHeliSelected"])) {
    $_SESSION["lastHeliSelected"] = $_COOKIE["lastHeliSelected"];
}
if (isset($_SESSION["lastHeliSelected"])) {
    $lastHeliSelected = $_SESSION["lastHeliSelected"];
}

$page = "hangar";
include_once "header.php";

// Check if $user_data is available, otherwise, provide a default value
$user_data = isset($user_data) ? $user_data : [];
$adminLevel = intval($_SESSION["admin"]);
?>
<div class="light-bg" id="personalSection">
    <div class="container inner-sm">
        <?php
        if (in_array($adminLevel, array(0, 1, 2, 3, 8))) {
            echo '<div class="row inner-left-md inner-right-md">

                  <!-- TEST SECTION-->
                  <h2 class="page-header">Check Validities <small>Click on <u>date</u> to update</small> <button class="btn btn-primary pull-right" onclick="window.open(\'print.php?print_type=user_info&user_id=user&data=validity&output=xlsx\', \'_blank\')">Export XLSX</button></h2>
                  <div id="validityHolder"></div>
                </div>
                <!-- ENDS TEST SECTION -->';
        }
        ?>
        <!-- PERSONAL INFO SECTION -->
        <div class="row inner-left-md inner-right-md">
            <div class="col-md-6">
                 <!--MODAL FOR THE PERSONAL INFO LINK-->
                <a href="#" data-toggle="modal" data-target="#personalInfoModal">
                    <h2 class="page-header">PersoNAL Information <small>Click to edit information</small></h2>
                </a>
                <ul id="personalInfo">
                    <li>Name: <div id="name"><?php echo isset($user_data['pilot_fname']) && isset($user_data['pilot_lname']) ? htmlspecialchars($user_data['pilot_fname'] . ' ' . $user_data['pilot_lname'], ENT_QUOTES, 'UTF-8') : ''; ?></div></li>
                    <li>Username: <div id="username"><?php echo isset($user_data['username']) ? htmlspecialchars($user_data['username'], ENT_QUOTES, 'UTF-8') : ''; ?></div></li>
                    <li>Nationality: <div id="nationality"><?php echo isset($user_data['user_nationality']) ? htmlspecialchars($user_data['user_nationality'], ENT_QUOTES, 'UTF-8') : ''; ?></div></li>
                    <?php
                    if (in_array($adminLevel, array(0, 1, 2, 3, 8))) {
                        echo '
              <li>Current Position: <div id="pos"></div></li>
              <li>' . htmlspecialchars($user_data["user_nationality"] ?? "", ENT_QUOTES, 'UTF-8') . ' License: <div id="angLic"></div></li>
              <li>Foreign License: <div id="forLic"></div></li> ';
                    }
                    ?>
                    <li>E-mail: <div id="persEmail"></div></li>
                    <li>Phone: <div id="persPhone"></div></li>
                    <li>Secondary Phone: <div id="persPhoneTwo"></div></li>
                </ul>
                <div class="col-md-7 no-padding">
                    <div class="lbl">Change Profile Picture</div>
                    <div id="profile-picture-container" style="max-width: 230px; max-height: 300px">
                        <img src="uploads/pictures/default_picture.jpg" id="profile-picture" width="100%"/>
                        <div id="profile-picture-overlay" data-toggle="modal" data-target="#change-profile-picture">
                            <div class="fa fa-3x fa-pencil"></div>
                        </div>
                    </div>
                </div>
                <p>
                    <a style="cursor: pointer;" data-toggle="modal" data-target=".changepass">
                        <h2><small>Change Password</small></h2>
                    </a>
                </p>
            </div>
            <?php
            $adminLevel = intval($_SESSION["admin"]);
            if (in_array($adminLevel, array(0, 1, 2, 3, 8))) {
                echo '<div class="col-md-6">
                  <h2 class="page-header">Duty Schedule</h2>
                  <div id="availabilitySection">
                    <table class=\'table table-condensed table-bordered no-shadow\' id="availabilityTable">
                      <thead>
                        <th>On Duty</th>
                        <th>Off Duty</th>
                        <th>Scheduled</th>
                      </thead>
                      <tbody>
                        <tr id=\'addDateRow\'>
                          <td><input type=\'text\' placeholder=\'YYYY-mm-dd\' class=\'on-date form-control\' /></td>
                          <td><input type=\'text\'  placeholder=\'YYYY-mm-dd\' class=\'off-date form-control\' /></td>
                          <td></td>
                          <th class="text-center"><div class="btn btn-sm btn-primary addOnOffDate" data-id=\'' . $_SESSION["HeliUser"] . '\'>+</div></th>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                </div>';
            }
            ?>

        </div>
        <div class="row inner-left-md inner-right-md">
            <h2 class="page-header">Clock Settings</h2>
            <div class="col-md-3">
                <div class="lbl">Clock Name</div>
                <input type="text" id="clock-name" class="form-control"/>
                <div class="lbl outer-top-xxs">Clock Timezone</div>
                <select class="form-control" id="clock-timezone"><option>Timezone 1</option></select>
                <button class="btn btn-primary outer-top-xxs form-control" id="saveClockSettings">Save Clock</button>
            </div>
        </div>
        <!-- ENDS PERSONAL INFO SECTION -->

        <!-- PERSONAL INFORMATION MODAL -->
        <div class="modal" id="personalInfoModal" tabindex="-1" role="dialog" aria-labelledby="personalInfoModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                        <h3 class="modal-title">Edit Personal Information</h3>
                    </div>
                    <div class="modal-body">
                        <form>
                            <div class="form-group">
                                <label for="fname">First Name:</label>
                                <input type="text" class="form-control" id="fname" value="<?php echo htmlspecialchars($user_data['pilot_fname'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                            </div>
                            <div class="form-group">
                                <label for="lname">Last Name:</label>
                                <input type="text" class="form-control" id="lname" value="<?php echo htmlspecialchars($user_data['pilot_lname'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                            </div>
                            <div class="form-group">
                                <label for="usernameInput">Username:</label>
                                <input type="text" class="form-control" id="usernameInput" value="<?php echo htmlspecialchars($user_data['username'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                            </div>
                            <div class="form-group">
                                <label for="user_nationality">Nationality:</label>
                                <input type="text" class="form-control" id="user_nationality" value="<?php echo htmlspecialchars($user_data['user_nationality'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                            </div>
                            <?php if (in_array($adminLevel, array(0, 1, 2, 3, 8))) {
                                echo '
                                <div class="form-group">
                                    <label for="comandante">Current Position:</label>
                                    <select class="form-control" id="comandante">
                                        <option value="0"' . ((isset($user_data['comandante']) && $user_data['comandante'] == 0) ? ' selected' : '') . '>SIC-Piloto</option>
                                        <option value="1"' . ((isset($user_data['comandante']) && $user_data['comandante'] == 1) ? ' selected' : '') . '>PIC-Comandante</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="ang_license">License:</label>
                                    <input type="text" class="form-control" id="ang_license" value="' . htmlspecialchars($user_data['ang_license'] ?? '', ENT_QUOTES, 'UTF-8') . '">
                                </div>
                                <div class="form-group">
                                    <label for="for_license">Foreign License:</label>
                                    <input type="text" class="form-control" id="for_license" value="' . htmlspecialchars($user_data['for_license'] ?? '', ENT_QUOTES, 'UTF-8') . '">
                                </div>';
                            } ?>
                            <div class="form-group">
                                <label for="email">E-mail:</label>
                                <input type="email" class="form-control" id="email" value="<?php echo htmlspecialchars($user_data['email'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                            </div>
                            <div class="form-group">
                                <label for="phone">Phone:</label>
                                <input type="text" class="form-control" id="phone" value="<?php echo htmlspecialchars($user_data['phone'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                            </div>
                            <div class="form-group">
                                <label for="phonetwo">Secondary Phone:</label>
                                <input type="text" class="form-control" id="phonetwo" value="<?php echo htmlspecialchars($user_data['phonetwo'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary savePersonalInfo">Save changes</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- END PERSONAL INFORMATION MODAL -->

        <!-- CHANGE PASSWORD MODAL -->
        <div class="modal changepass" tabindex="-1" role="dialog" aria-labelledby="personinfo" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                        <h3 class="modal-title">Change Your Password</h3>
                    </div>
                    <div class="modal-body">
                        <p id="changePassError"></p>
                        <p id="passwordRules">Your password must contain letters, numbers and symbols, and be a minimum of 8 characters long.</p>
                        <form class="outer-xs">
                            <div class="form-group">
                                <label for="oldpass">Enter old password:</label><br/>
                                <input type="password" id="oldpass" name="oldpass">
                            </div>
                            <div class="form-group">
                                <label for="newpass">Enter NEW password:</label><br/>
                                <input type="password" id="newpass" name="newpass">
                            </div>
                            <div class="form-group">
                                <label for="confpass">Confirm password:</label><br/>
                                <input type="password" id="confpass" name="confpass">
                            </div>
                            <div class="form-group">
                                <input type="button" class="btn btn-success" value="Submit" id="changePassBtn"
                                       onclick="changePass(this.form.oldpass, this.form.newpass, this.form.confpass);">
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- ENDS CHANGE PASSWORD MODAL-->

        <div class="modal" id="change-profile-picture" tabindex="-1" role="dialog" aria-labelledby="personinfo"
             aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                        <h3 class="modal-title">Change Your Profile Picture</h3>
                    </div>
                    <div class="modal-body">
                        <div class="col-md-12 no-float">
                            <form class="dropzone no-float" id="uploadDocuments">
                                <p class="dz-message">Drag and Drop, or click, to upload files.<br/><strong>Maximum 1.5Mb</strong></p>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
<div class="container" id="thoughtsContainer">
    <div class="row">
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
<?php include_once "footer.php"; ?>
<script src="hangarfunctions.js"></script>