<?php
session_start();

$pageTitle = 'Edit Paket';
$topbarTitle = 'Edit Paket Membership';
$topbarSubtitle = 'Ubah detail paket membership.';
$searchPlaceholder = 'Cari...';

include '../../includes/layout_top.php';

$id = (int) ($_GET['id'] ?? 0);
$error = '';

$package = gymbrut_query_one($conn, "
  SELECT * FROM membership_packages
  WHERE package_id = $id
  LIMIT 1
");

if (!$package) {
    header("Location: memberships.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $package_name = trim($_POST['package_name'] ?? '');
    $duration_days = (int) ($_POST['duration_days'] ?? 0);
    $price = (int) ($_POST['price'] ?? 0);
    $description = trim($_POST['description'] ?? '');

    if ($package_name === '' || $duration_days <= 0 || $price <= 0) {
        $error = 'Nama paket, durasi, dan harga wajib diisi.';
    } else {
        $stmt = $conn->prepare("
      UPDATE membership_packages
      SET package_name = ?, duration_days = ?, price = ?, description = ?
      WHERE package_id = ?
    ");
        $stmt->bind_param("siisi", $package_name, $duration_days, $price, $description, $id);
        $stmt->execute();

        header("Location: memberships.php");
        exit;
    }
}
?>

<section class="page-section">
    <div class="membership-section-header">
        <div>
            <h3 class="section-title">Edit Paket Membership</h3>
            <p class="section-subtitle">Ubah informasi paket.</p>
        </div>

        <a href="memberships.php" class="btn-outline-soft btn-sm">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
    </div>

    <div class="form-card">
        <?php if ($error): ?>
            <div class="auth-alert auth-alert-danger mb-3">
                <?= e($error) ?>
            </div>
        <?php endif; ?>

        <form method="POST" class="form-grid">
            <div class="form-group">
                <label class="form-label">Nama Paket</label>
                <input type="text" name="package_name" class="form-control" value="<?= e($package['package_name']) ?>"
                    required>
            </div>

            <div class="form-group">
                <label class="form-label">Durasi Hari</label>
                <input type="number" name="duration_days" class="form-control"
                    value="<?= e($package['duration_days']) ?>" required>
            </div>

            <div class="form-group">
                <label class="form-label">Harga</label>
                <input type="number" name="price" class="form-control" value="<?= e($package['price']) ?>" required>
            </div>

            <div class="form-group full">
                <label class="form-label">Deskripsi</label>
                <textarea name="description" class="form-control"><?= e($package['description'] ?? '') ?></textarea>
            </div>

            <div class="form-group full">
                <button type="submit" class="gradient-btn">
                    <i class="bi bi-save"></i> Update Paket
                </button>
            </div>
        </form>
    </div>
</section>

<?php include '../../includes/layout_bottom.php'; ?>