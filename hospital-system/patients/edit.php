<?php
include('../config/db.php');
include('../includes/header.php');

$id = intval($_GET['id']);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name   = $_POST['name'];
    $age    = $_POST['age'];
    $gender = $_POST['gender'];
    $conn->query("UPDATE patients SET name='$name', age=$age, gender='$gender' WHERE id=$id");
    echo "<p style='color:green;'>Patient updated successfully!</p>";
}

$row = $conn->query("SELECT * FROM patients WHERE id=$id")->fetch_assoc();
?>
<h2>Edit Patient</h2>
<form method="POST" action="">
    <label>Name:</label>
    <input type="text" name="name" value="<?= $row['name'] ?>" required>

    <label>Age:</label>
    <input type="number" name="age" value="<?= $row['age'] ?>" required>

    <label>Gender:</label>
    <select name="gender">
        <option value="Male"   <?= $row['gender']=='Male'   ? 'selected':'' ?>>Male</option>
        <option value="Female" <?= $row['gender']=='Female' ? 'selected':'' ?>>Female</option>
        <option value="Other"  <?= $row['gender']=='Other'  ? 'selected':'' ?>>Other</option>
    </select>

    <input type="submit" value="Update Patient">
</form>
<br><a href="list.php">Back to Patients List</a>
<?php include('../includes/footer.php'); ?>