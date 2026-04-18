<?php
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/functions.php';
require_login();

$userId = current_user_id();

$stmt = mysqli_prepare($conn, "SELECT name, email, phone, company_name, profile_photo FROM users WHERE id = ?");
mysqli_stmt_bind_param($stmt, "i", $userId);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$user = mysqli_fetch_assoc($result);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $company = trim($_POST['company_name'] ?? '');

    $u = mysqli_prepare($conn, "UPDATE users SET name = ?, phone = ?, company_name = ? WHERE id = ?");
    mysqli_stmt_bind_param($u, "sssi", $name, $phone, $company, $userId);
    mysqli_stmt_execute($u);

    $_SESSION['user_name'] = $name;
    header('Location: ' . BASE_URL . '/dashboard/profile.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Profile - Dev Limited</title>
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
        <h1>Profile</h1>

        <form method="post" class="dashboard-card form-wrap-small">
            <div class="form-field">
                <label>Full name</label>
                <input type="text" name="name" value="<?php echo e($user['name']); ?>" required>
            </div>

            <div class="form-field">
                <label>Email</label>
                <input type="email" value="<?php echo e($user['email']); ?>" disabled>
            </div>

            <div class="form-field">
                <label>Phone</label>
                <input type="text" name="phone" value="<?php echo e($user['phone']); ?>">
            </div>

            <div class="form-field">
                <label>Company</label>
                <input type="text" name="company_name" value="<?php echo e($user['company_name']); ?>">
            </div>

            <button type="submit" class="btn btn-primary">Save profile</button>
        </form>
    </main>
</div>

<?php include __DIR__ . '/chat.php'; ?>
</body>
</html>