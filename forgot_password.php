<?php
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/functions.php';

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');

    if ($email !== '') {
        $token = bin2hex(random_bytes(32));
        $expiresAt = date('Y-m-d H:i:s', time() + 3600);

        $stmt = mysqli_prepare($conn, "INSERT INTO password_resets (email, token, expires_at) VALUES (?, ?, ?)");
        mysqli_stmt_bind_param($stmt, "sss", $email, $token, $expiresAt);
        mysqli_stmt_execute($stmt);

        $resetLink = BASE_URL . '/reset_password.php?token=' . urlencode($token);
        $message = "Password reset link created: <a href=\"{$resetLink}\">Reset password</a>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Forgot Password</title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/styles.css">
</head>
<body>
<?php include __DIR__ . '/includes/header.php'; ?>

<div class="form-wrap">
    <div class="dashboard-card form-wrap-small">
        <h1>Forgot Password</h1>

        <?php if ($message): ?>
            <div class="success-note"><?php echo $message; ?></div>
        <?php endif; ?>

        <form method="post">
            <div class="form-field">
                <label>Email</label>
                <input type="email" name="email" required>
            </div>
            <button type="submit" class="btn btn-primary">Generate reset link</button>
        </form>
    </div>
</div>
</body>
</html>