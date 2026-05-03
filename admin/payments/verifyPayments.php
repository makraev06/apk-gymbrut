<?php
session_start();
require_once __DIR__ . '/../../config/database.php';

$id = (int) ($_GET['id'] ?? 0);

if ($id > 0) {
  $stmt = $conn->prepare("
    UPDATE payments
    SET status = 'verified'
    WHERE payment_id = ?
  ");
  $stmt->bind_param("i", $id);
  $stmt->execute();

  $stmt = $conn->prepare("
    UPDATE memberships m
    JOIN payments p ON m.membership_id = p.membership_id
    SET m.status = 'aktif'
    WHERE p.payment_id = ?
  ");
  $stmt->bind_param("i", $id);
  $stmt->execute();
}

header("Location: payments.php");
exit;