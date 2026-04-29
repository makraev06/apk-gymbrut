<?php
$pageTitle = 'Dashboard Admin';
$pageSubtitle = 'Ringkasan operasional GYMBRUT hari ini.';
include '../includes/layout_top.php';

$stats = [
    'total_member' => gymbrut_query_one($conn, "SELECT COUNT(*) AS total FROM users WHERE role='member'", ['total' => 0])['total'],
    'active_member' => gymbrut_query_one($conn, "SELECT COUNT(*) AS total FROM memberships WHERE status='aktif'", ['total' => 0])['total'],
    'pending_payment' => gymbrut_query_one($conn, "SELECT COUNT(*) AS total FROM payments WHERE status='pending'", ['total' => 0])['total'],
    'total_workout' => gymbrut_query_one($conn, "SELECT COUNT(*) AS total FROM workouts", ['total' => 0])['total'],
];

$income = gymbrut_query_one($conn, "
    SELECT COALESCE(SUM(amount), 0) AS total 
    FROM payments 
    WHERE status='verified'
    AND MONTH(payment_date) = MONTH(CURRENT_DATE())
    AND YEAR(payment_date) = YEAR(CURRENT_DATE())
", ['total' => 0])['total'];

$todayCheckin = gymbrut_query_one($conn, "
    SELECT COUNT(*) AS total 
    FROM checkins 
    WHERE DATE(checkin_time) = CURRENT_DATE()
", ['total' => 0])['total'];

$popularPackage = gymbrut_query_one($conn, "
    SELECT mp.package_name, COUNT(*) AS total
    FROM memberships m
    JOIN membership_packages mp ON m.package_id = mp.package_id
    GROUP BY mp.package_id
    ORDER BY total DESC
    LIMIT 1
", ['package_name' => 'Belum ada'])['package_name'];

$expiringSoon = gymbrut_query_one($conn, "
    SELECT COUNT(*) AS total
    FROM memberships
    WHERE status='aktif'
    AND end_date BETWEEN CURRENT_DATE() AND DATE_ADD(CURRENT_DATE(), INTERVAL 7 DAY)
", ['total' => 0])['total'];

$recentMembers = gymbrut_query_all($conn, "
    SELECT name, email, created_at
    FROM users
    WHERE role='member'
    ORDER BY created_at DESC
    LIMIT 5
");

$recentPayments = gymbrut_query_all($conn, "
    SELECT p.amount, p.status, p.payment_date, u.name
    FROM payments p
    JOIN memberships m ON p.membership_id = m.membership_id
    JOIN users u ON m.user_id = u.user_id
    ORDER BY p.payment_date DESC
    LIMIT 5
");

$chartRows = gymbrut_query_all($conn, "
    SELECT 
        DATE_FORMAT(created_at, '%b') AS month_name,
        COUNT(*) AS total
    FROM users
    WHERE role='member'
    GROUP BY YEAR(created_at), MONTH(created_at)
    ORDER BY YEAR(created_at), MONTH(created_at)
");

$chartLabels = [];
$chartData = [];

foreach ($chartRows as $row) {
    $chartLabels[] = $row['month_name'];
    $chartData[] = (int) $row['total'];
}

if (empty($chartLabels)) {
    $chartLabels = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun'];
    $chartData = [0, 0, 0, 0, 0, 0];
}
?>

<div class="dashboard-hero">
    <div>
        <span class="banner-pill">
            <i class="bi bi-lightning-charge-fill"></i> Admin Overview
        </span>

        <h2>Kelola operasional gym lebih cepat.</h2>
        <p>
            Pantau member, membership aktif, pembayaran, check-in, dan aktivitas terbaru
            dari satu dashboard yang lebih rapi.
        </p>
    </div>

    <div class="dashboard-hero-card">
        <span>Pendapatan bulan ini</span>
        <strong>Rp
            <?= number_format($income, 0, ',', '.') ?>
        </strong>
        <small>Dari pembayaran verified</small>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-md-6 col-xl-3">
        <div class="stat-card stat-card-modern">
            <div>
                <p class="stat-label">Total Member</p>
                <h3 class="stat-value">
                    <?= number_format($stats['total_member']) ?>
                </h3>
                <span class="stat-meta">Semua akun member</span>
            </div>
            <div class="stat-icon"><i class="bi bi-people-fill"></i></div>
        </div>
    </div>

    <div class="col-md-6 col-xl-3">
        <div class="stat-card stat-card-modern">
            <div>
                <p class="stat-label">Member Aktif</p>
                <h3 class="stat-value">
                    <?= number_format($stats['active_member']) ?>
                </h3>
                <span class="stat-meta">Membership status aktif</span>
            </div>
            <div class="stat-icon"><i class="bi bi-person-check-fill"></i></div>
        </div>
    </div>

    <div class="col-md-6 col-xl-3">
        <div class="stat-card stat-card-modern">
            <div>
                <p class="stat-label">Pending Payment</p>
                <h3 class="stat-value">
                    <?= number_format($stats['pending_payment']) ?>
                </h3>
                <span class="stat-meta">Menunggu verifikasi</span>
            </div>
            <div class="stat-icon"><i class="bi bi-wallet2"></i></div>
        </div>
    </div>

    <div class="col-md-6 col-xl-3">
        <div class="stat-card stat-card-modern">
            <div>
                <p class="stat-label">Workout Program</p>
                <h3 class="stat-value">
                    <?= number_format($stats['total_workout']) ?>
                </h3>
                <span class="stat-meta">Program tersedia</span>
            </div>
            <div class="stat-icon"><i class="bi bi-activity"></i></div>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-xl-8">
        <div class="premium-card h-100">
            <div class="card-header-inline">
                <div>
                    <h3 class="section-title">Pertumbuhan Member</h3>
                    <p class="section-subtitle">Jumlah member baru dalam 6 bulan terakhir</p>
                </div>
                <span class="badge-soft badge-info">Live Data</span>
            </div>

            <div class="dashboard-chart-wrap">
                <canvas id="memberGrowthChart"></canvas>
            </div>
        </div>
    </div>

    <div class="col-xl-4">
        <div class="premium-card h-100">
            <h3 class="section-title mb-3">Ringkasan Operasional</h3>

            <ul class="metric-list">
                <li>
                    <span>Check-in hari ini</span>
                    <strong>
                        <?= number_format($todayCheckin) ?> orang
                    </strong>
                </li>
                <li>
                    <span>Paket populer</span>
                    <strong>
                        <?= e($popularPackage) ?>
                    </strong>
                </li>
                <li>
                    <span>Membership hampir habis</span>
                    <strong>
                        <?= number_format($expiringSoon) ?> akun
                    </strong>
                </li>
                <li>
                    <span>Pembayaran pending</span>
                    <strong>
                        <?= number_format($stats['pending_payment']) ?> transaksi
                    </strong>
                </li>
            </ul>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-xl-6">
        <div class="premium-card h-100">
            <h3 class="section-title mb-3">Member Terbaru</h3>

            <div class="card-list">
                <?php if (empty($recentMembers)): ?>
                    <p class="text-soft mb-0">Belum ada member baru.</p>
                <?php endif; ?>

                <?php foreach ($recentMembers as $member): ?>
                    <div class="list-row">
                        <div>
                            <p class="list-row-title">
                                <?= e($member['name']) ?>
                            </p>
                            <p class="list-row-subtitle">
                                <?= e($member['email']) ?>
                            </p>
                        </div>
                        <span class="badge-soft badge-active">
                            <?= date('d M Y', strtotime($member['created_at'])) ?>
                        </span>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <div class="col-xl-6">
        <div class="premium-card h-100">
            <h3 class="section-title mb-3">Pembayaran Terbaru</h3>

            <div class="card-list">
                <?php if (empty($recentPayments)): ?>
                    <p class="text-soft mb-0">Belum ada pembayaran.</p>
                <?php endif; ?>

                <?php foreach ($recentPayments as $payment): ?>
                    <?php
                    $badge = 'badge-pending';
                    if ($payment['status'] === 'verified')
                        $badge = 'badge-active';
                    if ($payment['status'] === 'rejected')
                        $badge = 'badge-failed';
                    ?>

                    <div class="list-row">
                        <div>
                            <p class="list-row-title">
                                <?= e($payment['name']) ?>
                            </p>
                            <p class="list-row-subtitle">
                                Rp
                                <?= number_format($payment['amount'], 0, ',', '.') ?>
                            </p>
                        </div>
                        <span class="badge-soft <?= $badge ?>">
                            <?= e(ucfirst($payment['status'])) ?>
                        </span>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const memberCtx = document.getElementById('memberGrowthChart');

    if (memberCtx) {
        new Chart(memberCtx, {
            type: 'line',
            data: {
                labels: <?= json_encode($chartLabels) ?>,
                datasets: [{
                    label: 'Member Baru',
                    data: <?= json_encode($chartData) ?>,
                    borderColor: '#ff7a00',
                    backgroundColor: 'rgba(255, 122, 0, 0.12)',
                    fill: true,
                    tension: 0.35,
                    borderWidth: 3,
                    pointRadius: 4,
                    pointHoverRadius: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    x: {
                        grid: { display: false },
                        ticks: { color: '#64748b' }
                    },
                    y: {
                        beginAtZero: true,
                        grid: { color: 'rgba(148, 163, 184, 0.18)' },
                        ticks: { color: '#64748b', precision: 0 }
                    }
                }
            }
        });
    }
</script>

<?php include '../includes/layout_bottom.php'; ?>