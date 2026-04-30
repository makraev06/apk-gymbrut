<?php
/* member/payments.php */
session_start();

$pageTitle = 'Payment History';
$topbarTitle = 'Riwayat Pembayaran';
$topbarSubtitle = 'Lihat seluruh transaksi membership kamu dengan status yang jelas.';
$searchPlaceholder = 'Cari invoice pembayaran...';

include '../includes/layout_top.php';

$userId = (int) $_SESSION['user_id'];


$payments = gymbrut_query_all($conn, "
  SELECT
    p.payment_id,
    p.amount,
    p.status,
    p.payment_date,
    p.proof_file,
    mp.package_name,
    m.start_date,
    m.end_date
  FROM payments p
  JOIN memberships m ON p.membership_id = m.membership_id
  JOIN membership_packages mp ON m.package_id = mp.package_id
  WHERE m.user_id = $userId
  ORDER BY p.payment_date DESC
");
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
            if ($payment['status'] === 'verified') {
              $badgeClass = 'badge-active';
            } elseif ($payment['status'] === 'rejected')
              $badgeClass = 'badge-failed';
            ?>
                    <tr>
                        <td><strong>INV-<?= e($payment['payment_id']) ?></strong></td>

                        <td>Rp <?= number_format($payment['amount'], 0, ',', '.') ?></td>

                        <td>
                            <span class="badge-soft <?= $badgeClass ?>">
                                <?= e(ucfirst($payment['status'])) ?>
                            </span>
                        </td>

                        <td>
                            <?= e(date('d M Y', strtotime($payment['payment_date']))) ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</section>

<?php include '../includes/layout_bottom.php'; ?>