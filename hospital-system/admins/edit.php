<?php
$root = dirname(__DIR__);
require_once $root . '/manager_check.php';
require_once $root . '/config/db.php';
require_once $root . '/includes/header.php';

$id    = intval($_GET['id']);
$error = '';

$target = $conn->query("SELECT * FROM users WHERE id=$id")->fetch_assoc();

if (!$target || $target['role'] === 'manager') {
    echo "<p style='color:red;'>⛔ Access denied. Cannot edit a manager account.</p>";
    require_once $root . '/includes/footer.php';
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name  = trim($_POST['name']);
    $phone = trim($_POST['phone']);
    $pw    = $_POST['password'];
    $conf  = $_POST['confirm_password'];

    if (empty($name) || empty($phone)) {
        $error = "Name and phone are required.";
    } elseif (!empty($pw) && strlen($pw) < 6) {
        $error = "Password must be at least 6 characters.";
    } elseif (!empty($pw) && $pw !== $conf) {
        $error = "Passwords do not match.";
    } else {
        if (!empty($pw)) {
            $hashed = password_hash($pw, PASSWORD_DEFAULT);
            $stmt   = $conn->prepare("UPDATE users SET name=?, phone=?, password=? WHERE id=?");
            $stmt->bind_param("sssi", $name, $phone, $hashed, $id);
        } else {
            $stmt = $conn->prepare("UPDATE users SET name=?, phone=? WHERE id=?");
            $stmt->bind_param("ssi", $name, $phone, $id);
        }
        $stmt->execute();
        echo "<p style='color:green;'>✅ Admin updated successfully!</p>";
        $target = $conn->query("SELECT * FROM users WHERE id=$id")->fetch_assoc();
    }

    if ($error) {
        echo "<p style='color:red;padding:12px;background:#fef2f2;border-left:4px solid #e05c5c;border-radius:8px;margin-bottom:16px;'>⚠️ $error</p>";
    }
}
?>

<h2>Edit Admin</h2>
<form method="POST" action="">
    <label>Full Name:</label>
    <input type="text" name="name" value="<?= htmlspecialchars($target['name']) ?>" required>

    <label>Phone Number (User ID):</label>
    <input type="text" name="phone" value="<?= htmlspecialchars($target['phone']) ?>" required>

    <label>New Password <span style="color:var(--grey-text);font-weight:400;text-transform:none;">(leave blank to keep current)</span>:</label>
    <input type="password" name="password" placeholder="Min 6 characters">

    <label>Confirm New Password:</label>
    <input type="password" name="confirm_password" placeholder="Repeat new password">

    <input type="submit" value="Update Admin">
</form>
<br><a href="list.php">Back to Admin List</a>

<?php require_once $root . '/includes/footer.php'; ?>