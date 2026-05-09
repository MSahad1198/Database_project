<?php 
include('../config/db.php');
include('../includes/header.php'); 
?>

<h2>Report 7: Appointments for Patients with Bills > 3000</h2>
<table border="1" cellpadding="5">
    <tr><th>Patient</th><th>Doctor</th></tr>
    <?php
    $sql = "SELECT p.name, d.name AS doctor
            FROM patients p
            JOIN appointments a ON p.id = a.patient_id
            JOIN doctors d ON a.doctor_id = d.id
            WHERE p.id IN (
                SELECT patient_id
                FROM bills
                WHERE total_amount > 3000
            )";
            
    $result = $conn->query($sql);
    while ($row = $result->fetch_assoc()) {
        echo "<tr><td>{$row['name']}</td><td>{$row['doctor']}</td></tr>";
    }
    ?>
</table>
<br><a href="../index.php">Back to Home</a>
<?php include('../includes/footer.php'); ?>