<?php
require_once dirname(__DIR__) . '/includes/init.php';
require_once dirname(__DIR__) . '/includes/crud-helpers.php';
requireAdmin();

$pageTitle = 'Lead Management';
$activeNav = 'leads';

$search = clean($_GET['q'] ?? '');
$status = clean($_GET['status'] ?? '');
$page = max(1, (int)($_GET['page'] ?? 1));
$perPage = 20;
$offset = ($page - 1) * $perPage;

$where = ['deleted_at IS NULL'];
$params = [];

if ($search) {
    $where[] = '(name LIKE ? OR email LIKE ? OR phone LIKE ? OR destination LIKE ?)';
    $q = '%' . $search . '%';
    $params = array_merge($params, [$q, $q, $q, $q]);
}
if ($status && in_array($status, ['new','contacted','quotation_sent','confirmed','lost'], true)) {
    $where[] = 'status = ?';
    $params[] = $status;
}

$whereSql = implode(' AND ', $where);
$total = dbCount('inquiries', $whereSql, $params);
$leads = dbFetchAll(
    "SELECT * FROM inquiries WHERE {$whereSql} ORDER BY created_at DESC LIMIT {$perPage} OFFSET {$offset}",
    $params
);
$totalPages = (int)ceil($total / $perPage);

require dirname(__DIR__) . '/includes/layout-start.php';
adminShowFlash();
?>

<div class="filters">
  <form method="GET" style="display:flex;gap:0.75rem;flex-wrap:wrap;flex:1">
    <input type="text" name="q" class="form-control" placeholder="Search name, email, phone…" value="<?= e($search) ?>" style="max-width:220px">
    <select name="status" class="form-control" style="max-width:160px">
      <option value="">All statuses</option>
      <?php foreach (['new','contacted','quotation_sent','confirmed','lost'] as $s): ?>
      <option value="<?= $s ?>" <?= $status === $s ? 'selected' : '' ?>><?= e(ucwords(str_replace('_', ' ', $s))) ?></option>
      <?php endforeach; ?>
    </select>
    <button type="submit" class="btn btn-primary">Filter</button>
    <a href="<?= adminUrl('leads/index.php') ?>" class="btn btn-outline">Reset</a>
  </form>
</div>

<div class="admin-card" style="padding:0;overflow:hidden">
  <table class="admin-table">
    <thead>
      <tr>
        <th>ID</th><th>Name</th><th>Phone</th><th>Email</th><th>Destination</th><th>Status</th><th>Date</th><th></th>
      </tr>
    </thead>
    <tbody>
      <?php if (!$leads): ?>
      <tr><td colspan="8" style="text-align:center;color:var(--adm-muted)">No leads found.</td></tr>
      <?php else: foreach ($leads as $lead): ?>
      <tr>
        <td>#<?= (int)$lead['id'] ?></td>
        <td><?= e($lead['name']) ?></td>
        <td><a href="tel:+91<?= e(preg_replace('/\D/', '', $lead['phone'])) ?>"><?= e($lead['phone']) ?></a></td>
        <td><?= e($lead['email']) ?></td>
        <td><?= e($lead['destination'] ?: '—') ?></td>
        <td><span class="badge badge-<?= e($lead['status']) ?>"><?= e(ucwords(str_replace('_', ' ', $lead['status']))) ?></span></td>
        <td><?= e(formatDate($lead['created_at'])) ?></td>
        <td><a href="<?= adminUrl('leads/view.php?id=' . (int)$lead['id']) ?>" class="btn btn-outline btn-sm">View</a></td>
      </tr>
      <?php endforeach; endif; ?>
    </tbody>
  </table>
</div>

<?php if ($totalPages > 1): ?>
<p style="margin-top:1rem">
  <?php for ($i = 1; $i <= $totalPages; $i++): ?>
    <a href="?page=<?= $i ?>&q=<?= urlencode($search) ?>&status=<?= urlencode($status) ?>" class="btn btn-sm <?= $i === $page ? 'btn-primary' : 'btn-outline' ?>"><?= $i ?></a>
  <?php endfor; ?>
</p>
<?php endif; ?>

<?php require dirname(__DIR__) . '/includes/layout-end.php'; ?>
