<?php
session_start();
require_once '../../config/database.php';

$id = (int) ($_GET['id'] ?? 0);

if ($id > 0) {
    $stmt = $conn->prepare("SELECT membership_id FROM memberships WHERE package_id = ? LIMIT 1");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $used = $stmt->get_result()->fetch_assoc();

    if (!$used) {
        $stmt = $conn->prepare("DELETE FROM membership_packages WHERE package_id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
    }
}

header("Location: memberships.php");
exit;