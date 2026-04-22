<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$currentPage = basename($_SERVER['PHP_SELF']);
$userName = $_SESSION['name'] ?? 'Admin';
$userRole = 'Administrator';

$menus = [
    ['file' => 'dashboard.php', 'label' => 'Dashboard', 'icon' => 'bi bi-grid-1x2-fill'],
    ['file' => 'members.php', 'label' => 'Members', 'icon' => 'bi bi-people-fill'],
    ['file' => 'memberships.php', 'label' => 'Memberships', 'icon' => 'bi bi-card-checklist'],
    ['file' => 'payments.php', 'label' => 'Payments', 'icon' => 'bi bi-credit-card-2-front-fill'],
    ['file' => 'reports.php', 'label' => 'Reports', 'icon' => 'bi bi-bar-chart-fill'],
    ['file' => 'workouts.php', 'label' => 'Workouts', 'icon' => 'bi bi-fire'],
    ['file' => 'profile.php', 'label' => 'Profile', 'icon' => 'bi bi-person-circle'],
];
?>

<aside class="sidebar">
    <div class="sidebar-brand">
        <div class="sidebar-brand-logo">
            <i class="bi bi-lightning-charge-fill"></i>
        </div>
        <div class="sidebar-brand-text">
            <h2>GYMBRUT</h2>
            <p>Admin Panel</p>
        </div>
    </div>

    <div class="sidebar-title">Main Menu</div>

    <nav class="sidebar-nav">
        <?php foreach ($menus as $menu): ?>
            <a href="<?= e($menu['file']) ?>" class="sidebar-link <?= $currentPage === $menu['file'] ? 'active' : '' ?>">
                <i class="<?= e($menu['icon']) ?>"></i>
                <span><?= e($menu['label']) ?></span>
            </a>
        <?php endforeach; ?>
    </nav>

    <div class="sidebar-footer">
        <div class="sidebar-user">
            <div class="sidebar-avatar">
                <?= strtoupper(substr($userName, 0, 1)) ?>
            </div>
            <div class="sidebar-user-info">
                <p class="sidebar-user-name"><?= e($userName) ?></p>
                <p class="sidebar-user-role"><?= e($userRole) ?></p>
            </div>
        </div>

        <a href="../logout.php" class="gradient-btn sidebar-logout">
            <i class="bi bi-box-arrow-right"></i>
            <span>Logout</span>
        </a>
    </div>
</aside>