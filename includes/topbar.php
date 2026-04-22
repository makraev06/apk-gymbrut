<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$topbarTitle = $topbarTitle ?? ($pageTitle ?? 'GYMBRUT');
$topbarSubtitle = $topbarSubtitle ?? 'Kelola aktivitas gym dengan tampilan yang rapi dan modern.';
$searchPlaceholder = $searchPlaceholder ?? 'Cari data...';

$userName = $_SESSION['name'] ?? 'Guest';
$role = strtolower($_SESSION['role'] ?? 'member');
$roleLabel = $role === 'admin' ? 'Admin' : 'Member';
?>

<header class="topbar">
    <div class="topbar-left">
        <h1><?= e($topbarTitle) ?></h1>
        <p><?= e($topbarSubtitle) ?></p>
    </div>

    <div class="topbar-right">
        <div class="search-box">
            <i class="bi bi-search"></i>
            <input type="text" placeholder="<?= e($searchPlaceholder) ?>">
        </div>

        <button type="button" class="icon-btn" title="Notifikasi">
            <i class="bi bi-bell"></i>
        </button>

        <div class="profile-chip">
            <div class="profile-chip-avatar">
                <?= strtoupper(substr($userName, 0, 1)) ?>
            </div>
            <div>
                <p class="profile-chip-name"><?= e($userName) ?></p>
                <p class="profile-chip-role"><?= e($roleLabel) ?></p>
            </div>
        </div>
    </div>
</header>