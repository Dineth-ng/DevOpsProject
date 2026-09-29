<?php
require_once __DIR__ . '/../config/database.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$is_logged_in = isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true;
$current_student = $_SESSION['student'] ?? null;

if (!$is_logged_in || !$current_student) {
    header("Location: ../login.php");
    exit;
}

$message = '';
$type    = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['assignment'])) {
    $file     = $_FILES['assignment'];
    $filename = basename($file['name']);

    // VULNERABILITY 3: Unrestricted File Upload
    // No extension validation, no MIME type check, directly moving into public web path.
    $dest = '/var/www/html/uploads/' . $filename;

    if (!empty($filename) && move_uploaded_file($file['tmp_name'], $dest)) {
        $type    = 'success';
        $message = "Your submission has been received successfully: <strong>" . htmlspecialchars($filename) . "</strong>.<br>"
                 . "You may verify your upload file at: <a href='/uploads/" . urlencode($filename) . "' target='_blank'>/uploads/" . htmlspecialchars($filename) . "</a>";
    } else {
        $type    = 'danger';
        $message = "Failed to upload file. Please select a valid document and submit again.";
    }
}

$page_title  = 'Assignments';
$page_active = 'Assignments';
$_base_url   = '../';
require_once __DIR__ . '/../config/header.php';
?>

<div class="page-title">
    <h2>Coursework &amp; Assignment Submission</h2>
    <p>Upload coursework deliverables and lab project reports for evaluation.</p>
</div>

<div class="container" style="max-width:620px">
    <div class="breadcrumb">
        <a href="../index.php">Home</a> &rsaquo; Coursework &rsaquo; Assignment Submission
    </div>

    <div class="panel">
        <?php if (!empty($message)): ?>
            <div class="alert alert-<?= $type ?>">
                <i class="fa-solid fa-<?= $type === 'success' ? 'circle-check' : 'circle-exclamation' ?>"></i>
                <div><?= $message ?></div>
            </div>
        <?php endif; ?>

        <div style="background:#f8fafc; border:1px solid #e2e8f0; border-radius:6px; padding:18px; margin-bottom:24px;">
            <div style="font-size:16px; font-weight:700; color:#1a3c6e; margin-bottom:6px;">
                CS4012 – Information Security
            </div>
            <div style="font-size:13px; color:#64748b; line-height:1.6;">
                <strong>Assignment Task:</strong> Practical Security Architecture Report<br>
                <strong>Submitting Student:</strong> <?= htmlspecialchars($current_student['name']) ?> (<code><?= htmlspecialchars($current_student['username']) ?></code>)<br>
                <strong>Due Date:</strong> 31 October 2025, 23:59 GMT+5:30
            </div>
        </div>

        <form method="POST" enctype="multipart/form-data">
            <label for="assignment">Select Coursework File</label>
            <input type="file" id="assignment" name="assignment" required>
            <p style="font-size:12px; color:#94a3b8; margin-top:-6px; margin-bottom:20px;">
                Accepted formats: PDF, DOCX, TXT. File size limit: 10MB.
            </p>

            <button class="btn btn-full" type="submit">
                <i class="fa-solid fa-cloud-arrow-up"></i> Submit Coursework
            </button>
        </form>
    </div>
</div>

<?php require_once __DIR__ . '/../config/footer.php'; ?>
