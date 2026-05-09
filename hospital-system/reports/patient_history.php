<?php
include('../config/db.php');
include('../includes/header.php');
?>

<h2>Patient Full History</h2>

<!-- Patient selector -->
<form method="GET" action="" style="max-width:400px; margin-bottom:28px;">
    <label>Select Patient:</label>
    <select name="patient_id" required onchange="this.form.submit()">
        <option value="">-- Choose a Patient --</option>
        <?php
        $patients = $conn->query("SELECT * FROM patients ORDER BY name");
        while ($p = $patients->fetch_assoc()) {
            $sel = (isset($_GET['patient_id']) && $_GET['patient_id'] == $p['id']) ? 'selected' : '';
            echo "<option value='{$p['id']}' $sel>{$p['name']}</option>";
        }
        ?>
    </select>
</form>

<?php if (isset($_GET['patient_id']) && !empty($_GET['patient_id'])): 
    $pid = intval($_GET['patient_id']);

    // Get patient info
    $patient = $conn->query("SELECT * FROM patients WHERE id = $pid")->fetch_assoc();
?>

<!-- Patient Info Card -->
<div style="
    background: linear-gradient(135deg, var(--navy) 0%, #163a5f 100%);
    border-radius: var(--radius);
    padding: 24px 28px;
    margin-bottom: 28px;
    display: flex;
    gap: 40px;
    align-items: center;
">
    <div style="font-size:3rem;">🧑‍⚕️</div>
    <div>
        <div style="color:var(--teal);font-size:.75rem;font-weight:700;text-transform:uppercase;letter-spacing:.1em;">Patient</div>
        <div style="color:var(--white);font-family:'Cormorant Garamond',serif;font-size:1.8rem;font-weight:700;margin:4px 0;">
            <?= $patient['name'] ?>
        </div>
        <div style="color:var(--grey-mid);font-size:.85rem;">
            Age: <?= $patient['age'] ?> &nbsp;·&nbsp; Gender: <?= $patient['gender'] ?> &nbsp;·&nbsp; ID: #<?= $patient['id'] ?>
        </div>
    </div>
</div>

<?php
    /*
     * UNION QUERY — combines 5 different record types into one timeline:
     * 1. Appointments
     * 2. Treatments
     * 3. Prescriptions (medicines ordered by doctor)
     * 4. Pharmacy Sales (medicines bought directly)
     * 5. Bills
     * All unified with: type, date, description, amount columns
     */
    $sql = "
        -- 1. APPOINTMENTS
        SELECT 
            'Appointment' AS type,
            '📅'          AS icon,
            a.date        AS record_date,
            CONCAT('Visited Dr. ', d.name, ' (', s.name, ')') AS description,
            NULL          AS amount
        FROM appointments a
        JOIN doctors d       ON a.doctor_id = d.id
        JOIN specializations s ON d.specialization_id = s.id
        WHERE a.patient_id = $pid

        UNION ALL

        -- 2. TREATMENTS
        SELECT 
            'Treatment'   AS type,
            '🩺'          AS icon,
            a.date        AS record_date,
            CONCAT('Treatment: ', t.description) AS description,
            NULL          AS amount
        FROM treatments t
        JOIN appointments a ON t.appointment_id = a.id
        WHERE a.patient_id = $pid

        UNION ALL

        -- 3. PRESCRIPTIONS (medicines ordered via doctor)
        SELECT 
            'Prescription' AS type,
            '📋'           AS icon,
            a.date         AS record_date,
            CONCAT('Prescribed: ', m.name, ' | Dosage: ', pr.dosage, ' | Qty: ', pr.quantity) AS description,
            (m.price * pr.quantity) AS amount
        FROM prescriptions pr
        JOIN treatments t    ON pr.treatment_id = t.id
        JOIN appointments a  ON t.appointment_id = a.id
        JOIN medications m   ON pr.medication_id = m.id
        WHERE a.patient_id = $pid

        UNION ALL

        -- 4. PHARMACY SALES (medicines bought directly at pharmacy)
        SELECT 
            'Pharmacy Sale' AS type,
            '🏪'            AS icon,
            ps.sale_date    AS record_date,
            CONCAT('Bought from Pharmacy: ', m.name, ' | Qty: ', ps.quantity) AS description,
            ps.total_price  AS amount
        FROM pharmacy_sales ps
        JOIN medications m ON ps.medication_id = m.id
        WHERE ps.patient_id = $pid

        UNION ALL

        -- 5. BILLS
        SELECT 
            'Bill'        AS type,
            '💳'          AS icon,
            NULL          AS record_date,
            'Hospital Service Bill' AS description,
            b.total_amount AS amount
        FROM bills b
        WHERE b.patient_id = $pid

        ORDER BY record_date ASC, type ASC
    ";

    $result = $conn->query($sql);

    // Colour coding per type
    $type_colors = [
        'Appointment'  => '#3b82f6',
        'Treatment'    => '#8b5cf6',
        'Prescription' => '#f59e0b',
        'Pharmacy Sale'=> '#10b981',
        'Bill'         => '#e05c5c',
    ];

    $grand_total     = 0;
    $med_total       = 0;
    $pharmacy_total  = 0;
    $bill_total      = 0;
    $rows            = [];

    while ($row = $result->fetch_assoc()) {
        $rows[] = $row;
        if ($row['amount'] !== null) {
            $grand_total += $row['amount'];
            if ($row['type'] === 'Prescription')  $med_total      += $row['amount'];
            if ($row['type'] === 'Pharmacy Sale') $pharmacy_total += $row['amount'];
            if ($row['type'] === 'Bill')          $bill_total     += $row['amount'];
        }
    }
?>

<!-- Summary Cards -->
<div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(180px,1fr));gap:14px;margin-bottom:28px;">
    <?php
    $summary = [
        ['💊 Prescriptions', '$'.number_format($med_total,2),      '#f59e0b'],
        ['🏪 Pharmacy',      '$'.number_format($pharmacy_total,2),  '#10b981'],
        ['💳 Hospital Bill', '$'.number_format($bill_total,2),      '#e05c5c'],
        ['💰 Grand Total',   '$'.number_format($grand_total,2),     '#00c2b3'],
    ];
    foreach ($summary as [$label, $val, $color]) {
        echo "
        <div style='background:var(--white);border-radius:var(--radius);
                    box-shadow:var(--shadow-sm);padding:18px 20px;
                    border-top:3px solid $color;'>
            <div style='font-size:.75rem;font-weight:600;color:var(--grey-text);
                        text-transform:uppercase;letter-spacing:.07em;margin-bottom:6px;'>$label</div>
            <div style='font-size:1.4rem;font-weight:700;color:$color;'>$val</div>
        </div>";
    }
    ?>
