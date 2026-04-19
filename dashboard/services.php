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
        <a href="<?php echo BASE_URL; ?>/dashboard/payments.php">Payments</a>
        <a href="<?php echo BASE_URL; ?>/dashboard/notifications.php">Notifications</a>
        <button id="openAdminChatPanel" type="button">
            Chat with admin
            <span id="chatUnreadBadge" class="badge" hidden>0</span>
        </button>
    </aside>

    <main class="dashboard-main">
        <h1>Services</h1>

        <div id="serviceMessage"></div>

        <?php while ($row = mysqli_fetch_assoc($services)): ?>
            <div class="dashboard-card">
                <h3><?php echo e($row['name']); ?></h3>
                <p><?php echo e($row['description']); ?></p>
                <p>Category: <?php echo e($row['category']); ?></p>
                <p>Price: £<?php echo e($row['price']); ?></p>

                <div class="service-action-row">
                    <form class="pay-later-form">
                        <input type="hidden" name="service_name" value="<?php echo e($row['name']); ?>">
                        <input type="hidden" name="amount" value="<?php echo e($row['price']); ?>">
                        <button class="btn btn-primary btn-sm" type="submit">Pay later</button>
                    </form>

                    <form class="pay-now-form">
                        <input type="hidden" name="service_name" value="<?php echo e($row['name']); ?>">
                        <input type="hidden" name="amount" value="<?php echo e($row['price']); ?>">
                        <button class="btn btn-ghost btn-sm" type="submit">Mark as paid (demo)</button>
                    </form>
                </div>
            </div>
        <?php endwhile; ?>
    </main>
</div>

<?php include __DIR__ . '/chat.php'; ?>

<script>
const serviceMessage = document.getElementById('serviceMessage');

function showServiceMessage(text) {
    serviceMessage.innerHTML = `<div class="success-note">${text}</div>`;
    setTimeout(() => {
        serviceMessage.innerHTML = '';
    }, 4000);
}

document.querySelectorAll('.pay-later-form').forEach(form => {
    form.addEventListener('submit', async (e) => {
        e.preventDefault();
        const formData = new FormData(form);
        formData.append('payment_status', 'pending');

        const response = await fetch('<?php echo BASE_URL; ?>/api/create_payment_request.php', {
            method: 'POST',
            body: formData
        });

        const result = await response.json();
        showServiceMessage(result.message);
    });
});

document.querySelectorAll('.pay-now-form').forEach(form => {
    form.addEventListener('submit', async (e) => {
        e.preventDefault();
        const formData = new FormData(form);
        formData.append('payment_status', 'paid');

        const response = await fetch('<?php echo BASE_URL; ?>/api/create_payment_request.php', {
            method: 'POST',
            body: formData
        });

        const result = await response.json();
        showServiceMessage(result.message);
    });
});
</script>
</body>
</html>