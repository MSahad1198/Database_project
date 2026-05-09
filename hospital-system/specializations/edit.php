<?php
include('../config/db.php');
include('../includes/header.php');

$id = intval($_GET['id']);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $conn->query("UPDATE specializations SET name='$name' WHERE id=$id");
    echo "<p style='color:green;'>Specialization updated successfully!</p>";
}

$row = $conn->query("SELECT * FROM specializations WHERE id=$id")->fetch_assoc();
?>
<h2>Edit Specialization</h2>
<form method="POST" action="">
    <label>Specialization Name:</label>
    <input type="text" name="name" value="<?= $row['name'] ?>" required>

    <input type="submit" value="Update Specialization">
</form>
<br><a href="list.php">Back to Specializations List</a>
<?php include('../includes/footer.php'); ?>