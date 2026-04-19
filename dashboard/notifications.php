<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/functions.php';
require_login();

$userId = current_user_id();

$res = mysqli_query($conn, "SELECT * FROM notifications WHERE user_id = {$userId} ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Notifications - Dev Limited</title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/styles.css">
</head>
<body>
<?php include __DIR__ . '/../includes/header.php'; ?>

<div class="dashboard-layout">
    <aside class="dashboard-sidebar">
        <a href="<?php echo BASE_URL; ?>/dashboard/index.php">Overview</a>
        <a href="<?php echo BASE_URL; ?>/dashboard/requests.php">My Requests</a>
        <a href="<?php echo BASE_URL; ?>/dashboard/request_form.php">Create Request</a>
        <a href="<?php echo BASE_URL; ?>/dashboard/services.php">Services</a>
        <a href="<?php echo BASE_URL; ?>/dashboard/profile.php">Profile</a>
        <a href="<?php echo BASE_URL; ?>/dashboard/payments.php">Payments</a>
        <a href="<?php echo BASE_URL; ?>/dashboard/notifications.php">Notifications</a>
        <button id="openAdminChatPanel" type="button">Chat with admin <span id="chatUnreadBadge" class="badge" hidden>0</span></button>
    </aside>

    <main class="dashboard-main">
        <div class="inline-actions">
            <h1>Notifications</h1>
            <button id="markAllReadBtn" class="btn btn-sm btn-primary" type="button">Mark all as read</button>
        </div>

        <?php while ($row = mysqli_fetch_assoc($res)): ?>
            <div class="dashboard-card <?php echo (int)$row['is_read'] === 0 ? 'unread-card' : ''; ?>">
                <h3><?php echo e($row['title']); ?></h3>
                <p><?php echo e($row['body']); ?></p>
                <small><?php echo e($row['created_at']); ?></small>
            </div>
        <?php endwhile; ?>
    </main>
</div>

<?php include __DIR__ . '/chat.php'; ?>

<script>
document.getElementById('markAllReadBtn')?.addEventListener('click', async () => {
    const response = await fetch('<?php echo BASE_URL; ?>/api/mark_notifications_read.php', {
        method: 'POST'
    });
    const result = await response.json();
    if (result.success) {
        window.location.reload();
    }
});
</script>
</body>
</html>