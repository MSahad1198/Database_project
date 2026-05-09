<?php 
include('../config/db.php');
include('../includes/header.php'); 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $patient_id = $_POST['patient_id'];
    $doctor_id = $_POST['doctor_id'];
    $date = $_POST['date'];

    $sql = "INSERT INTO appointments (patient_id, doctor_id, date) VALUES ($patient_id, $doctor_id, '$date')";
    
    if ($conn->query($sql) === TRUE) {
        echo "<p style='color:green;'>New appointment booked successfully!</p>";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}
?>

<h2>Book New Appointment</h2>
<form method="POST" action="">
    <label>Patient:</label><br>
    <select name="patient_id" required>
        <option value="">Select Patient</option>
        <?php
        $pat_sql = "SELECT * FROM patients";
        $pat_result = $conn->query($pat_sql);
        while($pat = $pat_result->fetch_assoc()) {
            echo "<option value='" . $pat['id'] . "'>" . $pat['name'] . "</option>";
        }
        ?>
    </select><br><br>
    
    <label>Doctor:</label><br>
    <select name="doctor_id" required>
        <option value="">Select Doctor</option>
        <?php
        $doc_sql = "SELECT * FROM doctors";
        $doc_result = $conn->query($doc_sql);
        while($doc = $doc_result->fetch_assoc()) {
            echo "<option value='" . $doc['id'] . "'>" . $doc['name'] . "</option>";
        }
        ?>
    </select><br><br>
    
    <label>Date:</label><br>
    <input type="date" name="date" required><br><br>
    
    <input type="submit" value="Book Appointment">
</form>

<br><a href="list.php">Back to Appointments List</a>
<?php include('../includes/footer.php'); ?>