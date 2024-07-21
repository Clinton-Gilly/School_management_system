<?php
require 'config.php';

// Check if the user is logged in and has a teacher role
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'teacher') {
    header('Location: login.php');
    exit();
}

// Fetch teacher data from the database
$userId = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT * FROM teachers WHERE user_id = ?");
$stmt->execute([$userId]);
$teacher = $stmt->fetch();

// Fetch assigned classes
$classesStmt = $pdo->prepare("SELECT * FROM classes WHERE teacher_id = ?");
$classesStmt->execute([$userId]);
$classes = $classesStmt->fetchAll();

// Fetch class schedule
$scheduleStmt = $pdo->prepare("
    SELECT c.subject, c.schedule_time 
    FROM classes c
    WHERE c.teacher_id = ?
");
$scheduleStmt->execute([$userId]);
$schedule = $scheduleStmt->fetchAll();

// Fetch announcements
$announcementsStmt = $pdo->query("SELECT * FROM announcements ORDER BY date DESC LIMIT 5");
$announcements = $announcementsStmt->fetchAll();

// Fetch homework and assignments
$homeworkStmt = $pdo->prepare("SELECT * FROM homework WHERE teacher_id = ?");
$homeworkStmt->execute([$userId]);
$homework = $homeworkStmt->fetchAll();

// Fetch notifications
$notificationsStmt = $pdo->query("SELECT * FROM notifications ORDER BY date DESC LIMIT 5");
$notifications = $notificationsStmt->fetchAll();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Teacher Dashboard</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
</head>
<body>
    <header>
        <h1>Welcome to Your Dashboard</h1>
        <a href="logout.php" class="logout-button">Logout</a>
    </header>

    <main>
        <section class="profile-overview">
            <div class="profile-header">
                <img src="<?php echo htmlspecialchars($teacher['profile_picture']); ?>" alt="Profile Picture" class="profile-pic">
                <h2>Welcome, <?php echo htmlspecialchars($teacher['name']); ?>!</h2>
            </div>
            <div class="profile-info">
                <p><strong>Email:</strong> <?php echo htmlspecialchars($teacher['email']); ?></p>
                <p><strong>Phone:</strong> <?php echo htmlspecialchars($teacher['phone']); ?></p>
                <a href="update_profile.php" class="button">Update Profile</a>
            </div>
        </section>

        <section class="class-management">
            <h2>Class Management</h2>
            <ul>
                <?php foreach ($classes as $class) : ?>
                    <li>
                        <strong><?php echo htmlspecialchars($class['subject']); ?></strong><br>
                        Students: <?php echo htmlspecialchars($class['students']); ?><br>
                        <a href="view_class.php?id=<?php echo urlencode($class['id']); ?>" class="button">View Class Details</a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </section>

        <section class="attendance-management">
            <h2>Attendance Management</h2>
            <a href="mark_attendance.php" class="button">Mark Attendance</a>
            <a href="view_attendance.php" class="button">View Attendance Records</a>
        </section>

        <section class="grade-management">
            <h2>Grade Management</h2>
            <a href="enter_grades.php" class="button">Enter/Update Grades</a>
            <a href="view_grades.php" class="button">View Grade Records</a>
        </section>

        <section class="class-schedule">
            <h2>Class Schedule</h2>
            <ul>
                <?php foreach ($schedule as $class) : ?>
                    <li>
                        <strong><?php echo htmlspecialchars($class['subject']); ?></strong><br>
                        Time: <?php echo htmlspecialchars($class['schedule_time']); ?>
                    </li>
                <?php endforeach; ?>
            </ul>
        </section>

        <section class="assignments">
            <h2>Assignments and Homework</h2>
            <a href="create_homework.php" class="button">Create Homework</a>
            <a href="view_homework.php" class="button">View Submitted Homework</a>
        </section>

        <section class="announcements">
            <h2>Announcements</h2>
            <a href="post_announcement.php" class="button">Post Announcement</a>
            <ul>
                <?php foreach ($announcements as $announcement) : ?>
                    <li>
                        <strong><?php echo htmlspecialchars($announcement['title']); ?></strong><br>
                        <?php echo htmlspecialchars($announcement['message']); ?><br>
                        <small><?php echo htmlspecialchars($announcement['date']); ?></small>
                    </li>
                <?php endforeach; ?>
            </ul>
        </section>

        <section class="performance-reports">
            <h2>Performance Reports</h2>
            <a href="generate_performance_report.php" class="button">Generate Performance Report</a>
        </section>

        <section class="communication">
            <h2>Communication</h2>
            <a href="send_message.php" class="button">Send Message</a>
            <a href="view_messages.php" class="button">View Messages</a>
        </section>

        <section class="notifications">
            <h2>Notifications</h2>
            <ul>
                <?php foreach ($notifications as $notification) : ?>
                    <li>
                        <strong><?php echo htmlspecialchars($notification['title']); ?></strong><br>
                        <?php echo htmlspecialchars($notification['message']); ?><br>
                        <small><?php echo htmlspecialchars($notification['date']); ?></small>
                    </li>
                <?php endforeach; ?>
            </ul>
        </section>

        <section class="support">
            <h2>Support and Help</h2>
            <p>If you need help, please contact:</p>
            <p>Email: support@school.com</p>
            <p>Phone: +1234567890</p>
        </section>
    </main>

    <footer>
        <p>&copy; 2024 School Management System. All rights reserved.</p>
    </footer>
</body>
</html>
