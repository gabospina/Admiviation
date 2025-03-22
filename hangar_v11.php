<?php
error_reporting(E_ALL); //Use E_ALL for reporting all errors
ini_set('display_errors', 1);

// 

session_start();
echo "<pre>";
var_dump($_SESSION);
echo "</pre>";

$lastHeliSelected = "";
if (isset($_COOKIE["lastHeliSelected"])) {
    $_SESSION["lastHeliSelected"] = $_COOKIE["lastHeliSelected"];
}

if (isset($_SESSION["lastHeliSelected"])) {
    $lastHeliSelected = $_SESSION["lastHeliSelected"];
}

include_once "db_connect.php";

$page = "hangar";
include_once "header.php";

// Check if $user_data is available, otherwise, provide a default value
$user_data = isset($user_data) ? $user_data : [];
// $adminLevel = intval($_SESSION["admin"]);


// $pilot_id = $_SESSION["HeliUser"]["id"]; //Or whatever session variable holds the pilot_id, this is important

// Check if $user_data is available, otherwise, provide a default value
// $user_data = isset($user_data) ? $user_data : [];
// $adminLevel = intval($_SESSION["admin"]);

// Output the user ID to a JavaScript variable
echo "<script>";
echo "var phpHeliUser = '" . (isset($_SESSION["HeliUser"]) ? htmlspecialchars($_SESSION["HeliUser"], ENT_QUOTES, 'UTF-8') : '') . "';";
echo "</script>";

?>

