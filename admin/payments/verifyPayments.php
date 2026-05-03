<?php
/* admin/payments/verifyPayments.php */
session_start();

include '../../config/database.php';

if (!isset($_SESSION['user_id']) || ($_SESSION['role'] ?? '') !== 'admin') {
  header("Location: ../../login.php");
  exit;
}

$adminId = (int) ($_SESSION['user_id'] ?? 0);
$paymentId = (int) ($_GET['id'] ?? $_POST['payment_id'] ?? 0);

if ($paymentId <= 0) {
  header("Location: payments.php?error=Payment tidak valid");
  exit;
}

/* Ambil data payment + membership + user */
$stmt = $conn->prepare("
    SELECT 
        p.payment_id,
        p.membership_id,
        p.status AS payment_status,
        p.proof_file,
        m.user_id,
        m.package_id,
        m.start_date,
        m.end_date,
        m.status AS membership_status,
        mp.package_name,
        mp.duration_days
    FROM payments p
    JOIN memberships m ON p.membership_id = m.membership_id
    JOIN membership_packages mp ON m.package_id = mp.package_id
    WHERE p.payment_id = ?
    LIMIT 1
");
$stmt->bind_param("i", $paymentId);
$stmt->execute();
$result = $stmt->get_result();

if (!$result || $result->num_rows === 0) {
  header("Location: payments.php?error=Data payment tidak ditemukan");
  exit;
}

$data = $result->fetch_assoc();

$membershipId = (int) $data['membership_id'];
$memberUserId = (int) $data['user_id'];
$packageName = $data['package_name'];
$durationDays = (int) $data['duration_days'];

/* Kalau sudah verified, tidak perlu proses ulang */
if ($data['payment_status'] === 'verified') {
  header("Location: payments.php?success=Payment sudah verified");
  exit;
}

/* Optional: wajib ada bukti pembayaran sebelum verify */
if (empty($data['proof_file'])) {
  header("Location: payments.php?error=Payment belum memiliki bukti pembayaran");
  exit;
}

/* Tentukan tanggal membership */
$startDate = date('Y-m-d');

/*
   Kalau membership end_date lama masih di masa depan,
   bisa lanjut dari end_date lama.
   Kalau sudah lewat / pending, mulai dari hari ini.
*/
if (!empty($data['end_date']) && strtotime($data['end_date']) >= strtotime(date('Y-m-d'))) {
  $endDate = $data['end_date'];
} else {
  $endDate = date('Y-m-d', strtotime("+$durationDays days"));
}

/* Untuk membership pending, lebih masuk akal aktif dari hari verify */
$endDate = date('Y-m-d', strtotime("+$durationDays days"));

$conn->begin_transaction();

try {
  /* Update payment jadi verified */
  $stmt = $conn->prepare("
        UPDATE payments
        SET 
            status = 'verified',
            verified_by = ?,
            payment_date = NOW()
        WHERE payment_id = ?
    ");
  $stmt->bind_param("ii", $adminId, $paymentId);
  $stmt->execute();

  /* Update membership jadi aktif */
  $stmt = $conn->prepare("
        UPDATE memberships
        SET 
            status = 'aktif',
            start_date = ?,
            end_date = ?
        WHERE membership_id = ?
    ");
  $stmt->bind_param("ssi", $startDate, $endDate, $membershipId);
  $stmt->execute();

  /* Buat notifikasi untuk member */
  $title = 'Pembayaran Diverifikasi';
  $message = 'Pembayaran paket ' . $packageName . ' sudah diverifikasi admin. Membership kamu sekarang aktif sampai ' . date('d M Y', strtotime($endDate)) . '.';
  $type = 'payment';

  $stmt = $conn->prepare("
        INSERT INTO notifications (user_id, title, message, type)
        VALUES (?, ?, ?, ?)
    ");
  $stmt->bind_param("isss", $memberUserId, $title, $message, $type);
  $stmt->execute();

  $conn->commit();

  header("Location: payments.php?success=Payment berhasil diverifikasi");
  exit;
} catch (Exception $e) {
  $conn->rollback();

  header("Location: payments.php?error=Gagal verify payment");
  exit;
}