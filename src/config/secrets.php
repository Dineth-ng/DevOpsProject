<?php
// VULNERABILITY 4: Sensitive Information Disclosure
// Production system credentials and secrets accidentally placed in public web root.

$config = [
    'ENVIRONMENT'      => 'production',
    'DB_HOST'          => 'db',
    'DB_DATABASE'      => 'university_portal',
    'DB_USERNAME'      => 'student',
    'DB_PASSWORD'      => 'studentpass',
    'DB_ROOT_PASSWORD' => 'root',
    'ADMIN_USER'       => 'admin',
    'ADMIN_PASSWORD'   => 'sup3rS3cr3t!',
    'JWT_SECRET'       => 'un1v3rs1ty_s3cr3t_jwt_k3y_987654321',
    'SMTP_HOST'        => 'smtp.university.edu',
    'SMTP_PORT'        => 587,
    'SMTP_USER'        => 'noreply@university.edu',
    'SMTP_PASS'        => 'Un1_M41l_P@ssw0rd!#',
    'AWS_S3_KEY'       => 'AKIAIOSFODNN7EXAMPLE',
    'AWS_S3_SECRET'    => 'wJalrXUtnFEMI/K7MDENG/bPxRfiCYEXAMPLEKEY',
];

header('Content-Type: text/plain; charset=UTF-8');
echo "=== University Portal Internal Server Configuration ===\n";
echo "CONFIDENTIAL: For University IT Infrastructure Staff Only\n\n";

foreach ($config as $key => $val) {
    printf("%-20s : %s\n", $key, $val);
}

echo "\nPHP Version: " . phpversion() . "\n";
echo "Server Software: " . ($_SERVER['SERVER_SOFTWARE'] ?? 'Apache/2.4') . "\n";
?>
