<?php
/* admin/workouts.php */
session_start();
$_SESSION['role'] = $_SESSION['role'] ?? 'admin';
$_SESSION['name'] = $_SESSION['name'] ?? 'Michael Admin';

$pageTitle = 'Workout Programs';
$topbarTitle = 'Workouts';
$topbarSubtitle = 'Kelola program latihan yang tersedia untuk seluruh member gym.';
$searchPlaceholder = 'Cari program workout...';

include '../includes/layout_top.php';

$workouts = [
  [
    'name' => 'Fat Loss',
    'desc' => 'Program pembakaran lemak dengan kombinasi cardio, HIIT, dan pola latihan intens.',
    'level' => 'Beginner - Intermediate',
    'duration' => '8 Minggu'
  ],
  [
    'name' => 'Muscle Gain',
    'desc' => 'Fokus pada hypertrophy training untuk membantu peningkatan massa otot secara bertahap.',
    'level' => 'Intermediate',
    'duration' => '12 Minggu'
  ],
  [
    'name' => 'Cardio Blast',
    'desc' => 'Latihan ketahanan jantung dan stamina dengan sesi cardio terstruktur dan variatif.',
    'level' => 'All Levels',
    'duration' => '6 Minggu'
  ],
  [
    'name' => 'Strength Builder',
    'desc' => 'Program peningkatan kekuatan tubuh menggunakan compound movement dan progressive overload.',
    'level' => 'Intermediate - Advanced',
    'duration' => '10 Minggu'
  ],
];
?>

<section class="page-section">
  <div class="card-header-inline">
    <div>
      <h3 class="section-title">Daftar Program Workout</h3>
      <p class="section-subtitle">Program latihan modern yang bisa dipilih sesuai tujuan fitness.</p>
    </div>
    <a href="#" class="gradient-btn btn-sm"><i class="bi bi-plus-lg"></i> Tambah Program</a>
  </div>

  <div class="page-grid grid-2">
    <?php foreach ($workouts as $workout): ?>
      <div class="card-soft">
        <div class="card-header-inline">
          <div>
            <h3 class="section-title"><?= e($workout['name']) ?></h3>
            <p class="section-subtitle"><?= e($workout['duration']) ?> • <?= e($workout['level']) ?></p>
          </div>
          <span class="badge-soft badge-info">Workout</span>
        </div>

        <p class="section-subtitle" style="margin-top:0; line-height:1.8;">
          <?= e($workout['desc']) ?>
        </p>

        <div class="d-flex align-center gap-8 mt-3">
          <a href="#" class="btn-outline-soft btn-sm"><i class="bi bi-eye"></i> Detail</a>
          <a href="#" class="btn-outline-soft btn-sm"><i class="bi bi-pencil-square"></i> Edit</a>
          <a href="#" class="btn-outline-soft btn-sm"><i class="bi bi-trash3"></i> Hapus</a>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
</section>

<?php include '../includes/layout_bottom.php'; ?>