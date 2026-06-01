<?php
require_once dirname(__DIR__) . '/includes/init.php';
require_once dirname(__DIR__) . '/includes/crud-helpers.php';
requireAdmin();
$pageTitle='Blogs'; $activeNav='blogs';
$rows=dbFetchAll("SELECT id,title,slug,category,status,published_at FROM blogs WHERE deleted_at IS NULL ORDER BY published_at DESC");
require dirname(__DIR__).'/includes/layout-start.php'; adminShowFlash();
?><a href="<?= adminUrl('blogs/edit.php') ?>" class="btn btn-primary" style="margin-bottom:1rem">+ New Post</a>
<div class="admin-card" style="padding:0;overflow:hidden"><table class="admin-table"><thead><tr><th>Title</th><th>Category</th><th>Status</th><th>Published</th><th></th></tr></thead><tbody>
<?php foreach($rows as $r): ?><tr><td><?= e($r['title']) ?></td><td><?= e($r['category']??'—') ?></td><td><?= e($r['status']) ?></td><td><?= $r['published_at']?e(formatDate($r['published_at'])):'—' ?></td>
<td><a href="<?= adminUrl('blogs/edit.php?id='.(int)$r['id']) ?>" class="btn btn-outline btn-sm">Edit</a>
<a href="<?= adminUrl('blogs/delete.php?id='.(int)$r['id']) ?>" class="btn btn-danger btn-sm" onclick="return confirm('Delete?')">Delete</a></td></tr><?php endforeach; ?>
</tbody></table></div><?php require dirname(__DIR__).'/includes/layout-end.php';
