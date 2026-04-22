<?php
/* member/reports.php */
session_start();
$_SESSION['role'] = $_SESSION['role'] ?? 'member';
$_SESSION['name'] = $_SESSION['name'] ?? 'Michael Member';

$pageTitle = 'My Reports';
$topbarTitle = 'Laporan Aktivitas';
$topbarSubtitle = 'Ringkasan aktivitas gym dan membership kamu dalam satu tampilan.';
$searchPlaceholder = 'Cari ringkasan aktivitas...';

include '../includes/layout_top.php';
?>

<section class="page-section">
  <div class="page-grid grid-4">
    <div class="stat-card">
      <p class="stat-label">Total Check-in</p>
      <h3 class="stat-value">18x</h3>
      <div class="stat-meta">Aktif bulan ini</div>
    </div>

    <div class="stat-card">
      <p class="stat-label">Total Pembayaran</p>
      <h3 class="stat-value">3x</h3>
      <div class="stat-meta">Transaksi membership</div>
    </div>

    <div class="stat-card">
      <p class="stat-label">Progress Latihan</p>
      <h3 class="stat-value">90%</h3>
      <div class="stat-meta">Target mingguan tercapai</div>
    </div>

    <div class="stat-card">
      <p class="stat-label">Membership Aktif</p>
      <h3 class="stat-value">Premium</h3>
      <div class="stat-meta">Sisa 69 hari</div>
    </div>
  </div>

  <div class="table-card">
    <div class="card-header-inline">
      <div>
        <h3 class="section-title">Ringkasan Aktivitas Saya</h3>
        <p class="section-subtitle">Gambaran singkat performa dan keaktifan gym kamu.</p>
      </div>
    </div>

    <div class="card-list">
      <div class="list-row">
        <div>
          <p class="list-row-title">Check-in Paling Aktif</p>
          <p class="list-row-subtitle">Hari Senin dan Rabu di jam sore</p>
        </div>
        <span class="badge-soft badge-info">Kebiasaan</span>
      </div>

      <div class="list-row">
        <div>
          <p class="list-row-title">Pembayaran Terakhir</p>
          <p class="list-row-subtitle">INV-24081 • Rp 650.000 • 20 Apr 2026</p>
        </div>
        <span class="badge-soft badge-active">Lunas</span>
      </div>

      <div class="list-row">
        <div>
          <p class="list-row-title">Program Terbanyak Diikuti</p>
          <p class="list-row-subtitle">Fat Loss dan Cardio Blast</p>
        </div>
        <span class="badge-soft badge-info">Workout</span>
      </div>

      <div class="list-row">
        <div>
          <p class="list-row-title">Catatan Progres</p>
          <p class="list-row-subtitle">Berat turun 2 kg dalam 6 minggu dengan latihan konsisten.</p>
        </div>
        <span class="badge-soft badge-active">Bagus</span>
      </div>
    </div>
  </div>
</section>

<?php include '../includes/layout_bottom.php'; ?>