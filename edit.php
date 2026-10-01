<?php
// UPDATE: edit an existing student record
require 'config/db.php';

$id = (int)($_GET['id'] ?? 0);
$stmt = $conn->prepare('SELECT * FROM students WHERE id = ?');
$stmt->bind_param('i', $id);
$stmt->execute();
$existing = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$existing) {
    flash('error', 'Student not found.');
    header('Location: index.php');
    exit;
}

$student = $existing;
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    [$student, $errors] = validate_student($_POST);

    if (!$errors) {
        // Student number must stay unique (ignore this record itself)
        $chk = $conn->prepare('SELECT id FROM students WHERE student_no = ? AND id <> ?');
        $chk->bind_param('si', $student['student_no'], $id);
        $chk->execute();
        if ($chk->get_result()->num_rows > 0) $errors[] = 'That student number belongs to another student.';
        $chk->close();
    }

    if (!$errors) {
        $stmt = $conn->prepare('UPDATE students SET student_no=?, first_name=?, last_name=?, gender=?, birthdate=?, course=?, year_level=?, email=?, contact_no=?, address=? WHERE id=?');
        $stmt->bind_param('ssssssisssi',
            $student['student_no'], $student['first_name'], $student['last_name'], $student['gender'],
            $student['birthdate'], $student['course'], $student['year_level'], $student['email'],
            $student['contact_no'], $student['address'], $id);
        $stmt->execute();
        $stmt->close();
        flash('success', 'Changes saved.');
        header('Location: index.php');
        exit;
    }
}

$page_title = 'Edit student';
$submit_label = 'Save changes';
include 'includes/header.php';
?>
<div class="page-head"><h1>Edit student</h1></div>
<?php include 'includes/form.php'; ?>
<?php include 'includes/footer.php'; ?>
