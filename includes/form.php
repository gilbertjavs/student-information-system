<?php
// Shared form fields for create + edit. Expects $student (array), $errors (array), $submit_label (string).
$courses = ['BS Information Technology', 'BS Computer Science', 'BS Education', 'BS Business Administration', 'BS Nursing', 'BS Engineering', 'Other'];
?>
<?php if (!empty($errors)): ?>
    <div class="alert alert-error" role="alert">
        <strong>Please fix the following:</strong>
        <ul><?php foreach ($errors as $err): ?><li><?= e($err) ?></li><?php endforeach; ?></ul>
    </div>
<?php endif; ?>

<form method="post" class="card form-grid" id="student-form" novalidate>
    <label>Student number
        <input type="text" name="student_no" value="<?= e($student['student_no']) ?>" maxlength="20" required placeholder="2024-0004">
    </label>
    <label>Course
        <select name="course" required>
            <option value="">Select course</option>
            <?php foreach ($courses as $c): ?>
                <option value="<?= e($c) ?>" <?= $student['course'] === $c ? 'selected' : '' ?>><?= e($c) ?></option>
            <?php endforeach; ?>
        </select>
    </label>
    <label>First name
        <input type="text" name="first_name" value="<?= e($student['first_name']) ?>" maxlength="60" required>
    </label>
    <label>Last name
        <input type="text" name="last_name" value="<?= e($student['last_name']) ?>" maxlength="60" required>
    </label>
    <label>Gender
        <select name="gender" required>
            <option value="">Select gender</option>
            <?php foreach (['Male', 'Female', 'Other'] as $g): ?>
                <option value="<?= $g ?>" <?= $student['gender'] === $g ? 'selected' : '' ?>><?= $g ?></option>
            <?php endforeach; ?>
        </select>
    </label>
    <label>Birthdate
        <input type="date" name="birthdate" value="<?= e($student['birthdate']) ?>" max="<?= date('Y-m-d') ?>" required>
    </label>
    <label>Year level
        <select name="year_level" required>
            <option value="">Select year</option>
            <?php for ($y = 1; $y <= 6; $y++): ?>
                <option value="<?= $y ?>" <?= (int)$student['year_level'] === $y ? 'selected' : '' ?>>Year <?= $y ?></option>
            <?php endfor; ?>
        </select>
    </label>
    <label>Contact number
        <input type="tel" name="contact_no" value="<?= e($student['contact_no']) ?>" maxlength="20" required placeholder="09171234567">
    </label>
    <label class="full">Email
        <input type="email" name="email" value="<?= e($student['email']) ?>" maxlength="120" required>
    </label>
    <label class="full">Address
        <input type="text" name="address" value="<?= e($student['address']) ?>" maxlength="255" required>
    </label>
    <div class="full actions">
        <button type="submit" class="btn btn-primary"><?= e($submit_label) ?></button>
        <a href="index.php" class="btn btn-ghost">Cancel</a>
    </div>
</form>
