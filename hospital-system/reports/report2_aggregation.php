<?php 
include('../config/db.php');
include('../includes/header.php'); 
?>

<h2>Report 2: Doctor Appointment Counts</h2>
<table border="1" cellpadding="5">
    <tr><th>Doctor</th><th>Total Appointments</th></tr>
    <?php
    $sql = "SELECT d.name AS doctor, COUNT(a.id) AS total_appointments
            FROM doctors d
            JOIN appointments a ON d.id = a.doctor_id
            GROUP BY d.id";
            
    $result = $conn->query($sql);
    while ($row = $result->fetch_assoc()) {
        echo "<tr><td>{$row['doctor']}</td><td>{$row['total_appointments']}</td></tr>";
    }
    ?>
</table>
<br><a href="../index.php">Back to Home</a>
<?php include('../includes/footer.php'); ?>