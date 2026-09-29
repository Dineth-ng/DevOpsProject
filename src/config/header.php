<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$is_logged_in = isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true;
$current_student = $_SESSION['student'] ?? null;
$_base = $_base_url ?? '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($page_title ?? 'UniPortal') ?> – University Student Portal</title>
    <!-- Web Icons (Font Awesome) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        * { margin:0; padding:0; box-sizing:border-box; }
        body {
            font-family: 'Segoe UI', -apple-system, BlinkMacSystemFont, Arial, sans-serif;
            background: #f0f2f5;
            color: #2c3e50;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* Top utility bar */
        .topbar {
            background: #102a4e;
            color: #b0c7e8;
            font-size: 12px;
            padding: 6px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .topbar a { color: #d6e4ff; text-decoration: none; }
        .topbar a:hover { text-decoration: underline; }

        /* Main header */
        header {
            background: #ffffff;
            border-bottom: 3px solid #1a3c6e;
            padding: 14px 30px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: 0 2px 6px rgba(0,0,0,.06);
        }
        .brand {
            display: flex;
            align-items: center;
            gap: 16px;
            text-decoration: none;
        }
        .logo-circle {
            width: 48px;
            height: 48px;
            background: linear-gradient(135deg, #1a3c6e 0%, #295799 100%);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-size: 20px;
            box-shadow: 0 2px 5px rgba(26,60,110,.3);
        }
        .brand-text h1 {
            font-size: 20px;
            color: #1a3c6e;
            font-weight: 700;
            line-height: 1.2;
        }
        .brand-text p {
            font-size: 12px;
            color: #64748b;
        }
        .user-status {
            font-size: 13px;
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .user-badge {
            background: #e6f0fa;
            color: #1a3c6e;
            padding: 6px 12px;
            border-radius: 20px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        /* Navigation bar */
        nav {
            background: #1a3c6e;
            padding: 0 30px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .nav-links {
            display: flex;
            align-items: center;
        }
        nav a {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            color: #ccd9ee;
            text-decoration: none;
            padding: 12px 18px;
            font-size: 14px;
            font-weight: 500;
            transition: all .2s;
        }
        nav a:hover, nav a.active {
            background: #275291;
            color: #ffffff;
        }
        .nav-right {
            display: flex;
            align-items: center;
        }
        .btn-nav-login {
            background: #f59e0b;
            color: #111827 !important;
            font-weight: 600 !important;
            border-radius: 4px;
            padding: 6px 14px !important;
            margin: 6px 0;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }
        .btn-nav-login:hover {
            background: #d97706 !important;
            color: #fff !important;
        }

        /* Page title bar */
        .page-title {
            background: #ffffff;
            border-bottom: 1px solid #e2e8f0;
            padding: 18px 30px;
        }
        .page-title h2 { font-size: 20px; color: #1a3c6e; font-weight: 700; }
        .page-title p { font-size: 13px; color: #64748b; margin-top: 3px; }

        /* Content containers */
        .page-body { flex: 1; }
        .container {
            max-width: 960px;
            margin: 28px auto;
            padding: 0 20px;
        }

        /* Panels */
        .panel {
            background: #ffffff;
            border-radius: 8px;
            padding: 28px 32px;
            box-shadow: 0 1px 4px rgba(0,0,0,.08);
            border: 1px solid #e2e8f0;
        }

        /* Form elements */
        label { display: block; font-size: 13px; font-weight: 600; color: #475569; margin-bottom: 6px; }
        input[type=text], input[type=password] {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid #cbd5e1;
            border-radius: 6px;
            font-size: 14px;
            margin-bottom: 18px;
            outline: none;
            transition: all .2s;
        }
        input[type=text]:focus, input[type=password]:focus {
            border-color: #1a3c6e;
            box-shadow: 0 0 0 3px rgba(26,60,110,.12);
        }
        input[type=file] { margin-bottom: 10px; font-size: 14px; }
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 10px 22px;
            background: #1a3c6e;
            color: #fff;
            border: none;
            border-radius: 6px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            transition: background .2s;
            text-align: center;
        }
        .btn:hover { background: #275291; }
        .btn-full { width: 100%; }
        .btn-warning { background: #f59e0b; color: #111; }
        .btn-warning:hover { background: #d97706; color: #fff; }

        /* Alerts */
        .alert {
            padding: 12px 16px;
            border-radius: 6px;
            font-size: 14px;
            margin-bottom: 18px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .alert-danger { background: #fee2e2; border: 1px solid #fca5a5; color: #991b1b; }
        .alert-success { background: #dcfce7; border: 1px solid #86efac; color: #166534; }
        .alert-info { background: #e0f2fe; border: 1px solid #7dd3fc; color: #075985; }

        /* Tables */
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { padding: 12px 14px; text-align: left; font-size: 14px; border-bottom: 1px solid #e2e8f0; }
        th { background: #f8fafc; color: #1e293b; font-weight: 600; }
        tr:hover td { background: #f8fafc; }

        /* Breadcrumbs */
        .breadcrumb { font-size: 12px; color: #64748b; margin-bottom: 16px; }
        .breadcrumb a { color: #1a3c6e; text-decoration: none; }
        .breadcrumb a:hover { text-decoration: underline; }

        /* Footer */
        footer {
            background: #102a4e;
            color: #8da4c4;
            text-align: center;
            font-size: 12px;
            padding: 20px;
            margin-top: auto;
        }
    </style>
</head>
<body>

<div class="topbar">
    <span><i class="fa-solid fa-calendar-days"></i> Academic Year 2025/2026 &nbsp;|&nbsp; Semester 1 &nbsp;|&nbsp; Faculty of Computing</span>
    <div>
        <?php if ($is_logged_in && $current_student): ?>
            <span>Signed in as: <strong><?= htmlspecialchars($current_student['name']) ?></strong> (<?= htmlspecialchars($current_student['username']) ?>)</span>
            &nbsp;|&nbsp;
            <a href="<?= $_base ?>logout.php"><i class="fa-solid fa-right-from-bracket"></i> Sign Out</a>
        <?php else: ?>
            <span>Guest Access &nbsp;|&nbsp; <a href="<?= $_base ?>login.php"><i class="fa-solid fa-right-to-bracket"></i> Student Sign In</a></span>
        <?php endif; ?>
    </div>
</div>

<header>
    <a href="<?= $_base ?>index.php" class="brand">
        <div class="logo-circle">
            <i class="fa-solid fa-building-columns"></i>
        </div>
        <div class="brand-text">
            <h1>UniPortal</h1>
            <p>Student Information &amp; Learning Management System</p>
        </div>
    </a>
    <div class="user-status">
        <?php if ($is_logged_in && $current_student): ?>
            <span class="user-badge">
                <i class="fa-solid fa-graduation-cap"></i> <?= htmlspecialchars($current_student['course'] ?? 'Student') ?>
            </span>
        <?php else: ?>
            <a href="<?= $_base ?>login.php" class="btn btn-warning" style="font-size:13px; padding:7px 14px;">
                <i class="fa-solid fa-right-to-bracket"></i> Student Login
            </a>
        <?php endif; ?>
    </div>
</header>

<nav>
    <div class="nav-links">
        <a href="<?= $_base ?>index.php" class="<?= (($page_active ?? '') === 'Home') ? 'active' : '' ?>">
            <i class="fa-solid fa-house"></i> Home
        </a>

        <?php if ($is_logged_in): ?>
            <a href="<?= $_base ?>profile.php" class="<?= (($page_active ?? '') === 'Profile') ? 'active' : '' ?>">
                <i class="fa-solid fa-user"></i> My Profile
            </a>
            <a href="<?= $_base ?>students/view.php" class="<?= (($page_active ?? '') === 'Marks') ? 'active' : '' ?>">
                <i class="fa-solid fa-chart-column"></i> Student Marks
            </a>
            <a href="<?= $_base ?>students/upload.php" class="<?= (($page_active ?? '') === 'Assignments') ? 'active' : '' ?>">
                <i class="fa-solid fa-file-arrow-up"></i> Assignments
            </a>
        <?php else: ?>
            <a href="<?= $_base ?>login.php" class="<?= (($page_active ?? '') === 'Login') ? 'active' : '' ?>">
                <i class="fa-solid fa-right-to-bracket"></i> Login
            </a>
        <?php endif; ?>
    </div>
    
    <div class="nav-right">
        <?php if ($is_logged_in): ?>
            <a href="<?= $_base ?>logout.php" style="color:#fca5a5; font-size:13px;">
                <i class="fa-solid fa-right-from-bracket"></i> Logout
            </a>
        <?php else: ?>
            <a href="<?= $_base ?>login.php" class="btn-nav-login">
                Portal Login <i class="fa-solid fa-arrow-right"></i>
            </a>
        <?php endif; ?>
    </div>
</nav>

<div class="page-body">
