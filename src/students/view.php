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

// VULNERABILITY 2: Insecure Direct Object Reference (IDOR)
// Takes the 'id' parameter directly from user input without verifying session ownership.
$id = isset($_GET['id']) ? intval($_GET['id']) : intval($current_student['id']);

$student = null;
$marks   = [];

$query  = "SELECT * FROM students WHERE id = $id";
$result = mysqli_query($conn, $query);

if ($result && mysqli_num_rows($result) > 0) {
    $student = mysqli_fetch_assoc($result);

    $marks_query = "SELECT * FROM marks WHERE student_id = $id";
    $marks_res   = mysqli_query($conn, $marks_query);
    if ($marks_res) {
        while ($row = mysqli_fetch_assoc($marks_res)) {
            $marks[] = $row;
        }
    }
}

$page_title  = 'Student Marks';
$page_active = 'Marks';
$_base_url   = '../';
require_once __DIR__ . '/../config/header.php';
?>

<div class="page-title">
    <h2>Academic Marks &amp; Grades</h2>
    <p>Official student examination results and coursework modular grade report.</p>
</div>

<div class="container">
    <div class="breadcrumb">
        <a href="../index.php">Home</a> &rsaquo; Academic Records &rsaquo; Modular Marks
    </div>

    <div class="panel">
        <?php if ($student): ?>
            <div style="background:#f8fafc; border:1px solid #e2e8f0; border-radius:6px; padding:18px 20px; margin-bottom:24px; display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:14px;">
                <div>
                    <div style="font-size:18px; font-weight:700; color:#1a3c6e;">
                        <?= htmlspecialchars($student['name']) ?>
                    </div>
                    <div style="font-size:13px; color:#64748b; margin-top:3px;">
                        Degree Programme: <strong><?= htmlspecialchars($student['course']) ?></strong> &nbsp;|&nbsp;
                        Registration: <code><?= htmlspecialchars($student['username']) ?></code>
                    </div>
                </div>
                <div style="text-align:right;">
                    <span style="font-size:12px; color:#64748b; text-transform:uppercase; font-weight:600;">Student Record</span><br>
                    <span style="font-size:18px; font-weight:700; color:#1a3c6e;">#<?= htmlspecialchars($student['id']) ?></span>
                </div>
            </div>

            <h3 style="font-size:16px; color:#1a3c6e; margin-bottom:12px; font-weight:700;">
                Semester 1 Grade Transcript
            </h3>

            <?php if (!empty($marks)): ?>
                <table>
                    <thead>
                        <tr>
                            <th>Module Code</th>
                            <th>Module Name</th>
                            <th style="text-align:center;">Credits</th>
                            <th style="text-align:center;">Coursework</th>
                            <th style="text-align:center;">Exam</th>
                            <th style="text-align:center;">Final Mark</th>
                            <th style="text-align:center;">Grade</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($marks as $m): ?>
                            <tr>
                                <td><strong><?= htmlspecialchars($m['module_code']) ?></strong></td>
                                <td><?= htmlspecialchars($m['module_name']) ?></td>
                                <td style="text-align:center;"><?= htmlspecialchars($m['credits']) ?></td>
                                <td style="text-align:center;"><?= htmlspecialchars($m['assignment_score']) ?>%</td>
                                <td style="text-align:center;"><?= htmlspecialchars($m['exam_score']) ?>%</td>
                                <td style="text-align:center; font-weight:700; color:#1a3c6e;"><?= htmlspecialchars($m['final_mark']) ?>%</td>
                                <td style="text-align:center;">
                                    <span style="background:#e0f2fe; color:#0369a1; padding:3px 10px; border-radius:12px; font-weight:700; font-size:13px;">
                                        <?= htmlspecialchars($m['grade']) ?>
                                    </span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>

                <div style="margin-top:20px; display:flex; justify-content:flex-end;">
                    <button class="btn" onclick="window.print()" style="font-size:13px; background:#475569;">
                        <i class="fa-solid fa-print"></i> Print Statement
                    </button>
                </div>
            <?php else: ?>
                <p style="color:#64748b; font-size:14px; padding:16px 0;">No module marks have been published for this student record yet.</p>
            <?php endif; ?>

        <?php else: ?>
            <div class="alert alert-danger">
                <i class="fa-solid fa-circle-exclamation"></i>
                Record not found for student ID <strong><?= intval($id) ?></strong>.
            </div>
        <?php endif; ?>
    </div>
</div>

<?php require_once __DIR__ . '/../config/footer.php'; ?>