<div class="light-bg" id="personalSection">
    <div class="container inner-sm">

        <?php
        // if (in_array($adminLevel, array(0, 1, 2, 3, 8))) {
            echo '<div class="row inner-left-md inner-right-md">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">Check Validities <small>Click on <u>date</u> to update</small>
                         <button class="btn btn-primary pull-right" onclick="window.open(\'print.php?print_type=user_info&user_id=user&data=validity&output=xlsx\', \'_blank\')">Export XLSX</button>
                        </h3>
                    </div>
                    <div class="panel-body" id="validityHolder">
                        <!-- Content from #validityHolder will be loaded here -->
                    </div>
                </div>
            </div>';
        // }
        ?>

        <!-- BELOW - Personal Information and Duty Schedule - SECTION - Feb-19-24-->

        <div class="row inner-left-md inner-right-md">
            <div class="col-md-6">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <a href="#" data-toggle="modal" data-target="#personalInfoModal">
                            <h3 class="panel-title">Personal Information  <small>(Click to edit)</small></h3>
                        </a>
                    </div>
                    <div class="panel-body">
                        <ul class="list-unstyled">
                            <li><strong>Name:</strong>  <span id="displayName"></span></li>
                            <li><strong>Username:</strong> <span id="displayUsername"></span></li>
                            <li><strong>Nationality:</strong> <span id="nationality"></span></li>
                            <li><strong>License:</strong> <span id="nalLic"></span></li>
                            <li><strong>Foreign License:</strong> <span id="forLic"></span></li>
                            <li><strong>Current Position:</strong></li>
                            <?php
                            // if (in_array($adminLevel, array(0, 1, 2, 3, 8))) {
                                echo '
                                    <div id="craftTypeSection">
                                        <!-- Craft Type Dropdown -->
                                        <select id="craftTypeInput">
                                            <option value="">Select Craft Type</option>'; // Added a default option
                                            // Fetch craft types from the database and populate the options
                                            $sql = "SELECT DISTINCT craft_type FROM crafts ORDER BY craft_type";  // SELECT DISTINCT and ORDER BY
                                            $result = $mysqli->query($sql);

                                            if ($result->num_rows > 0) {
                                                while ($row = $result->fetch_assoc()) {
                                                    echo '<option value="' . htmlspecialchars($row["craft_type"]) . '">' . htmlspecialchars($row["craft_type"]) . '</option>';
                                                }
                                            } else {
                                                echo '<option value="">No craft types found</option>';
                                            }
                                echo    '</select>
                                        <label><input type="checkbox" id="picCheckbox" name="position" value="PIC"> PIC</label>
                                        <label><input type="checkbox" id="sicCheckbox" name="position" value="SIC"> SIC</label>
                                        <button id="addCraftTypeBtn">+</button>

                                        <!-- List of craft types -->
                                        <ul id="craftTypeList"></ul>
                                    </div>';
                            // }
                            ?>
                            </select>
                            <li><strong>E-mail:</strong> <span id="persEmail"></span></li>
                            <li><strong>Phone:</strong> <span id="persPhone"></span></li>
                            <li><strong>Secondary Phone:</strong> <span id="persPhoneTwo"></span></li>
                        </ul>
                    </div>
                </div>
            </div>
            
            <!-- ================== BELOW - ON DUTY OFF DUTY user_availibility ======================= -->
            <?php
            // $adminLevel = intval($_SESSION["admin"]);
            if (in_array($adminLevel, array(0, 1, 2, 3, 8))) {
                echo '<div class="col-md-6">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h3 class="panel-title">Duty Schedule</h3>
                            </div>
                            <div class="panel-body">
                                <div id="availabilitySection">
                                    <table class="table table-condensed table-bordered no-shadow" id="availabilityTable">
                                        <thead>
                                            <tr>
                                                <th>On Duty</th>
                                                <th>Off Duty</th>
                                                <th>Scheduled</th>
                                                <th></th> <!-- Added for the button -->
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr id="addDateRow">
                                                <td>
                                                    <input type="text" placeholder="YYYY-mm-dd" class="on-date form-control datepicker" />
                                                </td>
                                                <td>
                                                    <input type="text" placeholder="YYYY-mm-dd" class="off-date form-control datepicker" />
                                                </td>
                                                <td></td>
                                                <td class="text-center">
                                                    <div class="btn btn-sm btn-primary addOnOffDate" data-id="' . htmlspecialchars($_SESSION["HeliUser"], ENT_QUOTES, 'UTF-8') . '">+</div>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>';
            }
            ?>
        </div>
        <!-- ABOVE - Personal Information and ON DUTY OFF DUTY user_availibility SECTION -->

        <!-- =========== BELOW - VALIFITY DATES for EXPIRE CHECKS & LICENCES - SECTION ================== -->

        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">Certification Validity</h3>
            </div>
            <div class="panel-body">
                <div id="notification-container"></div> <!-- Notification container -->
                <table class="table table-condensed table-bordered no-shadow" id="validityTable">
                    <thead>
                        <tr>
                            <th>Certification</th>
                            <th>Expiry Date</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                        $validityFields = [
                            'for_lic' => 'Foreign License',
                            'passport' => 'Passport',
                            'nal_visa' => 'National Visa',
                            'us_visa' => 'USA Visa',
                            'instruments' => 'Instrument Rating',
                            'booklet' => 'Flight Log Book',
                            'train_rec' => 'Training Records',
                            'flight_train' => 'Flight Training',
                            'base_check' => 'Base Check',
                            'night_cur' => 'Night Currency',
                            'night_check' => 'Night Check',
                            'ifr_cur' => 'IFR Currency',
                            'ifr_check' => 'IFR Check',
                            'line_check' => 'Line Check',
                            'hoist_check' => 'Hoist Check',
                            'hoist_cur' => 'Hoist Currency',
                            'crm' => 'CRM Certification',
                            'hook' => 'Hook Operation',
                            'herds' => 'HERDS Training',
                            'dang_good' => 'Dangerous Goods',
                            'huet' => 'HUET Certification',
                            'english' => 'English Proficiency',
                            'faids' => 'First Aid',
                            'fire' => 'Fire Fighting',
                            'avsec' => 'AVSEC Certification'
                        ];

                        foreach ($validityFields as $field => $label): 
                        $expiryDate = $user_data[$field] ?? '';
                        $statusClass = ($expiryDate && $expiryDate >= date('Y-m-d')) ? 'text-success' : 'text-danger';
                        $statusText = ($expiryDate && $expiryDate >= date('Y-m-d')) ? 'Valid' : 'Expired';
                    ?>
                        <tr data-field="<?= $field ?>">
                            <td><strong><?= $label ?></strong></td>
                            <td>
                                <div class="input-group" style="width: 170px;">
                                    
                                    <input type="text" 
                                        class="form-control datepicker validity-date" 
                                        value="<?= $expiryDate ?>"
                                        placeholder="YYYY-MM-DD">
                                        <span class="input-group-btn">
                                        <button class="btn btn-primary save-validity"  style="margin-left: 5px !important;" type="button">
                                            <i class="fa fa-save"></i> Save
                                        </button>
                                    </span>
                                </div>
                            </td>
                            <td class="<?= $statusClass ?> status-cell"><?= $statusText ?></td>
                            <td>
                                <button class="btn btn-xs btn-danger remove-validity">
                                    <i class="fa fa-minus"></i> Remove
                                </button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        <!-- Template for new rows (hidden) -->
                        <tr id="validityTemplate" style="display: none;">
                            <td>
                                <input type="text" class="form-control validity-name" placeholder="Certification Name">
                            </td>
                            <td>
                                <input type="text" class="form-control datepicker validity-date" placeholder="YYYY-MM-DD">
                            </td>
                            <td class="text-muted status-cell">New</td>
                            <td>
                                <button class="btn btn-xs btn-primary save-validity">
                                    <i class="fa fa-save"></i> Save
                                </button>
                                <button class="btn btn-xs btn-danger remove-validity">
                                    <i class="fa fa-minus"></i>
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <button class="btn btn-xs btn-success pull-right" id="addValidityRow">
                        <i class="fa fa-plus"></i> Add New
                    </button>
            </div>
        </div>

        <!-- BELOW - Validity dates for trainng schedule - SECTION -->
        
        <!-- BELOW - Profile Picture, Change Password, and Clock Settings - SECTION -->

        <!-- BELOW - Profile picture - COMPLETED Feb 20-24 ===================== -->

        <div class="row inner-left-md inner-right-md">
           <div class="col-md-3"> <!-- Reduced width -->
                <div class="panel panel-default">
                    <div class="panel-heading">
                    <h3 class="panel-title">Profile Picture - <span style="color: red;">Pending update</span></h3>
                    </div>
                    <div class="panel-body text-center equal-height"> <!-- Added equal-height class -->
                        <div class="lbl"></div>
                        <div id="profile-picture-container" style="max-width: 230px; max-height: 300px; margin: 0 auto;">
                            <!-- <img src="uploads/pictures/default_picture.jpg" id="profile-picture" width="100%" alt="Click to Change Picture"/> -->
                            <div id="profile-picture-overlay" data-toggle="modal" data-target="#change-profile-picture">
                                <div class="fa fa-3x fa-pencil"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ABOVE - Profile picture - COMPLETED Feb 20-24 ===================== -->

            <!-- BELOW - Change Password - COMPLETED - Feb 20-24 ===================== -->

            <div class="col-md-2"> <!-- Reduced width -->
                <div class="panel panel-default">
                    <div class="panel-heading"> <!-- Added panel-heading -->
                        <h6 class="panel-title">Change Password</h6>
                    </div>
                    <div class="panel-body text-center equal-height"> <!-- Added equal-height class -->
                        <a style="cursor: pointer;" data-toggle="modal" data-target=".changepass">
                            <h5>Click to Change Password</h5>
                        </a>
                    </div>
                </div>
            </div>

            <!-- ABOVE - Change Password - COMPLETED Feb-20-24 ======================== -->

            <!-- BELOW Clock Settings in progress - Feb 20-24 ===================== -->

            <div class="col-md-7"> <!-- Increased width -->
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">Clock Settings - <span style="color: red;">Pending update</span></h3>
                    </div>
                    <div class="panel-body equal-height" style="padding: 15px;"> <!-- Removed text-center, Added padding for better input spacing -->
                        <div class="form-group">
                            <label for="clock-name">Clock Name</label>
                            <input type="text" id="clock-name" class="form-control clock-input"/>
                        </div>
                        <div class="form-group">
                            <label for="clock-timezone">Clock Timezone</label>
                            <input type="text" id="clock-timezone" class="form-control clock-input"/>
                        </div>
                        <button class="btn btn-primary clock-button" id="saveClockSettings">Save Clock</button>
                    </div>
                </div>
            </div>

            <!-- ABOVE - Clock Settings in progress - Feb-??-24 ===================== -->

        </div>

        <!-- ENDS Profile Picture, Change Password, and Clock Settings SECTION -->

        <!-- BELOW - Thoughts Container Section -->
        <div class="row inner-left-md inner-right-md">
            <div class="col-md-6"> <!-- Takes up half the space -->
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">Let Us Know Your Thoughts</h3>
                    </div>
                    <div class="panel-body reduce-body"> <!-- Added reduce-body class -->
                        <div class="form-group">
                            <label for="thoughtsEmail">Email (Optional)</label>
                            <input type="email" class="form-control" id='thoughtsEmail' placeholder="Email (Optional)">
                        </div>
                        <div class="form-group">
                            <label for="thoughtsName">Name (Optional)</label>
                            <input type="text" class="form-control" id='thoughtsName' placeholder="Name (Optional)">
                        </div>
                        <div class="form-group">
                            <label for="thoughtsMessage">Message</label>
                            <textarea id="thoughtsMessage" class="form-control" rows="5"></textarea>
                        </div>
                        <button class="btn btn-default form-control" id='submitThoughts'>Send</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- ABOVE - Thoughts Container Section -->

        <!-- BELOW _ Modal for Change Profile Picture -->
        <div class="modal" id="change-profile-picture" tabindex="-1" role="dialog" aria-labelledby="personinfo" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                        <h4 class="modal-title">Change Your Profile Picture</h4>
                    </div>
                    <div class="modal-body">
                        <div class="col-md-12 no-float">
                            <!-- <form class="dropzone no-float" id="uploadDocuments"> -->
                                <p class="dz-message">Drag and Drop, or click, to upload files.<br/><strong>Maximum 1.5Mb</strong></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- ABOVE - Modal for Change Profile Picture -->
    </div>
