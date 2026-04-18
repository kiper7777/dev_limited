<?php
require_once __DIR__ . '/../includes/auth.php';
require_admin();
require_once __DIR__ . '/../includes/functions.php';

$result = mysqli_query($conn, "SELECT * FROM leads ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Leads - Dev Limited</title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/styles.css">
</head>
<body>
<?php include __DIR__ . '/../includes/header.php'; ?>

<div class="dashboard-layout">
    <aside class="dashboard-sidebar">
        <a href="<?php echo BASE_URL; ?>/admin/dashboard.php">Overview</a>
        <a href="<?php echo BASE_URL; ?>/admin/users.php">Users</a>
        <a href="<?php echo BASE_URL; ?>/admin/requests.php">Requests</a>
        <a href="<?php echo BASE_URL; ?>/admin/leads.php">Leads</a>
        <a href="<?php echo BASE_URL; ?>/admin/chat.php">Live Chat</a>
    </aside>

    <main class="dashboard-main">
        <h1>Leads</h1>

        <?php while ($row = mysqli_fetch_assoc($result)): ?>
            <div class="dashboard-card">
                <h3><?php echo e($row['full_name']); ?></h3>
                <p>Email: <?php echo e($row['email']); ?></p>
                <p>Phone: <?php echo e($row['phone']); ?></p>
                <p>Company: <?php echo e($row['company_name']); ?></p>
                <p>Source: <?php echo e($row['source']); ?></p>
                <p>Status: <?php echo e($row['status']); ?></p>
                <p>Website type: <?php echo e($row['website_type']); ?></p>
                <p>Budget: <?php echo e($row['budget_range']); ?></p>
                <p>Timeline: <?php echo e($row['timeline']); ?></p>
                <p>Features: <?php echo e($row['required_features']); ?></p>
                <p>Message: <?php echo e($row['message']); ?></p>
            </div>
        <?php endwhile; ?>
    </main>
</div>
</body>
</html>