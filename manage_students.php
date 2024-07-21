// admin_manage_teachers.php

<?php
require 'config.php';
session_start();

if ($_SESSION['role'] !== 'admin') {
    header('Location: index.php');
    exit();
}

// Fetch teachers
$teachers = $pdo->query("SELECT * FROM teachers")->fetchAll();

?>

<!-- Add/Edit/Delete Teacher Form -->
<form method="POST" action="admin_add_teacher.php">
    Name: <input type="text" name="name" required><br>
    Email: <input type="email" name="email" required><br>
    Phone: <input type="text" name="phone" required><br>
    <button type="submit">Add Teacher</button>
</form>

<!-- Display Teachers -->
<table>
    <tr>
        <th>Name</th>
        <th>Email</th>
        <th>Phone</th>
        <th>Actions</th>
    </tr>
    <?php foreach ($teachers as $teacher) { ?>
        <tr>
            <td><?= $teacher['name'] ?></td>
            <td><?= $teacher['email'] ?></td>
            <td><?= $teacher['phone'] ?></td>
            <td>
                <a href="admin_edit_teacher.php?id=<?= $teacher['id'] ?>">Edit</a>
                <a href="admin_delete_teacher.php?id=<?= $teacher['id'] ?>">Delete</a>
            </td>
        </tr>
    <?php } ?>
</table>
