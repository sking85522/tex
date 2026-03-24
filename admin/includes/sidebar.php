<?php
$role = $_SESSION['admin_role'] ?? 'super_admin';
$current_page = basename($_SERVER['PHP_SELF']);
?>
<div class="sidebar">
    <div class="sidebar-brand">
        <i class="fas fa-rocket"></i> Tech Elevate X
    </div>
    <div class="sidebar-nav">
        <a href="index.php" class="<?php echo ($current_page == 'index.php') ? 'active' : ''; ?>">
            <i class="fas fa-fw fa-tachometer-alt"></i> Dashboard
        </a>

        <?php if($role == 'super_admin' || $role == 'chat_support'): ?>
        <a href="live_chat.php" class="<?php echo ($current_page == 'live_chat.php') ? 'active' : ''; ?>">
            <i class="fas fa-fw fa-comments"></i> Live Chat Support
        </a>
        <?php endif; ?>

        <?php if($role == 'super_admin' || $role == 'hr'): ?>
        <a href="careers.php" class="<?php echo ($current_page == 'careers.php') ? 'active' : ''; ?>">
            <i class="fas fa-fw fa-user-tie"></i> Job Applications (HR)
        </a>
        <?php endif; ?>

        <?php if($role == 'super_admin'): ?>
        <a href="users.php" class="<?php echo ($current_page == 'users.php') ? 'active' : ''; ?>">
            <i class="fas fa-fw fa-users"></i> Manage Users
        </a>
        <a href="services.php" class="<?php echo ($current_page == 'services.php') ? 'active' : ''; ?>">
            <i class="fas fa-fw fa-cogs"></i> Manage Services
        </a>
        <a href="portfolio.php" class="<?php echo ($current_page == 'portfolio.php') ? 'active' : ''; ?>">
            <i class="fas fa-fw fa-briefcase"></i> Portfolio Projects
        </a>
        <a href="settings.php" class="<?php echo ($current_page == 'settings.php') ? 'active' : ''; ?>">
            <i class="fas fa-fw fa-sliders-h"></i> CMS / Site Settings
        </a>
        <a href="staff.php" class="<?php echo ($current_page == 'staff.php') ? 'active' : ''; ?>">
            <i class="fas fa-fw fa-users-cog"></i> Staff Members
        </a>
        <?php endif; ?>
    </div>
</div>
