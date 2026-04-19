<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/functions.php';
require_login();

$userId = current_user_id();

$sql = "SELECT * FROM project_requests WHERE user_id = ? ORDER BY id DESC";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $userId);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Requests - Dev Limited</title>
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
        <a href="<?php echo BASE_URL; ?>/dashboard/notifications.php">Notifications <span id="notificationsBadge" class="badge" hidden>0</span></a>
        <button id="openAdminChatPanel" type="button">Chat with admin <span id="chatUnreadBadge" class="badge" hidden>0</span></button>
    </aside>

    <main class="dashboard-main">
        <h1>My Requests</h1>

        <?php while ($row = mysqli_fetch_assoc($result)): ?>
            <div class="dashboard-card">
                <h3><?php echo e($row['project_name']); ?></h3>
                <p>Status: <strong><?php echo e($row['status']); ?></strong></p>
                <p>Website type: <?php echo e($row['website_type']); ?></p>
                <p>Budget: <?php echo e($row['budget_range']); ?></p>
                <p>Estimated price: £<?php echo e($row['estimated_price']); ?></p>
                <p>Timeline: <?php echo e($row['timeline']); ?></p>
                <p><?php echo e($row['description']); ?></p>

                <?php if (!in_array($row['status'], ['completed', 'cancelled'], true)): ?>
                    <form class="request-edit-form" data-id="<?php echo (int)$row['id']; ?>">
                        <input type="hidden" name="request_id" value="<?php echo (int)$row['id']; ?>">
                        <div class="form-grid">
                            <input type="text" name="project_name" value="<?php echo e($row['project_name']); ?>" required>
                            <input type="text" name="budget_range" value="<?php echo e($row['budget_range']); ?>" placeholder="Budget range">
                            <input type="text" name="timeline" value="<?php echo e($row['timeline']); ?>" placeholder="Timeline">
                        </div>
                        <textarea name="description" placeholder="Description"><?php echo e($row['description']); ?></textarea>
                        <div class="inline-actions">
                            <button type="submit" class="btn btn-sm btn-primary">Save changes</button>
                        </div>
                    </form>

                    <form method="post" action="<?php echo BASE_URL; ?>/api/delete_request.php" class="inline-form request-cancel-form">
                        <input type="hidden" name="request_id" value="<?php echo (int)$row['id']; ?>">
                        <button type="submit" class="btn btn-sm btn-ghost">Cancel request</button>
                    </form>
                <?php endif; ?>
            </div>
        <?php endwhile; ?>
    </main>
</div>

<?php include __DIR__ . '/chat.php'; ?>

<script>
    document.querySelectorAll('.request-cancel-form').forEach(form => {
        form.addEventListener('submit', async function(e) {
            e.preventDefault();
            const formData = new FormData(this);

            const response = await fetch(this.action, {
                method: 'POST',
                body: formData
            });

            const result = await response.json();
            alert(result.message);
            if (result.success) {
                window.location.reload();
            }
        });
    });

    document.querySelectorAll('.request-edit-form').forEach(form => {
        form.addEventListener('submit', async function(e) {
            e.preventDefault();
            const formData = new FormData(this);

            const response = await fetch('<?php echo BASE_URL; ?>/api/update_request.php', {
                method: 'POST',
                body: formData
            });

            const result = await response.json();
            alert(result.success ? 'Request updated.' : 'Failed to update request.');
            if (result.success) {
                window.location.reload();
            }
        });
    });
</script>
</body>
</html>