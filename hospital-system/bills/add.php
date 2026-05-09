<?php
include('../config/db.php');
include('../includes/header.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $patient_id  = $_POST['patient_id'];
    $total_amount = $_POST['total_amount'];
    $sql = "INSERT INTO bills (patient_id, total_amount) VALUES ($patient_id, $total_amount)";
    if ($conn->query($sql) === TRUE) {
        echo "<p style='color:green;'>Payment of $$total_amount added successfully!</p>";
    } else {
        echo "Error: " . $conn->error;
    }
}
?>
<h2>Add Payment / Bill</h2>
<form method="POST" action="">
    <label>Patient:</label>
    <select name="patient_id" required>
        <option value="">Select Patient</option>
        <?php
        $result = $conn->query("SELECT * FROM patients");
        while ($row = $result->fetch_assoc()) {
            echo "<option value='{$row['id']}'>{$row['name']}</option>";
        }
        ?>
    </select>

    <label>Total Amount ($):</label>
    <input type="number" step="0.01" min="0" name="total_amount" required>

    <input type="submit" value="Add Payment">
</form>
<br><a href="list.php">Back to Bills List</a>
<?php include('../includes/footer.php'); ?>