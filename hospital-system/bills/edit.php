<?php
include('../config/db.php');
include('../includes/header.php');

$id = intval($_GET['id']);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $patient_id   = $_POST['patient_id'];
    $total_amount = $_POST['total_amount'];
    $conn->query("UPDATE bills SET patient_id=$patient_id, total_amount=$total_amount WHERE id=$id");
    echo "<p style='color:green;'>Bill updated successfully!</p>";
}

$row = $conn->query("SELECT * FROM bills WHERE id=$id")->fetch_assoc();
?>
<h2>Edit Bill</h2>
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

    <label>Total Amount ($):</label>
    <input type="number" step="0.01" min="0" name="total_amount" value="<?= $row['total_amount'] ?>" required>

    <input type="submit" value="Update Bill">
</form>
<br><a href="list.php">Back to Bills List</a>
<?php include('../includes/footer.php'); ?>