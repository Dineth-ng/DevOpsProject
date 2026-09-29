<?php
// Database connection config
// Disable strict exception throwing on SQL errors so mysqli_query returns false / can be handled
mysqli_report(MYSQLI_REPORT_OFF);

$conn = @mysqli_connect(
    "db",
    "student",
    "studentpass",
    "university_portal"
);

$db_error = $conn ? '' : 'Database connection failed: ' . mysqli_connect_error();
?>
