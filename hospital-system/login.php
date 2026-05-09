<?php
session_start();
if (isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

include('config/db.php');
$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $phone    = trim($_POST['phone']);
    $password = $_POST['password'];
    $role     = $_POST['role']; // 'manager' or 'admin'

    $stmt = $conn->prepare("SELECT * FROM users WHERE phone = ? AND role = ?");
    $stmt->bind_param("ss", $phone, $role);
    $stmt->execute();
    $result = $stmt->get_result();
    $user   = $result->fetch_assoc();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id']    = $user['id'];
        $_SESSION['user_name']  = $user['name'];
        $_SESSION['user_role']  = $user['role'];
        $_SESSION['user_phone'] = $user['phone'];
        header("Location: index.php");
        exit;
    } else {
        $error = "Invalid phone number or password for the selected role.";
    }
}

$selected_role = $_POST['role'] ?? 'admin';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MediCore — Login</title>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@400;600;700&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --navy:     #0b1f3a;
            --teal:     #00c2b3;
            --teal-dim: #009e91;
            --white:    #ffffff;
            --grey-light:#f4f7fb;
            --grey-mid: #dce5ef;
            --grey-text:#6b7f96;
            --danger:   #e05c5c;
            --radius:   12px;
        }
        *, *::before, *::after { box-sizing:border-box; margin:0; padding:0; }
        body {
            font-family: 'DM Sans', sans-serif;
            background: var(--grey-light);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .login-wrap {
            display: flex;
            width: 880px;
            min-height: 520px;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 24px 64px rgba(11,31,58,.18);
        }

        /* ── LEFT PANEL ── */
        .login-left {
            background: linear-gradient(145deg, var(--navy) 0%, #163a5f 100%);
            width: 42%;
            padding: 52px 40px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            position: relative;
            overflow: hidden;
        }
        .login-left::before {
            content: '+';
            position: absolute; right: -20px; bottom: -60px;
            font-size: 260px; font-weight: 700;
            color: rgba(0,194,179,.06); line-height:1; pointer-events:none;
        }
        .brand-logo {
            font-family: 'Cormorant Garamond', serif;
            font-size: 2rem; font-weight: 700;
            color: var(--white); z-index:1;
        }
        .brand-logo span { color: var(--teal); }
        .brand-tagline { color:rgba(255,255,255,.4); font-size:.82rem; margin-top:6px; z-index:1; }

        .role-cards { display:flex; flex-direction:column; gap:12px; z-index:1; }
        .role-card {
            display: flex; align-items: center; gap: 12px;
            background: rgba(255,255,255,.06);
            border: 1px solid rgba(255,255,255,.1);
            border-radius: 10px; padding: 12px 16px;
            cursor: pointer;
            transition: all .25s;
            user-select: none;
        }
        .role-card:hover,
        .role-card.active {
            background: rgba(0,194,179,.15);
            border-color: rgba(0,194,179,.4);
        }
        .role-card .rc-icon { font-size: 1.5rem; }
        .role-card .rc-title {
            color: var(--white); font-size: .85rem; font-weight: 700;
        }
        .role-card .rc-desc {
            color: rgba(255,255,255,.4); font-size: .75rem; margin-top:2px;
        }
        .role-card .rc-check {
            margin-left: auto;
            width: 20px; height: 20px;
            border-radius: 50%;
            border: 2px solid rgba(255,255,255,.2);
            display: flex; align-items:center; justify-content:center;
            transition: all .2s;
            flex-shrink: 0;
        }
        .role-card.active .rc-check {
            background: var(--teal);
            border-color: var(--teal);
        }
        .role-card.active .rc-check::after {
            content: '✓';
            color: var(--navy);
            font-size: .7rem; font-weight: 700;
        }

        /* ── RIGHT PANEL ── */
        .login-right {
            background: var(--white);
            flex: 1;
            padding: 52px 44px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .role-indicator {
            display: inline-flex; align-items: center; gap: 8px;
            background: var(--grey-light);
            border-radius: 50px; padding: 6px 14px;
            margin-bottom: 20px;
            font-size: .78rem; font-weight: 700;
            text-transform: uppercase; letter-spacing: .07em;
            transition: all .3s;
        }
        .role-indicator.manager { background:rgba(0,194,179,.12); color:var(--teal); }
        .role-indicator.admin   { background:rgba(59,130,246,.10); color:#3b82f6; }

        .login-right h2 {
            font-family: 'Cormorant Garamond', serif;
            font-size: 1.9rem; font-weight: 700;
            color: var(--navy); margin-bottom: 6px;
        }
        .subtitle { color:var(--grey-text); font-size:.85rem; margin-bottom:28px; }

        .field { margin-bottom: 16px; }
        .field label {
            display: block; font-size:.75rem; font-weight:700;
            text-transform:uppercase; letter-spacing:.07em;
            color:var(--grey-text); margin-bottom:7px;
        }
        .field input {
            width: 100%; padding: 11px 15px;
            border: 1.5px solid var(--grey-mid);
            border-radius: 8px;
            font-family: 'DM Sans', sans-serif; font-size:.92rem;
            color: var(--navy); background: var(--grey-light);
            outline: none; transition: border-color .2s, box-shadow .2s;
        }
        .field input:focus {
            border-color: var(--teal); background: var(--white);
            box-shadow: 0 0 0 3px rgba(0,194,179,.12);
        }
        .error-msg {
            background:#fef2f2; color:var(--danger);
            border-left:4px solid var(--danger);
            padding:11px 14px; border-radius:8px;
            font-size:.85rem; font-weight:500; margin-bottom:16px;
        }
        .btn-login {
            width: 100%; padding: 13px;
            background: var(--teal); color: var(--navy);
            border: none; border-radius: 8px;
            font-family: 'DM Sans', sans-serif;
            font-size:.95rem; font-weight:700; letter-spacing:.04em;
            cursor: pointer; margin-top: 8px;
            transition: background .2s, transform .15s, box-shadow .2s;
        }
        .btn-login:hover {
            background: var(--teal-dim);
            transform: translateY(-1px);
            box-shadow: 0 4px 14px rgba(0,194,179,.35);
        }
        .btn-login.admin-btn { background: #3b82f6; color: var(--white); }
        .btn-login.admin-btn:hover {
            background: #2563eb;
            box-shadow: 0 4px 14px rgba(59,130,246,.35);
        }
    </style>
</head>
<body>
<div class="login-wrap">

    <!-- LEFT: role selector -->
    <div class="login-left">
        <div>
            <div class="brand-logo">Medi<span>Core</span></div>
            <div class="brand-tagline">Hospital Management System</div>
        </div>

        <div>
            <p style="color:rgba(255,255,255,.4);font-size:.8rem;margin-bottom:14px;z-index:1;position:relative;">
                Select your role to continue:
            </p>
            <div class="role-cards">
                <div class="role-card <?= $selected_role === 'manager' ? 'active' : '' ?>"
                     onclick="selectRole('manager')">
                    <div class="rc-icon">🛡️</div>
                    <div>
                        <div class="rc-title">Manager</div>
                        <div class="rc-desc">Full access · Manage admins</div>
                    </div>
                    <div class="rc-check"></div>
                </div>
                <div class="role-card <?= $selected_role === 'admin' ? 'active' : '' ?>"
                     onclick="selectRole('admin')">
                    <div class="rc-icon">👤</div>
                    <div>
                        <div class="rc-title">Admin</div>
                        <div class="rc-desc">Hospital data management</div>
                    </div>
                    <div class="rc-check"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- RIGHT: credentials -->
    <div class="login-right">

        <div class="role-indicator <?= $selected_role ?>" id="roleIndicator">
            <span id="roleIcon"><?= $selected_role === 'manager' ? '🛡️' : '👤' ?></span>
            <span id="roleLabel"><?= $selected_role === 'manager' ? 'Manager Login' : 'Admin Login' ?></span>
        </div>

        <h2 id="loginTitle">
            <?= $selected_role === 'manager' ? 'Manager Access' : 'Admin Access' ?>
        </h2>
        <p class="subtitle">Enter your phone number and password</p>

        <?php if ($error): ?>
            <div class="error-msg">⚠️ <?= $error ?></div>
        <?php endif; ?>

        <form method="POST" action="" id="loginForm">
            <!-- Hidden role field submitted with form -->
            <input type="hidden" name="role" id="roleInput" value="<?= $selected_role ?>">

            <div class="field">
                <label>Phone Number (User ID)</label>
                <input type="text" name="phone" placeholder="e.g. 01700000000"
                       value="<?= isset($_POST['phone']) ? htmlspecialchars($_POST['phone']) : '' ?>" required>
            </div>
            <div class="field">
                <label>Password</label>
                <input type="password" name="password" placeholder="••••••••" required>
            </div>
            <button type="submit" class="btn-login <?= $selected_role === 'admin' ? 'admin-btn' : '' ?>"
                    id="loginBtn">
                Sign in as <?= $selected_role === 'manager' ? 'Manager' : 'Admin' ?> →
            </button>
        </form>
    </div>
</div>

<script>
function selectRole(role) {
    // Update hidden input
    document.getElementById('roleInput').value = role;

    // Update card active states
    document.querySelectorAll('.role-card').forEach(c => c.classList.remove('active'));
    event.currentTarget.classList.add('active');

    // Update right panel
    const isManager = role === 'manager';

    const indicator = document.getElementById('roleIndicator');
    indicator.className = 'role-indicator ' + role;
    document.getElementById('roleIcon').textContent  = isManager ? '🛡️' : '👤';
    document.getElementById('roleLabel').textContent = isManager ? 'Manager Login' : 'Admin Login';

    document.getElementById('loginTitle').textContent = isManager ? 'Manager Access' : 'Admin Access';

    const btn = document.getElementById('loginBtn');
    btn.textContent = 'Sign in as ' + (isManager ? 'Manager' : 'Admin') + ' →';
    btn.className   = 'btn-login' + (isManager ? '' : ' admin-btn');
}
</script>
</body>
</html>