<div class="admin-chat-drawer" id="adminChatDrawer" hidden>
    <div class="drawer-header">
        <h2>Chat with admin</h2>
        <button id="closeAdminChatDrawer" type="button" class="drawer-close-btn">×</button>
    </div>

    <div id="drawerMessages" class="drawer-messages"></div>

    <form id="drawerChatForm" class="drawer-form">
        <input type="text" id="drawerChatInput" placeholder="Write a message..." required>
        <button type="submit" class="btn btn-primary btn-sm">Send</button>
    </form>
</div>

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
            div.className = 'drawer-message ' + (msg.sender_type === 'operator' ? 'operator' : 'client');
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