</div>

<script>
$( function() {
    $( ".datepicker" ).datepicker({
        dateFormat: "yy-mm-dd"
    });
});
</script>

<!-- Validity Edit Save Delete dates SECTION -->

<script>
$(function() {
    // Initialize datepicker
    function initDatepickers() {
        $('.datepicker').datepicker({
            dateFormat: 'yy-mm-dd',
            onSelect: function(dateText, inst) {
                const row = $(this).closest('tr');
                const statusCell = row.find('.status-cell');
                const currentDate = new Date();
                const selectedDate = new Date(dateText);
                
                statusCell.removeClass('text-success text-danger text-muted')
                           .text(selectedDate > currentDate ? 'Valid' : 'Expired')
                           .addClass(selectedDate > currentDate ? 'text-success' : 'text-danger');
            }
        });
    }
    initDatepickers();

    // Save single validity
    $('#validityTable').on('click', '.save-validity', function() {
        const row = $(this).closest('tr');
        const isNew = row.hasClass('new-validity');
        const data = {
            field: row.data('field') || row.find('.validity-name').val(),
            value: row.find('.validity-date').val()
        };

        $.ajax({
            url: 'update_validity.php',
            method: 'POST',
            data: data,
            success: function(response) {
                const res = JSON.parse(response);
                if(res.success) {
                    showNotification('success', 'Validity updated successfully');
                    if(isNew) {
                        row.removeClass('new-validity')
                           .find('.validity-name').replaceWith(`<strong>${data.field}</strong>`);
                    }
                } else {
                    showNotification('error', res.error || 'Error saving validity');
                }
            }
        });
    });

    // Add new validity row
    $('#addValidityRow').click(function() {
        const newRow = $('#validityTemplate').clone()
            .removeAttr('id')
            .addClass('new-validity')
            .show();
        $('#validityTable tbody').append(newRow);
        initDatepickers();
    });

    // Remove validity row
    $('#validityTable').on('click', '.remove-validity', function() {
        const row = $(this).closest('tr');
        if(row.hasClass('new-validity')) {
            row.remove();
        } else {
            if(confirm('Are you sure you want to delete this validity?')) {
                $.ajax({
                    url: 'delete_validity.php',
                    method: 'POST',
                    data: { field: row.data('field') },
                    success: function(response) {
                        row.remove();
                        showNotification('success', 'Validity removed successfully');
                    }
                });
            }
        }
    });
});
</script>

