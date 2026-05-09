<?php 
include('../config/db.php');
include('../includes/header.php'); 
?>

<h2>Report 3: High Spending Patients (>5000)</h2>
<table border="1" cellpadding="5">
    <tr><th>Patient Name</th><th>Total Spent</th></tr>
    <?php
    $sql = "SELECT p.name, SUM(b.total_amount) AS total_spent
            FROM patients p
            JOIN bills b ON p.id = b.patient_id
            GROUP BY p.id
            HAVING total_spent > 5000";
            
    $result = $conn->query($sql);
    while ($row = $result->fetch_assoc()) {
        echo "<tr><td>{$row['name']}</td><td>{$row['total_spent']}</td></tr>";
    }
    ?>
</table>
<br><a href="../index.php">Back to Home</a>
<?php include('../includes/footer.php'); ?>