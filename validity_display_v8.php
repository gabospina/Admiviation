

<?php

function displayValidityForPilot($pilotId) {
    global $mysqli;  // Access the database connection from db_connect.php
    if (!$mysqli) {
        echo "Error: Unable to connect to the database.";
        return;
    }

    // Query validity table from database
    $sql = "SELECT field, value FROM validity WHERE pilot_id = ?";
    $stmt = $mysqli->prepare($sql);
    if (!$stmt) {
        echo "Error: " . $mysqli->error;
        return;
    }

    $stmt->bind_param("i", $pilotId);
    $stmt->execute();
    $result = $stmt->get_result();

    $validityData = [];
    while ($row = $result->fetch_assoc()) {
        $validityData[$row['field']] = $row['value'];
    }

    $stmt->close();

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

    <div class="panel panel-default validity-panel">
        <div class="panel-heading">
            <h3 class="panel-title">Certification Validity-display</h3>
        </div>
        <div class="panel-body">
            <table class="table table-condensed table-bordered">
                <thead>
                    <tr>
                        <th>Certification123</th>
                        <th>Expiry Date</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($validityFields as $field => $label): ?>
                        <tr>
                            <td><?= htmlspecialchars($label) ?></td>
                            <td>
                                <?php 
                                    $expiryDate = isset($validityData[$field]) ? htmlspecialchars($validityData[$field]) : 'N/A';
                                    echo $expiryDate;
                                ?>
                            </td>
                            <td>
                                <?php
                                    $statusClass = ($expiryDate != 'N/A' && strtotime($expiryDate) >= strtotime(date('Y-m-d'))) ? 'text-success' : 'text-danger';
                                    $statusText = ($expiryDate != 'N/A' && strtotime($expiryDate) >= strtotime(date('Y-m-d'))) ? 'Valid' : 'Expired';
                                    echo "<span class='$statusClass'>$statusText</span>";
                                ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php
}
?>