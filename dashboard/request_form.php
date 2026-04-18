<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/functions.php';
require_login();

$features = mysqli_query($conn, "SELECT * FROM feature_options WHERE is_active = 1 ORDER BY name ASC");
$services = mysqli_query($conn, "SELECT * FROM services WHERE is_active = 1 ORDER BY name ASC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create Request - Dev Limited</title>
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
        <h1>Create Website Request</h1>

        <form id="requestForm" class="dashboard-card">
            <div class="form-grid">
                <input type="text" name="project_name" placeholder="Project name" required>
                <input type="text" name="website_type" placeholder="Website type">
                <input type="text" name="business_name" placeholder="Business name">
                <input type="text" name="industry" placeholder="Industry">
                <input type="text" name="budget_range" placeholder="Budget range">
                <input type="text" name="timeline" placeholder="Timeline">
                <input type="text" name="target_audience" placeholder="Target audience">
                <input type="text" name="preferred_style" placeholder="Preferred style">
            </div>

            <textarea name="description" placeholder="Project description"></textarea>
            <textarea name="reference_sites" placeholder="Reference websites"></textarea>

            <h3>Feature options</h3>
            <div class="checkbox-grid">
                <?php while ($feature = mysqli_fetch_assoc($features)): ?>
                    <label class="checkbox-card">
                        <input
                            type="checkbox"
                            name="features[]"
                            value="<?php echo (int)$feature['id']; ?>"
                            data-price="<?php echo e($feature['price']); ?>"
                        >
                        <span><?php echo e($feature['name']); ?></span>
                        <small>£<?php echo e($feature['price']); ?></small>
                    </label>
                <?php endwhile; ?>
            </div>

            <h3>Additional services</h3>
            <div class="checkbox-grid">
                <?php while ($service = mysqli_fetch_assoc($services)): ?>
                    <label class="checkbox-card">
                        <input
                            type="checkbox"
                            name="services[]"
                            value="<?php echo (int)$service['id']; ?>"
                            data-price="<?php echo e($service['price']); ?>"
                        >
                        <span><?php echo e($service['name']); ?></span>
                        <small>£<?php echo e($service['price']); ?></small>
                    </label>
                <?php endwhile; ?>
            </div>

            <div class="total-box">
                Estimated total: <strong>£<span id="estimatedTotal">0.00</span></strong>
            </div>

            <button type="submit" class="btn btn-primary">Submit Request</button>
        </form>
    </main>
</div>

<script>
const requestForm = document.getElementById('requestForm');
const totalEl = document.getElementById('estimatedTotal');

function updateEstimatedTotal() {
    let total = 0;
    requestForm.querySelectorAll('input[type="checkbox"]:checked').forEach(input => {
        total += parseFloat(input.dataset.price || '0');
    });
    totalEl.textContent = total.toFixed(2);
}

requestForm.querySelectorAll('input[type="checkbox"]').forEach(input => {
    input.addEventListener('change', updateEstimatedTotal);
});

requestForm.addEventListener('submit', async function(e) {
    e.preventDefault();

    const formData = new FormData(this);

    const response = await fetch('<?php echo BASE_URL; ?>/api/save_request.php', {
        method: 'POST',
        body: formData
    });

    const result = await response.json();
    alert(result.message);

    if (result.success) {
        this.reset();
        updateEstimatedTotal();
        window.location.href = '<?php echo BASE_URL; ?>/dashboard/requests.php';
    }
});

updateEstimatedTotal();
</script>
</body>
</html>