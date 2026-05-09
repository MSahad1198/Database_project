<?php
include('../manager_check.php');
include('../config/db.php');
include('../includes/header.php');

if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    // Prevent deleting self or other managers
    $target = $conn->query("SELECT role FROM users WHERE id=$id")->fetch_assoc();
    if ($target && $target['role'] !== 'manager') {
        $conn->query("DELETE FROM users WHERE id=$id");
    }
    header("Location: list.php");
    exit;
}
?>
<h2>Admin Management</h2>
<a href="../index.php">Back to Home</a> | <a href="add.php">Add New Admin</a>
<br><br>

<table border="1" cellpadding="10">
    <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Phone (User ID)</th>
        <th>Role</th>
        <th>Actions</th>
    </tr>
    <?php
    $result = $conn->query("SELECT * FROM users ORDER BY role DESC, name ASC");
    while ($row = $result->fetch_assoc()):
        $is_manager = $row['role'] === 'manager';
    ?>
    <tr>
        <td><?= $row['id'] ?></td>
        <td><?= $row['name'] ?></td>
        <td><?= $row['phone'] ?></td>
        <td>
            <span style="
                display:inline-block;
                background:<?= $is_manager ? 'rgba(0,194,179,.12)' : 'rgba(59,130,246,.10)' ?>;
                color:<?= $is_manager ? 'var(--teal)' : '#3b82f6' ?>;
                border:1px solid <?= $is_manager ? 'rgba(0,194,179,.3)' : 'rgba(59,130,246,.3)' ?>;
                border-radius:20px; padding:3px 12px;
                font-size:.78rem; font-weight:700;
                text-transform:uppercase; letter-spacing:.05em;">
                <?= $is_manager ? '🛡️ Manager' : '👤 Admin' ?>
            </span>
        </td>
        <td>
            <?php if (!$is_manager): ?>
                <a href="edit.php?id=<?= $row['id'] ?>"
                   style="color:#00c2b3;font-weight:600;text-decoration:none;margin-right:10px;">Edit</a>
                <a href="list.php?delete=<?= $row['id'] ?>"
                   style="color:#e05c5c;font-weight:600;text-decoration:none;"
                   onclick="return confirm('Delete admin <?= $row['name'] ?>?')">Delete</a>
            <?php else: ?>
                <span style="color:var(--grey-text);font-size:.82rem;">Protected</span>
            <?php endif; ?>
        </td>
    </tr>
    <?php endwhile; ?>
</table>

<?php include('../includes/footer.php'); ?>