<?php
require 'config.php';

if ($_SESSION['role'] !== 'admin') {
    header('Location: index.php');
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
    <link rel="stylesheet" type="text/css" href="admin.css">
</head>
<body>
    <h2>Admin Dashboard</h2>
    <ul>
        <li><a href="add_user.php">Add User</a></li>
        <li><a href="admin_manage_teachers.php">Manage Teachers</a></li>
        <li><a href="manage_students.php">Manage Students</a></li>
        <li><a href="manage_classes.php">Manage Classes</a></li>
        <li><a href="manage_subjects.php">Manage Subjects</a></li>
        <li><a href="view_reports.php">View Reports</a></li>
    </ul>
    <a href="logout.php">Logout</a>
</body>
</html>
