<?php
include('../config/db.php');
include('../includes/header.php');

$id = intval($_GET['id']);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $spec = $_POST['specialization_id'];
    $conn->query("UPDATE doctors SET name='$name', specialization_id=$spec WHERE id=$id");
    echo "<p style='color:green;'>Doctor updated successfully!</p>";
}

$row = $conn->query("SELECT * FROM doctors WHERE id=$id")->fetch_assoc();
?>
<h2>Edit Doctor</h2>
<form method="POST" action="">
    <label>Name:</label>
    <input type="text" name="name" value="<?= $row['name'] ?>" required>

    <label>Specialization:</label>
    <select name="specialization_id" required>
        <option value="">Select Specialization</option>
        <?php
        $specs = $conn->query("SELECT * FROM specializations");
        while ($s = $specs->fetch_assoc()) {
            $sel = $s['id'] == $row['specialization_id'] ? 'selected' : '';
            echo "<option value='{$s['id']}' $sel>{$s['name']}</option>";
        }
        ?>
    </select>

    <input type="submit" value="Update Doctor">
</form>
<br><a href="list.php">Back to Doctors List</a>
<?php include('../includes/footer.php'); ?>