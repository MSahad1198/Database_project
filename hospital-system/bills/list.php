<?php
include('../config/db.php');
include('../includes/header.php');

if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $conn->query("DELETE FROM bills WHERE id = $id");
    header("Location: list.php");
    exit;
}
?>
<h2>Bills / Payments</h2>
<a href="../index.php">Back to Home</a> | <a href="add.php">Add New Bill</a>
<br><br>
<table border="1" cellpadding="10">
    <tr>
        <th>ID</th><th>Patient</th><th>Total Amount ($)</th><th>Actions</th>
    </tr>
    <?php
    $result = $conn->query("SELECT b.id, p.name AS patient_name, b.total_amount
                            FROM bills b
                            JOIN patients p ON b.patient_id = p.id
                            ORDER BY b.total_amount DESC");
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<tr>
                <td>{$row['id']}</td>
                <td>{$row['patient_name']}</td>
                <td>" . number_format($row['total_amount'], 2) . "</td>
                <td>
                    <a href='edit.php?id={$row['id']}' style='color:#00c2b3;font-weight:600;text-decoration:none;margin-right:10px;'>Edit</a>
                    <a href='list.php?delete={$row['id']}' style='color:#e05c5c;font-weight:600;text-decoration:none;' onclick=\"return confirm('Delete this bill?')\">Delete</a>
                </td>
            </tr>";
        }
    } else {
        echo "<tr><td colspan='4'>No bills found</td></tr>";
    }
    ?>
</table>
<?php include('../includes/footer.php'); ?>