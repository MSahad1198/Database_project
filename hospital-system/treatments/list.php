<?php
include('../config/db.php');
include('../includes/header.php');
?>
<h2>Treatments List</h2>
<a href="../index.php">Back to Home</a> | <a href="add.php">Add New Treatment</a>
<br><br>
<table border="1" cellpadding="10">
    <tr>
        <th>ID</th>
        <th>Patient</th>
        <th>Doctor</th>
        <th>Appointment Date</th>
        <th>Description</th>
    </tr>
    <?php
    $sql = "SELECT t.id, p.name AS patient, d.name AS doctor, a.date, t.description
            FROM treatments t
            JOIN appointments a ON t.appointment_id = a.id
            JOIN patients p ON a.patient_id = p.id
            JOIN doctors d ON a.doctor_id = d.id";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<tr>
                <td>" . $row['id'] . "</td>
                <td>" . $row['patient'] . "</td>
                <td>" . $row['doctor'] . "</td>
                <td>" . $row['date'] . "</td>
                <td>" . $row['description'] . "</td>
            </tr>";
        }
    } else {
        echo "<tr><td colspan='5'>No treatments found</td></tr>";
    }
    ?>
</table>
<?php include('../includes/footer.php'); ?>
