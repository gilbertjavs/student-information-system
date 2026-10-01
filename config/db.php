<?php
/**
 * MySQLi database connection.
 *
 * LOCALHOST (XAMPP): keep the defaults below.
 * INFINITYFREE: replace with the values shown in your hosting panel
 *   (MySQL Databases section): host like sqlXXX.infinityfree.com,
 *   username like if0_12345678, your DB name like if0_12345678_student_db.
 */
$DB_HOST = 'localhost';
$DB_USER = 'root';
$DB_PASS = '';
$DB_NAME = 'student_db';

// Make MySQLi throw exceptions so errors are caught instead of silently ignored
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {
    $conn = new mysqli($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);
    $conn->set_charset('utf8mb4');
} catch (mysqli_sql_exception $e) {
    http_response_code(500);
    die('<h2>Database connection failed.</h2><p>Check config/db.php (host, username, password, database name).</p>');
}

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/** Escape output to prevent XSS */
function e($value) {
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}

/** Flash message helpers (shown once after redirect) */
function flash($type, $message) {
    $_SESSION['flash'] = ['type' => $type, 'message' => $message];
}
function show_flash() {
    if (!empty($_SESSION['flash'])) {
        $f = $_SESSION['flash'];
        unset($_SESSION['flash']);
        echo '<div class="alert alert-' . e($f['type']) . '" role="status">' . e($f['message']) . '</div>';
    }
}

/** Shared server-side validation for create + update. Returns [data, errors]. */
function validate_student(array $in) {
    $data = [
        'student_no' => trim($in['student_no'] ?? ''),
        'first_name' => trim($in['first_name'] ?? ''),
        'last_name'  => trim($in['last_name'] ?? ''),
        'gender'     => $in['gender'] ?? '',
        'birthdate'  => $in['birthdate'] ?? '',
        'course'     => trim($in['course'] ?? ''),
        'year_level' => (int)($in['year_level'] ?? 0),
        'email'      => trim($in['email'] ?? ''),
        'contact_no' => trim($in['contact_no'] ?? ''),
        'address'    => trim($in['address'] ?? ''),
    ];
    $errors = [];

    if (!preg_match('/^[A-Za-z0-9-]{3,20}$/', $data['student_no'])) $errors[] = 'Student number must be 3-20 letters, numbers, or dashes.';
    if ($data['first_name'] === '' || strlen($data['first_name']) > 60) $errors[] = 'First name is required (max 60 characters).';
    if ($data['last_name'] === ''  || strlen($data['last_name'])  > 60) $errors[] = 'Last name is required (max 60 characters).';
    if (!in_array($data['gender'], ['Male', 'Female', 'Other'], true)) $errors[] = 'Choose a gender.';
    $d = DateTime::createFromFormat('Y-m-d', $data['birthdate']);
    if (!$d || $d->format('Y-m-d') !== $data['birthdate'] || $d > new DateTime('today')) $errors[] = 'Enter a valid birthdate that is not in the future.';
    if ($data['course'] === '') $errors[] = 'Course is required.';
    if ($data['year_level'] < 1 || $data['year_level'] > 6) $errors[] = 'Year level must be between 1 and 6.';
    if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) $errors[] = 'Enter a valid email address.';
    if (!preg_match('/^[0-9+\- ]{7,20}$/', $data['contact_no'])) $errors[] = 'Contact number must be 7-20 digits (+ and - allowed).';
    if ($data['address'] === '') $errors[] = 'Address is required.';

    return [$data, $errors];
}
