<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/functions.php';
require_login();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User Dashboard - Dev Limited</title>
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
        <button id="openAdminChatPanel" type="button">Chat with admin</button>
    </aside>

    <main class="dashboard-main">
        <h1>Welcome, <?php echo e($_SESSION['user_name']); ?></h1>
        <p>Your Dev Limited client dashboard.</p>

        <div class="dashboard-card">
            <h3>Account type</h3>
            <p><?php echo e($_SESSION['role']); ?></p>
        </div>

        <div id="userRequestsList"></div>
    </main>
</div>

<div class="admin-chat-drawer" id="adminChatDrawer" hidden>
    <div class="drawer-header">
        <h2>Chat with admin</h2>
        <button id="closeAdminChatDrawer" type="button">×</button>
    </div>
    <div id="drawerMessages" class="drawer-messages"></div>
    <form id="drawerChatForm" class="drawer-form">
        <input type="text" id="drawerChatInput" placeholder="Write a message..." required>
        <button type="submit">Send</button>
    </form>
</div>

<script>
async function loadUserRequests() {
    const response = await fetch('<?php echo BASE_URL; ?>/api/get_user_requests.php');
    const result = await response.json();

    const list = document.getElementById('userRequestsList');
    list.innerHTML = '';

    result.items.forEach(item => {
        const card = document.createElement('div');
        card.className = 'dashboard-card';
        card.innerHTML = `
            <h3>${item.project_name}</h3>
            <p>Status: <strong>${item.status}</strong></p>
            <p>Budget: ${item.budget_range || '-'}</p>
            <p>Timeline: ${item.timeline || '-'}</p>
            <button type="button" onclick="cancelRequest(${item.id})">Cancel request</button>
        `;
        list.appendChild(card);
    });
}

async function cancelRequest(id) {
    const formData = new FormData();
    formData.append('request_id', id);

    const response = await fetch('<?php echo BASE_URL; ?>/api/delete_request.php', {
        method: 'POST',
        body: formData
    });

    const result = await response.json();
    alert(result.message);
    loadUserRequests();
}

loadUserRequests();
</script>
</body>
</html>