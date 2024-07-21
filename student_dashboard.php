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

$user_id = $_SESSION['user_id'];

// Debug: Print the user_id
echo "Debug: user_id from session: " . htmlspecialchars($user_id) . "<br>";

// Fetch student information
$stmt = $pdo->prepare("SELECT * FROM students WHERE user_id = ?");
$stmt->execute([$user_id]);
$student = $stmt->fetch();

// Debug: Check if student record is found
if (!$student) {
    echo "Student record not found. Check if the user_id in the students table matches the user_id in the users table.";
    exit();
}

// Fetch class schedule
$stmt = $pdo->prepare("SELECT classes.class_name, subjects.subject_name, schedule.day, schedule.time 
                       FROM schedule
                       JOIN classes ON schedule.class_id = classes.id
                       JOIN subjects ON schedule.subject_id = subjects.id
                       WHERE schedule.student_id = ?");
$stmt->execute([$student['id']]);
$schedule = $stmt->fetchAll();

// Fetch grades
$stmt = $pdo->prepare("SELECT subjects.subject_name, grades.grade
                       FROM grades
                       JOIN subjects ON grades.subject_id = subjects.id
                       WHERE grades.student_id = ?");
$stmt->execute([$student['id']]);
$grades = $stmt->fetchAll();

// Fetch announcements
$stmt = $pdo->prepare("SELECT * FROM announcements ORDER BY date DESC");
$stmt->execute();
$announcements = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Student Dashboard</title>
    <link rel="stylesheet" type="text/css" href="student.css">
</head>
<body>
    <div class="dashboard-container">
        <h1>Welcome, <?php echo htmlspecialchars($student['name']); ?></h1>
        <div class="profile-section">
            <p>Name: <?php echo htmlspecialchars($student['name']); ?></p>
            <p>Student ID: <?php echo htmlspecialchars($student['id']); ?></p>
            <p>Email: <?php echo htmlspecialchars($student['email']); ?></p>
            <p>Contact: <?php echo htmlspecialchars($student['phone']); ?></p>
            <a href="update_profile.php">Update Profile</a>
        </div>

        <div class="schedule-section">
            <h2>Class Schedule</h2>
            <table>
                <tr>
                    <th>Class</th>
                    <th>Subject</th>
                    <th>Day</th>
                    <th>Time</th>
                </tr>
                <?php foreach ($schedule as $class): ?>
                <tr>
                    <td><?php echo htmlspecialchars($class['class_name']); ?></td>
                    <td><?php echo htmlspecialchars($class['subject_name']); ?></td>
                    <td><?php echo htmlspecialchars($class['day']); ?></td>
                    <td><?php echo htmlspecialchars($class['time']); ?></td>
                </tr>
                <?php endforeach; ?>
            </table>
        </div>

        <div class="grades-section">
            <h2>Grades</h2>
            <table>
                <tr>
                    <th>Subject</th>
                    <th>Grade</th>
                </tr>
                <?php foreach ($grades as $grade): ?>
                <tr>
                    <td><?php echo htmlspecialchars($grade['subject_name']); ?></td>
                    <td><?php echo htmlspecialchars($grade['grade']); ?></td>
                </tr>
                <?php endforeach; ?>
            </table>
        </div>

        <div class="announcements-section">
            <h2>Announcements</h2>
            <?php foreach ($announcements as $announcement): ?>
                <div class="announcement">
                    <h3><?php echo htmlspecialchars($announcement['title']); ?></h3>
                    <p><?php echo htmlspecialchars($announcement['content']); ?></p>
                    <small><?php echo htmlspecialchars($announcement['date']); ?></small>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="report-problem-section">
            <h2>Report a Problem</h2>
            <form method="POST" action="report_problem.php">
                <textarea name="problem" rows="5" required placeholder="Describe your problem..."></textarea>
                <button type="submit">Report Problem</button>
            </form>
        </div>

        <a href="logout.php">Logout</a>
    </div>
</body>
</html>
