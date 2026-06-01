<?php
require_once dirname(__DIR__) . '/includes/init.php';
require_once dirname(__DIR__) . '/includes/crud-helpers.php';
requireAdmin();
$pageTitle = 'Packages'; $activeNav = 'packages';
$rows = dbFetchAll("SELECT p.id,p.name,p.slug,p.price,p.status,d.name AS dest FROM packages p LEFT JOIN destinations d ON p.destination_id=d.id WHERE p.deleted_at IS NULL ORDER BY p.sort_order");
require dirname(__DIR__) . '/includes/layout-start.php'; adminShowFlash();
?>
<a href="<?= adminUrl('packages/edit.php') ?>" class="btn btn-primary" style="margin-bottom:1rem">+ Add Package</a>
<div class="admin-card" style="padding:0;overflow:hidden">
<table class="admin-table"><thead><tr><th>Name</th><th>Destination</th><th>Price</th><th>Status</th><th></th></tr></thead><tbody>
<?php foreach ($rows as $r): ?>
<tr><td><?= e($r['name']) ?></td><td><?= e($r['dest'] ?? '—') ?></td><td><?= formatPrice((int)$r['price']) ?></td><td><?= e($r['status']) ?></td>
<td><a href="<?= adminUrl('packages/edit.php?id='.(int)$r['id']) ?>" class="btn btn-outline btn-sm">Edit</a>
<a href="<?= adminUrl('packages/delete.php?id='.(int)$r['id']) ?>" class="btn btn-danger btn-sm" onclick="return confirm('Delete?')">Delete</a></td></tr>
<?php endforeach; ?></tbody></table></div>
<?php require dirname(__DIR__) . '/includes/layout-end.php'; ?>
