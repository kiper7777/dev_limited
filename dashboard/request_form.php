<?php
require_once __DIR__ . '/../includes/auth.php';
require_login();

$features = mysqli_query($conn, "SELECT * FROM feature_options WHERE is_active = 1 ORDER BY name ASC");
$services = mysqli_query($conn, "SELECT * FROM services WHERE is_active = 1 ORDER BY name ASC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create Request - Dev Limited</title>
    <link rel="stylesheet" href="/project/styles.css">
</head>
<body>
<!-- <?php include __DIR__ . '/../includes/header.php'; ?> -->

<div class="form-wrap">
    <h1>Create Website Request</h1>

    <form id="requestForm">
        <input type="text" name="project_name" placeholder="Project name" required>
        <input type="text" name="website_type" placeholder="Website type">
        <input type="text" name="business_name" placeholder="Business name">
        <input type="text" name="industry" placeholder="Industry">
        <input type="text" name="budget_range" placeholder="Budget range">
        <input type="text" name="timeline" placeholder="Timeline">
        <input type="text" name="target_audience" placeholder="Target audience">
        <input type="text" name="preferred_style" placeholder="Preferred style">
        <textarea name="description" placeholder="Project description"></textarea>
        <textarea name="reference_sites" placeholder="Reference websites"></textarea>

        <h3>Feature options</h3>
        <?php while ($feature = mysqli_fetch_assoc($features)): ?>
            <label>
                <input type="checkbox" name="features[]" value="<?php echo (int)$feature['id']; ?>">
                <?php echo e($feature['name']); ?> (£<?php echo e($feature['price']); ?>)
            </label><br>
        <?php endwhile; ?>

        <h3>Additional services</h3>
        <?php while ($service = mysqli_fetch_assoc($services)): ?>
            <label>
                <input type="checkbox" name="services[]" value="<?php echo (int)$service['id']; ?>">
                <?php echo e($service['name']); ?> (£<?php echo e($service['price']); ?>)
            </label><br>
        <?php endwhile; ?>

        <button type="submit">Submit Request</button>
    </form>
</div>

<script>
document.getElementById('requestForm').addEventListener('submit', async function(e) {
    e.preventDefault();

    const formData = new FormData(this);
    const response = await fetch('/project/api/save_request.php', {
        method: 'POST',
        body: formData
    });

    const result = await response.json();
    alert(result.message);

    if (result.success) {
        this.reset();
    }
});
</script>
</body>
</html>