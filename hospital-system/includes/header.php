<?php
// Start session and enforce login on every page
if (session_status() === PHP_SESSION_NONE) session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: /hospital-system/login.php");
    exit;
}
$is_manager = $_SESSION['user_role'] === 'manager';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MediCore Hospital System</title>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@400;600;700&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --navy:      #0b1f3a;
            --navy-mid:  #122947;
            --teal:      #00c2b3;
            --teal-dim:  #009e91;
            --sky:       #e8f7f6;
            --white:     #ffffff;
            --grey-light:#f4f7fb;
            --grey-mid:  #dce5ef;
            --grey-text: #6b7f96;
            --danger:    #e05c5c;
            --success:   #27ae7a;
            --shadow-sm: 0 2px 8px rgba(11,31,58,.08);
            --shadow-md: 0 6px 24px rgba(11,31,58,.12);
            --shadow-lg: 0 16px 48px rgba(11,31,58,.16);
            --radius:    12px;
            --radius-sm: 7px;
        }
        *, *::before, *::after { box-sizing:border-box; margin:0; padding:0; }
        body {
            font-family: 'DM Sans', sans-serif;
            background: var(--grey-light);
            color: var(--navy);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* ── TOP NAV ── */
        .site-header {
            background: var(--navy);
            padding: 0 32px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            height: 64px;
            box-shadow: var(--shadow-md);
            position: sticky;
            top: 0;
            z-index: 100;
        }
        .header-left { display:flex; align-items:center; gap:28px; }
        .site-logo {
            font-family: 'Cormorant Garamond', serif;
            font-size: 1.55rem; font-weight: 700;
            color: var(--white); letter-spacing: .02em;
            text-decoration: none; white-space: nowrap;
        }
        .site-logo span { color: var(--teal); }
        .nav-links { display:flex; gap:4px; flex-wrap:wrap; }
        .nav-links a {
            color: var(--grey-mid);
            text-decoration: none;
            font-size: .8rem; font-weight: 500;
            padding: 5px 11px;
            border-radius: 20px;
            transition: all .2s;
            white-space: nowrap;
        }
        .nav-links a:hover { background:rgba(0,194,179,.15); color:var(--teal); }
        .nav-links a.manager-link {
            color: var(--teal);
            border: 1px solid rgba(0,194,179,.3);
        }
        .nav-links a.manager-link:hover { background:rgba(0,194,179,.2); }

        /* ── USER BADGE + LOGOUT ── */
        .header-right { display:flex; align-items:center; gap:12px; }
        .user-badge {
            display: flex; align-items: center; gap: 8px;
            background: rgba(255,255,255,.07);
            border: 1px solid rgba(255,255,255,.12);
            border-radius: 50px;
            padding: 5px 14px 5px 8px;
        }
        .user-avatar {
            width: 28px; height: 28px;
            background: var(--teal);
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-size: .75rem; font-weight: 700; color: var(--navy);
        }
        .user-info .user-name {
            color: var(--white); font-size: .8rem; font-weight: 600;
            line-height: 1.2;
        }
        .user-info .user-role {
            color: var(--teal); font-size: .68rem;
            text-transform: uppercase; letter-spacing: .06em;
        }
        .btn-logout {
            background: rgba(224,92,92,.15);
            border: 1px solid rgba(224,92,92,.3);
            color: #e05c5c;
            padding: 5px 14px;
            border-radius: 20px;
            font-family: 'DM Sans', sans-serif;
            font-size: .8rem; font-weight: 600;
            cursor: pointer; text-decoration: none;
            transition: all .2s;
        }
        .btn-logout:hover { background:rgba(224,92,92,.25); }

        /* ── PAGE WRAPPER ── */
        .page-body {
            flex: 1;
            padding: 36px 40px 60px;
            max-width: 1300px;
            width: 100%;
            margin: 0 auto;
            animation: fadeUp .4s ease both;
        }
        @keyframes fadeUp {
            from { opacity:0; transform:translateY(16px); }
            to   { opacity:1; transform:translateY(0); }
        }

        /* ── ACCESS DENIED BANNER ── */
        .access-denied {
            background: #fef2f2; color: #e05c5c;
            border-left: 4px solid #e05c5c;
            padding: 12px 16px; border-radius: 8px;
            margin-bottom: 20px; font-weight: 500;
        }

        h1 { display:none; }
        h2 {
            font-family: 'Cormorant Garamond', serif;
            font-size: 2rem; font-weight: 700;
            color: var(--navy);
            margin-bottom: 24px; padding-bottom: 12px;
            border-bottom: 2px solid var(--teal);
            display: inline-block;
        }
        h3 {
            font-family: 'Cormorant Garamond', serif;
            font-size: 1.3rem; font-weight: 600;
            color: var(--navy-mid);
            margin: 28px 0 14px;
        }
        p > a, .page-body > a {
            display: inline-flex; align-items: center; gap: 5px;
            color: var(--teal-dim); text-decoration: none;
            font-size: .85rem; font-weight: 500;
            margin-bottom: 20px; transition: color .2s;
        }
        p > a:hover, .page-body > a:hover { color: var(--teal); }
        p > a + a, .page-body > a + a { margin-left: 14px; }

        table {
            width: 100%; border-collapse: collapse;
            background: var(--white); border-radius: var(--radius);
            overflow: hidden; box-shadow: var(--shadow-sm); margin-top: 8px;
        }
        thead tr, table > tr:first-child { background: var(--navy); }
        th {
            color: var(--teal); font-size: .78rem; font-weight: 600;
            text-transform: uppercase; letter-spacing: .08em;
            padding: 14px 16px; text-align: left; border: none !important;
        }
        td {
            padding: 13px 16px; font-size: .88rem; color: var(--navy);
            border: none !important;
            border-bottom: 1px solid var(--grey-light) !important;
            vertical-align: middle;
        }
        tbody tr:last-child td { border-bottom: none !important; }
        tbody tr:hover { background: var(--sky); transition: background .15s; }
        tr[style*="background:var(--navy)"] td { border-bottom: none !important; }
        tr[style*="background:#ffffcc"] { background:#e6f9f4 !important; }
        tr[style*="background:#ffffcc"] td { color:var(--success); font-weight:600; }

        form {
            background: var(--white); border-radius: var(--radius);
            box-shadow: var(--shadow-sm); padding: 32px 36px; max-width: 520px;
        }
        label {
            display: block; font-size: .8rem; font-weight: 600;
            text-transform: uppercase; letter-spacing: .07em;
            color: var(--grey-text); margin-bottom: 6px; margin-top: 18px;
        }
        label:first-child { margin-top: 0; }
        input[type="text"], input[type="number"], input[type="date"],
        input[type="password"], select, textarea {
            width: 100%; padding: 10px 14px;
            border: 1.5px solid var(--grey-mid); border-radius: var(--radius-sm);
            font-family: 'DM Sans', sans-serif; font-size: .9rem;
            color: var(--navy); background: var(--grey-light);
            transition: border-color .2s, box-shadow .2s; outline: none;
        }
        input:focus, select:focus, textarea:focus {
            border-color: var(--teal); background: var(--white);
            box-shadow: 0 0 0 3px rgba(0,194,179,.12);
        }
        input[readonly] { background:var(--sky); color:var(--teal-dim); font-weight:600; cursor:default; }
        input[type="submit"] {
            margin-top: 24px; width: 100%; padding: 12px;
            background: var(--teal); color: var(--navy); border: none;
            border-radius: var(--radius-sm);
            font-family: 'DM Sans', sans-serif;
            font-size: .92rem; font-weight: 700; letter-spacing: .04em;
            cursor: pointer; transition: background .2s, transform .15s, box-shadow .2s;
        }
        input[type="submit"]:hover {
            background: var(--teal-dim); transform: translateY(-1px);
            box-shadow: 0 4px 14px rgba(0,194,179,.35);
        }
        p[style*="color:green"] {
            background: #e6f9f1; color: var(--success) !important;
            border-left: 4px solid var(--success);
            padding: 12px 16px; border-radius: var(--radius-sm);
            font-weight: 500; margin-bottom: 16px;
        }
        hr { display: none; }
    </style>
</head>
<body>
<header class="site-header">
    <div class="header-left">
        <a class="site-logo" href="/hospital-system/index.php">Medi<span>Core</span></a>
        <nav class="nav-links">
            <a href="/hospital-system/patients/list.php">Patients</a>
            <a href="/hospital-system/doctors/list.php">Doctors</a>
            <a href="/hospital-system/specializations/list.php">Specs</a>
            <a href="/hospital-system/appointments/list.php">Appointments</a>
            <a href="/hospital-system/bills/list.php">Bills</a>
            <a href="/hospital-system/medications/list.php">Medications</a>
            <a href="/hospital-system/treatments/list.php">Treatments</a>
            <a href="/hospital-system/prescriptions/list.php">Prescriptions</a>
            <a href="/hospital-system/pharmacy/list.php">Pharmacy</a>
            <a href="/hospital-system/operation_bills/list.php">Operations</a>
            <?php if ($is_manager): ?>
            <a href="/hospital-system/admins/list.php" class="manager-link">🛡️ Admins</a>
            <?php endif; ?>
        </nav>
    </div>
    <div class="header-right">
        <div class="user-badge">
            <div class="user-avatar"><?= strtoupper(substr($_SESSION['user_name'], 0, 1)) ?></div>
            <div class="user-info">
                <div class="user-name"><?= htmlspecialchars($_SESSION['user_name']) ?></div>
                <div class="user-role"><?= $is_manager ? '🛡️ Manager' : '👤 Admin' ?></div>
            </div>
        </div>
        <a href="/hospital-system/logout.php" class="btn-logout">Logout</a>
    </div>
</header>
<div class="page-body">
<?php if (isset($_GET['error']) && $_GET['error'] === 'access_denied'): ?>
    <div class="access-denied">⛔ Access denied. This section is for managers only.</div>
<?php endif; ?>