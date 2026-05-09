<?php
include('../config/db.php');
include('../includes/header.php');
?>
<h2>Pharmacy - Sales Record</h2>
<a href="../index.php">Back to Home</a> | <a href="add.php">New Sale</a>
<br><br>

<!-- Summary per patient -->
<h3>Summary by Patient</h3>
<table border="1" cellpadding="8">
    <tr>
        <th>Patient</th>
        <th>Total Medicines Sold (Units)</th>
        <th>Total Amount Paid ($)</th>
    </tr>
    <?php
    $sql = "SELECT p.name AS patient, 
                   SUM(ps.quantity) AS total_units,
                   SUM(ps.total_price) AS total_paid
            FROM pharmacy_sales ps
            JOIN patients p ON ps.patient_id = p.id
            GROUP BY ps.patient_id
            ORDER BY total_paid DESC";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<tr>
                <td>" . $row['patient'] . "</td>
                <td>" . $row['total_units'] . "</td>
                <td><strong>" . number_format($row['total_paid'], 2) . "</strong></td>
            </tr>";
        }
    } else {
        echo "<tr><td colspan='3'>No sales yet</td></tr>";
    }
    ?>
</table>

<br>

<!-- Full detail table -->
<h3>All Sales (Detailed)</h3>
<table border="1" cellpadding="8">
    <tr>
        <th>ID</th>
        <th>Patient</th>
        <th>Medicine</th>
        <th>Unit Price ($)</th>
        <th>Quantity</th>
        <th>Total Price ($)</th>
        <th>Sale Date</th>
    </tr>
    <?php
    $sql = "SELECT ps.id, p.name AS patient, m.name AS medicine,
                   m.price AS unit_price, ps.quantity, ps.total_price, ps.sale_date
            FROM pharmacy_sales ps
            JOIN patients p ON ps.patient_id = p.id
            JOIN medications m ON ps.medication_id = m.id
            ORDER BY ps.sale_date DESC, p.name";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $grand_total = 0;
        while ($row = $result->fetch_assoc()) {
            $grand_total += $row['total_price'];
            echo "<tr>
                <td>" . $row['id'] . "</td>
                <td>" . $row['patient'] . "</td>
                <td>" . $row['medicine'] . "</td>
                <td>" . number_format($row['unit_price'], 2) . "</td>
                <td>" . $row['quantity'] . "</td>
                <td>" . number_format($row['total_price'], 2) . "</td>
                <td>" . $row['sale_date'] . "</td>
            </tr>";
        }
        echo "<tr style='background:#ffffcc;'>
            <td colspan='5'><strong>Grand Total</strong></td>
            <td><strong>" . number_format($grand_total, 2) . "</strong></td>
            <td></td>
        </tr>";
    } else {
        echo "<tr><td colspan='7'>No sales found</td></tr>";
    }
    ?>
</table>

<?php include('../includes/footer.php'); ?>