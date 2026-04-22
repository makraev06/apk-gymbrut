<?php
/* member/dashboard.php */
session_start();
$_SESSION['role'] = $_SESSION['role'] ?? 'member';
$_SESSION['name'] = $_SESSION['name'] ?? 'Michael Member';

$pageTitle = 'Member Dashboard';
$topbarTitle = 'Dashboard Member';
$topbarSubtitle = 'Lihat membership, progress, dan aktivitas gym kamu dalam satu halaman.';
$searchPlaceholder = 'Cari workout, pembayaran, membership...';

include '../includes/layout_top.php';

$memberName = $_SESSION['name'] ?? 'Member';
$quickStats = [
    ['label' => 'Membership', 'value' => 'Premium Plus', 'meta' => 'Aktif sampai 30 Jun 2026', 'icon' => 'bi bi-award-fill'],
    ['label' => 'Check-in Bulan Ini', 'value' => '18x', 'meta' => 'Konsisten 4 minggu terakhir', 'icon' => 'bi bi-box-arrow-in-right'],
    ['label' => 'Berat Saat Ini', 'value' => '68 kg', 'meta' => 'Turun 2 kg dari bulan lalu', 'icon' => 'bi bi-speedometer2'],
    ['label' => 'Pembayaran Terakhir', 'value' => 'Rp 650.000', 'meta' => 'Berhasil pada 20 Apr 2026', 'icon' => 'bi bi-wallet2'],
];

$workoutSchedule = [
    ['day' => 'Senin', 'program' => 'Strength Builder', 'time' => '18:00 WIB'],
    ['day' => 'Rabu', 'program' => 'Cardio Blast', 'time' => '17:30 WIB'],
    ['day' => 'Jumat', 'program' => 'Fat Loss Training', 'time' => '18:15 WIB'],
];

$recentPayments = [
    ['invoice' => 'INV-24081', 'amount' => 'Rp 650.000', 'status' => 'Paid', 'date' => '20 Apr 2026'],
    ['invoice' => 'INV-23890', 'amount' => 'Rp 650.000', 'status' => 'Paid', 'date' => '20 Mar 2026'],
];
?>

<section class="page-section">
    <div class="card-soft">
        <div class="card-header-inline">
            <div>
                <h3 class="section-title">Halo, <?= e($memberName) ?> 👋</h3>
                <p class="section-subtitle">Semangat latihan hari ini. Jangan lupa check-in dan jaga progresmu tetap
                    stabil.</p>
            </div>

            <div class="d-flex align-center gap-8">
                <a href="checkin.php" class="gradient-btn btn-sm">
                    <i class="bi bi-box-arrow-in-right"></i> Check In
                </a>
                <a href="memberships.php" class="btn-outline-soft btn-sm">
                    <i class="bi bi-award"></i> Lihat Membership
                </a>
            </div>
        </div>
    </div>

    <div class="page-grid grid-4">
        <?php foreach ($quickStats as $item): ?>
            <div class="stat-card">
                <div class="d-flex align-center justify-between gap-12">
                    <div>
                        <p class="stat-label"><?= e($item['label']) ?></p>
                        <h3 class="stat-value" style="font-size:24px;"><?= e($item['value']) ?></h3>
                        <div class="stat-meta"><?= e($item['meta']) ?></div>
                    </div>
                    <div class="stat-icon">
                        <i class="<?= e($item['icon']) ?>"></i>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <div class="page-grid grid-2">
        <div class="table-card">
            <div class="card-header-inline">
                <div>
                    <h3 class="section-title">Jadwal Workout Minggu Ini</h3>
                    <p class="section-subtitle">Latihan yang sudah tersusun untuk menjaga progresmu.</p>
                </div>
            </div>

            <div class="card-list">
                <?php foreach ($workoutSchedule as $item): ?>
                    <div class="list-row">
                        <div>
                            <p class="list-row-title"><?= e($item['day']) ?> — <?= e($item['program']) ?></p>
                            <p class="list-row-subtitle">Jadwal mulai <?= e($item['time']) ?></p>
                        </div>
                        <span class="badge-soft badge-info">Terjadwal</span>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="table-card">
            <div class="card-header-inline">
                <div>
                    <h3 class="section-title">Riwayat Pembayaran Terakhir</h3>
                    <p class="section-subtitle">Status pembayaran membership terbaru kamu.</p>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Invoice</th>
                            <th>Nominal</th>
                            <th>Status</th>
                            <th>Tanggal</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($recentPayments as $payment): ?>
                            <tr>
                                <td><strong><?= e($payment['invoice']) ?></strong></td>
                                <td><?= e($payment['amount']) ?></td>
                                <td><span class="badge-soft badge-active"><?= e($payment['status']) ?></span></td>
                                <td><?= e($payment['date']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>

<?php include '../includes/layout_bottom.php'; ?>