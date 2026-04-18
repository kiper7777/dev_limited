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

<script>
    let drawerSessionId = null;
    let drawerInterval = null;

    const openDrawerBtn = document.getElementById('openAdminChatPanel');
    const closeDrawerBtn = document.getElementById('closeAdminChatDrawer');
    const drawer = document.getElementById('adminChatDrawer');
    const drawerMessages = document.getElementById('drawerMessages');
    const drawerForm = document.getElementById('drawerChatForm');
    const drawerInput = document.getElementById('drawerChatInput');
    const badge = document.getElementById('chatUnreadBadge');

    async function ensureDrawerSession() {
        if (drawerSessionId) return drawerSessionId;

        const response = await fetch('<?php echo BASE_URL; ?>/api/chat_create_session.php', {
            method: 'POST'
        });
        const result = await response.json();

        if (result.success) {
            drawerSessionId = result.session_id;
        }

        return drawerSessionId;
    }

    async function loadDrawerMessages() {
        if (!drawerSessionId) return;

        const response = await fetch('<?php echo BASE_URL; ?>/api/chat_fetch_messages.php?viewer=client&session_id=' + encodeURIComponent(drawerSessionId));
        const result = await response.json();

        if (!result.success) return;

        drawerMessages.innerHTML = '';

        result.messages.forEach(msg => {
            const div = document.createElement('div');
            div.className = 'dashboard-card';
            div.style.marginBottom = '10px';
            div.innerHTML = `<strong>${msg.sender_type}</strong><br>${msg.message ?? ''}`;
            drawerMessages.appendChild(div);
        });

        drawerMessages.scrollTop = drawerMessages.scrollHeight;
    }

    async function loadUnreadBadge() {
        const sessionId = await ensureDrawerSession();
        if (!sessionId) return;

        const response = await fetch('<?php echo BASE_URL; ?>/api/chat_fetch_messages.php?viewer=client&session_id=' + encodeURIComponent(sessionId));
        const result = await response.json();

        if (!result.success) return;

        const unreadCount = result.messages.filter(m => m.sender_type === 'operator').length;
        badge.textContent = unreadCount;
        badge.hidden = unreadCount === 0;
    }

    openDrawerBtn?.addEventListener('click', async () => {
        drawer.hidden = false;
        await ensureDrawerSession();
        await loadDrawerMessages();

        if (!drawerInterval) {
            drawerInterval = setInterval(loadDrawerMessages, 3000);
        }
    });

    closeDrawerBtn?.addEventListener('click', () => {
        drawer.hidden = true;
        if (drawerInterval) {
            clearInterval(drawerInterval);
            drawerInterval = null;
        }
    });

    drawerForm?.addEventListener('submit', async (e) => {
        e.preventDefault();

        const text = drawerInput.value.trim();
        if (!text) return;

        await fetch('<?php echo BASE_URL; ?>/api/chat_send_message.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                session_id: drawerSessionId,
                sender_type: 'client',
                message: text
            })
        });

        drawerInput.value = '';
        await loadDrawerMessages();
    });

    loadUnreadBadge();
    setInterval(loadUnreadBadge, 5000);
</script>

</body>
</html>