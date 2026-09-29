<?php
require_once __DIR__ . '/config/database.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$is_logged_in = isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true;
$current_student = $_SESSION['student'] ?? null;

if (!$is_logged_in || !$current_student) {
    header("Location: login.php");
    exit;
}

// VULNERABILITY: Insecure Direct Object Reference (IDOR)
// Uses ?id= from URL directly without checking if the ID matches the authenticated session.
$id = isset($_GET['id']) ? intval($_GET['id']) : intval($current_student['id']);

$student = null;
$query   = "SELECT * FROM students WHERE id = $id";
$result  = mysqli_query($conn, $query);

if ($result && mysqli_num_rows($result) > 0) {
    $student = mysqli_fetch_assoc($result);
}

$page_title  = 'Student Profile';
$page_active = 'Profile';
$_base_url   = '';
require_once __DIR__ . '/config/header.php';
?>

<div class="page-title">
    <h2>Student Profile</h2>
    <p>Personal enrollment record, registration credentials, and academic status.</p>
</div>

<div class="container" style="max-width:720px">
    <div class="breadcrumb">
        <a href="index.php">Home</a> &rsaquo; Student Profile
    </div>

    <div class="panel">
        <?php if ($student): ?>
            <div style="display:flex; align-items:center; gap:20px; margin-bottom:24px; padding-bottom:20px; border-bottom:1px solid #e2e8f0;">
                <div style="width:68px; height:68px; background:linear-gradient(135deg, #1a3c6e 0%, #295799 100%); border-radius:50%; display:flex; align-items:center; justify-content:center; color:#fff; font-size:28px; font-weight:700; flex-shrink:0;">
                    <?= strtoupper(substr($student['name'] ?? 'S', 0, 1)) ?>
                </div>
                <div>
                    <h3 style="font-size:20px; color:#1a3c6e; font-weight:700; margin-bottom:4px;">
                        <?= htmlspecialchars($student['name']) ?>
                    </h3>
                    <div style="font-size:13px; color:#64748b;">
                        Programme: <strong><?= htmlspecialchars($student['course']) ?></strong> &nbsp;·&nbsp;
                        Registration: <code><?= htmlspecialchars($student['username']) ?></code>
                    </div>
                </div>
            </div>

            <table>
                <thead>
                    <tr><th style="width:200px">Field</th><th>Record Value</th></tr>
                </thead>
                <tbody>
                    <tr><td>Student ID</td>     <td><code>#<?= htmlspecialchars($student['id']) ?></code></td></tr>
                    <tr><td>Username / Reg No</td><td><?= htmlspecialchars($student['username']) ?></td></tr>
                    <tr><td>Full Name</td>       <td><?= htmlspecialchars($student['name']) ?></td></tr>
                    <tr><td>University Email</td><td><?= htmlspecialchars($student['email']) ?></td></tr>
                    <tr><td>Academic Faculty</td><td>Faculty of Computing</td></tr>
                    <tr><td>Degree Course</td>   <td><?= htmlspecialchars($student['course']) ?></td></tr>
                    <tr><td>Status</td>          <td><span style="background:#dcfce7; color:#166534; padding:3px 10px; border-radius:12px; font-size:12px; font-weight:700;"><i class="fa-solid fa-check"></i> Enrolled Active</span></td></tr>
                    <tr><td>Academic Term</td>   <td>Academic Year 2025/2026 – Semester 1</td></tr>
                </tbody>
            </table>

            <div style="margin-top:24px; display:flex; gap:12px;">
                <a href="students/view.php?id=<?= $student['id'] ?>" class="btn">
                    <i class="fa-solid fa-chart-column"></i> View Academic Marks
                </a>
                <a href="students/upload.php" class="btn" style="background:#475569;">
                    <i class="fa-solid fa-file-arrow-up"></i> Assignment Submissions
                </a>
            </div>
        <?php else: ?>
            <div class="alert alert-danger">
                <i class="fa-solid fa-circle-exclamation"></i>
                Student profile record not found for ID <strong>#<?= intval($id) ?></strong>.
            </div>
        <?php endif; ?>
    </div>
</div>

<?php require_once __DIR__ . '/config/footer.php'; ?>
