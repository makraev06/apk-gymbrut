<?php
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

$_SESSION['role'] = $_SESSION['role'] ?? 'member';
$_SESSION['name'] = $_SESSION['name'] ?? 'Member';

$pageTitle = 'Progress Member';
$topbarTitle = 'Progress Saya';
$topbarSubtitle = 'Pantau perubahan berat badan, target, dan performa latihan kamu.';
$searchPlaceholder = 'Cari progres...';

include '../includes/layout_top.php';
?>

<div class="hero-banner mb-4">
  <span class="banner-pill">
    <i class="bi bi-graph-up-arrow"></i> Progress Overview
  </span>

  <h2 style="margin: 14px 0 8px; font-size: 2rem; font-weight: 800;">
    Lihat perkembangan latihan kamu dengan lebih jelas
  </h2>

  <p class="text-soft" style="margin: 0; max-width: 760px;">
    Pantau berat badan, target fitness, dan konsistensi latihan dalam satu halaman yang rapi dan mudah dibaca.
  </p>
</div>

<div class="row g-4 mb-4">
  <div class="col-md-6 col-xl-3">
    <div class="stat-card h-100">
      <div style="display:flex; justify-content:space-between; align-items:flex-start; gap:14px;">
        <div>
          <div class="stat-label">Berat Awal</div>
          <div class="stat-value">70 kg</div>
          <div class="text-soft" style="font-size: 0.9rem; margin-top: 10px;">
            Saat mulai program
          </div>
        </div>
        <div class="stat-icon">
          <i class="bi bi-speedometer2"></i>
        </div>
      </div>
    </div>
  </div>

  <div class="col-md-6 col-xl-3">
    <div class="stat-card h-100">
      <div style="display:flex; justify-content:space-between; align-items:flex-start; gap:14px;">
        <div>
          <div class="stat-label">Berat Sekarang</div>
          <div class="stat-value">68 kg</div>
          <div class="text-soft" style="font-size: 0.9rem; margin-top: 10px;">
            Update minggu ini
          </div>
        </div>
        <div class="stat-icon">
          <i class="bi bi-heart-pulse-fill"></i>
        </div>
      </div>
    </div>
  </div>

  <div class="col-md-6 col-xl-3">
    <div class="stat-card h-100">
      <div style="display:flex; justify-content:space-between; align-items:flex-start; gap:14px;">
        <div>
          <div class="stat-label">Target Berat</div>
          <div class="stat-value">65 kg</div>
          <div class="text-soft" style="font-size: 0.9rem; margin-top: 10px;">
            Target 2 bulan
          </div>
        </div>
        <div class="stat-icon">
          <i class="bi bi-bullseye"></i>
        </div>
      </div>
    </div>
  </div>

  <div class="col-md-6 col-xl-3">
    <div class="stat-card h-100">
      <div style="display:flex; justify-content:space-between; align-items:flex-start; gap:14px;">
        <div>
          <div class="stat-label">Total Workout</div>
          <div class="stat-value">22</div>
          <div class="text-soft" style="font-size: 0.9rem; margin-top: 10px;">
            Sesi latihan selesai
          </div>
        </div>
        <div class="stat-icon">
          <i class="bi bi-fire"></i>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="row g-4 mb-4">
  <div class="col-xl-8">
    <div class="premium-card h-100">
      <div
        style="display:flex; justify-content:space-between; align-items:center; gap:14px; margin-bottom:16px; flex-wrap:wrap;">
        <div>
          <div class="section-title">Grafik Progress Berat</div>
          <div class="text-soft" style="font-size:0.9rem;">
            Perubahan berat badan selama 6 minggu terakhir
          </div>
        </div>

        <span class="badge-soft badge-info">Progress Live</span>
      </div>

      <div class="dashboard-chart-wrap">
        <canvas id="progressChart"></canvas>
      </div>
    </div>
  </div>

  <div class="col-xl-4">
    <div class="premium-card h-100">
      <div class="section-title" style="margin-bottom: 16px;">Ringkasan Progress</div>

      <ul class="metric-list" style="list-style:none; margin:0; padding:0;">
        <li>
          <span>Berat turun</span>
          <strong>2 kg</strong>
        </li>
        <li>
          <span>Workout selesai</span>
          <strong>22 sesi</strong>
        </li>
        <li>
          <span>Check-in bulan ini</span>
          <strong>18x</strong>
        </li>
        <li>
          <span>Program dominan</span>
          <strong>Fat Loss</strong>
        </li>
        <li>
          <span>Konsistensi</span>
          <strong>Baik</strong>
        </li>
      </ul>
    </div>
  </div>
</div>

<div class="row g-4">
  <div class="col-xl-6">
    <div class="premium-card h-100">
      <div class="section-title" style="margin-bottom: 16px;">Catatan Latihan</div>

      <div style="display:grid; gap:12px;">
        <div class="glass-soft" style="padding:14px;">
          <strong>Minggu 1</strong>
          <div class="text-soft" style="font-size:0.92rem; margin-top:6px;">
            Fokus adaptasi latihan dan cardio ringan.
          </div>
        </div>

        <div class="glass-soft" style="padding:14px;">
          <strong>Minggu 3</strong>
          <div class="text-soft" style="font-size:0.92rem; margin-top:6px;">
            Intensitas mulai naik, stamina membaik.
          </div>
        </div>

        <div class="glass-soft" style="padding:14px;">
          <strong>Minggu 6</strong>
          <div class="text-soft" style="font-size:0.92rem; margin-top:6px;">
            Berat turun stabil dan latihan makin konsisten.
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="col-xl-6">
    <div class="premium-card h-100">
      <div class="section-title" style="margin-bottom: 16px;">Target Berikutnya</div>

      <div style="display:grid; gap:12px;">
        <div class="glass-soft" style="padding:14px;">
          <strong>Target Berat</strong>
          <div class="text-soft" style="font-size:0.92rem; margin-top:6px;">
            Turun ke 65 kg dalam 8 minggu.
          </div>
        </div>

        <div class="glass-soft" style="padding:14px;">
          <strong>Target Workout</strong>
          <div class="text-soft" style="font-size:0.92rem; margin-top:6px;">
            Minimal 4 sesi latihan per minggu.
          </div>
        </div>

        <div class="glass-soft" style="padding:14px;">
          <strong>Target Konsistensi</strong>
          <div class="text-soft" style="font-size:0.92rem; margin-top:6px;">
            Jaga pola check-in dan tidur teratur.
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
  const progressCtx = document.getElementById('progressChart');
  if (progressCtx) {
    new Chart(progressCtx, {
      type: 'line',
      data: {
        labels: ['Minggu 1', 'Minggu 2', 'Minggu 3', 'Minggu 4', 'Minggu 5', 'Minggu 6'],
        datasets: [{
          label: 'Berat Badan',
          data: [70, 69.7, 69.2, 68.9, 68.4, 68],
          borderColor: '#ff6b00',
          backgroundColor: 'rgba(255,107,0,0.08)',
          fill: true,
          tension: 0.35,
          borderWidth: 3,
          pointRadius: 3,
          pointHoverRadius: 5
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: {
            labels: { color: '#64748b' }
          }
        },
        scales: {
          x: {
            ticks: { color: '#64748b' },
            grid: { color: 'rgba(148, 163, 184, 0.15)' }
          },
          y: {
            ticks: { color: '#64748b' },
            grid: { color: 'rgba(148, 163, 184, 0.15)' }
          }
        }
      }
    });
  }
</script>

<?php include '../includes/layout_bottom.php'; ?>