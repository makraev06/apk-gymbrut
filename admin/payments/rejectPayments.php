<?php
/* admin/payments/rejectPayments.php */
session_start();

include '../../config/database.php';

if (!isset($_SESSION['user_id']) || ($_SESSION['role'] ?? '') !== 'admin') {
  header("Location: ../../login.php");
  exit;
}

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
        m.user_id,
        m.status AS membership_status,
        mp.package_name
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

/* Kalau sudah rejected, tidak perlu proses ulang */
if ($data['payment_status'] === 'rejected') {
  header("Location: payments.php?success=Payment sudah rejected");
  exit;
}

$conn->begin_transaction();

try {
  /* Update payment jadi rejected */
  $stmt = $conn->prepare("
        UPDATE payments
        SET status = 'rejected'
        WHERE payment_id = ?
    ");
  $stmt->bind_param("i", $paymentId);
  $stmt->execute();

  /*
     Membership jangan diaktifkan.
     Balikkan ke pending supaya member bisa upload ulang bukti pembayaran.
  */
  $stmt = $conn->prepare("
        UPDATE memberships
        SET status = 'pending'
        WHERE membership_id = ?
    ");
  $stmt->bind_param("i", $membershipId);
  $stmt->execute();

  /* Buat notifikasi untuk member */
  $title = 'Pembayaran Ditolak';
  $message = 'Pembayaran paket ' . $packageName . ' ditolak admin. Silakan upload ulang bukti pembayaran di menu Payments.';
  $type = 'payment';

  $stmt = $conn->prepare("
        INSERT INTO notifications (user_id, title, message, type)
        VALUES (?, ?, ?, ?)
    ");
  $stmt->bind_param("isss", $memberUserId, $title, $message, $type);
  $stmt->execute();

  $conn->commit();

  header("Location: payments.php?success=Payment berhasil ditolak");
  exit;
} catch (Exception $e) {
  $conn->rollback();

  header("Location: payments.php?error=Gagal reject payment");
  exit;
}