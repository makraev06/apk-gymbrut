<?php
/* member/workouts.php */
session_start();
$_SESSION['role'] = $_SESSION['role'] ?? 'member';
$_SESSION['name'] = $_SESSION['name'] ?? 'Michael Member';

$pageTitle = 'Available Workouts';
$topbarTitle = 'Workout Tersedia';
$topbarSubtitle = 'Pilih program latihan terbaik sesuai tujuan fitness kamu.';
$searchPlaceholder = 'Cari workout favoritmu...';

include '../includes/layout_top.php';

$workouts = [
  [
    'name' => 'Fat Loss',
    'desc' => 'Program pembakaran lemak dengan kombinasi HIIT, treadmill, dan circuit training.',
    'level' => 'Beginner - Intermediate',
    'duration' => '8 Minggu'
  ],
  [
    'name' => 'Muscle Gain',
    'desc' => 'Program untuk membangun massa otot secara bertahap dengan fokus hypertrophy.',
    'level' => 'Intermediate',
    'duration' => '12 Minggu'
  ],
  [
    'name' => 'Cardio Blast',
    'desc' => 'Latihan cardio intens untuk meningkatkan stamina dan kesehatan jantung.',
    'level' => 'All Levels',
    'duration' => '6 Minggu'
  ],
  [
    'name' => 'Strength Builder',
    'desc' => 'Latihan kekuatan dengan gerakan compound untuk performa tubuh lebih maksimal.',
    'level' => 'Intermediate - Advanced',
    'duration' => '10 Minggu'
  ],
];
?>

<section class="page-section">
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
          <a href="#" class="gradient-btn btn-sm">
            <i class="bi bi-play-circle"></i> Ikuti Program
          </a>
          <a href="#" class="btn-outline-soft btn-sm">
            <i class="bi bi-eye"></i> Detail
          </a>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
</section>

<?php include '../includes/layout_bottom.php'; ?>