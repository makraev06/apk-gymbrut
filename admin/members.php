<?php
/* admin/members.php */
session_start();
$_SESSION['role'] = $_SESSION['role'] ?? 'admin';
$_SESSION['name'] = $_SESSION['name'] ?? 'Michael Admin';

$pageTitle = 'Manage Members';
$topbarTitle = 'Members';
$topbarSubtitle = 'Kelola seluruh data member gym secara rapi dan terstruktur.';
$searchPlaceholder = 'Cari nama member atau email...';

include '../includes/layout_top.php';

$members = [
  ['name' => 'Andi Saputra', 'email' => 'andi@email.com', 'phone' => '0812-3344-5566', 'package' => 'Premium Plus', 'join' => '12 Jan 2026', 'status' => 'Active'],
  ['name' => 'Rina Permata', 'email' => 'rina@email.com', 'phone' => '0813-7777-4455', 'package' => 'Fat Loss Plan', 'join' => '20 Jan 2026', 'status' => 'Active'],
  ['name' => 'Dimas Pratama', 'email' => 'dimas@email.com', 'phone' => '0822-1234-5678', 'package' => 'Basic Monthly', 'join' => '05 Feb 2026', 'status' => 'Pending'],
  ['name' => 'Salsa Putri', 'email' => 'salsa@email.com', 'phone' => '0819-8745-3321', 'package' => 'Strength Builder', 'join' => '18 Feb 2026', 'status' => 'Active'],
  ['name' => 'Fikri Ramadhan', 'email' => 'fikri@email.com', 'phone' => '0852-9000-8821', 'package' => 'Premium Plus', 'join' => '03 Mar 2026', 'status' => 'Inactive'],
];
?>

<section class="page-section">
  <div class="table-card">
    <div class="card-header-inline">
      <div>
        <h3 class="section-title">Data Member</h3>
        <p class="section-subtitle">Daftar member aktif, pending, dan nonaktif.</p>
      </div>

      <div class="d-flex align-center gap-8">
        <a href="#" class="btn-outline-soft btn-sm"><i class="bi bi-funnel"></i> Filter</a>
        <a href="#" class="gradient-btn btn-sm"><i class="bi bi-plus-lg"></i> Tambah Member</a>
      </div>
    </div>

    <div class="table-responsive">
      <table class="table">
        <thead>
          <tr>
            <th>Nama</th>
            <th>Email</th>
            <th>No HP</th>
            <th>Paket</th>
            <th>Join Date</th>
            <th>Status</th>
            <th class="text-end">Action</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($members as $member): ?>
            <tr>
              <td><strong><?= e($member['name']) ?></strong></td>
              <td><?= e($member['email']) ?></td>
              <td><?= e($member['phone']) ?></td>
              <td><?= e($member['package']) ?></td>
              <td><?= e($member['join']) ?></td>
              <td>
                <?php
                $badgeClass = 'badge-active';
                if ($member['status'] === 'Pending')
                  $badgeClass = 'badge-pending';
                if ($member['status'] === 'Inactive')
                  $badgeClass = 'badge-inactive';
                ?>
                <span class="badge-soft <?= $badgeClass ?>"><?= e($member['status']) ?></span>
              </td>
              <td class="text-end">
                <div class="d-flex align-center justify-between gap-8" style="justify-content:flex-end;">
                  <a href="#" class="btn-outline-soft btn-sm"><i class="bi bi-eye"></i> Detail</a>
                  <a href="#" class="btn-outline-soft btn-sm"><i class="bi bi-pencil-square"></i> Edit</a>
                  <a href="#" class="btn-outline-soft btn-sm"><i class="bi bi-trash3"></i> Hapus</a>
                </div>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
</section>

<?php include '../includes/layout_bottom.php'; ?>