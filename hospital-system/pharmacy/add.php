<?php
include('../config/db.php');
include('../includes/header.php');

// Fetch medicine unit price via AJAX-style hidden field update
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $patient_id    = $_POST['patient_id'];
    $medication_id = $_POST['medication_id'];
    $quantity      = $_POST['quantity'];
    $sale_date     = $_POST['sale_date'];

    // Get unit price from medications table
    $price_res = $conn->query("SELECT price FROM medications WHERE id = $medication_id");
    $price_row = $price_res->fetch_assoc();
    $total_price = $price_row['price'] * $quantity;

    $sql = "INSERT INTO pharmacy_sales (patient_id, medication_id, quantity, sale_date, total_price)
            VALUES ($patient_id, $medication_id, $quantity, '$sale_date', $total_price)";

    if ($conn->query($sql) === TRUE) {
        echo "<p style='color:green;'>Sale recorded! Total: $" . number_format($total_price, 2) . "</p>";
    } else {
        echo "Error: " . $conn->error;
    }
}
?>
<h2>Pharmacy - Sell Medicine</h2>
<form method="POST" action="">

    <label>Patient:</label><br>
    <select name="patient_id" required>
        <option value="">Select Patient</option>
        <?php
        $result = $conn->query("SELECT * FROM patients");
        while ($row = $result->fetch_assoc()) {
            echo "<option value='" . $row['id'] . "'>" . $row['name'] . "</option>";
        }
        ?>
    </select><br><br>

    <label>Medication:</label><br>
    <select name="medication_id" id="medication_id" required onchange="updatePrice(this)">
        <option value="">Select Medication</option>
        <?php
        $result = $conn->query("SELECT * FROM medications");
        $med_prices = [];
        while ($row = $result->fetch_assoc()) {
            $med_prices[$row['id']] = $row['price'];
            echo "<option value='" . $row['id'] . "' data-price='" . $row['price'] . "'>"
                . $row['name'] . " (Unit: $" . number_format($row['price'], 2) . ")"
                . "</option>";
        }
        ?>
    </select><br><br>

    <label>Quantity:</label><br>
    <input type="number" name="quantity" id="quantity" min="1" value="1" required
           oninput="updateTotal()"><br><br>

    <label>Sale Date:</label><br>
    <input type="date" name="sale_date" value="<?php echo date('Y-m-d'); ?>" required><br><br>

    <label>Unit Price ($):</label><br>
    <input type="text" id="unit_price" value="0.00" readonly
           style="background:#f0f0f0; width:120px;"><br><br>

    <label>Total Price ($):</label><br>
    <input type="text" id="total_display" value="0.00" readonly
           style="background:#f0f0f0; font-weight:bold; font-size:16px; width:120px;"><br><br>

    <input type="submit" value="Confirm Sale">
</form>
<br><a href="list.php">Back to Pharmacy Sales List</a>

<script>
function updatePrice(select) {
    const price = select.options[select.selectedIndex].getAttribute('data-price') || 0;
    document.getElementById('unit_price').value = parseFloat(price).toFixed(2);
    updateTotal();
}
function updateTotal() {
    const price = parseFloat(document.getElementById('unit_price').value) || 0;
    const qty   = parseInt(document.getElementById('quantity').value) || 0;
    document.getElementById('total_display').value = (price * qty).toFixed(2);
}
</script>

<?php include('../includes/footer.php'); ?>