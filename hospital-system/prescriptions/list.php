<?php
include('../config/db.php');
include('../includes/header.php');
?>
<h2>Prescriptions List</h2>
<a href="../index.php">Back to Home</a> | <a href="add.php">Add New Prescription</a>
<br><br>
<table border="1" cellpadding="10">
    <tr>
        <th>Patient</th>
        <th>Treatment</th>
        <th>Medicine(s)</th>
        <th>Unit Price(s) ($)</th>
        <th>Qty</th>

        <th>Dosage(s)</th>
    </tr>
    <?php
    $sql = "SELECT 
                p.name AS patient,
                t.description AS treatment,
                GROUP_CONCAT(m.name ORDER BY m.name SEPARATOR ' | ') AS medicines,
                GROUP_CONCAT(
                    FORMAT(m.price, 2) ORDER BY m.name SEPARATOR ' | '
                ) AS unit_prices,
                GROUP_CONCAT(
                    COALESCE(ps_totals.sold_qty, pr.quantity)
                    ORDER BY m.name SEPARATOR ' | '
                ) AS quantities,
                SUM(m.price * COALESCE(ps_totals.sold_qty, pr.quantity)) AS line_total,
                GROUP_CONCAT(pr.dosage ORDER BY m.name SEPARATOR ' | ') AS dosages
            FROM prescriptions pr
            JOIN treatments t      ON pr.treatment_id = t.id
            JOIN appointments a    ON t.appointment_id = a.id
            JOIN patients p        ON a.patient_id = p.id
            JOIN medications m     ON pr.medication_id = m.id
            LEFT JOIN (
                SELECT patient_id, medication_id, SUM(quantity) AS sold_qty
                FROM pharmacy_sales
                GROUP BY patient_id, medication_id
            ) ps_totals ON ps_totals.patient_id = p.id
                       AND ps_totals.medication_id = m.id
            GROUP BY p.id, t.id
            ORDER BY p.name, t.id";

    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $grand_total = 0;
        while ($row = $result->fetch_assoc()) {
            $grand_total += $row['line_total'];
            echo "<tr>
                <td>{$row['patient']}</td>
                <td>{$row['treatment']}</td>
                <td>{$row['medicines']}</td>
                <td>{$row['unit_prices']}</td>
                <td>{$row['quantities']}</td>
                <td>{$row['dosages']}</td>
            </tr>";
        }
        
    } else {
        echo "<tr><td colspan='7'>No prescriptions found</td></tr>";
    }
    ?>
</table>
<?php include('../includes/footer.php'); ?>