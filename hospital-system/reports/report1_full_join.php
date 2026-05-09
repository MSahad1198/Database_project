<?php
include('../config/db.php');
include('../includes/header.php'); 
?>
<h2>Report 1: Full Complex Report (7 Joins)</h2>
<table border="1" cellpadding="5">
    <tr>
        <th>Patient</th>
        <th>Doctor</th>
        <th>Specialization</th>
        <th>Date</th>
        <th>Treatment</th>
        <th>Medicine(s)</th>
        <th>Qty × Unit Price</th>
        <th>Dosage(s)</th>
        <th>Medicine Total ($)</th>
        <th>Bill Amount ($)</th>
        <th>Grand Total ($)</th>
    </tr>
    <?php
    /*
     * pharmacy_qty subquery: gets the total quantity sold per patient per medication
     * from pharmacy_sales. If no pharmacy sale exists, falls back to prescription quantity.
     */
    $sql = "SELECT 
                p.name AS patient, 
                d.name AS doctor, 
                s.name AS specialization, 
                a.date, 
                t.description,
                GROUP_CONCAT(m.name ORDER BY m.name SEPARATOR ' | ') AS medicine,
                GROUP_CONCAT(
                    CONCAT(
                        COALESCE(ps_totals.sold_qty, pr.quantity),
                        ' × $',
                        m.price
                    )
                    ORDER BY m.name SEPARATOR ' | '
                ) AS qty_price,
                GROUP_CONCAT(pr.dosage ORDER BY m.name SEPARATOR ' | ') AS dosage,
                SUM(m.price * COALESCE(ps_totals.sold_qty, pr.quantity)) AS med_total,
                b.total_amount AS bill_amount,
                (SUM(m.price * COALESCE(ps_totals.sold_qty, pr.quantity)) + b.total_amount) AS grand_total
            FROM patients p
            JOIN appointments a       ON p.id = a.patient_id
            JOIN doctors d            ON a.doctor_id = d.id
            JOIN specializations s    ON d.specialization_id = s.id
            JOIN treatments t         ON a.id = t.appointment_id
            JOIN prescriptions pr     ON t.id = pr.treatment_id
            JOIN medications m        ON pr.medication_id = m.id
            JOIN bills b              ON p.id = b.patient_id
            LEFT JOIN (
                SELECT patient_id, medication_id, SUM(quantity) AS sold_qty
                FROM pharmacy_sales
                GROUP BY patient_id, medication_id
            ) ps_totals ON ps_totals.patient_id = p.id 
                       AND ps_totals.medication_id = m.id
            GROUP BY p.id, a.id, t.id, d.id, s.id, b.id";

    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<tr>
                <td>{$row['patient']}</td>
                <td>{$row['doctor']}</td>
                <td>{$row['specialization']}</td>
                <td>{$row['date']}</td>
                <td>{$row['description']}</td>
                <td>{$row['medicine']}</td>
                <td>{$row['qty_price']}</td>
                <td>{$row['dosage']}</td>
                <td>" . number_format($row['med_total'], 2) . "</td>
                <td>" . number_format($row['bill_amount'], 2) . "</td>
                <td><strong>" . number_format($row['grand_total'], 2) . "</strong></td>
            </tr>";
        }
    } else {
        echo "<tr><td colspan='11'>No data found</td></tr>";
    }
    ?>
</table>
<br><a href="../index.php">Back to Home</a>
<?php include('../includes/footer.php'); ?>