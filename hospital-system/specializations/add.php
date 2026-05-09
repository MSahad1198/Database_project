<?php 
include('../config/db.php');
include('../includes/header.php'); 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];

    // Basic validation to ensure the name isn't empty
    if(!empty($name)){
        $sql = "INSERT INTO specializations (name) VALUES ('$name')";
        
        if ($conn->query($sql) === TRUE) {
            echo "<p style='color:green;'>Specialization '$name' added successfully!</p>";
        } else {
            echo "Error: " . $conn->error;
        }
    }
}
?>

<h2>Add Specialization</h2>
<form method="POST" action="">
    <label>Specialization Name:</label><br>
    <input type="text" name="name" placeholder="e.g. Pediatrics" required><br><br>
    
    <input type="submit" value="Save Specialization">
</form>

<br><a href="list.php">View All Specializations</a>
<?php include('../includes/footer.php'); ?>