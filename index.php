<?php
// READ: list students with search, course filter, and pagination
require 'config/db.php';

$search = trim($_GET['q'] ?? '');
$course = trim($_GET['course'] ?? '');
$page   = max(1, (int)($_GET['page'] ?? 1));
$per    = 10;

$where  = [];
$params = [];
$types  = '';

if ($search !== '') {
    $where[] = '(student_no LIKE ? OR first_name LIKE ? OR last_name LIKE ? OR email LIKE ?)';
    $like = "%$search%";
    array_push($params, $like, $like, $like, $like);
    $types .= 'ssss';
}
if ($course !== '') {
    $where[] = 'course = ?';
    $params[] = $course;
    $types .= 's';
}
$whereSql = $where ? 'WHERE ' . implode(' AND ', $where) : '';

// Count for pagination
$stmt = $conn->prepare("SELECT COUNT(*) FROM students $whereSql");
if ($params) $stmt->bind_param($types, ...$params);
$stmt->execute();
$total = (int)$stmt->get_result()->fetch_row()[0];
$stmt->close();

$pages  = max(1, (int)ceil($total / $per));
$page   = min($page, $pages);
$offset = ($page - 1) * $per;

// Fetch rows
$stmt = $conn->prepare("SELECT * FROM students $whereSql ORDER BY last_name, first_name LIMIT ? OFFSET ?");
$p2 = $params; $t2 = $types . 'ii';
array_push($p2, $per, $offset);
$stmt->bind_param($t2, ...$p2);
$stmt->execute();
$students = $stmt->get_result();

// Course list for the filter dropdown
$courseList = $conn->query('SELECT DISTINCT course FROM students ORDER BY course');

$page_title = 'All students';
include 'includes/header.php';

function page_url($p, $search, $course) {
    return 'index.php?' . http_build_query(['q' => $search, 'course' => $course, 'page' => $p]);
}
?>
<div class="page-head">
    <h1>Students <span class="count"><?= $total ?> record<?= $total === 1 ? '' : 's' ?></span></h1>
</div>

<form method="get" class="toolbar">
    <input type="search" name="q" value="<?= e($search) ?>" placeholder="Search name, student number, or email" aria-label="Search students">
    <select name="course" aria-label="Filter by course">
        <option value="">All courses</option>
        <?php while ($c = $courseList->fetch_assoc()): ?>
            <option value="<?= e($c['course']) ?>" <?= $course === $c['course'] ? 'selected' : '' ?>><?= e($c['course']) ?></option>
        <?php endwhile; ?>
    </select>
    <button class="btn btn-primary" type="submit">Search</button>
    <?php if ($search !== '' || $course !== ''): ?><a class="btn btn-ghost" href="index.php">Clear</a><?php endif; ?>
</form>

<?php if ($total === 0): ?>
    <div class="card empty">
        <p>No students found.</p>
        <a class="btn btn-primary" href="create.php">Add the first student</a>
    </div>
<?php else: ?>
<div class="table-scroll card">
    <table>
        <thead>
            <tr>
                <th>Student no.</th><th>Name</th><th>Course</th><th>Year</th>
                <th>Gender</th><th>Birthdate</th><th>Contact</th><th>Email</th><th class="col-actions">Actions</th>
            </tr>
        </thead>
        <tbody>
        <?php while ($s = $students->fetch_assoc()): ?>
            <tr>
                <td><?= e($s['student_no']) ?></td>
                <td><strong><?= e($s['last_name']) ?></strong>, <?= e($s['first_name']) ?></td>
                <td><?= e($s['course']) ?></td>
                <td><?= (int)$s['year_level'] ?></td>
                <td><?= e($s['gender']) ?></td>
                <td><?= e(date('M d, Y', strtotime($s['birthdate']))) ?></td>
                <td><?= e($s['contact_no']) ?></td>
                <td><?= e($s['email']) ?></td>
                <td class="col-actions">
                    <a class="btn btn-small" href="edit.php?id=<?= (int)$s['id'] ?>">Edit</a>
                    <form method="post" action="delete.php" class="inline" data-confirm="Delete <?= e($s['first_name'] . ' ' . $s['last_name']) ?>? This cannot be undone.">
                        <input type="hidden" name="id" value="<?= (int)$s['id'] ?>">
                        <button type="submit" class="btn btn-small btn-danger">Delete</button>
                    </form>
                </td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
</div>

<?php if ($pages > 1): ?>
<nav class="pager" aria-label="Pagination">
    <?php if ($page > 1): ?><a href="<?= e(page_url($page - 1, $search, $course)) ?>">Previous</a><?php endif; ?>
    <span>Page <?= $page ?> of <?= $pages ?></span>
    <?php if ($page < $pages): ?><a href="<?= e(page_url($page + 1, $search, $course)) ?>">Next</a><?php endif; ?>
</nav>
<?php endif; ?>
<?php endif; ?>

<?php
$stmt->close();
include 'includes/footer.php';
