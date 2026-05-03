<?php
$currentPage = basename($_SERVER['PHP_SELF']);
?>

<nav class="mobile-bottom-nav">
    <a href="../member/dashboard.php" class="<?= $currentPage === 'dashboard.php' ? 'active' : '' ?>">
        <i class="bi bi-house-door-fill"></i>
        <span>Home</span>
    </a>

    <a href="../member/memberships.php" class="<?= $currentPage === 'memberships.php' ? 'active' : '' ?>">
        <i class="bi bi-award-fill"></i>
        <span>Member</span>
    </a>

    <a href="../member/payments.php" class="<?= $currentPage === 'payments.php' ? 'active' : '' ?>">
        <i class="bi bi-wallet2"></i>
        <span>Pay</span>
    </a>

    <a href="../member/checkin.php" class="<?= $currentPage === 'checkin.php' ? 'active' : '' ?>">
        <i class="bi bi-box-arrow-in-right"></i>
        <span>Check</span>
    </a>

    <a href="../member/profile.php" class="<?= $currentPage === 'profile.php' ? 'active' : '' ?>">
        <i class="bi bi-person-circle"></i>
        <span>Profile</span>
    </a>
</nav>