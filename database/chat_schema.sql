CREATE TABLE chat_sessions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    session_token VARCHAR(100) NOT NULL UNIQUE,
    user_id INT NULL,
    status ENUM('open', 'closed') DEFAULT 'open',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE chat_messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    chat_session_id INT NOT NULL,
    sender_type ENUM('client', 'operator') NOT NULL,
    message TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (chat_session_id) REFERENCES chat_sessions(id) ON DELETE CASCADE
);