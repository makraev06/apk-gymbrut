<?php
session_start();
include '../config/database.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION['user_id'];
    $nama_aset = $_POST['nama_aset'];
    $kategori = $_POST['kategori'];
    $nilai = $_POST['nilai'];
    $deskripsi = $_POST['deskripsi'];
    $tanggal_perolehan = $_POST['tanggal_perolehan'];

    $stmt = mysqli_prepare(
        $conn,
        "INSERT INTO assets (user_id, nama_aset, kategori, nilai, deskripsi, tanggal_perolehan) VALUES (?, ?, ?, ?, ?, ?)"
    );

    mysqli_stmt_bind_param(
        $stmt,
        "ississ",
        $user_id,
        $nama_aset,
        $kategori,
        $nilai,
        $deskripsi,
        $tanggal_perolehan
    );

    mysqli_stmt_execute($stmt);

    header("Location: ../assets.php");
    exit;
} else {
    header("Location: ../add_asset.php");
    exit;
}