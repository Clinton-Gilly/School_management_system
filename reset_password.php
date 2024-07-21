<?php
require 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $token = trim($_POST['token']);
    $newPassword = trim($_POST['password']);

    // Validate the token
    $stmt = $pdo->prepare("SELECT * FROM users WHERE reset_token = ? AND reset_token_expiry > NOW()");
    $stmt->execute([$token]);
    $user = $stmt->fetch();

    if ($user) {
        // Update the password
        $hashedPassword = password_hash($newPassword, PASSWORD_BCRYPT);
        $stmt = $pdo->prepare("UPDATE users SET password = ?, reset_token = NULL, reset_token_expiry = NULL WHERE reset_token = ?");
        $stmt->execute([$hashedPassword, $token]);

        $message = "Your password has been reset successfully.";
    } else {
        $message = "Invalid or expired reset token.";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Reset Password - School Management System</title>
    <link rel="stylesheet" type="text/css" href="gilly.css">
</head>
<body>
    <div class="reset-password-container">
        <h1>Reset Password</h1>
        <?php if (isset($message)): ?>
            <div class="message"><?php echo htmlspecialchars($message); ?></div>
        <?php endif; ?>
        <form method="post" action="">
            <input type="hidden" name="token" value="<?php echo htmlspecialchars($_GET['token']); ?>">
            <label for="password">New Password:</label>
            <input type="password" id="password" name="password" required>
            <button type="submit">Reset Password</button>
        </form>
    </div>
</body>
</html>
