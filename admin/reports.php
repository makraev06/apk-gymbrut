<?php
/* admin/reports.php */
session_start();

$pageTitle = 'Reports';
$topbarTitle = 'Reports';
$topbarSubtitle = 'Laporan pendapatan, member, dan performa gym.';
$searchPlaceholder = 'Cari laporan...';

include '../includes/layout_top.php';

$todayIncome = gymbrut_query_one($conn, "
  SELECT COALESCE(SUM(amount), 0) AS total
  FROM payments
  WHERE status = 'verified'
  AND DATE(payment_date) = CURRENT_DATE()
", ['total' => 0])['total'];

$monthIncome = gymbrut_query_one($conn, "
  SELECT COALESCE(SUM(amount), 0) AS total
  FROM payments
  WHERE status = 'verified'
  AND MONTH(payment_date) = MONTH(CURRENT_DATE())
  AND YEAR(payment_date) = YEAR(CURRENT_DATE())
", ['total' => 0])['total'];

$newMembersMonth = gymbrut_query_one($conn, "
  SELECT COUNT(*) AS total
  FROM users
  WHERE role = 'member'
  AND MONTH(created_at) = MONTH(CURRENT_DATE())
  AND YEAR(created_at) = YEAR(CURRENT_DATE())
", ['total' => 0])['total'];

$bestPackage = gymbrut_query_one($conn, "
  SELECT mp.package_name, COUNT(*) AS total
  FROM memberships m
  JOIN membership_packages mp ON m.package_id = mp.package_id
  GROUP BY mp.package_id
  ORDER BY total DESC
  LIMIT 1
", ['package_name' => 'Belum ada'])['package_name'];

$monthlyReports = gymbrut_query_all($conn, "
  SELECT 
    DATE_FORMAT(u.created_at, '%M %Y') AS month_name,
    COUNT(DISTINCT u.user_id) AS new_members,
    COALESCE(SUM(CASE WHEN p.status = 'verified' THEN p.amount ELSE 0 END), 0) AS revenue,
    COALESCE((
      SELECT mp2.package_name
      FROM memberships m2
      JOIN membership_packages mp2 ON m2.package_id = mp2.package_id
      WHERE YEAR(m2.start_date) = YEAR(u.created_at)
      AND MONTH(m2.start_date) = MONTH(u.created_at)
      GROUP BY mp2.package_id
      ORDER BY COUNT(*) DESC
      LIMIT 1
    ), '-') AS best_package
  FROM users u
  LEFT JOIN memberships m ON u.user_id = m.user_id
  LEFT JOIN payments p ON m.membership_id = p.membership_id
  WHERE u.role = 'member'
  GROUP BY YEAR(u.created_at), MONTH(u.created_at)
  ORDER BY YEAR(u.created_at) DESC, MONTH(u.created_at) DESC
  LIMIT 6
");

$incomeChartRows = gymbrut_query_all($conn, "
  SELECT 
    DATE_FORMAT(payment_date, '%b') AS month_name,
    COALESCE(SUM(amount), 0) AS total
  FROM payments
  WHERE status = 'verified'
  GROUP BY YEAR(payment_date), MONTH(payment_date)
  ORDER BY YEAR(payment_date), MONTH(payment_date)
  LIMIT 6
");

$memberChartRows = gymbrut_query_all($conn, "
  SELECT 
    DATE_FORMAT(created_at, '%b') AS month_name,
    COUNT(*) AS total
  FROM users
  WHERE role = 'member'
  GROUP BY YEAR(created_at), MONTH(created_at)
  ORDER BY YEAR(created_at), MONTH(created_at)
  LIMIT 6
");

$incomeLabels = [];
$incomeData = [];
foreach ($incomeChartRows as $row) {
  $incomeLabels[] = $row['month_name'];
  $incomeData[] = (int) $row['total'];
}

$memberLabels = [];
$memberData = [];
foreach ($memberChartRows as $row) {
  $memberLabels[] = $row['month_name'];
  $memberData[] = (int) $row['total'];
}

if (empty($incomeLabels)) {
  $incomeLabels = ['Jan', 'Feb', 'Mar', 'Apr'];
  $incomeData = [0, 0, 0, 0];
}

if (empty($memberLabels)) {
  $memberLabels = ['Jan', 'Feb', 'Mar', 'Apr'];
  $memberData = [0, 0, 0, 0];
}
?>

<div class="dashboard-hero">
  <div>
    <span class="banner-pill">
      <i class="bi bi-bar-chart-fill"></i> Reports Overview
    </span>
    <h2>Laporan performa gym lebih rapi dan mudah dibaca.</h2>
    <p>
      Pantau pendapatan, pertumbuhan member, dan paket terlaris dari data database.
    </p>
  </div>

  <div class="dashboard-hero-card">
    <span>Paket Terlaris</span>
    <strong><?= e($bestPackage) ?></strong>
    <small>Berdasarkan data membership</small>
  </div>
</div>

<section class="page-section">
  <div class="page-grid grid-4">
    <div class="stat-card stat-card-modern">
      <div>
        <p class="stat-label">Pendapatan Hari Ini</p>
        <h3 class="stat-value">Rp <?= number_format($todayIncome, 0, ',', '.') ?></h3>
        <div class="stat-meta">Pembayaran verified hari ini</div>
      </div>
      <div class="stat-icon"><i class="bi bi-cash-stack"></i></div>
    </div>

    <div class="stat-card stat-card-modern">
      <div>
        <p class="stat-label">Pendapatan Bulan Ini</p>
        <h3 class="stat-value">Rp <?= number_format($monthIncome, 0, ',', '.') ?></h3>
        <div class="stat-meta">Akumulasi bulan berjalan</div>
      </div>
      <div class="stat-icon"><i class="bi bi-wallet2"></i></div>
    </div>

    <div class="stat-card stat-card-modern">
      <div>
        <p class="stat-label">Member Baru</p>
        <h3 class="stat-value"><?= number_format($newMembersMonth) ?></h3>
        <div class="stat-meta">Member baru bulan ini</div>
      </div>
      <div class="stat-icon"><i class="bi bi-person-plus-fill"></i></div>
    </div>

    <div class="stat-card stat-card-modern">
      <div>
        <p class="stat-label">Paket Terlaris</p>
        <h3 class="stat-value" style="font-size:22px;"><?= e($bestPackage) ?></h3>
        <div class="stat-meta">Paling banyak dipilih</div>
      </div>
      <div class="stat-icon"><i class="bi bi-award-fill"></i></div>
    </div>
  </div>

  <div class="row g-4">
    <div class="col-xl-7">
      <div class="premium-card h-100">
        <div class="card-header-inline">
          <div>
            <h3 class="section-title">Grafik Pendapatan</h3>
            <p class="section-subtitle">Total pembayaran verified per bulan.</p>
          </div>
          <span class="badge-soft badge-info">Live Data</span>
        </div>

        <div class="dashboard-chart-wrap">
          <canvas id="incomeReportChart"></canvas>
        </div>
      </div>
    </div>

    <div class="col-xl-5">
      <div class="premium-card h-100">
        <div class="card-header-inline">
          <div>
            <h3 class="section-title">Pertumbuhan Member</h3>
            <p class="section-subtitle">Jumlah member baru per bulan.</p>
          </div>
        </div>

        <div class="dashboard-chart-wrap">
          <canvas id="memberReportChart"></canvas>
        </div>
      </div>
    </div>
  </div>

  <div class="table-card">
    <div class="card-header-inline">
      <div>
        <h3 class="section-title">Ringkasan Statistik Bulanan</h3>
        <p class="section-subtitle">Laporan pendapatan dan pertumbuhan member.</p>
      </div>

      <a href="#" class="gradient-btn btn-sm">
        <i class="bi bi-download"></i> Download Laporan
      </a>
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
          <?php if (empty($monthlyReports)): ?>
            <tr>
              <td colspan="4" class="text-soft">Belum ada data laporan.</td>
            </tr>
          <?php endif; ?>

          <?php foreach ($monthlyReports as $report): ?>
            <tr>
              <td><strong><?= e($report['month_name']) ?></strong></td>
              <td>Rp <?= number_format($report['revenue'], 0, ',', '.') ?></td>
              <td><?= number_format($report['new_members']) ?> orang</td>
              <td><?= e($report['best_package']) ?></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
</section>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
  const incomeCtx = document.getElementById('incomeReportChart');
  const memberCtx = document.getElementById('memberReportChart');

  if (incomeCtx) {
    new Chart(incomeCtx, {
      type: 'line',
      data: {
        labels: <?= json_encode($incomeLabels) ?>,
        datasets: [{
          label: 'Pendapatan',
          data: <?= json_encode($incomeData) ?>,
          borderColor: '#ff7a00',
          backgroundColor: 'rgba(255, 122, 0, 0.12)',
          fill: true,
          tension: 0.35,
          borderWidth: 3
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: { legend: { display: false } },
        scales: {
          y: { beginAtZero: true },
          x: { grid: { display: false } }
        }
      }
    });
  }

  if (memberCtx) {
    new Chart(memberCtx, {
      type: 'bar',
      data: {
        labels: <?= json_encode($memberLabels) ?>,
        datasets: [{
          label: 'Member Baru',
          data: <?= json_encode($memberData) ?>,
          backgroundColor: 'rgba(255, 122, 0, 0.75)',
          borderRadius: 12
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: { legend: { display: false } },
        scales: {
          y: { beginAtZero: true, ticks: { precision: 0 } },
          x: { grid: { display: false } }
        }
      }
    });
  }
</script>

<?php include '../includes/layout_bottom.php'; ?>