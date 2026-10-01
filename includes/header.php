<?php
// Usage: set $page_title before including
$page_title = $page_title ?? 'Student Records';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= e($page_title) ?> | Student Records</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<header class="site-header">
    <div class="wrap header-inner">
        <a class="brand" href="index.php">Student Records</a>
        <nav>
            <a href="index.php">All students</a>
            <a class="btn btn-primary" href="create.php">Add student</a>
        </nav>
    </div>
</header>
<main class="wrap">
<?php show_flash(); ?>
