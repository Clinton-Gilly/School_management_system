<?php
require 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']);

    // Check if the email exists in the users table
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user) {
        // Generate a reset token and save it to the database
        $resetToken = bin2hex(random_bytes(16));
        $stmt = $pdo->prepare("UPDATE users SET reset_token = ?, reset_token_expiry = DATE_ADD(NOW(), INTERVAL 1 HOUR) WHERE email = ?");
        $stmt->execute([$resetToken, $email]);

        // Send the reset link to the user's email (implement email sending here)
        $resetLink = "http://yourdomain.com/reset_password.php?token=" . $resetToken;
        // mail($email, "Password Reset Request", "Click the following link to reset your password: $resetLink");

        $message = "A password reset link has been sent to your email address.";
    } else {
        $message = "No account found with that email address.";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Forgot Password - School Management System</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
</head>
<body>
    <div class="forgot-password-container">
        <h1>Forgot Password</h1>
        <?php if (isset($message)): ?>
            <div class="message"><?php echo htmlspecialchars($message); ?></div>
        <?php endif; ?>
        <form method="post" action="">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>
            <button type="submit">Send Reset Link</button>
        </form>
    </div>
</body>
</html>
