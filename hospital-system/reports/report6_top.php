<?php 
include('../config/db.php');
include('../includes/header.php'); 
?>

<h2>Report 6: Top 5 Highest Bills</h2>
<table border="1" cellpadding="5">
    <tr><th>Patient</th><th>Bill Amount</th></tr>
    <?php
    $sql = "SELECT p.name, b.total_amount
            FROM patients p
            JOIN bills b ON p.id = b.patient_id
            ORDER BY b.total_amount DESC
            LIMIT 5";
            
    $result = $conn->query($sql);
    while ($row = $result->fetch_assoc()) {
        echo "<tr><td>{$row['name']}</td><td>{$row['total_amount']}</td></tr>";
    }
    ?>
</table>
<br><a href="../index.php">Back to Home</a>
<?php include('../includes/footer.php'); ?>