<?php
include('../config/db.php');
include('../includes/header.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $price = $_POST['price'];
    $sql = "INSERT INTO medications (name, price) VALUES ('$name', $price)";
    if ($conn->query($sql) === TRUE) {
        echo "<p style='color:green;'>Medication '$name' added successfully!</p>";
    } else {
        echo "Error: " . $conn->error;
    }
}
?>
<h2>Add Medication</h2>
<form method="POST" action="">
    <label>Medication Name:</label><br>
    <input type="text" name="name" required><br><br>

    <label>Price ($):</label><br>
    <input type="number" step="0.01" min="0" name="price" required><br><br>

    <input type="submit" value="Add Medication">
</form>
<br><a href="list.php">Back to Medications List</a>
<?php include('../includes/footer.php'); ?>
