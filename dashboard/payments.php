<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/functions.php';
require_login();

$userId = current_user_id();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $serviceName = trim($_POST['service_name'] ?? '');
    $amount = (float)($_POST['amount'] ?? 0);

    if ($serviceName !== '' && $amount > 0) {
        $stmt = mysqli_prepare($conn, "INSERT INTO payments (user_id, service_name, amount, status, payment_method) VALUES (?, ?, ?, 'paid', 'manual_demo')");
        mysqli_stmt_bind_param($stmt, "isd", $userId, $serviceName, $amount);
        mysqli_stmt_execute($stmt);

        $title = 'Payment received';
        $body = "Payment for {$serviceName} (£{$amount}) has been recorded.";
        $n = mysqli_prepare($conn, "INSERT INTO notifications (user_id, title, body) VALUES (?, ?, ?)");
        mysqli_stmt_bind_param($n, "iss", $userId, $title, $body);
        mysqli_stmt_execute($n);

        header('Location: ' . BASE_URL . '/dashboard/payments.php');
        exit;
    }
}

$res = mysqli_prepare($conn, "SELECT * FROM payments WHERE user_id = ? ORDER BY id DESC");
mysqli_stmt_bind_param($res, "i", $userId);
mysqli_stmt_execute($res);
$result = mysqli_stmt_get_result($res);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Payments - Dev Limited</title>
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
        <h1>Payments</h1>

        <form method="post" class="dashboard-card form-wrap-small">
            <div class="form-field">
                <label>Service</label>
                <select name="service_name" required>
                    <option value="Website Development">Website Development</option>
                    <option value="Website Maintenance">Website Maintenance</option>
                    <option value="Technical Support">Technical Support</option>
                    <option value="Feature Upgrade">Feature Upgrade</option>
                </select>
            </div>

            <div class="form-field">
                <label>Amount</label>
                <input type="number" step="0.01" name="amount" required>
            </div>

            <button type="submit" class="btn btn-primary">Pay now</button>
        </form>

        <h2>Payment history</h2>
        <?php while ($row = mysqli_fetch_assoc($result)): ?>
            <div class="dashboard-card">
                <h3><?php echo e($row['service_name']); ?></h3>
                <p>Amount: £<?php echo e($row['amount']); ?></p>
                <p>Status: <?php echo e($row['status']); ?></p>
                <p>Method: <?php echo e($row['payment_method']); ?></p>
                <p>Date: <?php echo e($row['created_at']); ?></p>
            </div>
        <?php endwhile; ?>
    </main>
</div>

<?php include __DIR__ . '/chat.php'; ?>
</body>
</html>