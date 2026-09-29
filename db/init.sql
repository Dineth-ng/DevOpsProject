-- University Student Portal Database Initialization

CREATE TABLE IF NOT EXISTS students (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50),
    password VARCHAR(100),
    name VARCHAR(100),
    email VARCHAR(100),
    course VARCHAR(100)
);

CREATE TABLE IF NOT EXISTS marks (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT,
    module_code VARCHAR(20),
    module_name VARCHAR(100),
    credits INT,
    assignment_score INT,
    exam_score INT,
    final_mark INT,
    grade VARCHAR(5)
);

INSERT INTO students (username, password, name, email, course) VALUES
('student01', 'student123', 'Kamal Perera',  'kamal@example.com', 'Cyber Security'),
('student02', 'student456', 'ineth Silva',   'nimal@example.com', 'Software Engineering'),
('student03', 'student45', 'Dineth Neth',   'dineth@example.com', 'Software Engineering'),
('admin',     'sup3rS3cr3t!', 'Admin User',  'admin@university.edu', 'Administration');

INSERT INTO marks (student_id, module_code, module_name, credits, assignment_score, exam_score, final_mark, grade) VALUES
(1, 'CS4012', 'Information Security', 4, 88, 82, 85, 'A'),
(1, 'CS4020', 'Network Defense & Architecture', 3, 76, 80, 78, 'B+'),
(1, 'CS4035', 'Ethical Hacking & Penetration Testing', 4, 95, 90, 92, 'A+'),
(2, 'SE3010', 'Software Architecture & Design', 4, 72, 75, 74, 'B'),
(2, 'SE3025', 'Data Structures & Algorithms II', 3, 85, 79, 81, 'A-'),
(2, 'SE3040', 'Web Application Security', 4, 65, 70, 68, 'C+'),
(3, 'ADM9001', 'Confidential Staff Evaluation', 4, 100, 98, 99, 'A+'),
(3, 'ADM9002', 'System Administration Audit', 4, 96, 94, 95, 'A+');

