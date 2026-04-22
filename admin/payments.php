<?php
/* admin/payments.php */
session_start();
$_SESSION['role'] = $_SESSION['role'] ?? 'admin';
$_SESSION['name'] = $_SESSION['name'] ?? 'Michael Admin';

$pageTitle = 'Payments';
$topbarTitle = 'Payments';
$topbarSubtitle = 'Pantau status pembayaran member dan invoice secara real-time.';
$searchPlaceholder = 'Cari invoice atau nama member...';

include '../includes/layout_top.php';

$payments = [
  ['invoice' => 'INV-24081', 'member' => 'Andi Saputra', 'amount' => 'Rp 650.000', 'method' => 'Transfer Bank', 'status' => 'Paid', 'date' => '21 Apr 2026'],
  ['invoice' => 'INV-24082', 'member' => 'Rina Permata', 'amount' => 'Rp 500.000', 'method' => 'QRIS', 'status' => 'Paid', 'date' => '21 Apr 2026'],
  ['invoice' => 'INV-24083', 'member' => 'Dimas Pratama', 'amount' => 'Rp 250.000', 'method' => 'Cash', 'status' => 'Pending', 'date' => '20 Apr 2026'],
  ['invoice' => 'INV-24084', 'member' => 'Salsa Putri', 'amount' => 'Rp 650.000', 'method' => 'Transfer Bank', 'status' => 'Failed', 'date' => '20 Apr 2026'],
  ['invoice' => 'INV-24085', 'member' => 'Fikri Ramadhan', 'amount' => 'Rp 250.000', 'method' => 'Debit Card', 'status' => 'Paid', 'date' => '19 Apr 2026'],
];
?>

<section class="page-section">
  <div class="table-card">
    <div class="card-header-inline">
      <div>
        <h3 class="section-title">Riwayat Pembayaran</h3>
        <p class="section-subtitle">Semua transaksi member dalam satu tabel yang rapi.</p>
      </div>
      <a href="#" class="gradient-btn btn-sm">
        <i class="bi bi-download"></i> Export
      </a>
    </div>

    <div class="table-responsive">
      <table class="table">
        <thead>
          <tr>
            <th>Invoice</th>
            <th>Nama Member</th>
            <th>Nominal</th>
            <th>Metode</th>
            <th>Status</th>
            <th>Tanggal</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($payments as $payment): ?>
            <tr>
              <td><strong><?= e($payment['invoice']) ?></strong></td>
              <td><?= e($payment['member']) ?></td>
              <td><?= e($payment['amount']) ?></td>
              <td><?= e($payment['method']) ?></td>
              <td>
                <?php
                $badgeClass = 'badge-pending';
                if ($payment['status'] === 'Paid')
                  $badgeClass = 'badge-active';
                if ($payment['status'] === 'Failed')
                  $badgeClass = 'badge-failed';
                ?>
                <span class="badge-soft <?= $badgeClass ?>"><?= e($payment['status']) ?></span>
              </td>
              <td><?= e($payment['date']) ?></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
</section>

<?php include '../includes/layout_bottom.php'; ?>