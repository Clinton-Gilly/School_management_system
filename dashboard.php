<?php
require 'config.php';

// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
</head>
<body>
    <header>
        <h1>School Management System</h1>
    </header>

    <main>
        <div class="container">
            <!-- Box for each function -->
            <div class="box">
                <h2>Manage Teachers</h2>
                <a href="manage_teachers.php">Manage Teachers</a>
            </div>
            <div class="box">
                <h2>Manage Students</h2>
                <a href="manage_students.php">Manage Students</a>
            </div>
            <div class="box">
                <h2>Manage Classes</h2>
                <a href="manage_classes.php">Manage Classes</a>
            </div>
            <div class="box">
                <h2>Manage Subjects</h2>
                <a href="manage_subjects.php">Manage Subjects</a>
            </div>
            <div class="box">
                <h2>View Reports</h2>
                <a href="view_reports.php">View Reports</a>
            </div>
        </div>
    </main>

    <footer>
        <p>&copy; 2024 School Management System. All rights reserved.</p>
    </footer>
</body>
</html>
