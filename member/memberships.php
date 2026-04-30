<?php
/* member/memberships.php */
session_start();


$pageTitle = 'My Membership';
$topbarTitle = 'Membership Saya';
$topbarSubtitle = 'Lihat detail paket aktif, masa berlaku, dan status membership kamu.';
$searchPlaceholder = 'Cari paket membership...';

include '../includes/layout_top.php';

$userId = (int) $_SESSION['user_id'];

$stmt = $conn->prepare("
  SELECT 
    m.membership_id,
    m.start_date,
    m.end_date,
    m.status,
    p.package_name,
    p.duration_days,
    p.price,
    p.description
  FROM memberships m
  JOIN membership_packages p ON m.package_id = p.package_id
  WHERE m.user_id = ?
  ORDER BY m.end_date DESC
  LIMIT 1
");

$stmt->bind_param("i", $userId);
$stmt->execute();
$membership = $stmt->get_result()->fetch_assoc();

$remainingDays = 0;

if ($membership) {
  $today = new DateTime();
  $endDate = new DateTime($membership['end_date']);

  if ($endDate >= $today) {
    $remainingDays = $today->diff($endDate)->days;
  }
}




?>

<section class="page-section">

  <?php if (!$membership): ?>
    <div class="card-soft">
      <h3 class="section-title">Belum Ada Membership</h3>
      <p class="section-subtitle">
        Kamu belum memiliki paket membership aktif.
      </p>
    </div>
  <?php else: ?>

    <div class="page-grid grid-2">

      <!-- CARD 1 -->
      <div class="card-soft">
        <div class="card-header-inline">
          <div>
            <h3 class="section-title"><?= e($membership['package_name']) ?></h3>
            <p class="section-subtitle">Detail membership aktif kamu saat ini.</p>
          </div>

          <span class="badge-soft badge-active">
            <?= e(ucfirst($membership['status'])) ?>
          </span>
        </div>

        <div class="card-list">
          <div class="list-row">
            <div>
              <p class="list-row-title">Tanggal Aktif</p>
              <p class="list-row-subtitle">
                <?= e(date('d M Y', strtotime($membership['start_date']))) ?>
              </p>
            </div>
          </div>

          <div class="list-row">
            <div>
              <p class="list-row-title">Tanggal Berakhir</p>
              <p class="list-row-subtitle">
                <?= e(date('d M Y', strtotime($membership['end_date']))) ?>
              </p>
            </div>
          </div>

          <div class="list-row">
            <div>
              <p class="list-row-title">Sisa Masa Aktif</p>
              <p class="list-row-subtitle">
                <?= e($remainingDays . ' Hari') ?>
              </p>
            </div>
          </div>

          <div class="list-row">
            <div>
              <p class="list-row-title">Harga Paket</p>
              <p class="list-row-subtitle">
                Rp <?= number_format($membership['price'], 0, ',', '.') ?>
              </p>
            </div>
          </div>
        </div>
      </div>

      <!-- CARD 2 -->
      <div class="card-soft">
        <div class="card-header-inline">
          <div>
            <h3 class="section-title">Deskripsi Paket</h3>
            <p class="section-subtitle">Detail manfaat membership kamu.</p>
          </div>
        </div>

        <div class="card-list">
          <div class="list-row">
            <div>
              <p class="list-row-title">
                <?= e($membership['description'] ?? 'Belum ada deskripsi paket.') ?>
              </p>
            </div>
            <span class="badge-soft badge-info">Info</span>
          </div>
        </div>
      </div>

    </div>

  <?php endif; ?>

</section>

<?php include '../includes/layout_bottom.php'; ?>