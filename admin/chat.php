<?php
require_once __DIR__ . '/../includes/auth.php';
require_admin();
require_once __DIR__ . '/../includes/functions.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Live Chat - Dev Limited</title>
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
        <h1>Admin Live Chat</h1>
        <div style="display:grid;grid-template-columns:320px 1fr;gap:20px;">
            <div class="dashboard-card">
                <h3>Sessions</h3>
                <div id="adminSessions"></div>
            </div>

            <div class="dashboard-card">
                <h3>Conversation</h3>
                <div id="adminMessages" style="height:420px;overflow-y:auto;margin-bottom:15px;"></div>

                <form id="adminChatForm">
                    <input type="text" id="adminChatInput" placeholder="Type reply..." required>
                    <button type="submit" class="btn btn-primary btn-sm">Send reply</button>
                </form>
            </div>
        </div>
    </main>
</div>

<script>
    let currentSessionToken = "";

    async function loadSessions() {
        const response = await fetch('<?php echo BASE_URL; ?>/api/admin_chat_sessions.php');
        const result = await response.json();

        const container = document.getElementById("adminSessions");
        container.innerHTML = "";

        result.sessions.forEach(session => {
            const item = document.createElement("div");
            item.className = "dashboard-card";
            item.style.cursor = "pointer";
            item.innerHTML = `
                <strong>${session.user_name || 'Guest'}</strong><br>
                <small>${session.email || ''}</small><br>
                <small>Unread: ${session.unread_for_admin}</small>
            `;
            item.addEventListener("click", () => {
                currentSessionToken = session.session_token;
                loadMessages();
            });
            container.appendChild(item);
        });
    }

    async function loadMessages() {
        if (!currentSessionToken) return;

        const response = await fetch('<?php echo BASE_URL; ?>/api/chat_fetch_messages.php?viewer=admin&session_id=' + encodeURIComponent(currentSessionToken));
        const result = await response.json();

        const container = document.getElementById("adminMessages");
        container.innerHTML = "";

        result.messages.forEach(msg => {
            const bubble = document.createElement("div");
            bubble.className = "dashboard-card";
            bubble.style.marginBottom = "10px";
            bubble.innerHTML = `<strong>${msg.sender_type}</strong><br>${msg.message ?? ''}`;
            container.appendChild(bubble);
        });

        container.scrollTop = container.scrollHeight;
    }

    document.getElementById("adminChatForm").addEventListener("submit", async (e) => {
        e.preventDefault();
        if (!currentSessionToken) return;

        const input = document.getElementById("adminChatInput");
        const message = input.value.trim();
        if (!message) return;

        await fetch('<?php echo BASE_URL; ?>/api/chat_send_message.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                session_id: currentSessionToken,
                sender_type: 'operator',
                message: message
            })
        });

        input.value = "";
        loadMessages();
    });

    loadSessions();
    setInterval(() => {
        loadSessions();
        loadMessages();
    }, 3000);
</script>
</body>
</html>