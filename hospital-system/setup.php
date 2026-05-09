<?php
include('config/db.php');

// First delete any broken manager account
$conn->query("DELETE FROM users WHERE phone = '01000000000'");

// Insert with correctly hashed password
$name     = 'Manager';
$phone    = '01000000000';
$password = 'manager123';
$hashed   = password_hash($password, PASSWORD_DEFAULT);
$role     = 'manager';

$stmt = $conn->prepare("INSERT INTO users (name, phone, password, role) VALUES (?, ?, ?, ?)");
$stmt->bind_param("ssss", $name, $phone, $hashed, $role);

if ($stmt->execute()) {
    echo "<h2 style='color:green;font-family:sans-serif;'>✅ Manager account created successfully!</h2>";
    echo "<p style='font-family:sans-serif;'>Phone: <strong>01000000000</strong><br>Password: <strong>manager123</strong></p>";
    echo "<br><a href='login.php' style='font-family:sans-serif;'>→ Go to Login</a>";
    echo "<br><br><strong style='color:red;font-family:sans-serif;'>⚠️ Delete this file (setup.php) now!</strong>";
} else {
    echo "<h2 style='color:red;font-family:sans-serif;'>❌ Error: " . $conn->error . "</h2>";
}
?>