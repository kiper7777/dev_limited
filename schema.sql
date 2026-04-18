CREATE DATABASE IF NOT EXISTS devin_limited CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE devin_limited;

DROP TABLE IF EXISTS notifications;
DROP TABLE IF EXISTS chat_messages;
DROP TABLE IF EXISTS chat_sessions;
DROP TABLE IF EXISTS payments;
DROP TABLE IF EXISTS leads;
DROP TABLE IF EXISTS project_request_services;
DROP TABLE IF EXISTS project_request_features;
DROP TABLE IF EXISTS project_requests;
DROP TABLE IF EXISTS feature_options;
DROP TABLE IF EXISTS services;
DROP TABLE IF EXISTS users;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    role ENUM('client','admin') NOT NULL DEFAULT 'client',
    name VARCHAR(120) NOT NULL,
    email VARCHAR(190) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    phone VARCHAR(50) DEFAULT NULL,
    company_name VARCHAR(150) DEFAULT NULL,
    profile_photo VARCHAR(255) DEFAULT NULL,
    email_verified TINYINT(1) NOT NULL DEFAULT 0,
    remember_token VARCHAR(255) DEFAULT NULL,
    is_online TINYINT(1) NOT NULL DEFAULT 0,
    last_seen DATETIME DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE services (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(120) NOT NULL,
    category VARCHAR(120) DEFAULT NULL,
    description TEXT DEFAULT NULL,
    price DECIMAL(10,2) DEFAULT 0.00,
    is_active TINYINT(1) NOT NULL DEFAULT 1
);

CREATE TABLE feature_options (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(120) NOT NULL,
    category VARCHAR(120) DEFAULT NULL,
    description TEXT DEFAULT NULL,
    price DECIMAL(10,2) DEFAULT 0.00,
    is_active TINYINT(1) NOT NULL DEFAULT 1
);

CREATE TABLE project_requests (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    project_name VARCHAR(180) NOT NULL,
    website_type VARCHAR(120) DEFAULT NULL,
    business_name VARCHAR(180) DEFAULT NULL,
    industry VARCHAR(120) DEFAULT NULL,
    budget_range VARCHAR(120) DEFAULT NULL,
    timeline VARCHAR(120) DEFAULT NULL,
    target_audience VARCHAR(255) DEFAULT NULL,
    preferred_style VARCHAR(255) DEFAULT NULL,
    description TEXT DEFAULT NULL,
    reference_sites TEXT DEFAULT NULL,
    status ENUM('draft','submitted','in_review','quoted','approved','in_progress','completed','cancelled') NOT NULL DEFAULT 'submitted',
    estimated_price DECIMAL(10,2) DEFAULT 0.00,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE project_request_features (
    id INT AUTO_INCREMENT PRIMARY KEY,
    project_request_id INT NOT NULL,
    feature_option_id INT NOT NULL,
    FOREIGN KEY (project_request_id) REFERENCES project_requests(id) ON DELETE CASCADE,
    FOREIGN KEY (feature_option_id) REFERENCES feature_options(id) ON DELETE CASCADE
);

CREATE TABLE project_request_services (
    id INT AUTO_INCREMENT PRIMARY KEY,
    project_request_id INT NOT NULL,
    service_id INT NOT NULL,
    FOREIGN KEY (project_request_id) REFERENCES project_requests(id) ON DELETE CASCADE,
    FOREIGN KEY (service_id) REFERENCES services(id) ON DELETE CASCADE
);

CREATE TABLE leads (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT DEFAULT NULL,
    source ENUM('chatbot_quiz','contact_form','dashboard') NOT NULL DEFAULT 'chatbot_quiz',
    full_name VARCHAR(150) NOT NULL,
    email VARCHAR(190) NOT NULL,
    phone VARCHAR(50) DEFAULT NULL,
    company_name VARCHAR(150) DEFAULT NULL,
    website_type VARCHAR(120) DEFAULT NULL,
    budget_range VARCHAR(120) DEFAULT NULL,
    timeline VARCHAR(120) DEFAULT NULL,
    required_features TEXT DEFAULT NULL,
    message TEXT DEFAULT NULL,
    status ENUM('new','contacted','qualified','proposal_sent','won','lost') NOT NULL DEFAULT 'new',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
);

CREATE TABLE payments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    project_request_id INT DEFAULT NULL,
    service_name VARCHAR(150) NOT NULL,
    amount DECIMAL(10,2) NOT NULL,
    status ENUM('pending','paid','failed','refunded') NOT NULL DEFAULT 'pending',
    payment_method VARCHAR(100) DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (project_request_id) REFERENCES project_requests(id) ON DELETE SET NULL
);

CREATE TABLE chat_sessions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    session_token VARCHAR(100) NOT NULL UNIQUE,
    user_id INT DEFAULT NULL,
    user_name VARCHAR(150) DEFAULT NULL,
    status ENUM('open','closed') NOT NULL DEFAULT 'open',
    unread_for_admin INT NOT NULL DEFAULT 0,
    unread_for_user INT NOT NULL DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
);

CREATE TABLE chat_messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    chat_session_id INT NOT NULL,
    sender_type ENUM('client','operator','bot') NOT NULL,
    sender_user_id INT DEFAULT NULL,
    message TEXT DEFAULT NULL,
    file_path VARCHAR(255) DEFAULT NULL,
    file_name VARCHAR(190) DEFAULT NULL,
    is_read_by_admin TINYINT(1) NOT NULL DEFAULT 0,
    is_read_by_user TINYINT(1) NOT NULL DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (chat_session_id) REFERENCES chat_sessions(id) ON DELETE CASCADE,
    FOREIGN KEY (sender_user_id) REFERENCES users(id) ON DELETE SET NULL
);

CREATE TABLE notifications (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    title VARCHAR(180) NOT NULL,
    body TEXT DEFAULT NULL,
    is_read TINYINT(1) NOT NULL DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

INSERT INTO services (name, category, description, price) VALUES
('Website Development', 'Development', 'Custom business website development', 1200.00),
('Website Maintenance', 'Support', 'Ongoing website maintenance and updates', 150.00),
('Technical Support', 'Support', 'Technical support and troubleshooting', 120.00),
('Feature Upgrade', 'Development', 'Add new features or modules', 300.00),
('Admin Panel Development', 'Development', 'Secure admin dashboard and management tools', 700.00),
('CRM Integration', 'Business', 'CRM and sales workflow integration', 900.00);

INSERT INTO feature_options (name, category, description, price) VALUES
('Landing Page', 'Pages', 'Single-page conversion-focused website', 300.00),
('Multi-page Website', 'Pages', 'Multi-page business website', 500.00),
('Authentication System', 'Core', 'Sign up, login, sessions', 250.00),
('User Dashboard', 'Core', 'Private client dashboard', 450.00),
('Admin Panel', 'Core', 'Back-office admin panel', 550.00),
('CRM Integration', 'Business', 'Lead and request tracking', 400.00),
('Booking System', 'Business', 'Appointments and scheduling', 350.00),
('Payments', 'Business', 'Payments and billing integration', 300.00),
('Blog / CMS', 'Content', 'Editable content and blog', 250.00),
('Live Chat', 'Communication', 'Chat with admin/operator', 220.00),
('File Uploads', 'Utility', 'Upload assets and documents', 160.00),
('SEO Setup', 'Marketing', 'On-page SEO and metadata', 180.00),
('Analytics', 'Marketing', 'Analytics integration', 140.00),
('Accessibility WCAG AA', 'Quality', 'Accessibility improvements', 190.00);