<!-- <script>
$(function () {
    // Initialize datepicker
    function initDatepickers() {
        $('.datepicker').datepicker({
            dateFormat: 'yy-mm-dd',
            onSelect: function (dateText, inst) {
                const row = $(this).closest('tr');
                const statusCell = row.find('.status-cell');
                const currentDate = new Date();
                const selectedDate = new Date(dateText);

                statusCell.removeClass('text-success text-danger text-muted')
                    .text(selectedDate > currentDate ? 'Valid' : 'Expired')
                    .addClass(selectedDate > currentDate ? 'text-success' : 'text-danger');
            }
        });
    }
    initDatepickers();

    // Save single validity
    $('#validityTable').on('click', '.save-validity', function () {
        const row = $(this).closest('tr');
        const isNew = row.hasClass('new-validity');
        const data = {
            field: row.data('field') || row.find('.validity-name').val(),
            value: row.find('.validity-date').val()
        };

        $.ajax({
            url: 'update_validity.php',
            method: 'POST',
            data: data,
            success: function (response) {
                try {
                    const res = JSON.parse(response);
                    if (res.success) {
                        showNotification('success', 'The date was saved successfully');
                        if (isNew) {
                            row.removeClass('new-validity')
                                .find('.validity-name').replaceWith(`<strong>${data.field}</strong>`);
                        }
                    } else {
                        showNotification('error', res.error || 'Error saving validity');
                    }
                } catch (e) {
                    console.error('Invalid JSON response:', response);
                    showNotification('error', 'An error occurred while processing the response.');
                }
            },
            error: function (xhr, status, error) {
                console.error('Error saving validity date:', status, error);
                showNotification('error', 'An error occurred while saving the validity date.');
            }
        });
    });

    // Add new validity row
    $('#addValidityRow').click(function () {
        const newRow = $('#validityTemplate').clone()
            .removeAttr('id')
            .addClass('new-validity')
            .show();
        $('#validityTable tbody').append(newRow);
        initDatepickers();
    });

    // Remove validity row
    $('#validityTable').on('click', '.remove-validity', function () {
        const row = $(this).closest('tr');
        if (row.hasClass('new-validity')) {
            row.remove();
        } else {
            if (confirm('Are you sure you want to delete this validity?')) {
                $.ajax({
                    url: 'delete_validity.php',
                    method: 'POST',
                    data: { field: row.data('field') },
                    success: function (response) {
                        try {
                            const res = JSON.parse(response);
                            if (res.success) {
                                row.remove();
                                showNotification('success', 'Validity removed successfully');
                            } else {
                                showNotification('error', res.error || 'Error removing validity');
                            }
                        } catch (e) {
                            console.error('Invalid JSON response:', response);
                            showNotification('error', 'An error occurred while processing the response.');
                        }
                    },
                    error: function (xhr, status, error) {
                        console.error('Error removing validity date:', status, error);
                        showNotification('error', 'An error occurred while removing the validity date.');
                    }
                });
            }
        }
    });

    // Function to show notifications
    function showNotification(type, message) {
        const notification = $('<div>')
            .addClass(`alert alert-${type}`)
            .text(message)
            .append('<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>');

        $('#notification-container').append(notification);

        setTimeout(function () {
            notification.alert('close');
        }, 3000);
    }
});
</script> -->

