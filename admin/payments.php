<?php
/* admin/payments.php */
session_start();

$pageTitle = 'Payments';
$topbarTitle = 'Payments';
$topbarSubtitle = 'Pantau status pembayaran member dan invoice secara real-time.';
$searchPlaceholder = 'Cari invoice atau nama member...';

include '../includes/layout_top.php';

$payments = gymbrut_query_all($conn, "
  SELECT
    p.payment_id,
    p.amount,
    p.payment_date,
    p.proof_file,
    p.status,
    u.name AS member_name,
    mp.package_name
  FROM payments p
  JOIN memberships m ON p.membership_id = m.membership_id
  JOIN users u ON m.user_id = u.user_id
  JOIN membership_packages mp ON m.package_id = mp.package_id
  ORDER BY p.payment_date DESC
");
?>

<section class="page-section">
  <div class="table-card">
    <div class="card-header-inline">
      <div>
        <h3 class="section-title">Riwayat Pembayaran</h3>
        <p class="section-subtitle">Data pembayaran langsung dari database.</p>
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
            <th>Paket</th>
            <th>Nominal</th>
            <th>Bukti</th>
            <th>Status</th>
            <th>Tanggal</th>
          </tr>
        </thead>

        <tbody>
          <?php if (empty($payments)): ?>
            <tr>
              <td colspan="7" class="text-soft">Belum ada data pembayaran.</td>
            </tr>
          <?php endif; ?>

          <?php foreach ($payments as $payment): ?>
            <?php
            $badgeClass = 'badge-pending';

            if ($payment['status'] === 'verified') {
              $badgeClass = 'badge-active';
            }

            if ($payment['status'] === 'rejected') {
              $badgeClass = 'badge-failed';
            }
            ?>

            <tr>
              <td><strong>INV-<?= str_pad($payment['payment_id'], 5, '0', STR_PAD_LEFT) ?></strong></td>
              <td><?= e($payment['member_name']) ?></td>
              <td><?= e($payment['package_name']) ?></td>
              <td>Rp <?= number_format($payment['amount'], 0, ',', '.') ?></td>
              <td>
                <?php if (!empty($payment['proof_file'])): ?>
                  <a href="../uploads/payments/<?= e($payment['proof_file']) ?>" target="_blank"
                    class="btn-outline-soft btn-sm">
                    <i class="bi bi-image"></i> Lihat
                  </a>
                <?php else: ?>
                  <span class="text-soft">-</span>
                <?php endif; ?>
              </td>
              <td>
                <span class="badge-soft <?= $badgeClass ?>">
                  <?= e(ucfirst($payment['status'])) ?>
                </span>
              </td>
              <td><?= date('d M Y', strtotime($payment['payment_date'])) ?></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
</section>

<?php include '../includes/layout_bottom.php'; ?>