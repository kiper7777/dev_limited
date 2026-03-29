<?php
require_once __DIR__ . '/../includes/auth.php';
require_admin();
require_once __DIR__ . '/../includes/functions.php';


$_SESSION['user_id'] = 1;
$_SESSION['role'] = 'admin';

$usersCount = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS c FROM users"))['c'];
$requestsCount = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS c FROM project_requests"))['c'];
$leadsCount = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS c FROM leads"))['c'];
$unreadAdmin = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COALESCE(SUM(unread_for_admin),0) AS c FROM chat_sessions"))['c'];
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
        <a href="<?= BASE_URL ?>/admin/dashboard.php">Overview</a>
        <a href="<?= BASE_URL ?>/admin/requests.php">Requests</a>
        <a href="<?= BASE_URL ?>/admin/chat.php">Live Chat <?php echo $unreadAdmin > 0 ? '(' . (int)$unreadAdmin . ')' : ''; ?></a>
    </aside>

    <main class="dashboard-main">
        <h1>Admin Dashboard</h1>

        <div class="dashboard-card">
            <h3>Role</h3>
            <p><?php echo e($_SESSION['role']); ?></p>
        </div>

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
