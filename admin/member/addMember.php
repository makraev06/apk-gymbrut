<?php
session_start();
require_once __DIR__ . '/../../config/database.php';

$error = '';
$packages = [];

/* ambil paket sebelum layout */
$result = $conn->query("
  SELECT package_id, package_name, duration_days, price
  FROM membership_packages
  ORDER BY price ASC
");

if ($result) {
    while ($row = $result->fetch_assoc()) {
        $packages[] = $row;
    }
}

/* proses submit HARUS sebelum include layout */
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
            $stmt = $conn->prepare("
    SELECT duration_days, price
    FROM membership_packages 
    WHERE package_id = ?
    LIMIT 1
");
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
                $membership_id = $conn->insert_id;

                /* ambil harga paket */
                $amount = $package['price'];

                /* insert payment otomatis */
                $stmt = $conn->prepare("
  INSERT INTO payments (membership_id, amount, status, payment_date)
  VALUES (?, ?, 'verified', NOW())
");
                $stmt->bind_param("ii", $membership_id, $amount);
                $stmt->execute();

                header("Location: members.php");
                exit;
            }
        }
    }
}

$pageTitle = 'Tambah Member';
$topbarTitle = 'Tambah Member';
$topbarSubtitle = 'Tambahkan member baru dan paket membership.';
$searchPlaceholder = 'Cari...';

include __DIR__ . '/../../includes/layout_top.php';
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
        <?php if (!empty($error)): ?>
        <div class="auth-alert auth-alert-danger mb-3">
            <?= htmlspecialchars($error) ?>
        </div>
        <?php endif; ?>

        <form method="POST" class="form-grid">
            <div class="form-group">
                <label class="form-label">Nama</label>
                <input type="text" name="name" class="form-control"
                    value="<?= htmlspecialchars($_POST['name'] ?? '') ?>" required>
            </div>

            <div class="form-group">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control"
                    value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" required>
            </div>

            <div class="form-group">
                <label class="form-label">No HP</label>
                <input type="text" name="phone" class="form-control"
                    value="<?= htmlspecialchars($_POST['phone'] ?? '') ?>">
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
                    <option value="<?= htmlspecialchars($package['package_id']) ?>"
                        <?= ((int) ($_POST['package_id'] ?? 0) === (int) $package['package_id']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($package['package_name']) ?> -
                        Rp <?= number_format($package['price'], 0, ',', '.') ?>
                    </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label class="form-label">Tanggal Mulai</label>
                <input type="date" name="start_date" class="form-control"
                    value="<?= htmlspecialchars($_POST['start_date'] ?? date('Y-m-d')) ?>" required>
            </div>

            <div class="form-group">
                <label class="form-label">Status</label>
                <select name="status" class="form-select">
                    <option value="aktif" <?= ($_POST['status'] ?? 'aktif') === 'aktif' ? 'selected' : '' ?>>Aktif
                    </option>
                    <option value="pending" <?= ($_POST['status'] ?? '') === 'pending' ? 'selected' : '' ?>>Pending
                    </option>
                    <option value="expired" <?= ($_POST['status'] ?? '') === 'expired' ? 'selected' : '' ?>>Expired
                    </option>
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

<?php include __DIR__ . '/../../includes/layout_bottom.php'; ?>