<style>
    #notification-container {
        position: fixed;
        top: 20px;
        right: 20px;
        z-index: 9999;
        min-width: 300px;
    }
    .notification {
        position: relative;
        padding: 15px;
        margin-bottom: 10px;
        border-radius: 4px;
        animation: slideIn 0.3s ease-out;
    }
    .notification.success { background: #d4edda; color: #155724; }
    .notification.error { background: #f8d7da; color: #721c24; }
    .notification.info { background: #d1ecf1; color: #0c5460; }
    @keyframes slideIn {
        from { transform: translateX(100%); }
        to { transform: translateX(0); }
    }
</style>

<style>
.equal-height {
    display: flex;
    align-items: center;
    justify-content: center; /*Horizontally center the content*/
    min-height: 150px; /*Adjust as needed*/
}
.reduce-body {
        padding: 10px; /* Adjust the padding to reduce the body size */

}
.clock-input {
    width: 45%; /* Reduce input width  */
   /* Keep inputs inline */
}
.clock-button {
    width: 30%; /* Reduce button width  */
}
.form-group label {
    display: block; /* Ensure labels are on their own line */
    margin-bottom: 5px;
}
.form-group input{
        margin-bottom: 5px;
        width: 45%;
}
</style>
<!-- VALIDITY SECTION -->
<style>
.validity-table {
    margin-top: 15px;
}

.validity-date {
    max-width: 150px;
    display: inline-block;
}

.text-success {
    color: #28a745;
    font-weight: bold;
}

.text-danger {
    color: #dc3545;
    font-weight: bold;
}

#saveValidityDates {
    margin-top: 20px;
    padding: 8px 20px;
}
</style>
<!-- Valdity SECTION -->
<style>
/* .status-cell {
    font-weight: bold;
    vertical-align: middle !important;
}
.validity-name {
    min-width: 150px;
}
.btn-xs {
    padding: 1px 5px;
    margin: 0 2px;
} */
.input-group {
    gap: 5px; /* Space between input and button */
}
.validity-date {
    width: 130px;
    border-top-right-radius: 0;
    border-bottom-right-radius: 0;
}
.btn-primary.save-validity {
    padding: 1px 6px;
    border-top-left-radius: 0;
    border-bottom-left-radius: 0;
    white-space: nowrap;
}
</style>


<!-- ====================== ABOVE - ENDS of WHOLE hangar.php DESIGN PAGE - COMPLETED - Feb-20-24 ====================== -->


        <!-- BELOW - PERSONAL INFORMATION MODAL - DO NOT DELETE - IT IS WORKING FINE - Feb-19-24 -->

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
                                <label for="firstname">First Name:</label>
                                <input type="text" class="form-control" id="firstname">
                            </div>
                            <div class="form-group">
                                <label for="lastname">Last Name:</label>
                                <input type="text" class="form-control" id="lastname">
                            </div>
                            <div class="form-group">
                                <label for="usernameInput">Username:</label>
                                <input type="text" class="form-control" id="usernameInput">
                            </div>
                            <div class="form-group">
                                <label for="user_nationality">Nationality:</label>
                                <input type="text" class="form-control" id="user_nationality">
                            </div>
                            <div class="form-group">
                                <label for="email">E-mail:</label>
                                <input type="email" class="form-control" id="email">
                            </div>
                            <div class="form-group">
                                <label for="phone">Phone:</label>
                                <input type="text" class="form-control" id="phone">
                            </div>
                            <div class="form-group">
                                <label for="phonetwo">Secondary Phone:</label>
                                <input type="text" class="form-control" id="phonetwo">
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

        <!-- ABOVE - END PERSONAL INFORMATION MODAL  DO NOT DELETE - IT IS WORKING FINE - Feb-19-24-->

        <!-- BELOW - CHANGE PASSWORD MODAL - in progress -->
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

        <!-- ABOVE - ENDS CHANGE PASSWORD MODAL in progress -->

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
                            <!-- <form class="dropzone no-float" id="uploadDocuments">
                                <p class="dz-message">Drag and Drop, or click, to upload files.<br/><strong>Maximum 1.5Mb</strong></p>
                            </form> -->
                        </div>
                    </div>
                </div>
            </div>
        </div>


<?php include_once "footer.php"; ?>

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
<script src="hangarfunctions.js"></script>
