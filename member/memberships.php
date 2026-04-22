<?php
/* member/memberships.php */
session_start();
$_SESSION['role'] = $_SESSION['role'] ?? 'member';
$_SESSION['name'] = $_SESSION['name'] ?? 'Michael Member';

$pageTitle = 'My Membership';
$topbarTitle = 'Membership Saya';
$topbarSubtitle = 'Lihat detail paket aktif, masa berlaku, dan status membership kamu.';
$searchPlaceholder = 'Cari paket membership...';

include '../includes/layout_top.php';

$membership = [
  'package' => 'Premium Plus',
  'active_date' => '01 Apr 2026',
  'expired_date' => '30 Jun 2026',
  'status' => 'Active',
  'remaining' => '69 Hari',
  'benefits' => [
    'Akses gym tanpa batas',
    'Kelas cardio & strength',
    '2x personal trainer session',
    'Locker area & konsultasi rutin',
  ]
];
?>

<section class="page-section">
  <div class="page-grid grid-2">
    <div class="card-soft">
      <div class="card-header-inline">
        <div>
          <h3 class="section-title"><?= e($membership['package']) ?></h3>
          <p class="section-subtitle">Detail membership aktif kamu saat ini.</p>
        </div>
        <span class="badge-soft badge-active"><?= e($membership['status']) ?></span>
      </div>

      <div class="card-list">
        <div class="list-row">
          <div>
            <p class="list-row-title">Tanggal Aktif</p>
            <p class="list-row-subtitle"><?= e($membership['active_date']) ?></p>
          </div>
        </div>
        <div class="list-row">
          <div>
            <p class="list-row-title">Tanggal Berakhir</p>
            <p class="list-row-subtitle"><?= e($membership['expired_date']) ?></p>
          </div>
        </div>
        <div class="list-row">
          <div>
            <p class="list-row-title">Sisa Masa Aktif</p>
            <p class="list-row-subtitle"><?= e($membership['remaining']) ?></p>
          </div>
        </div>
      </div>
    </div>

    <div class="card-soft">
      <div class="card-header-inline">
        <div>
          <h3 class="section-title">Benefit Paket</h3>
          <p class="section-subtitle">Keuntungan yang kamu dapat dari membership ini.</p>
        </div>
      </div>

      <div class="card-list">
        <?php foreach ($membership['benefits'] as $benefit): ?>
          <div class="list-row">
            <div>
              <p class="list-row-title"><?= e($benefit) ?></p>
            </div>
            <span class="badge-soft badge-info">Benefit</span>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  </div>
</section>

<?php include '../includes/layout_bottom.php'; ?>