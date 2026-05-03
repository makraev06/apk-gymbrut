<?php
/* member/read_notification.php */
session_start();

include '../config/database.php';

$userId = (int) ($_SESSION['user_id'] ?? 0);
$notificationId = (int) ($_GET['id'] ?? 0);
$redirect = $_GET['redirect'] ?? 'dashboard.php';

$allowedRedirects = [
    'dashboard.php',
    'memberships.php',
    'payments.php',
    'checkin.php',
    'progress.php',
    'workouts.php',
    'profile.php'
];

if (!in_array($redirect, $allowedRedirects)) {
    $redirect = 'dashboard.php';
}

if ($userId > 0 && $notificationId > 0) {
    $stmt = $conn->prepare("
        UPDATE notifications
        SET is_read = 1
        WHERE notification_id = ?
        AND user_id = ?
    ");
    $stmt->bind_param("ii", $notificationId, $userId);
    $stmt->execute();
}

header("Location: " . $redirect);
exit;