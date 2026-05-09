<?php
include('../config/db.php');
include('../includes/header.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $appointment_id = $_POST['appointment_id'];
    $description = $_POST['description'];
    $sql = "INSERT INTO treatments (appointment_id, description) VALUES ($appointment_id, '$description')";
    if ($conn->query($sql) === TRUE) {
        echo "<p style='color:green;'>Treatment added successfully!</p>";
    } else {
        echo "Error: " . $conn->error;
    }
}
?>
<h2>Add Treatment</h2>
<form method="POST" action="">
    <label>Appointment:</label><br>
    <select name="appointment_id" required>
        <option value="">Select Appointment</option>
        <?php
        $sql = "SELECT a.id, p.name AS patient, d.name AS doctor, a.date
                FROM appointments a
                JOIN patients p ON a.patient_id = p.id
                JOIN doctors d ON a.doctor_id = d.id";
        $result = $conn->query($sql);
        while ($row = $result->fetch_assoc()) {
            echo "<option value='" . $row['id'] . "'>"
                . "Appt #" . $row['id'] . " - " . $row['patient'] . " with " . $row['doctor'] . " on " . $row['date']
                . "</option>";
        }
        ?>
    </select><br><br>

    <label>Treatment Description:</label><br>
    <textarea name="description" rows="3" cols="40" required></textarea><br><br>

    <input type="submit" value="Add Treatment">
</form>
<br><a href="list.php">Back to Treatments List</a>
<?php include('../includes/footer.php'); ?>
