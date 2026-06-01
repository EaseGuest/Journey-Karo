<?php
require_once __DIR__ . '/includes/init.php';
requireAdmin();

$pageTitle = 'Dashboard';
$activeNav = 'dashboard';

$stats = [
    'leads' => 0,
    'packages' => 0,
    'destinations' => 0,
    'blogs' => 0,
];

$recentLeads = [];
$leadByStatus = [];

if (dbIsConnected()) {
    try {
        $stats['leads'] = dbCount('inquiries', 'deleted_at IS NULL');
        $stats['packages'] = dbCount('packages', 'deleted_at IS NULL AND status = ?', ['active']);
        $stats['destinations'] = dbCount('destinations', 'deleted_at IS NULL AND status = ?', ['active']);
        $stats['blogs'] = dbCount('blogs', "deleted_at IS NULL AND status = 'published'");
        $recentLeads = dbFetchAll(
            "SELECT id, name, phone, destination, status, created_at FROM inquiries
             WHERE deleted_at IS NULL ORDER BY created_at DESC LIMIT 8"
        );
        $leadByStatus = dbFetchAll(
            "SELECT status, COUNT(*) AS cnt FROM inquiries WHERE deleted_at IS NULL GROUP BY status"
        );
    } catch (Throwable $e) {
        error_log('Dashboard stats: ' . $e->getMessage());
    }
}

require __DIR__ . '/includes/layout-start.php';
?>

<?php if (!dbIsConnected()): ?>
<div class="alert alert-error">Database not connected. Import <code>database/schema.sql</code> and <code>database/seeds.sql</code>, then update <code>includes/config.php</code>.</div>
<?php endif; ?>

<div class="admin-grid">
  <div class="admin-card stat-card"><div class="num"><?= (int)$stats['leads'] ?></div><div class="lbl">Total Leads</div></div>
  <div class="admin-card stat-card"><div class="num"><?= (int)$stats['packages'] ?></div><div class="lbl">Active Packages</div></div>
  <div class="admin-card stat-card"><div class="num"><?= (int)$stats['destinations'] ?></div><div class="lbl">Destinations</div></div>
  <div class="admin-card stat-card"><div class="num"><?= (int)$stats['blogs'] ?></div><div class="lbl">Published Blogs</div></div>
</div>

<div style="display:grid;grid-template-columns:1fr 280px;gap:1.25rem">
  <div class="admin-card">
    <h2 style="margin:0 0 1rem;font-size:1rem">Recent Inquiries</h2>
    <?php if (!$recentLeads): ?>
    <p style="color:var(--adm-muted)">No inquiries yet.</p>
    <?php else: ?>
    <table class="admin-table">
      <thead><tr><th>Name</th><th>Phone</th><th>Destination</th><th>Status</th><th>Date</th></tr></thead>
      <tbody>
        <?php foreach ($recentLeads as $lead): ?>
        <tr>
          <td><a href="<?= adminUrl('leads/view.php?id=' . (int)$lead['id']) ?>"><?= e($lead['name']) ?></a></td>
          <td><?= e($lead['phone']) ?></td>
          <td><?= e($lead['destination'] ?: '—') ?></td>
          <td><span class="badge badge-<?= e(str_replace(' ', '_', $lead['status'])) ?>"><?= e(ucwords(str_replace('_', ' ', $lead['status']))) ?></span></td>
          <td><?= e(formatDate($lead['created_at'])) ?></td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  <a href="<?= adminUrl('leads/index.php') ?>" class="btn btn-outline btn-sm" style="margin-top:1rem">View all leads →</a>
    <?php endif; ?>
  </div>

  <div class="admin-card">
    <h2 style="margin:0 0 1rem;font-size:1rem">Lead Status</h2>
    <?php foreach ($leadByStatus as $row): ?>
    <div style="display:flex;justify-content:space-between;padding:0.5rem 0;border-bottom:1px solid var(--adm-border)">
      <span><?= e(ucwords(str_replace('_', ' ', $row['status']))) ?></span>
      <strong><?= (int)$row['cnt'] ?></strong>
    </div>
    <?php endforeach; ?>
    <?php if (!$leadByStatus): ?><p style="color:var(--adm-muted);font-size:0.85rem">No data</p><?php endif; ?>
  </div>
</div>

<?php require __DIR__ . '/includes/layout-end.php'; ?>
