<?php
$root = dirname(__DIR__);
require_once $root . '/manager_check.php';
require_once $root . '/config/db.php';
require_once $root . '/includes/header.php';

$error   = '';
$success = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name    = trim($_POST['name']);
    $phone   = trim($_POST['phone']);
    $password= $_POST['password'];
    $confirm = $_POST['confirm_password'];

    if (empty($name) || empty($phone) || empty($password)) {
        $error = "All fields are required.";
    } elseif ($password !== $confirm) {
        $error = "Passwords do not match.";
    } elseif (strlen($password) < 6) {
        $error = "Password must be at least 6 characters.";
    } else {
        $check = $conn->prepare("SELECT id FROM users WHERE phone = ?");
        $check->bind_param("s", $phone);
        $check->execute();
        if ($check->get_result()->num_rows > 0) {
            $error = "This phone number is already registered.";
        } else {
            $hashed = password_hash($password, PASSWORD_DEFAULT);
            $stmt   = $conn->prepare("INSERT INTO users (name, phone, password, role) VALUES (?, ?, ?, 'admin')");
            $stmt->bind_param("sss", $name, $phone, $hashed);
            if ($stmt->execute()) {
                $success = "Admin '$name' created successfully!";
            } else {
                $error = "Database error: " . $conn->error;
            }
        }
    }
}
?>

<h2>Add New Admin</h2>

<?php if ($error):   echo "<p style='color:red;padding:12px;background:#fef2f2;border-left:4px solid #e05c5c;border-radius:8px;margin-bottom:16px;'>⚠️ $error</p>"; endif; ?>
<?php if ($success): echo "<p style='color:green;'>✅ $success</p>"; endif; ?>

<form method="POST" action="">
    <label>Full Name:</label>
    <input type="text" name="name" placeholder="e.g. John Smith"
           value="<?= isset($_POST['name']) ? htmlspecialchars($_POST['name']) : '' ?>" required>

    <label>Phone Number (User ID):</label>
    <input type="text" name="phone" placeholder="e.g. 01700000000"
           value="<?= isset($_POST['phone']) ? htmlspecialchars($_POST['phone']) : '' ?>" required>

    <label>Password:</label>
    <input type="password" name="password" placeholder="Min 6 characters" required>

    <label>Confirm Password:</label>
    <input type="password" name="confirm_password" placeholder="Repeat password" required>

    <input type="submit" value="Create Admin Account">
</form>
<br><a href="list.php">Back to Admin List</a>

<?php require_once $root . '/includes/footer.php'; ?>