<?php
require "db.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Live Chat</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        .admin-chat-wrap {
            max-width: 1100px;
            margin: 40px auto;
            display: grid;
            grid-template-columns: 320px 1fr;
            gap: 20px;
        }
        .admin-card {
            background: #0f172a;
            color: #fff;
            border-radius: 18px;
            padding: 20px;
            border: 1px solid rgba(148,163,184,0.16);
        }
        .session-item {
            padding: 12px;
            border-radius: 12px;
            background: rgba(30,41,59,0.8);
            cursor: pointer;
            margin-bottom: 10px;
        }
        .admin-messages {
            height: 500px;
            overflow-y: auto;
            margin-bottom: 16px;
            display: flex;
            flex-direction: column;
            gap: 12px;
        }
        .admin-bubble {
            padding: 12px 14px;
            border-radius: 14px;
            max-width: 75%;
        }
        .admin-bubble.operator {
            align-self: flex-end;
            background: #2563eb;
            color: white;
        }
        .admin-bubble.client {
            align-self: flex-start;
            background: #1e293b;
            color: #e2e8f0;
        }
        .admin-chat-form {
            display: flex;
            gap: 10px;
        }
        .admin-chat-form input {
            flex: 1;
            min-height: 44px;
            border-radius: 10px;
            padding: 0 12px;
        }
        .admin-chat-form button {
            min-width: 120px;
        }
    </style>
</head>
<body>
    <div class="admin-chat-wrap">
        <div class="admin-card">
            <h2>Chat Sessions</h2>
            <div id="adminSessions"></div>
        </div>

        <div class="admin-card">
            <h2>Conversation</h2>
            <div class="admin-messages" id="adminMessages"></div>

            <form class="admin-chat-form" id="adminChatForm">
                <input type="text" id="adminChatInput" placeholder="Type reply..." required>
                <button type="submit">Send reply</button>
            </form>
        </div>
    </div>

    <script>
        let currentSessionToken = "";

        async function loadSessions() {
            const response = await fetch("admin_chat_sessions.php");
            const result = await response.json();

            const container = document.getElementById("adminSessions");
            container.innerHTML = "";

            result.sessions.forEach(session => {
                const item = document.createElement("div");
                item.className = "session-item";
                item.textContent = session.session_token;
                item.addEventListener("click", () => {
                    currentSessionToken = session.session_token;
                    loadMessages();
                });
                container.appendChild(item);
            });
        }

        async function loadMessages() {
            if (!currentSessionToken) return;

            const response = await fetch("chat_fetch_messages.php?session_id=" + encodeURIComponent(currentSessionToken));
            const result = await response.json();

            const container = document.getElementById("adminMessages");
            container.innerHTML = "";

            result.messages.forEach(msg => {
                const bubble = document.createElement("div");
                bubble.className = "admin-bubble " + (msg.sender_type === "operator" ? "operator" : "client");
                bubble.textContent = msg.message;
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

            await fetch("chat_send_message.php", {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify({
                    session_id: currentSessionToken,
                    sender_type: "operator",
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