</div>

<!-- Full Timeline Table -->
<h3>Full Activity Timeline</h3>
<table border="1" cellpadding="10">
    <tr>
        <th>Type</th>
        <th>Date</th>
        <th>Description</th>
        <th>Amount ($)</th>
    </tr>
    <?php if (count($rows) > 0):
        foreach ($rows as $row):
            $color = $type_colors[$row['type']] ?? '#888';
    ?>
    <tr>
        <td>
            <span style="
                display:inline-flex;align-items:center;gap:6px;
                background:<?= $color ?>18;
                color:<?= $color ?>;
                border:1px solid <?= $color ?>44;
                border-radius:20px;
                padding:3px 10px;
                font-size:.78rem;font-weight:700;
                white-space:nowrap;
            ">
                <?= $row['icon'] ?> <?= $row['type'] ?>
            </span>
        </td>
        <td style="color:var(--grey-text);font-size:.85rem;">
            <?= $row['record_date'] ?? '<span style="color:#ccc;">—</span>' ?>
        </td>
        <td><?= $row['description'] ?></td>
        <td style="font-weight:600;color:<?= $row['amount'] ? '#00c2b3' : 'var(--grey-text)' ?>;">
            <?= $row['amount'] !== null ? '$'.number_format($row['amount'],2) : '—' ?>
        </td>
    </tr>
    <?php endforeach; ?>

    <!-- Grand Total Row -->
    <tr style="background:var(--navy);">
        <td colspan="3" style="color:var(--teal);font-weight:700;font-size:.9rem;letter-spacing:.05em;text-transform:uppercase;">
            Grand Total (Prescriptions + Pharmacy + Hospital Bill)
        </td>
        <td style="color:var(--teal);font-weight:700;font-size:1.1rem;">
            $<?= number_format($grand_total, 2) ?>
        </td>
    </tr>

    <?php else: ?>
    <tr><td colspan="4" style="text-align:center;color:var(--grey-text);">No history found for this patient.</td></tr>
    <?php endif; ?>
</table>

<?php endif; ?>

<br><a href="../index.php">Back to Home</a>
<?php include('../includes/footer.php'); ?>