<?php
include('../config/db.php');
include('../includes/header.php');

if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $conn->query("DELETE FROM medications WHERE id = $id");
    header("Location: list.php");
    exit;
}
?>
<h2>Medications</h2>
<a href="../index.php">Back to Home</a> | <a href="add.php">Add New Medication</a>
<br><br>
<table border="1" cellpadding="10">
    <tr>
        <th>ID</th><th>Name</th><th>Price ($)</th><th>Actions</th>
    </tr>
    <?php
    $result = $conn->query("SELECT * FROM medications");
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<tr>
                <td>{$row['id']}</td>
                <td>{$row['name']}</td>
                <td>" . number_format($row['price'], 2) . "</td>
                <td>
                    <a href='edit.php?id={$row['id']}' style='color:#00c2b3;font-weight:600;text-decoration:none;margin-right:10px;'>Edit</a>
                    <a href='list.php?delete={$row['id']}' style='color:#e05c5c;font-weight:600;text-decoration:none;' onclick=\"return confirm('Delete this medication?')\">Delete</a>
                </td>
            </tr>";
        }
    } else {
        echo "<tr><td colspan='4'>No medications found</td></tr>";
    }
    ?>
</table>
<?php include('../includes/footer.php'); ?>