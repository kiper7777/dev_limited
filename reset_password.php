<?php
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/functions.php';

$token = trim($_GET['token'] ?? $_POST['token'] ?? '');
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $password = $_POST['password'] ?? '';

    $stmt = mysqli_prepare($conn, "SELECT email, expires_at FROM password_resets WHERE token = ? ORDER BY id DESC LIMIT 1");
    mysqli_stmt_bind_param($stmt, "s", $token);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($result);

    if ($row && strtotime($row['expires_at']) > time() && strlen($password) >= 6) {
        $hash = password_hash($password, PASSWORD_BCRYPT);

        $up = mysqli_prepare($conn, "UPDATE users SET password = ? WHERE email = ?");
        mysqli_stmt_bind_param($up, "ss", $hash, $row['email']);
        mysqli_stmt_execute($up);

        mysqli_query($conn, "DELETE FROM password_resets WHERE token = '" . mysqli_real_escape_string($conn, $token) . "'");
        $message = 'Password has been reset successfully.';
    } else {
        $message = 'Invalid or expired token.';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Reset Password</title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/styles.css">
</head>
<body>
<?php include __DIR__ . '/includes/header.php'; ?>

<div class="form-wrap">
    <div class="dashboard-card form-wrap-small">
        <h1>Reset Password</h1>

        <?php if ($message): ?>
            <div class="success-note"><?php echo e($message); ?></div>
        <?php endif; ?>

        <form method="post">
            <input type="hidden" name="token" value="<?php echo e($token); ?>">
            <div class="form-field">
                <label>New password</label>
                <input type="password" name="password" minlength="6" required>
            </div>
            <button type="submit" class="btn btn-primary">Reset password</button>
        </form>
    </div>
</div>
</body>
</html>