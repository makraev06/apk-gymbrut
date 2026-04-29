<?php
/* admin/memberships.php */
session_start();

$pageTitle = 'Membership Packages';
$topbarTitle = 'Memberships';
$topbarSubtitle = 'Atur paket membership gym dengan benefit yang jelas dan modern.';
$searchPlaceholder = 'Cari paket membership...';

include '../../includes/layout_top.php';

$packages = gymbrut_query_all($conn, "
  SELECT 
    package_id,
    package_name,
    duration_days,
    price,
    description
  FROM membership_packages
  ORDER BY package_id DESC
");
?>

<section class="page-section">
  <div class="membership-section-header">
    <div>
      <h3 class="section-title">Paket Membership</h3>
      <p class="section-subtitle">Pilih dan kelola paket yang tersedia untuk member.</p>
    </div>

    <a href="addMembership.php" class="gradient-btn btn-sm">
      <i class="bi bi-plus-lg"></i> Tambah Paket
    </a>
  </div>

  <?php if (empty($packages)): ?>
    <div class="card-soft">
      <p class="text-soft mb-0">Belum ada paket membership di database.</p>
    </div>
  <?php else: ?>
    <div class="page-grid grid-2">
      <?php foreach ($packages as $package): ?>
        <div class="card-soft">
          <div class="card-header-inline">
            <div>
              <h3 class="section-title"><?= e($package['package_name']) ?></h3>
              <p class="section-subtitle">
                <?= e($package['duration_days']) ?> Hari •
                Rp <?= number_format($package['price'], 0, ',', '.') ?>
              </p>
            </div>

            <span class="badge-soft badge-active">Active</span>
          </div>

          <div class="card-list">
            <div class="list-row">
              <div>
                <p class="list-row-title">Benefit Paket</p>
                <p class="list-row-subtitle">
                  <?= !empty($package['description']) ? e($package['description']) : 'Belum ada deskripsi paket.' ?>
                </p>
              </div>
            </div>
          </div>

          <div class="d-flex align-center gap-8 mt-3">
            <a href="editMembership.php?id=<?= e($package['package_id']) ?>" class="btn-outline-soft btn-sm">
              <i class="bi bi-pencil-square"></i> Edit
            </a>

            <a href="deleteMembership.php?id=<?= e($package['package_id']) ?>" class="btn-outline-soft btn-sm"
              onclick="return confirm('Yakin ingin menghapus paket ini?')">
              <i class="bi bi-trash3"></i> Hapus
            </a>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>
</section>

<?php include '../../includes/layout_bottom.php'; ?>