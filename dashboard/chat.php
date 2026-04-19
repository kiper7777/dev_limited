<div class="admin-chat-drawer" id="adminChatDrawer" hidden>
    <div class="drawer-header">
        <div>
            <h2>Chat with admin</h2>
            <small id="operatorStatusLabel">Operator status: checking...</small>
        </div>
        <button id="closeAdminChatDrawer" type="button" class="drawer-close-btn">×</button>
    </div>

    <div id="drawerMessages" class="drawer-messages"></div>

    <form id="drawerFileForm" class="drawer-file-form" enctype="multipart/form-data">
        <input type="file" id="drawerChatFile" name="chat_file">
        <button type="submit" class="btn btn-sm btn-ghost">Upload file</button>
    </form>

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
const drawerFileForm = document.getElementById('drawerFileForm');
const badge = document.getElementById('chatUnreadBadge');
const operatorStatusLabel = document.getElementById('operatorStatusLabel');

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

        let html = `<strong>${msg.sender_type}</strong><br>`;
        if (msg.message) {
            html += `${msg.message}`;
        }
        if (msg.file_path) {
            html += `<br><a href="<?php echo BASE_URL; ?>/${msg.file_path}" target="_blank" rel="noopener">${msg.file_name || 'Attachment'}</a>`;
        }

        div.innerHTML = html;
        drawerMessages.appendChild(div);
    });

    drawerMessages.scrollTop = drawerMessages.scrollHeight;
}

async function loadUnreadBadge() {
    const sessionId = await ensureDrawerSession();
    if (!sessionId) return;

    const response = await fetch('<?php echo BASE_URL; ?>/api/chat_unread_count.php?session_id=' + encodeURIComponent(sessionId));
    const result = await response.json();

    if (!result.success) return;

    badge.textContent = result.unread;
    badge.hidden = result.unread === 0;
}

async function loadOperatorStatus() {
    const response = await fetch('<?php echo BASE_URL; ?>/api/get_operator_status.php');
    const result = await response.json();

    if (!result.success) return;
    operatorStatusLabel.textContent = 'Operator status: ' + (result.online ? 'online' : 'offline');
}

openDrawerBtn?.addEventListener('click', async () => {
    drawer.hidden = false;
    await ensureDrawerSession();
    await loadDrawerMessages();
    await loadOperatorStatus();

    if (!drawerInterval) {
        drawerInterval = setInterval(async () => {
            await loadDrawerMessages();
            await loadUnreadBadge();
            await loadOperatorStatus();
        }, 3000);
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

drawerFileForm?.addEventListener('submit', async (e) => {
    e.preventDefault();

    const fileInput = document.getElementById('drawerChatFile');
    if (!fileInput.files.length) return;

    const formData = new FormData();
    formData.append('session_id', drawerSessionId);
    formData.append('chat_file', fileInput.files[0]);

    const response = await fetch('<?php echo BASE_URL; ?>/api/upload_chat_file.php', {
        method: 'POST',
        body: formData
    });

    const result = await response.json();
    alert(result.success ? 'File uploaded.' : (result.message || 'Upload failed.'));
    fileInput.value = '';
    await loadDrawerMessages();
});

loadUnreadBadge();
setInterval(loadUnreadBadge, 5000);
</script>