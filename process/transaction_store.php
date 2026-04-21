<?php
session_start();
include '../config/database.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION['user_id'];
    $jenis = $_POST['jenis'];
    $jumlah = $_POST['jumlah'];
    $keterangan = $_POST['keterangan'];
    $tanggal = $_POST['tanggal'];

    $stmt = mysqli_prepare(
        $conn,
        "INSERT INTO transactions (user_id, jenis, jumlah, keterangan, tanggal) VALUES (?, ?, ?, ?, ?)"
    );

    mysqli_stmt_bind_param($stmt, "isdss", $user_id, $jenis, $jumlah, $keterangan, $tanggal);
    mysqli_stmt_execute($stmt);

    header("Location: ../dashboard.php");
    exit;
}