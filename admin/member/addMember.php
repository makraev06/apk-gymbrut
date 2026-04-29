<?php
session_start();

$pageTitle = 'Tambah Member';
$topbarTitle = 'Tambah Member';
$topbarSubtitle = 'Tambahkan member baru dan paket membership.';
$searchPlaceholder = 'Cari...';

include '../../includes/layout_top.php';

$error = '';

$packages = gymbrut_query_all($conn, "
  SELECT package_id, package_name, duration_days, price
  FROM membership_packages
  ORDER BY price ASC
");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $password = trim($_POST['password'] ?? '');
    $package_id = (int) ($_POST['package_id'] ?? 0);
    $start_date = $_POST['start_date'] ?? date('Y-m-d');
    $status = $_POST['status'] ?? 'aktif';

    if ($name === '' || $email === '' || $password === '' || $package_id === 0) {
        $error = 'Nama, email, password, dan paket wajib diisi.';
    } else {
        $stmt = $conn->prepare("SELECT user_id FROM users WHERE email = ? LIMIT 1");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $exists = $stmt->get_result()->fetch_assoc();

        if ($exists) {
            $error = 'Email sudah terdaftar.';
        } else {
            $stmt = $conn->prepare("SELECT duration_days FROM membership_packages WHERE package_id = ?");
            $stmt->bind_param("i", $package_id);
            $stmt->execute();
            $package = $stmt->get_result()->fetch_assoc();

            if (!$package) {
                $error = 'Paket membership tidak ditemukan.';
            } else {
                $hashed = password_hash($password, PASSWORD_DEFAULT);

                $stmt = $conn->prepare("
          INSERT INTO users (name, email, phone, password, role, created_at)
          VALUES (?, ?, ?, ?, 'member', NOW())
        ");
                $stmt->bind_param("ssss", $name, $email, $phone, $hashed);
                $stmt->execute();

                $user_id = $conn->insert_id;
                $duration = (int) $package['duration_days'];
                $end_date = date('Y-m-d', strtotime($start_date . " +{$duration} days"));

                $stmt = $conn->prepare("
          INSERT INTO memberships (user_id, package_id, start_date, end_date, status)
          VALUES (?, ?, ?, ?, ?)
        ");
                $stmt->bind_param("iisss", $user_id, $package_id, $start_date, $end_date, $status);
                $stmt->execute();

                header("Location: members.php");
                exit;
            }
        }
    }
}
?>

<section class="page-section">
    <div class="membership-section-header">
        <div>
            <h3 class="section-title">Tambah Member</h3>
            <p class="section-subtitle">Isi data member baru.</p>
        </div>

        <a href="members.php" class="btn-outline-soft btn-sm">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
    </div>

    <div class="form-card">
        <?php if ($error): ?>
            <div class="auth-alert auth-alert-danger mb-3"><?= e($error) ?></div>
        <?php endif; ?>

        <form method="POST" class="form-grid">
            <div class="form-group">
                <label class="form-label">Nama</label>
                <input type="text" name="name" class="form-control" required>
            </div>

            <div class="form-group">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control" required>
            </div>

            <div class="form-group">
                <label class="form-label">No HP</label>
                <input type="text" name="phone" class="form-control">
            </div>

            <div class="form-group">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-control" required>
            </div>

            <div class="form-group">
                <label class="form-label">Paket</label>
                <select name="package_id" class="form-select" required>
                    <option value="">Pilih Paket</option>
                    <?php foreach ($packages as $package): ?>
                        <option value="<?= e($package['package_id']) ?>">
                            <?= e($package['package_name']) ?> - Rp <?= number_format($package['price'], 0, ',', '.') ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label class="form-label">Tanggal Mulai</label>
                <input type="date" name="start_date" class="form-control" value="<?= date('Y-m-d') ?>" required>
            </div>

            <div class="form-group">
                <label class="form-label">Status</label>
                <select name="status" class="form-select">
                    <option value="aktif">Aktif</option>
                    <option value="pending">Pending</option>
                    <option value="expired">Expired</option>
                </select>
            </div>

            <div class="form-group full">
                <button type="submit" class="gradient-btn">
                    <i class="bi bi-save"></i> Simpan
                </button>
            </div>
        </form>
    </div>
</section>

<?php include '../../includes/layout_bottom.php'; ?>