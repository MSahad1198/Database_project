<?php 
include('../config/db.php');
include('../includes/header.php'); 
?>

<h2>Report 5: Total Doctors per Specialization</h2>
<table border="1" cellpadding="5">
    <tr><th>Specialization</th><th>Total Doctors</th></tr>
    <?php
    $sql = "SELECT s.name AS specialization, COUNT(d.id) AS total_doctors
            FROM specializations s
            LEFT JOIN doctors d ON s.id = d.specialization_id
            GROUP BY s.id";
            
    $result = $conn->query($sql);
    while ($row = $result->fetch_assoc()) {
        echo "<tr><td>{$row['specialization']}</td><td>{$row['total_doctors']}</td></tr>";
    }
    ?>
</table>
<br><a href="../index.php">Back to Home</a>
<?php include('../includes/footer.php'); ?>