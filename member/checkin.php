<?php
/* member/checkin.php */
session_start();
$_SESSION['role'] = $_SESSION['role'] ?? 'member';
$_SESSION['name'] = $_SESSION['name'] ?? 'Michael Member';

$pageTitle = 'Gym Check In';
$topbarTitle = 'Check In';
$topbarSubtitle = 'Pantau status check-in harian dan riwayat kunjungan gym kamu.';
$searchPlaceholder = 'Cari riwayat check-in...';

include '../includes/layout_top.php';

$todayStatus = 'Belum Check-in';
$history = [
  ['date' => '21 Apr 2026', 'time' => '17:42 WIB', 'status' => 'Selesai'],
  ['date' => '19 Apr 2026', 'time' => '18:03 WIB', 'status' => 'Selesai'],
  ['date' => '17 Apr 2026', 'time' => '16:55 WIB', 'status' => 'Selesai'],
  ['date' => '15 Apr 2026', 'time' => '17:18 WIB', 'status' => 'Selesai'],
];
?>

<section class="page-section">
  <div class="page-grid grid-2">
    <div class="card-soft">
      <div class="card-header-inline">
        <div>
          <h3 class="section-title">Status Hari Ini</h3>
          <p class="section-subtitle">Silakan lakukan check-in sebelum mulai latihan.</p>
        </div>
        <span class="badge-soft badge-pending"><?= e($todayStatus) ?></span>
      </div>

      <p class="section-subtitle" style="line-height:1.8;">
        Dengan check-in rutin, kamu bisa memantau konsistensi latihan dan progres kebugaran dengan lebih mudah.
      </p>

      <div class="mt-3">
        <button type="button" class="gradient-btn">
          <i class="bi bi-box-arrow-in-right"></i> Check In Sekarang
        </button>
      </div>
    </div>

    <div class="card-soft">
      <div class="card-header-inline">
        <div>
          <h3 class="section-title">Ringkasan Aktivitas</h3>
          <p class="section-subtitle">Statistik kehadiran gym bulan ini.</p>
        </div>
      </div>

      <div class="page-grid grid-2">
        <div class="stat-card">
          <p class="stat-label">Total Check-in</p>
          <h3 class="stat-value">18x</h3>
          <div class="stat-meta">Bulan April 2026</div>
        </div>
        <div class="stat-card">
          <p class="stat-label">Target Bulanan</p>
          <h3 class="stat-value">20x</h3>
          <div class="stat-meta">Tinggal 2x lagi</div>
        </div>
      </div>
    </div>
  </div>

  <div class="table-card">
    <div class="card-header-inline">
      <div>
        <h3 class="section-title">Riwayat Check-in</h3>
        <p class="section-subtitle">Daftar kehadiran gym terbaru kamu.</p>
      </div>
    </div>

    <div class="table-responsive">
      <table class="table">
        <thead>
          <tr>
            <th>Tanggal</th>
            <th>Jam</th>
            <th>Status</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($history as $item): ?>
            <tr>
              <td><strong><?= e($item['date']) ?></strong></td>
              <td><?= e($item['time']) ?></td>
              <td><span class="badge-soft badge-active"><?= e($item['status']) ?></span></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
</section>

<?php include '../includes/layout_bottom.php'; ?>