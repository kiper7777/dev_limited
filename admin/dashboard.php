<?php
require_once __DIR__ . '/../includes/auth.php';
require_admin();
require_once __DIR__ . '/../includes/functions.php';

$usersCount = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS c FROM users"))['c'];
$requestsCount = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS c FROM project_requests"))['c'];
$leadsCount = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS c FROM leads"))['c'];
$unreadAdmin = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COALESCE(SUM(unread_for_admin),0) AS c FROM chat_sessions"))['c'];
$unreadNotifications = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS c FROM notifications WHERE user_id = " . (int)$_SESSION['user_id'] . " AND is_read = 0"))['c'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard - Dev Limited</title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/styles.css">
</head>
<body>
<?php include __DIR__ . '/../includes/header.php'; ?>

<div class="dashboard-layout">
    <aside class="dashboard-sidebar">
        <a href="<?php echo BASE_URL; ?>/admin/dashboard.php">Overview</a>
        <a href="<?php echo BASE_URL; ?>/admin/users.php">Users</a>
        <a href="<?php echo BASE_URL; ?>/admin/requests.php">Requests</a>
        <a href="<?php echo BASE_URL; ?>/admin/leads.php">Leads <?php if ($unreadNotifications > 0): ?><span class="badge"><?php echo (int)$unreadNotifications; ?></span><?php endif; ?></a>
        <a href="<?php echo BASE_URL; ?>/admin/chat.php">Live Chat <?php if ($unreadAdmin > 0): ?><span class="badge"><?php echo (int)$unreadAdmin; ?></span><?php endif; ?></a>
    </aside>

    <main class="dashboard-main">
        <h1>Admin Dashboard</h1>

        <div class="dashboard-grid">
            <div class="dashboard-card">Users: <?php echo (int)$usersCount; ?></div>
            <div class="dashboard-card">Requests: <?php echo (int)$requestsCount; ?></div>
            <div class="dashboard-card">Leads: <?php echo (int)$leadsCount; ?></div>
            <div class="dashboard-card">Unread messages: <?php echo (int)$unreadAdmin; ?></div>
        </div>
    </main>
</div>
</body>
</html>