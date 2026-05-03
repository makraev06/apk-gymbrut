<?php
session_start();
require_once __DIR__ . '/../../config/database.php';

$id = (int) ($_GET['id'] ?? 0);

if ($id > 0) {
  $stmt = $conn->prepare("
    UPDATE payments
    SET status = 'rejected'
    WHERE payment_id = ?
  ");
  $stmt->bind_param("i", $id);
  $stmt->execute();
}

header("Location: payments.php");
exit;