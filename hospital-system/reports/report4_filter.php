<?php
include('../config/db.php');
include('../includes/header.php'); 
?>
<h2>Report 4: Expensive Medications Prescribed (&gt;100)</h2>
<table border="1" cellpadding="5">
    <tr>
        <th>Patient</th>
        <th>Medicine(s)</th>
        <th>Dosage(s)</th>
    </tr>
    <?php
    $sql = "SELECT 
                p.name,
                GROUP_CONCAT(m.name ORDER BY m.name SEPARATOR ' | ') AS medicine,
                GROUP_CONCAT(pr.dosage ORDER BY m.name SEPARATOR ' | ') AS dosage
            FROM patients p
            JOIN appointments a  ON p.id = a.patient_id
            JOIN treatments t    ON a.id = t.appointment_id
            JOIN prescriptions pr ON t.id = pr.treatment_id
            JOIN medications m   ON pr.medication_id = m.id
            WHERE m.price > 100
            GROUP BY p.id";

    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<tr>
                <td>{$row['name']}</td>
                <td>{$row['medicine']}</td>
                <td>{$row['dosage']}</td>
            </tr>";
        }
    } else {
        echo "<tr><td colspan='3'>No data found</td></tr>";
    }
    ?>
</table>
<br><a href="../index.php">Back to Home</a>
<?php include('../includes/footer.php'); ?>