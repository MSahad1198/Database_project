<?php 
include('../config/db.php');
include('../includes/header.php'); 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $specialization_id = $_POST['specialization_id'];

    $sql = "INSERT INTO doctors (name, specialization_id) VALUES ('$name', $specialization_id)";
    
    if ($conn->query($sql) === TRUE) {
        echo "<p style='color:green;'>New doctor added successfully!</p>";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}
?>

<h2>Add New Doctor</h2>
<form method="POST" action="">
    <label>Name:</label><br>
    <input type="text" name="name" required><br><br>
    
    <label>Specialization:</label><br>
    <select name="specialization_id" required>
        <option value="">Select Specialization</option>
        <?php
        $spec_sql = "SELECT * FROM specializations";
        $spec_result = $conn->query($spec_sql);
        while($spec = $spec_result->fetch_assoc()) {
            echo "<option value='" . $spec['id'] . "'>" . $spec['name'] . "</option>";
        }
        ?>
    </select><br><br>
    
    <input type="submit" value="Add Doctor">
</form>

<br><a href="list.php">Back to Doctor List</a>
<?php include('../includes/footer.php'); ?>