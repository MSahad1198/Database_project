<?php
include('../config/db.php');
include('../includes/header.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $treatment_id  = $_POST['treatment_id'];
    $medication_id = $_POST['medication_id'];
    $dosage        = $_POST['dosage'];
    $quantity      = $_POST['quantity'];

    $sql = "INSERT INTO prescriptions (treatment_id, medication_id, dosage, quantity)
            VALUES ($treatment_id, $medication_id, '$dosage', $quantity)";

    if ($conn->query($sql) === TRUE) {
        echo "<p style='color:green;'>Prescription added successfully!</p>";
    } else {
        echo "Error: " . $conn->error;
    }
}
?>
<h2>Add Prescription</h2>
<form method="POST" action="">
    <label>Treatment:</label><br>
    <select name="treatment_id" required>
        <option value="">Select Treatment</option>
        <?php
        $sql = "SELECT t.id, p.name AS patient, t.description
                FROM treatments t
                JOIN appointments a ON t.appointment_id = a.id
                JOIN patients p ON a.patient_id = p.id";
        $result = $conn->query($sql);
        while ($row = $result->fetch_assoc()) {
            echo "<option value='" . $row['id'] . "'>"
                . $row['patient'] . " - " . $row['description']
                . "</option>";
        }
        ?>
    </select><br><br>

    <label>Medication:</label><br>
    <select name="medication_id" required>
        <option value="">Select Medication</option>
        <?php
        $result = $conn->query("SELECT * FROM medications");
        while ($row = $result->fetch_assoc()) {
            echo "<option value='" . $row['id'] . "'>"
                . $row['name'] . " (Unit Price: $" . number_format($row['price'], 2) . ")"
                . "</option>";
        }
        ?>
    </select><br><br>

    <label>Quantity:</label><br>
    <input type="number" name="quantity" min="1" value="1" required><br><br>

    <label>Dosage:</label><br>
    <input type="text" name="dosage" placeholder="e.g. 500mg twice a day" required><br><br>

    <input type="submit" value="Add Prescription">
</form>
<br><a href="list.php">Back to Prescriptions List</a>
<?php include('../includes/footer.php'); ?>