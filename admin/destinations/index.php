<?php
require_once dirname(__DIR__) . '/includes/init.php';
require_once dirname(__DIR__) . '/includes/crud-helpers.php';
requireAdmin();
$pageTitle = 'Destinations';
$activeNav = 'destinations';

$rows = dbFetchAll("SELECT id, name, slug, status, is_featured, sort_order FROM destinations WHERE deleted_at IS NULL ORDER BY sort_order ASC, name ASC");

require dirname(__DIR__) . '/includes/layout-start.php';
adminShowFlash();
?>
<a href="<?= adminUrl('destinations/edit.php') ?>" class="btn btn-primary" style="margin-bottom:1rem">+ Add Destination</a>
<div class="admin-card" style="padding:0;overflow:hidden">
<table class="admin-table">
<thead><tr><th>Name</th><th>Slug</th><th>Status</th><th>Featured</th><th>Order</th><th></th></tr></thead>
<tbody>
<?php foreach ($rows as $r): ?>
<tr>
  <td><?= e($r['name']) ?></td>
  <td><?= e($r['slug']) ?></td>
  <td><?= e($r['status']) ?></td>
  <td><?= $r['is_featured'] ? 'Yes' : 'No' ?></td>
  <td><?= (int)$r['sort_order'] ?></td>
  <td>
    <a href="<?= adminUrl('destinations/edit.php?id=' . (int)$r['id']) ?>" class="btn btn-outline btn-sm">Edit</a>
    <a href="<?= adminUrl('destinations/delete.php?id=' . (int)$r['id']) ?>" class="btn btn-danger btn-sm" onclick="return confirm('Delete?')">Delete</a>
  </td>
</tr>
<?php endforeach; ?>
</tbody>
</table>
</div>
<?php require dirname(__DIR__) . '/includes/layout-end.php'; ?>
