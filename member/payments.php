<?php
/* member/payments.php */
session_start();

$_SESSION['role'] = $_SESSION['role'] ?? 'member';
$_SESSION['name'] = $_SESSION['name'] ?? 'Member';

$pageTitle = 'Pembayaran Saya';
$topbarTitle = 'Pembayaran Saya';
$topbarSubtitle = 'Upload bukti pembayaran dan pantau status verifikasi membership kamu.';
$searchPlaceholder = 'Cari pembayaran...';

include '../includes/layout_top.php';

$userId = (int) ($_SESSION['user_id'] ?? 0);

$success = '';
$error = '';

/* =========================
   UPLOAD BUKTI PEMBAYARAN
========================= */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['payment_id'])) {
    $paymentId = (int) $_POST['payment_id'];

    // Pastikan payment milik user yang login
    $stmt = $conn->prepare("
        SELECT 
            p.payment_id,
            p.status,
            p.proof_file,
            m.user_id
        FROM payments p
        JOIN memberships m ON p.membership_id = m.membership_id
        WHERE p.payment_id = ?
        AND m.user_id = ?
        LIMIT 1
    ");
    $stmt->bind_param("ii", $paymentId, $userId);
    $stmt->execute();
    $paymentResult = $stmt->get_result();

    if (!$paymentResult || $paymentResult->num_rows === 0) {
        $error = 'Data pembayaran tidak ditemukan.';
    } else {
        $payment = $paymentResult->fetch_assoc();

        if ($payment['status'] === 'verified') {
            $error = 'Pembayaran ini sudah diverifikasi, bukti tidak bisa diubah.';
        } elseif (!isset($_FILES['proof_file']) || $_FILES['proof_file']['error'] !== UPLOAD_ERR_OK) {
            $error = 'Silakan upload bukti pembayaran terlebih dahulu.';
        } else {
            $file = $_FILES['proof_file'];

            $allowedExt = ['jpg', 'jpeg', 'png', 'pdf'];
            $fileName = $file['name'];
            $fileTmp = $file['tmp_name'];
            $fileSize = $file['size'];

            $ext = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

            if (!in_array($ext, $allowedExt)) {
                $error = 'Format file harus JPG, JPEG, PNG, atau PDF.';
            } elseif ($fileSize > 2 * 1024 * 1024) {
                $error = 'Ukuran file maksimal 2MB.';
            } else {
                $uploadDir = '../uploads/payments/';

                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }

                $newFileName = 'payment_' . $paymentId . '_' . time() . '.' . $ext;
                $targetPath = $uploadDir . $newFileName;

                if (move_uploaded_file($fileTmp, $targetPath)) {
                    $stmt = $conn->prepare("
                        UPDATE payments
                        SET proof_file = ?, status = 'pending', payment_date = NOW()
                        WHERE payment_id = ?
                    ");
                    $stmt->bind_param("si", $newFileName, $paymentId);

                    if ($stmt->execute()) {
                        $success = 'Bukti pembayaran berhasil diupload. Silakan tunggu verifikasi admin.';
                    } else {
                        $error = 'Gagal menyimpan bukti pembayaran ke database.';
                    }
                } else {
                    $error = 'Gagal upload file bukti pembayaran.';
                }
            }
        }
    }
}

/* =========================
   AMBIL RIWAYAT PAYMENT USER
========================= */
$payments = [];

$stmt = $conn->prepare("
    SELECT 
        p.payment_id,
        p.membership_id,
        p.amount,
        p.payment_date,
        p.proof_file,
        p.status,
        m.start_date,
        m.end_date,
        m.status AS membership_status,
        mp.package_name
    FROM payments p
    JOIN memberships m ON p.membership_id = m.membership_id
    JOIN membership_packages mp ON m.package_id = mp.package_id
    WHERE m.user_id = ?
    ORDER BY p.payment_date DESC, p.payment_id DESC
");
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();

if ($result) {
    while ($row = $result->fetch_assoc()) {
        $payments[] = $row;
    }
}

$highlightPaymentId = isset($_GET['payment_id']) ? (int) $_GET['payment_id'] : 0;

function paymentBadgeClassMember($status)
{
    if ($status === 'verified') {
        return 'badge-active';
    }

    if ($status === 'pending') {
        return 'badge-pending';
    }

    return 'badge-failed';
}
?>

<div class="dashboard-hero mb-4">
    <div>
        <span class="banner-pill">
            <i class="bi bi-wallet2"></i> Payment Member
        </span>

        <h2>Pembayaran Membership</h2>
        <p>
            Upload bukti pembayaran untuk membership yang kamu pilih.
            Setelah itu, admin akan melakukan verifikasi pembayaran.
        </p>
    </div>

    <div class="dashboard-hero-card">
        <span>Total Pembayaran</span>
        <strong><?= count($payments) ?></strong>
        <small>Riwayat pembayaran kamu</small>
    </div>
</div>

<?php if ($success): ?>
        <div class="alert alert-success">
            <i class="bi bi-check-circle"></i>
            <?= e($success) ?>
        </div>
<?php endif; ?>

<?php if ($error): ?>
        <div class="alert alert-danger">
            <i class="bi bi-exclamation-circle"></i>
            <?= e($error) ?>
        </div>
<?php endif; ?>

<div class="row g-4">
    <div class="col-12">
        <div class="premium-card">
            <div class="card-header-inline">
                <div>
                    <h3 class="section-title">Riwayat Pembayaran</h3>
                    <p class="section-subtitle">
                        Daftar pembayaran membership kamu beserta status verifikasinya.
                    </p>
                </div>

                <a href="memberships.php" class="btn-outline-soft btn-sm">
                    <i class="bi bi-award"></i>
                    Pilih Paket
                </a>
            </div>

            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Invoice</th>
                            <th>Paket</th>
                            <th>Nominal</th>
                            <th>Tanggal</th>
                            <th>Bukti</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php if (empty($payments)): ?>
                                <tr>
                                    <td colspan="7" class="text-soft">
                                        Belum ada pembayaran. Silakan pilih paket membership terlebih dahulu.
                                    </td>
                                </tr>
                        <?php endif; ?>

                        <?php foreach ($payments as $payment): ?>
                                <?php
                                $badge = paymentBadgeClassMember($payment['status']);

                                $paymentDate = !empty($payment['payment_date'])
                                    ? date('d M Y H:i', strtotime($payment['payment_date']))
                                    : '-';

                                $isHighlighted = $highlightPaymentId === (int) $payment['payment_id'];

                                $rowStyle = $isHighlighted
                                    ? 'style="background:#fff7ef;"'
                                    : '';

                                $proofFile = $payment['proof_file'];
                                ?>

                                <tr <?= $rowStyle ?>>
                                    <td>
                                        <strong>INV-<?= e($payment['payment_id']) ?></strong>
                                    </td>

                                    <td>
                                        <strong><?= e($payment['package_name']) ?></strong><br>
                                        <small class="text-soft">
                                            <?= e(date('d M Y', strtotime($payment['start_date']))) ?>
                                            -
                                            <?= e(date('d M Y', strtotime($payment['end_date']))) ?>
                                        </small>
                                    </td>

                                    <td>
                                        Rp <?= number_format((float) $payment['amount'], 0, ',', '.') ?>
                                    </td>

                                    <td>
                                        <?= e($paymentDate) ?>
                                    </td>

                                    <td>
                                        <?php if (!empty($proofFile)): ?>
                                                <a 
                                                    href="../uploads/payments/<?= e($proofFile) ?>" 
                                                    target="_blank" 
                                                    class="btn-outline-soft btn-sm"
                                                >
                                                    <i class="bi bi-file-earmark-text"></i>
                                                    Lihat Bukti
                                                </a>
                                        <?php else: ?>
                                                <span class="text-soft">Belum upload</span>
                                        <?php endif; ?>
                                    </td>

                                    <td>
                                        <span class="badge-soft <?= e($badge) ?>">
                                            <?= e(ucfirst($payment['status'])) ?>
                                        </span>
                                    </td>

                                    <td>
                                        <?php if ($payment['status'] !== 'verified'): ?>
                                                <form 
                                                    method="POST" 
                                                    enctype="multipart/form-data"
                                                    style="display:flex; gap:8px; align-items:center; flex-wrap:wrap;"
                                                >
                                                    <input 
                                                        type="hidden" 
                                                        name="payment_id" 
                                                        value="<?= e($payment['payment_id']) ?>"
                                                    >

                                                    <input 
                                                        type="file" 
                                                        name="proof_file" 
                                                        class="form-control"
                                                        accept=".jpg,.jpeg,.png,.pdf"
                                                        required
                                                        style="max-width:220px;"
                                                    >

                                                    <button type="submit" class="gradient-btn btn-sm">
                                                        <i class="bi bi-upload"></i>
                                                        Upload
                                                    </button>
                                                </form>
                                        <?php else: ?>
                                                <span class="text-soft">
                                                    Sudah diverifikasi
                                                </span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <p class="text-soft mt-3 mb-0">
                Format bukti pembayaran yang diperbolehkan: JPG, JPEG, PNG, atau PDF. Maksimal 2MB.
            </p>
        </div>
    </div>
</div>

<?php include '../includes/layout_bottom.php'; ?>