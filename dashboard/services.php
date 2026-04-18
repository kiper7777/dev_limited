<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/functions.php';
require_login();

$services = mysqli_query($conn, "SELECT * FROM services WHERE is_active = 1 ORDER BY name ASC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Services - Dev Limited</title>
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
        <button id="openAdminChatPanel" type="button">Chat with admin <span id="chatUnreadBadge" class="badge" hidden>0</span></button>
    </aside>

    <main class="dashboard-main">
        <h1>Services</h1>

        <?php while ($row = mysqli_fetch_assoc($services)): ?>
            <div class="dashboard-card">
                <h3><?php echo e($row['name']); ?></h3>
                <p><?php echo e($row['description']); ?></p>
                <p>Category: <?php echo e($row['category']); ?></p>
                <p>Price: £<?php echo e($row['price']); ?></p>
                <button class="btn btn-primary btn-sm" type="button">Pay later / Request quote</button>
            </div>
        <?php endwhile; ?>
    </main>
</div>

<?php include __DIR__ . '/chat.php'; ?>
</body>
</html>