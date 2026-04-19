<?php
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/functions.php';

$token = trim($_GET['token'] ?? '');
$message = 'Invalid or expired verification link.';

if ($token !== '') {
    $stmt = mysqli_prepare($conn, "SELECT user_id, expires_at FROM email_verifications WHERE token = ? ORDER BY id DESC LIMIT 1");
    mysqli_stmt_bind_param($stmt, "s", $token);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($result);

    if ($row && strtotime($row['expires_at']) > time()) {
        $uid = (int)$row['user_id'];
        mysqli_query($conn, "UPDATE users SET email_verified = 1 WHERE id = {$uid}");
        mysqli_query($conn, "DELETE FROM email_verifications WHERE user_id = {$uid}");
        $message = 'Email verified successfully.';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Verify Email</title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/styles.css">
</head>
<body>
<?php include __DIR__ . '/includes/header.php'; ?>

<div class="form-wrap">
    <div class="dashboard-card form-wrap-small">
        <h1>Email Verification</h1>
        <div class="success-note"><?php echo e($message); ?></div>
    </div>
</div>
</body>
</html>