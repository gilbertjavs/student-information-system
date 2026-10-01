<?php
// CREATE: add a new student record
require 'config/db.php';

$student = ['student_no'=>'','first_name'=>'','last_name'=>'','gender'=>'','birthdate'=>'','course'=>'','year_level'=>'','email'=>'','contact_no'=>'','address'=>''];
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    [$student, $errors] = validate_student($_POST);

    if (!$errors) {
        // Reject duplicate student numbers
        $chk = $conn->prepare('SELECT id FROM students WHERE student_no = ?');
        $chk->bind_param('s', $student['student_no']);
        $chk->execute();
        if ($chk->get_result()->num_rows > 0) $errors[] = 'That student number already exists.';
        $chk->close();
    }

    if (!$errors) {
        $stmt = $conn->prepare('INSERT INTO students (student_no, first_name, last_name, gender, birthdate, course, year_level, email, contact_no, address) VALUES (?,?,?,?,?,?,?,?,?,?)');
        $stmt->bind_param('ssssssisss',
            $student['student_no'], $student['first_name'], $student['last_name'], $student['gender'],
            $student['birthdate'], $student['course'], $student['year_level'], $student['email'],
            $student['contact_no'], $student['address']);
        $stmt->execute();
        $stmt->close();
        flash('success', 'Student added.');
        header('Location: index.php');
        exit;
    }
}

$page_title = 'Add student';
$submit_label = 'Save student';
include 'includes/header.php';
?>
<div class="page-head"><h1>Add student</h1></div>
<?php include 'includes/form.php'; ?>
<?php include 'includes/footer.php'; ?>
