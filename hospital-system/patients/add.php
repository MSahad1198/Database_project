<?php 
include('../config/db.php');
include('../includes/header.php'); 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $age = $_POST['age'];
    $gender = $_POST['gender'];

    $sql = "INSERT INTO patients (name, age, gender) VALUES ('$name', $age, '$gender')";
    
    if ($conn->query($sql) === TRUE) {
        echo "<p style='color:green;'>New patient added successfully!</p>";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}
?>

<h2>Add New Patient</h2>
<form method="POST" action="">
    <label>Name:</label><br>
    <input type="text" name="name" required><br><br>
    
    <label>Age:</label><br>
    <input type="number" name="age" required><br><br>
    
    <label>Gender:</label><br>
    <select name="gender">
        <option value="Male">Male</option>
        <option value="Female">Female</option>
        <option value="Other">Other</option>
    </select><br><br>
    
    <input type="submit" value="Add Patient">
</form>

<br><a href="list.php">Back to Patient List</a>
<?php include('../includes/footer.php'); ?>