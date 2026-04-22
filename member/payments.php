<?php
/* member/payments.php */
session_start();
$_SESSION['role'] = $_SESSION['role'] ?? 'member';
$_SESSION['name'] = $_SESSION['name'] ?? 'Michael Member';

$pageTitle = 'Payment History';
$topbarTitle = 'Riwayat Pembayaran';
$topbarSubtitle = 'Lihat seluruh transaksi membership kamu dengan status yang jelas.';
$searchPlaceholder = 'Cari invoice pembayaran...';

include '../includes/layout_top.php';

$payments = [
  ['invoice' => 'INV-24081', 'amount' => 'Rp 650.000', 'status' => 'Paid', 'date' => '20 Apr 2026'],
  ['invoice' => 'INV-23890', 'amount' => 'Rp 650.000', 'status' => 'Paid', 'date' => '20 Mar 2026'],
  ['invoice' => 'INV-23644', 'amount' => 'Rp 500.000', 'status' => 'Pending', 'date' => '18 Feb 2026'],
];
?>

<section class="page-section">
  <div class="table-card">
    <div class="card-header-inline">
      <div>
        <h3 class="section-title">Riwayat Pembayaran Saya</h3>
        <p class="section-subtitle">Semua pembayaran membership yang pernah dilakukan.</p>
      </div>
    </div>

    <div class="table-responsive">
      <table class="table">
        <thead>
          <tr>
            <th>Invoice</th>
            <th>Nominal</th>
            <th>Status</th>
            <th>Tanggal</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($payments as $payment): ?>
            <?php
            $badgeClass = 'badge-pending';
            if ($payment['status'] === 'Paid')
              $badgeClass = 'badge-active';
            if ($payment['status'] === 'Failed')
              $badgeClass = 'badge-failed';
            ?>
            <tr>
              <td><strong><?= e($payment['invoice']) ?></strong></td>
              <td><?= e($payment['amount']) ?></td>
              <td><span class="badge-soft <?= $badgeClass ?>"><?= e($payment['status']) ?></span></td>
              <td><?= e($payment['date']) ?></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
</section>

<?php include '../includes/layout_bottom.php'; ?>