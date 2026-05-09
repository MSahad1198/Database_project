<?php
include('../config/db.php');
include('../includes/header.php');

$id = intval($_GET['id']);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $patient_id = $_POST['patient_id'];
    $doctor_id  = $_POST['doctor_id'];
    $date       = $_POST['date'];
    $conn->query("UPDATE appointments SET patient_id=$patient_id, doctor_id=$doctor_id, date='$date' WHERE id=$id");
    echo "<p style='color:green;'>Appointment updated successfully!</p>";
}

$row = $conn->query("SELECT * FROM appointments WHERE id=$id")->fetch_assoc();
?>
<h2>Edit Appointment</h2>
<form method="POST" action="">
    <label>Patient:</label>
    <select name="patient_id" required>
        <?php
        $patients = $conn->query("SELECT * FROM patients");
        while ($p = $patients->fetch_assoc()) {
            $sel = $p['id'] == $row['patient_id'] ? 'selected' : '';
            echo "<option value='{$p['id']}' $sel>{$p['name']}</option>";
        }
        ?>
    </select>

    <label>Doctor:</label>
    <select name="doctor_id" required>
        <?php
        $doctors = $conn->query("SELECT * FROM doctors");
        while ($d = $doctors->fetch_assoc()) {
            $sel = $d['id'] == $row['doctor_id'] ? 'selected' : '';
            echo "<option value='{$d['id']}' $sel>{$d['name']}</option>";
        }
        ?>
    </select>

    <label>Date:</label>
    <input type="date" name="date" value="<?= $row['date'] ?>" required>

    <input type="submit" value="Update Appointment">
</form>
<br><a href="list.php">Back to Appointments List</a>
<?php include('../includes/footer.php'); ?>