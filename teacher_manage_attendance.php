// teacher_manage_attendance.php

<?php
require 'config.php';
session_start();

if ($_SESSION['role'] !== 'teacher') {
    header('Location: index.php');
    exit();
}

$teacher_id = $_SESSION['user_id'];

// Fetch classes assigned to the teacher
$classes = $pdo->prepare("SELECT * FROM classes WHERE teacher_id = ?");
$classes->execute([$teacher_id]);
$classes = $classes->fetchAll();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $class_id = $_POST['class_id'];
    $date = $_POST['date'];
    $student_ids = $_POST['student_ids'];
    $statuses = $_POST['statuses'];

    foreach ($student_ids as $index => $student_id) {
        $status = $statuses[$index];
        $stmt = $pdo->prepare("INSERT INTO attendance (student_id, class_id, date, status) VALUES (?, ?, ?, ?)");
        $stmt->execute([$student_id, $class_id, $date, $status]);
    }

    echo "Attendance recorded successfully";
}

?>

<form method="POST">
    Date: <input type="date" name="date" required><br>
    Class: <select name="class_id" required>
        <?php foreach ($classes as $class) { ?>
            <option value="<?= $class['id'] ?>"><?= $class['class_name'] ?></option>
        <?php } ?>
    </select><br>
    <table>
        <tr>
            <th>Student</th>
            <th>Status</th>
        </tr>
        <?php
        // Fetch students in the selected class
        if (isset($class_id)) {
            $students = $pdo->prepare("SELECT * FROM students WHERE class_id = ?");
            $students->execute([$class_id]);
            $students = $students->fetchAll();

            foreach ($students as $student) {
        ?>
            <tr>
                <td><?= $student['name'] ?></td>
                <td>
                    <select name="statuses[]">
                        <option value="Present">Present</option>
                        <option value="Absent">Absent</option>
                    </select>
                    <input type="hidden" name="student_ids[]" value="<?= $student['id'] ?>">
                </td>
            </tr>
        <?php } } ?>
    </table>
    <button type="submit">Record Attendance</button>
</form>
