<?php
/* member/checkin.php */
session_start();

$_SESSION['role'] = $_SESSION['role'] ?? 'member';
$_SESSION['name'] = $_SESSION['name'] ?? 'Member';

$pageTitle = 'Check In';
$topbarTitle = 'Check In Member';
$topbarSubtitle = 'Lakukan check-in harian untuk mencatat kehadiran gym kamu.';
$searchPlaceholder = 'Cari data check-in...';

include '../includes/layout_top.php';

$userId = (int) ($_SESSION['user_id'] ?? 0);
$memberName = $_SESSION['name'] ?? 'Member';

$success = '';
$error = '';

/* =========================
   CEK MEMBERSHIP AKTIF
========================= */
$activeMembership = null;

$stmt = $conn->prepare("
    SELECT 
        m.membership_id,
        m.start_date,
        m.end_date,
        m.status,
        mp.package_name
    FROM memberships m
    JOIN membership_packages mp ON m.package_id = mp.package_id
    WHERE m.user_id = ?
    AND m.status = 'aktif'
    AND m.end_date >= CURDATE()
    ORDER BY m.end_date DESC
    LIMIT 1
");
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();

if ($result && $result->num_rows > 0) {
    $activeMembership = $result->fetch_assoc();
}

/* =========================
   CEK SUDAH CHECK-IN HARI INI
========================= */
$alreadyCheckin = false;

$stmt = $conn->prepare("
    SELECT checkin_id
    FROM checkins
    WHERE user_id = ?
    AND DATE(checkin_time) = CURDATE()
    LIMIT 1
");
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();

if ($result && $result->num_rows > 0) {
    $alreadyCheckin = true;
}

/* =========================
   PROSES CHECK-IN
========================= */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $notes = trim($_POST['notes'] ?? '');

    if (!$activeMembership) {
        $error = 'Membership kamu belum aktif atau sudah expired. Silakan selesaikan pembayaran terlebih dahulu.';
    } elseif ($alreadyCheckin) {
        $error = 'Kamu sudah check-in hari ini. Check-in hanya bisa dilakukan satu kali per hari.';
    } else {
        if ($notes === '') {
            $notes = 'Check-in gym';
        }

        $stmt = $conn->prepare("
            INSERT INTO checkins (user_id, checkin_time, notes)
            VALUES (?, NOW(), ?)
        ");
        $stmt->bind_param("is", $userId, $notes);

        if ($stmt->execute()) {
            $success = 'Check-in berhasil dicatat. Selamat latihan!';
            $alreadyCheckin = true;
        } else {
            $error = 'Gagal melakukan check-in. Silakan coba lagi.';
        }
    }
}

/* =========================
   RIWAYAT CHECK-IN
========================= */
$checkins = [];

$stmt = $conn->prepare("
    SELECT checkin_id, checkin_time, notes
    FROM checkins
    WHERE user_id = ?
    ORDER BY checkin_time DESC
    LIMIT 10
");
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();

if ($result) {
    while ($row = $result->fetch_assoc()) {
        $checkins[] = $row;
    }
}

$membershipStatusText = $activeMembership ? 'Aktif' : 'Tidak Aktif';
$membershipBadge = $activeMembership ? 'badge-active' : 'badge-expired';

$membershipPackage = $activeMembership['package_name'] ?? 'Belum ada membership aktif';
$membershipEnd = !empty($activeMembership['end_date'])
    ? date('d M Y', strtotime($activeMembership['end_date']))
    : '-';
?>

<div class="dashboard-hero mb-4">
    <div>
        <span class="banner-pill">
            <i class="bi bi-box-arrow-in-right"></i> Member Check-in
        </span>

        <h2>Check In Gym</h2>
        <p>
            Halo, <?= e($memberName) ?>. Catat kehadiranmu hari ini agar progress latihan
            dan aktivitas gym kamu tersimpan dengan rapi.
        </p>
    </div>

    <div class="dashboard-hero-card">
        <span>Status Membership</span>
        <strong><?= e($membershipStatusText) ?></strong>
        <small><?= e($membershipPackage) ?></small>
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

<div class="row g-4 mb-4">
    <div class="col-xl-5">
        <div class="premium-card h-100">
            <div class="card-header-inline">
                <div>
                    <h3 class="section-title">Form Check-in</h3>
                    <p class="section-subtitle">
                        Check-in hanya bisa dilakukan satu kali dalam sehari.
                    </p>
                </div>

                <span class="badge-soft <?= e($membershipBadge) ?>">
                    <?= e($membershipStatusText) ?>
                </span>
            </div>

            <ul class="metric-list mb-4">
                <li>
                    <span>Paket</span>
                    <strong><?= e($membershipPackage) ?></strong>
                </li>
                <li>
                    <span>Berlaku sampai</span>
                    <strong><?= e($membershipEnd) ?></strong>
                </li>
                <li>
                    <span>Status hari ini</span>
                    <strong>
                        <?= $alreadyCheckin ? 'Sudah check-in' : 'Belum check-in' ?>
                    </strong>
                </li>
            </ul>

            <form method="POST">
                <div class="form-group mb-3">
                    <label class="form-label">Catatan Latihan</label>
                    <textarea 
                        name="notes" 
                        class="form-control" 
                        placeholder="Contoh: Latihan dada, cardio, full body..."
                        <?= (!$activeMembership || $alreadyCheckin) ? 'disabled' : '' ?>
                    ></textarea>
                </div>

                <button 
                    type="submit" 
                    class="gradient-btn w-100"
                    <?= (!$activeMembership || $alreadyCheckin) ? 'disabled' : '' ?>
                >
                    <i class="bi bi-box-arrow-in-right"></i>
                    <?= $alreadyCheckin ? 'Sudah Check-in Hari Ini' : 'Check In Sekarang' ?>
                </button>

                <?php if (!$activeMembership): ?>
                    <p class="text-soft mt-3 mb-0">
                        Kamu belum bisa check-in karena membership belum aktif atau sudah expired.
                    </p>
                <?php endif; ?>
            </form>
        </div>
    </div>

    <div class="col-xl-7">
        <div class="premium-card h-100">
            <div class="card-header-inline">
                <div>
                    <h3 class="section-title">Riwayat Check-in</h3>
                    <p class="section-subtitle">
                        10 aktivitas check-in terbaru kamu.
                    </p>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Jam</th>
                            <th>Catatan</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php if (empty($checkins)): ?>
                            <tr>
                                <td colspan="3" class="text-soft">
                                    Belum ada riwayat check-in.
                                </td>
                            </tr>
                        <?php endif; ?>

                        <?php foreach ($checkins as $checkin): ?>
                            <tr>
                                <td>
                                    <strong>
                                        <?= e(date('d M Y', strtotime($checkin['checkin_time']))) ?>
                                    </strong>
                                </td>
                                <td>
                                    <?= e(date('H:i', strtotime($checkin['checkin_time']))) ?>
                                </td>
                                <td>
                                    <?= e($checkin['notes'] ?: 'Check-in gym') ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/layout_bottom.php'; ?>