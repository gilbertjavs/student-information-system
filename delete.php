<?php
// DELETE: remove a student (POST only, so a link or crawler cannot delete data)
require 'config/db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}

$id = (int)($_POST['id'] ?? 0);
$stmt = $conn->prepare('DELETE FROM students WHERE id = ?');
$stmt->bind_param('i', $id);
$stmt->execute();

if ($stmt->affected_rows > 0) {
    flash('success', 'Student deleted.');
} else {
    flash('error', 'Student not found or already deleted.');
}
$stmt->close();
header('Location: index.php');
exit;
