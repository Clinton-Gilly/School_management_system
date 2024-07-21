<?php
require 'config.php';
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Check if user is a student
if ($_SESSION['role'] !== 'student') {
    header('Location: index.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $student_id = $_SESSION['user_id'];
    $problem = $_POST['problem'];

    $stmt = $pdo->prepare("INSERT INTO problems (student_id, problem) VALUES (?, ?)");
    $stmt->execute([$student_id, $problem]);

    header('Location: student_dashboard.php');
    exit();
}
?>
