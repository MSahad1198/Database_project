<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['user_id'])) {
    header("Location: /hospital-system/login.php");
    exit;
}
if ($_SESSION['user_role'] !== 'manager') {
    header("Location: /hospital-system/index.php?error=access_denied");
    exit;
}
?>