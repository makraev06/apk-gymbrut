<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../config/database.php';

$currentFolder = basename(dirname($_SERVER['PHP_SELF']));
$isAdminPage = ($currentFolder === 'admin');
$isMemberPage = ($currentFolder === 'member');

if ($isAdminPage) {
    require_once __DIR__ . '/auth_admin.php';
} elseif ($isMemberPage) {
    require_once __DIR__ . '/auth_user.php';
}

$pageTitle = $pageTitle ?? 'GYMBRUT';
$basePath = ($isAdminPage || $isMemberPage) ? '../' : '';
?>
<!DOCTYPE html>
<html lang="id">
<?php include __DIR__ . '/head.php'; ?>

<body>
    <div class="app-shell">
        <?php if ($isAdminPage): ?>
            <?php include __DIR__ . '/sidebar_admin.php'; ?>
        <?php elseif ($isMemberPage): ?>
            <?php include __DIR__ . '/sidebar_member.php'; ?>
        <?php endif; ?>

        <main class="main-content">
            <div class="content-body">
                <?php
                if ($isAdminPage || $isMemberPage) {
                    include __DIR__ . '/topbar.php';
                }
                ?>