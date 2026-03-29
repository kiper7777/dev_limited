<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<?php
require_once __DIR__ . '/config.php';
?>

<header class="site-header" id="top">
    <div class="container header-inner">
        <a href="<?php echo BASE_URL; ?>/index.php#top" class="logo" aria-label="Dev Limited home">
            <span class="logo-mark">Dev</span><span class="logo-text"> Limited</span>
            <span class="logo-tagline">Development of Innovative Solutions</span>
        </a>

        <nav class="main-nav" aria-label="Main navigation">
            <button class="nav-toggle" aria-label="Toggle navigation" aria-expanded="false" aria-controls="primaryMenu" type="button">
                <span></span>
                <span></span>
            </button>

            <ul class="nav-links" id="primaryMenu">
                <li><a href="<?php echo BASE_URL; ?>/index.php#services">Services</a></li>
                <li><a href="<?php echo BASE_URL; ?>/index.php#solutions">Solutions</a></li>
                <li><a href="<?php echo BASE_URL; ?>/index.php#process">Process</a></li>
                <li><a href="<?php echo BASE_URL; ?>/index.php#cases">Case Studies</a></li>
                <li><a href="<?php echo BASE_URL; ?>/index.php#about">About</a></li>
                <li><a href="<?php echo BASE_URL; ?>/index.php#contact">Contact</a></li>

                <?php if (isset($_SESSION['user_id'])): ?>
                    <li class="welcome-user">
                        <span class="welcome-text">
                            Welcome, <?php echo e($_SESSION['user_name']); ?>
                        </span>
                    </li>

                    <?php if (($_SESSION['role'] ?? 'client') === 'admin'): ?>
                        <li><a href="<?php echo BASE_URL; ?>/admin/dashboard.php" class="btn btn-sm btn-primary">Admin</a></li>
                    <?php else: ?>
                        <li><a href="<?php echo BASE_URL; ?>/dashboard/index.php" class="btn btn-sm btn-primary">Dashboard</a></li>
                    <?php endif; ?>

                    <li><a href="<?php echo BASE_URL; ?>/logout.php" class="btn btn-sm btn-primary">Logout</a></li>
                <?php else: ?>
                    <li>
                        <button class="btn btn-signin" id="openSignIn" type="button">
                            Sign In
                        </button>
                    </li>
                <?php endif; ?>
            </ul>
        </nav>
    </div>
</header>