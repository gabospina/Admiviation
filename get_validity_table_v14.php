<?php
session_start();
require_once 'db_connect.php';

// ... (Authentication check and database connection as before) ...

$userId = $_SESSION["HeliUser"];

try {
    // Prepare the SQL query
    $stmt = $pdo->prepare("
        SELECT *
        FROM validity
        WHERE pilot_id = ?
    ");

    // Execute the query with the user ID
    $stmt->execute([$userId]);

    // Fetch the data into an associative array
    $validityData = $stmt->fetch(PDO::FETCH_ASSOC);

    // If no data is found, create an empty array to avoid errors later
    if (!$validityData) {
        $validityData = [];
    }

} catch (PDOException $e) {
    // Handle database errors
    error_log("Database error: " . $e->getMessage());
    // You might want to display a user-friendly error message here
    echo "An error occurred while retrieving data.  Please check the logs.";
    $validityData = []; // Ensure $validityData is defined even on error
}


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

?>

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
        foreach ($validityFields as $field => $label):
            $expiryDate = $validityData[$field] ?? '';
            $statusClass = ($expiryDate && $expiryDate >= date('Y-m-d')) ? 'text-success' : 'text-danger';
            $statusText = ($expiryDate && $expiryDate >= date('Y-m-d')) ? 'Valid' : 'Expired';
        ?>
            <tr data-field="<?= $field ?>">
                <td><strong><?= $label ?></strong></td>
                <td>
                    <div class="input-group" style="width: 170px; position: relative; z-index: 1;">
                        <input type="text"
                               class="form-control datepicker validity-date"
                               value="<?= $expiryDate ?>"
                               placeholder="YYYY-MM-DD">
                        <span class="input-group-btn">
                            <button class="btn btn-primary save-validity" style="margin-left: 5px !important;" type="button">
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

<?php
  // Don't forget the "Add New" button
 ?>
<button class="btn btn-xs btn-success pull-right" id="addValidityRow">
    <i class="fa fa-plus"></i> Add New
</button>
<?php
exit(); // Very important to stop further execution
?>