<?php
require_once __DIR__ . '/config/database.php';

$page_title  = 'Home';
$page_active = 'Home';
$_base_url   = '';
require_once __DIR__ . '/config/header.php';
?>

<style>
    .hero-banner {
        background: linear-gradient(135deg, #102a4e 0%, #1e467d 100%);
        color: #ffffff;
        padding: 42px 30px;
        border-radius: 8px;
        margin-bottom: 28px;
        box-shadow: 0 4px 12px rgba(16,42,78,.15);
    }
    .hero-banner h2 { font-size: 26px; font-weight: 700; margin-bottom: 8px; }
    .hero-banner p { font-size: 15px; color: #cbd5e1; max-width: 650px; line-height: 1.5; }

    .dashboard-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
        gap: 20px;
        margin-bottom: 28px;
    }
    .portal-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        padding: 24px 20px;
        text-decoration: none;
        color: inherit;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        transition: transform .15s, box-shadow .15s;
        border-top: 4px solid #1a3c6e;
    }
    .portal-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 6px 16px rgba(0,0,0,.08);
    }
    .portal-card.locked {
        opacity: 0.9;
        border-top-color: #94a3b8;
    }
    .card-icon {
        font-size: 28px;
        color: #1a3c6e;
        margin-bottom: 14px;
    }
    .portal-card.locked .card-icon {
        color: #64748b;
    }
    .portal-card h3 {
        font-size: 17px;
        color: #1a3c6e;
        margin-bottom: 6px;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    .portal-card p {
        font-size: 13px;
        color: #64748b;
        line-height: 1.5;
        margin-bottom: 14px;
    }
    .badge-lock {
        font-size: 11px;
        background: #f1f5f9;
        color: #475569;
        padding: 4px 8px;
        border-radius: 4px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }
    .badge-active {
        font-size: 11px;
        background: #dcfce7;
        color: #166534;
        padding: 4px 8px;
        border-radius: 4px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }

    .notices-panel {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        padding: 24px;
    }
    .notices-header {
        font-size: 16px;
        font-weight: 700;
        color: #1a3c6e;
        margin-bottom: 16px;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .notice-item {
        padding: 12px 0;
        border-bottom: 1px solid #f1f5f9;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .notice-item:last-child { border-bottom: none; }
    .notice-title { font-size: 14px; color: #1e293b; font-weight: 500; }
    .notice-date { font-size: 12px; color: #94a3b8; }
</style>

<div class="container">

    <?php if (isset($_GET['logged_out'])): ?>
        <div class="alert alert-info">
            <i class="fa-solid fa-circle-check"></i>
            You have been signed out of the student portal successfully.
        </div>
    <?php endif; ?>

    <?php if ($is_logged_in && $current_student): ?>
        <!-- LOGGED IN VIEW: Student Dashboard -->
        <div class="hero-banner">
            <h2>Welcome back, <?= htmlspecialchars($current_student['name']) ?></h2>
            <p>
                Enrolled in <strong><?= htmlspecialchars($current_student['course'] ?? 'Undergraduate Studies') ?></strong>.
                Student ID: <code><?= htmlspecialchars($current_student['username']) ?></code>.
                Use the cards below to manage your profile, check semester marks, and submit assignments.
            </p>
            <div style="margin-top:18px;">
                <a href="students/view.php" class="btn btn-warning" style="font-size:13px; padding:8px 18px;">
                    <i class="fa-solid fa-chart-column"></i> View My Marks &amp; Grades
                </a>
            </div>
        </div>

        <h3 style="font-size:18px; color:#1a3c6e; margin-bottom:14px; font-weight:700;">Student Services</h3>
        <div class="dashboard-grid">
            <a href="profile.php" class="portal-card">
                <div>
                    <div class="card-icon"><i class="fa-solid fa-id-card"></i></div>
                    <h3>My Profile <span class="badge-active"><i class="fa-solid fa-check"></i> Active</span></h3>
                    <p>View your personal enrollment information, registered email address, and academic standing.</p>
                </div>
                <span class="btn" style="padding:6px 14px; font-size:13px;">
                    Open Profile <i class="fa-solid fa-arrow-right"></i>
                </span>
            </a>

            <a href="students/view.php" class="portal-card">
                <div>
                    <div class="card-icon"><i class="fa-solid fa-chart-column"></i></div>
                    <h3>Student Marks <span class="badge-active"><i class="fa-solid fa-check"></i> Available</span></h3>
                    <p>Access your modular marks, assignment and examination scores, grades, and GPA results.</p>
                </div>
                <span class="btn" style="padding:6px 14px; font-size:13px;">
                    View Marks <i class="fa-solid fa-arrow-right"></i>
                </span>
            </a>

            <a href="students/upload.php" class="portal-card">
                <div>
                    <div class="card-icon"><i class="fa-solid fa-cloud-arrow-up"></i></div>
                    <h3>Assignments <span class="badge-active"><i class="fa-solid fa-check"></i> Open</span></h3>
                    <p>Submit coursework files, upload assignments, and track submission statuses for all active modules.</p>
                </div>
                <span class="btn" style="padding:6px 14px; font-size:13px;">
                    Upload Files <i class="fa-solid fa-arrow-right"></i>
                </span>
            </a>
        </div>

    <?php else: ?>
        <!-- NOT LOGGED IN VIEW: Public University Homepage -->
        <div class="hero-banner">
            <h2>University Student Portal</h2>
            <p>
                Welcome to the student information system. Log in with your university credentials to access your academic profile, view module marks, and submit coursework assignments.
            </p>
            <div style="margin-top: 20px;">
                <a href="login.php" class="btn btn-warning" style="padding: 10px 24px; font-size: 15px;">
                    <i class="fa-solid fa-right-to-bracket"></i> Student Login <i class="fa-solid fa-arrow-right"></i>
                </a>
            </div>
        </div>

        <h3 style="font-size:18px; color:#1a3c6e; margin-bottom:14px; font-weight:700;">Portal Features</h3>
        <div class="dashboard-grid">
            <a href="login.php" class="portal-card locked">
                <div>
                    <div class="card-icon"><i class="fa-solid fa-id-card"></i></div>
                    <h3>Student Profile <span class="badge-lock"><i class="fa-solid fa-lock"></i> Login Required</span></h3>
                    <p>Access and verify student personal records, department details, and contact information.</p>
                </div>
                <span style="font-size:13px; color:#1a3c6e; font-weight:600;">
                    Sign in to view <i class="fa-solid fa-arrow-right"></i>
                </span>
            </a>

            <a href="login.php" class="portal-card locked">
                <div>
                    <div class="card-icon"><i class="fa-solid fa-chart-column"></i></div>
                    <h3>Student Marks <span class="badge-lock"><i class="fa-solid fa-lock"></i> Login Required</span></h3>
                    <p>Review examination marks, coursework components, credit points, and semester GPA reports.</p>
                </div>
                <span style="font-size:13px; color:#1a3c6e; font-weight:600;">
                    Sign in to view <i class="fa-solid fa-arrow-right"></i>
                </span>
            </a>

            <a href="login.php" class="portal-card locked">
                <div>
                    <div class="card-icon"><i class="fa-solid fa-cloud-arrow-up"></i></div>
                    <h3>Assignments <span class="badge-lock"><i class="fa-solid fa-lock"></i> Login Required</span></h3>
                    <p>Online assignment submissions for current semester modules and coursework deliverables.</p>
                </div>
                <span style="font-size:13px; color:#1a3c6e; font-weight:600;">
                    Sign in to view <i class="fa-solid fa-arrow-right"></i>
                </span>
            </a>
        </div>

    <?php endif; ?>

    <!-- Notices board -->
    <div class="notices-panel">
        <div class="notices-header">
            <i class="fa-solid fa-bullhorn" style="color:#1a3c6e;"></i> Campus &amp; Academic Notices
        </div>
        <div class="notice-item">
            <div>
                <div class="notice-title">Semester 1 Examination marks and modular grade reports published</div>
                <div style="font-size:12px; color:#64748b; margin-top:2px;">All registered students can verify their results via the Student Marks section.</div>
            </div>
            <div class="notice-date">Today</div>
        </div>
        <div class="notice-item">
            <div>
                <div class="notice-title">Assignment submissions now active for Information Security (CS4012)</div>
                <div style="font-size:12px; color:#64748b; margin-top:2px;">Submit your project files before the final deadline on 31st October.</div>
            </div>
            <div class="notice-date">2 days ago</div>
        </div>
        <div class="notice-item">
            <div>
                <div class="notice-title">System Maintenance Notice – Faculty IT Infrastructure</div>
                <div style="font-size:12px; color:#64748b; margin-top:2px;">Scheduled portal security audit and server update.</div>
            </div>
            <div class="notice-date">5 days ago</div>
        </div>
    </div>

</div>

<?php require_once __DIR__ . '/config/footer.php'; ?>
