<?php
/* admin/profile.php */
session_start();
$_SESSION['role'] = $_SESSION['role'] ?? 'admin';
$_SESSION['name'] = $_SESSION['name'] ?? 'Michael Admin';

$pageTitle = 'Admin Profile';
$topbarTitle = 'Profile';
$topbarSubtitle = 'Informasi akun admin dan identitas pengelola sistem.';
$searchPlaceholder = 'Cari pengaturan akun...';

include '../includes/layout_top.php';

$profile = [
  'name' => $_SESSION['name'] ?? 'Michael Admin',
  'email' => 'admin@gymbrut.com',
  'role' => 'Administrator',
  'phone' => '0812-9988-7766',
  'last_login' => '22 Apr 2026, 19:10 WIB',
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
          <p class="section-subtitle"><?= e($profile['role']) ?></p>
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
            <p class="list-row-title">No HP</p>
            <p class="list-row-subtitle"><?= e($profile['phone']) ?></p>
          </div>
        </div>
        <div class="list-row">
          <div>
            <p class="list-row-title">Last Login</p>
            <p class="list-row-subtitle"><?= e($profile['last_login']) ?></p>
          </div>
        </div>
      </div>
    </div>

    <div class="form-card">
      <div class="card-header-inline">
        <div>
          <h3 class="section-title">Edit Profil</h3>
          <p class="section-subtitle">Perbarui identitas admin dengan tampilan form yang clean.</p>
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
          <label class="form-label">Role</label>
          <input type="text" class="form-control" value="<?= e($profile['role']) ?>" readonly>
        </div>

        <div class="form-group">
          <label class="form-label">No HP</label>
          <input type="text" class="form-control" value="<?= e($profile['phone']) ?>">
        </div>

        <div class="form-group full">
          <button type="button" class="gradient-btn w-100">
            <i class="bi bi-check2-circle"></i> Simpan Perubahan
          </button>
        </div>
      </form>
    </div>
  </div>
</section>

<?php include '../includes/layout_bottom.php'; ?>