<?php
session_start();

$pageTitle = 'Tambah Workout';
$topbarTitle = 'Tambah Workout';
$topbarSubtitle = 'Tambahkan program latihan baru.';
$searchPlaceholder = 'Cari...';

include '../../includes/layout_top.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $category = trim($_POST['category'] ?? '');
    $title = trim($_POST['title'] ?? '');
    $equipment = trim($_POST['equipment'] ?? '');
    $tutorial = trim($_POST['tutorial'] ?? '');
    $youtube_url = trim($_POST['youtube_url'] ?? '');
    $sets_count = (int) ($_POST['sets_count'] ?? 0);
    $reps_count = trim($_POST['reps_count'] ?? '');
    $image_file = trim($_POST['image_file'] ?? '');

    if ($category === '' || $title === '') {
        $error = 'Kategori dan judul workout wajib diisi.';
    } else {
        $stmt = $conn->prepare("
      INSERT INTO workouts
      (category, title, equipment, tutorial, youtube_url, sets_count, reps_count, image_file)
      VALUES (?, ?, ?, ?, ?, ?, ?, ?)
    ");

        $stmt->bind_param(
            "sssssiss",
            $category,
            $title,
            $equipment,
            $tutorial,
            $youtube_url,
            $sets_count,
            $reps_count,
            $image_file
        );

        $stmt->execute();

        header("Location: workouts.php");
        exit;
    }
}
?>

<section class="page-section">
    <div class="membership-section-header">
        <div>
            <h3 class="section-title">Tambah Workout</h3>
            <p class="section-subtitle">Isi data program latihan baru.</p>
        </div>

        <a href="workouts.php" class="btn-outline-soft btn-sm">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
    </div>

    <div class="form-card">
        <?php if ($error): ?>
            <div class="auth-alert auth-alert-danger mb-3">
                <?= e($error) ?>
            </div>
        <?php endif; ?>

        <form method="POST" class="form-grid">
            <div class="form-group">
                <label class="form-label">Kategori</label>
                <input type="text" name="category" class="form-control" placeholder="Contoh: Fat Loss" required>
            </div>

            <div class="form-group">
                <label class="form-label">Judul Workout</label>
                <input type="text" name="title" class="form-control" placeholder="Contoh: HIIT Fat Burner" required>
            </div>

            <div class="form-group">
                <label class="form-label">Equipment</label>
                <input type="text" name="equipment" class="form-control" placeholder="Contoh: Dumbbell, Matras">
            </div>

            <div class="form-group">
                <label class="form-label">Youtube URL</label>
                <input type="url" name="youtube_url" class="form-control" placeholder="https://youtube.com/...">
            </div>

            <div class="form-group">
                <label class="form-label">Jumlah Set</label>
                <input type="number" name="sets_count" class="form-control" value="3">
            </div>

            <div class="form-group">
                <label class="form-label">Reps</label>
                <input type="text" name="reps_count" class="form-control" placeholder="Contoh: 10-12 / 20 menit">
            </div>

            <div class="form-group full">
                <label class="form-label">Nama File Gambar</label>
                <input type="text" name="image_file" class="form-control" placeholder="Contoh: workout.jpg">
            </div>

            <div class="form-group full">
                <label class="form-label">Tutorial</label>
                <textarea name="tutorial" class="form-control"
                    placeholder="Tuliskan langkah-langkah latihan..."></textarea>
            </div>

            <div class="form-group full">
                <button type="submit" class="gradient-btn">
                    <i class="bi bi-save"></i> Simpan Workout
                </button>
            </div>
        </form>
    </div>
</section>

<?php include '../../includes/layout_bottom.php'; ?>