<?php
/* member/profile.php */
session_start();
$_SESSION['role'] = $_SESSION['role'] ?? 'member';
$_SESSION['name'] = $_SESSION['name'] ?? 'Michael Member';

$pageTitle = 'My Profile';
$topbarTitle = 'Profil Saya';
$topbarSubtitle = 'Kelola data akun pribadi dan informasi membership kamu.';
$searchPlaceholder = 'Cari data profil...';

include '../includes/layout_top.php';

$profile = [
  'name' => $_SESSION['name'] ?? 'Michael Member',
  'email' => 'member@gymbrut.com',
  'phone' => '0812-1122-3344',
  'address' => 'Jl. Fitness No. 12, Palembang',
  'package' => 'Premium Plus',
];
?>

<section class="page-section">
  <div class="page-grid grid-2">
    <div class="card-soft">
      <div class="d-flex align-center gap-12 mb-3">
        <div class="profile-chip-avatar" style="width:64px;height:64px;border-radius:18px;">
          <?= strtoupper(substr($profile['name'], 0, 1)) ?>
        </div>
        <div>
          <h3 class="section-title mb-0"><?= e($profile['name']) ?></h3>
          <p class="section-subtitle"><?= e($profile['package']) ?></p>
        </div>
      </div>

      <div class="card-list">
        <div class="list-row">
          <div>
            <p class="list-row-title">Email</p>
            <p class="list-row-subtitle"><?= e($profile['email']) ?></p>
          </div>
        </div>
        <div class="list-row">
          <div>
            <p class="list-row-title">Nomor HP</p>
            <p class="list-row-subtitle"><?= e($profile['phone']) ?></p>
          </div>
        </div>
        <div class="list-row">
          <div>
            <p class="list-row-title">Alamat</p>
            <p class="list-row-subtitle"><?= e($profile['address']) ?></p>
          </div>
        </div>
        <div class="list-row">
          <div>
            <p class="list-row-title">Paket Aktif</p>
            <p class="list-row-subtitle"><?= e($profile['package']) ?></p>
          </div>
        </div>
      </div>
    </div>

    <div class="form-card">
      <div class="card-header-inline">
        <div>
          <h3 class="section-title">Update Profil</h3>
          <p class="section-subtitle">Edit data member dengan form yang clean dan konsisten.</p>
        </div>
      </div>

      <form class="form-grid">
        <div class="form-group">
          <label class="form-label">Nama</label>
          <input type="text" class="form-control" value="<?= e($profile['name']) ?>">
        </div>

        <div class="form-group">
          <label class="form-label">Email</label>
          <input type="email" class="form-control" value="<?= e($profile['email']) ?>">
        </div>

        <div class="form-group">
          <label class="form-label">Nomor HP</label>
          <input type="text" class="form-control" value="<?= e($profile['phone']) ?>">
        </div>

        <div class="form-group">
          <label class="form-label">Paket Aktif</label>
          <input type="text" class="form-control" value="<?= e($profile['package']) ?>" readonly>
        </div>

        <div class="form-group full">
          <label class="form-label">Alamat</label>
          <textarea><?= e($profile['address']) ?></textarea>
        </div>

        <div class="form-group full">
          <button type="button" class="gradient-btn w-100">
            <i class="bi bi-check2-circle"></i> Simpan Profil
          </button>
        </div>
      </form>
    </div>
  </div>
</section>

<?php include '../includes/layout_bottom.php'; ?>