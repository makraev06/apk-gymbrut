<?php
/* admin/memberships.php */
session_start();
$_SESSION['role'] = $_SESSION['role'] ?? 'admin';
$_SESSION['name'] = $_SESSION['name'] ?? 'Michael Admin';

$pageTitle = 'Membership Packages';
$topbarTitle = 'Memberships';
$topbarSubtitle = 'Atur paket membership gym dengan benefit yang jelas dan modern.';
$searchPlaceholder = 'Cari paket membership...';

include '../includes/layout_top.php';

$packages = [
  [
    'name' => 'Basic Monthly',
    'duration' => '30 Hari',
    'price' => 'Rp 250.000',
    'benefit' => 'Akses gym reguler, locker area, konsultasi awal',
    'status' => 'Active'
  ],
  [
    'name' => 'Premium Plus',
    'duration' => '90 Hari',
    'price' => 'Rp 650.000',
    'benefit' => 'Gym akses penuh, kelas grup, 2x PT session',
    'status' => 'Active'
  ],
  [
    'name' => 'Fat Loss Plan',
    'duration' => '60 Hari',
    'price' => 'Rp 500.000',
    'benefit' => 'Meal guide, kelas cardio, monitoring mingguan',
    'status' => 'Active'
  ],
  [
    'name' => 'Student Package',
    'duration' => '30 Hari',
    'price' => 'Rp 180.000',
    'benefit' => 'Akses gym jam tertentu, harga hemat mahasiswa',
    'status' => 'Inactive'
  ],
];
?>

<section class="page-section">
  <div class="card-header-inline">
    <div>
      <h3 class="section-title">Paket Membership</h3>
      <p class="section-subtitle">Pilih dan kelola paket yang tersedia untuk member.</p>
    </div>
    <a href="#" class="gradient-btn btn-sm">
      <i class="bi bi-plus-lg"></i> Tambah Paket
    </a>
  </div>

  <div class="page-grid grid-2">
    <?php foreach ($packages as $package): ?>
      <div class="card-soft">
        <div class="card-header-inline">
          <div>
            <h3 class="section-title"><?= e($package['name']) ?></h3>
            <p class="section-subtitle"><?= e($package['duration']) ?> • <?= e($package['price']) ?></p>
          </div>
          <span class="badge-soft <?= $package['status'] === 'Active' ? 'badge-active' : 'badge-inactive' ?>">
            <?= e($package['status']) ?>
          </span>
        </div>

        <div class="card-list">
          <div class="list-row">
            <div>
              <p class="list-row-title">Benefit Paket</p>
              <p class="list-row-subtitle"><?= e($package['benefit']) ?></p>
            </div>
          </div>
        </div>

        <div class="d-flex align-center gap-8 mt-3">
          <a href="#" class="btn-outline-soft btn-sm"><i class="bi bi-eye"></i> Detail</a>
          <a href="#" class="btn-outline-soft btn-sm"><i class="bi bi-pencil-square"></i> Edit</a>
          <a href="#" class="btn-outline-soft btn-sm"><i class="bi bi-trash3"></i> Hapus</a>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
</section>

<?php include '../includes/layout_bottom.php'; ?>