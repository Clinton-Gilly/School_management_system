// student_view_grades.php

<?php
require 'config.php';
session_start();

if ($_SESSION['role'] !== 'student') {
    header('Location: index.php');
    exit();
}

$student_id = $_SESSION['user_id'];

// Fetch grades for the student
$grades = $pdo->prepare("
    SELECT subjects.subject_name, grades.grade
    FROM grades
    JOIN subjects ON grades.subject_id = subjects.id
    WHERE grades.student_id = ?
");
$grades->execute([$student_id]);
$grades = $grades->fetchAll();

?>

<table>
    <tr>
        <th>Subject</th>
        <th>Grade</th>
    </tr>
    <?php foreach ($grades as $grade) { ?>
        <tr>
            <td><?= $grade['subject_name'] ?></td>
            <td><?= $grade['grade'] ?></td>
        </tr>
    <?php } ?>
</table>
