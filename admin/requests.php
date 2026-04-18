<?php
require_once __DIR__ . '/../includes/auth.php';
require_admin();

$sql = "SELECT pr.*, u.name AS user_name, u.email
        FROM project_requests pr
        JOIN users u ON u.id = pr.user_id
        ORDER BY pr.id DESC";
$result = mysqli_query($conn, $sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Requests - Dev Limited</title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/styles.css">
</head>
<body>
<?php include __DIR__ . '/../includes/header.php'; ?>

<div class="dashboard-layout">
    <aside class="dashboard-sidebar">
        <a href="<?php echo BASE_URL; ?>/admin/dashboard.php">Overview</a>
        <a href="<?php echo BASE_URL; ?>/admin/requests.php">Requests</a>
        <a href="<?php echo BASE_URL; ?>/admin/chat.php">Live Chat</a>
    </aside>

    <main class="dashboard-main">
        <h1>Project Requests</h1>

        <?php while ($row = mysqli_fetch_assoc($result)): ?>
            <div class="dashboard-card">
                <h3><?php echo e($row['project_name']); ?></h3>
                <p>User: <?php echo e($row['user_name']); ?> (<?php echo e($row['email']); ?>)</p>
                <p>Status: <?php echo e($row['status']); ?></p>
                <p>Budget: <?php echo e($row['budget_range']); ?></p>
                <p>Estimated price: £<?php echo e($row['estimated_price']); ?></p>
                <p>Timeline: <?php echo e($row['timeline']); ?></p>
                <p><?php echo e($row['description']); ?></p>

                <form action="<?php echo BASE_URL; ?>/admin/update_request_status.php" method="post">
                    <input type="hidden" name="request_id" value="<?php echo (int)$row['id']; ?>">
                    <select name="status">
                        <option value="submitted">submitted</option>
                        <option value="in_review">in_review</option>
                        <option value="quoted">quoted</option>
                        <option value="approved">approved</option>
                        <option value="in_progress">in_progress</option>
                        <option value="completed">completed</option>
                        <option value="cancelled">cancelled</option>
                    </select>
                    <button type="submit" class="btn btn-primary btn-sm">Update status</button>
                </form>
            </div>
        <?php endwhile; ?>
    </main>
</div>
</body>
</html>