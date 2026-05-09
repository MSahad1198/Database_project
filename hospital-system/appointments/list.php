<?php
include('../config/db.php');
include('../includes/header.php');

if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $conn->query("DELETE FROM appointments WHERE id = $id");
    header("Location: list.php");
    exit;
}
?>
<h2>Appointments</h2>
<a href="../index.php">Back to Home</a> | <a href="add.php">Add New Appointment</a>
<br><br>
<table border="1" cellpadding="10">
    <tr>
        <th>ID</th><th>Patient</th><th>Doctor</th><th>Date</th><th>Actions</th>
    </tr>
    <?php
    $result = $conn->query("SELECT a.id, p.name AS patient_name, d.name AS doctor_name, a.date
                            FROM appointments a
                            JOIN patients p ON a.patient_id = p.id
                            JOIN doctors d ON a.doctor_id = d.id");
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<tr>
                <td>{$row['id']}</td>
                <td>{$row['patient_name']}</td>
                <td>{$row['doctor_name']}</td>
                <td>{$row['date']}</td>
                <td>
                    <a href='edit.php?id={$row['id']}' style='color:#00c2b3;font-weight:600;text-decoration:none;margin-right:10px;'>Edit</a>
                    <a href='list.php?delete={$row['id']}' style='color:#e05c5c;font-weight:600;text-decoration:none;' onclick=\"return confirm('Delete this appointment?')\">Delete</a>
                </td>
            </tr>";
        }
    } else {
        echo "<tr><td colspan='5'>No appointments found</td></tr>";
    }
    ?>
</table>
<?php include('../includes/footer.php'); ?>