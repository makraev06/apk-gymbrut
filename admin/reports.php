<?php
/* admin/reports.php */
session_start();
$_SESSION['role'] = $_SESSION['role'] ?? 'admin';
$_SESSION['name'] = $_SESSION['name'] ?? 'Michael Admin';

$pageTitle = 'Reports';
$topbarTitle = 'Reports';
$topbarSubtitle = 'Ringkasan laporan performa gym untuk kebutuhan operasional dan evaluasi.';
$searchPlaceholder = 'Cari laporan...';

include '../includes/layout_top.php';

$monthlyReports = [
  ['month' => 'Januari 2026', 'revenue' => 'Rp 28.000.000', 'new_members' => 54, 'best_package' => 'Basic Monthly'],
  ['month' => 'Februari 2026', 'revenue' => 'Rp 31.000.000', 'new_members' => 62, 'best_package' => 'Premium Plus'],
  ['month' => 'Maret 2026', 'revenue' => 'Rp 35.000.000', 'new_members' => 70, 'best_package' => 'Premium Plus'],
  ['month' => 'April 2026', 'revenue' => 'Rp 48.500.000', 'new_members' => 84, 'best_package' => 'Fat Loss Plan'],
];
?>

<section class="page-section">
  <div class="page-grid grid-4">
    <div class="stat-card">
      <p class="stat-label">Pendapatan Hari Ini</p>
      <h3 class="stat-value">Rp 3.250.000</h3>
      <div class="stat-meta">Naik 7% dari kemarin</div>
    </div>

    <div class="stat-card">
      <p class="stat-label">Pendapatan Bulan Ini</p>
      <h3 class="stat-value">Rp 48.500.000</h3>
      <div class="stat-meta">Performa terbaik semester ini</div>
    </div>

    <div class="stat-card">
      <p class="stat-label">Member Baru</p>
      <h3 class="stat-value">84</h3>
      <div class="stat-meta">Akuisisi member April 2026</div>
    </div>

    <div class="stat-card">
      <p class="stat-label">Paket Terlaris</p>
      <h3 class="stat-value" style="font-size:22px;">Fat Loss Plan</h3>
      <div class="stat-meta">Tertinggi minggu ini</div>
    </div>
  </div>

  <div class="table-card">
    <div class="card-header-inline">
      <div>
        <h3 class="section-title">Ringkasan Statistik</h3>
        <p class="section-subtitle">Laporan bulanan pendapatan dan pertumbuhan member.</p>
      </div>
      <a href="#" class="gradient-btn btn-sm"><i class="bi bi-download"></i> Download Laporan</a>
    </div>

    <div class="table-responsive">
      <table class="table">
        <thead>
          <tr>
            <th>Bulan</th>
            <th>Pendapatan</th>
            <th>Member Baru</th>
            <th>Paket Terlaris</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($monthlyReports as $report): ?>
            <tr>
              <td><strong><?= e($report['month']) ?></strong></td>
              <td><?= e($report['revenue']) ?></td>
              <td><?= e($report['new_members']) ?> orang</td>
              <td><?= e($report['best_package']) ?></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
</section>

<?php include '../includes/layout_bottom.php'; ?>