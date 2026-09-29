<?php
require_once __DIR__ . '/config/database.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// If already logged in, redirect to dashboard
if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
    header("Location: index.php");
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    // VULNERABILITY 1: SQL Injection (Unsanitized direct string concatenation)
    $query = "SELECT * FROM students WHERE username='$username' AND password='$password'";

    try {
        $result = mysqli_query($conn, $query);

        if ($result && mysqli_num_rows($result) > 0) {
            $student = mysqli_fetch_assoc($result);

            $_SESSION['logged_in'] = true;
            $_SESSION['student']   = [
                'id'       => $student['id'],
                'username' => $student['username'],
                'name'     => $student['name'],
                'email'    => $student['email'],
                'course'   => $student['course'],
            ];

            header("Location: index.php");
            exit;
        } elseif (!$result) {
            // Error-based SQL Injection display
            $error = "Database Error: " . mysqli_error($conn);
        } else {
            $error = "Invalid Student ID or password. Please verify your credentials and try again.";
        }
    } catch (Throwable $e) {
        // Fallback catch for any uncaught driver exceptions in PHP 8.2+
        $error = "Database Error: " . $e->getMessage();
    }
}

$page_title  = 'Student Login';
$page_active = 'Login';
$_base_url   = '';
require_once __DIR__ . '/config/header.php';
?>

<div class="page-title">
    <h2>Student Sign In</h2>
    <p>Please enter your university credentials to access the student portal.</p>
</div>

<div class="container" style="max-width:440px">
    <div class="breadcrumb">
        <a href="index.php">Home</a> &rsaquo; Sign In
    </div>

    <div class="panel">
        <?php if ($error): ?>
            <div class="alert alert-danger">
                <i class="fa-solid fa-circle-exclamation"></i>
                <div style="word-break:break-word;"><?= htmlspecialchars($error) ?></div>
            </div>
        <?php endif; ?>

        <form method="POST" autocomplete="off">
            <label for="username">Student ID / Registration No.</label>
            <input type="text" id="username" name="username"
                   placeholder="e.g. student01" required>

            <label for="password">Password</label>
            <input type="password" id="password" name="password"
                   placeholder="Password" required>

            <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px; font-size:13px; color:#64748b;">
                <label style="margin:0; font-weight:normal; cursor:pointer;">
                    <input type="checkbox" name="remember" style="vertical-align:middle; margin-right:4px;"> Remember me
                </label>
                <a href="#" style="color:#1a3c6e; text-decoration:none;">Forgot password?</a>
            </div>

            <button class="btn btn-full" type="submit">
                <i class="fa-solid fa-right-to-bracket"></i> Sign In
            </button>
        </form>

        <div style="margin-top:20px; padding-top:16px; border-top:1px solid #e2e8f0; font-size:12px; color:#64748b; text-align:center;">
            Need help signing in? Contact IT Services at <strong>support@university.edu</strong>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/config/footer.php'; ?>
