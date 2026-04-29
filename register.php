<?php
session_start();
require_once 'config/database.php';

if (isset($_SESSION['role'])) {
    if ($_SESSION['role'] === 'admin') {
        header('Location: admin/dashboard.php');
        exit;
    } elseif ($_SESSION['role'] === 'member') {
        header('Location: member/dashboard.php');
        exit;
    }
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $password = trim($_POST['password'] ?? '');
    $confirmPassword = trim($_POST['confirm_password'] ?? '');

    if ($name === '' || $email === '' || $phone === '' || $password === '' || $confirmPassword === '') {
        $error = 'Semua field wajib diisi.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Format email tidak valid.';
    } elseif ($password !== $confirmPassword) {
        $error = 'Konfirmasi password tidak sama.';
    } else {
        $checkStmt = mysqli_prepare($conn, "SELECT id FROM users WHERE email = ? LIMIT 1");
        mysqli_stmt_bind_param($checkStmt, "s", $email);
        mysqli_stmt_execute($checkStmt);
        $checkResult = mysqli_stmt_get_result($checkStmt);

        if (mysqli_fetch_assoc($checkResult)) {
            $error = 'Email sudah terdaftar.';
        } else {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $role = 'member';

            $insertStmt = mysqli_prepare(
                $conn,
                "INSERT INTO users (name, email, phone, password, role) VALUES (?, ?, ?, ?, ?)"
            );
            mysqli_stmt_bind_param($insertStmt, "sssss", $name, $email, $phone, $hashedPassword, $role);

            if (mysqli_stmt_execute($insertStmt)) {
                $success = 'Registrasi berhasil. Silakan login.';
            } else {
                $error = 'Registrasi gagal. Cek struktur tabel users.';
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - GYMBRUT</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link rel="stylesheet" href="assets/css/theme.css?v=registerfix1">
</head>

<body>
    <div class="auth-page">
        <div class="auth-card">
            <div class="auth-logo">
                <i class="bi bi-person-plus-fill"></i>
            </div>

            <h1 class="auth-title">Buat Akun</h1>
            <p class="auth-subtitle">
                Daftar sebagai member GYMBRUT untuk mulai mengakses fitur gym management.
            </p>

            <?php if ($error !== ''): ?>
                <div class="auth-alert auth-alert-danger">
                    <?= e($error) ?>
                </div>
            <?php endif; ?>

            <?php if ($success !== ''): ?>
                <div class="auth-alert auth-alert-success">
                    <?= e($success) ?>
                </div>
            <?php endif; ?>

            <form method="POST" class="auth-form">
                <div class="form-group">
                    <label class="form-label">Nama Lengkap</label>
                    <input type="text" name="name" class="form-control" placeholder="Masukkan nama lengkap"
                        value="<?= e($_POST['name'] ?? '') ?>" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" placeholder="Masukkan email"
                        value="<?= e($_POST['email'] ?? '') ?>" required>
                </div>

                <div class="form-group">
                    <label class="form-label">No. HP</label>
                    <input type="text" name="phone" class="form-control" placeholder="Masukkan nomor HP"
                        value="<?= e($_POST['phone'] ?? '') ?>" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-control" placeholder="Masukkan password"
                        required>
                </div>

                <div class="form-group">
                    <label class="form-label">Konfirmasi Password</label>
                    <input type="password" name="confirm_password" class="form-control" placeholder="Ulangi password"
                        required>
                </div>

                <button type="submit" class="gradient-btn w-100">
                    <i class="bi bi-person-check-fill"></i>
                    Daftar
                </button>
            </form>

            <div class="auth-links">
                Sudah punya akun?
                <a href="login.php">Login di sini</a>
            </div>
        </div>
    </div>
</body>

</html>