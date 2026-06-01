<?php
require_once dirname(__DIR__) . '/includes/init.php';
require_once dirname(__DIR__) . '/includes/crud-helpers.php';
requireAdmin();
$pageTitle='Reviews'; $activeNav='reviews';
if(isset($_GET['approve'])){dbQuery("UPDATE reviews SET status='approved' WHERE id=?",[(int)$_GET['approve']]); adminFlash('success','Review approved.'); header('Location: '.adminUrl('reviews/index.php')); exit;}
$rows=dbFetchAll("SELECT * FROM reviews WHERE deleted_at IS NULL ORDER BY created_at DESC");
require dirname(__DIR__).'/includes/layout-start.php'; adminShowFlash();
?><a href="<?= adminUrl('reviews/edit.php') ?>" class="btn btn-primary" style="margin-bottom:1rem">+ Add Review</a>
<div class="admin-card" style="padding:0;overflow:hidden"><table class="admin-table"><thead><tr><th>Name</th><th>Rating</th><th>Destination</th><th>Status</th><th></th></tr></thead><tbody>
<?php foreach($rows as $r): ?><tr><td><?= e($r['reviewer_name']) ?></td><td><?= (int)$r['rating'] ?>★</td><td><?= e($r['destination']??'—') ?></td><td><?= e($r['status']) ?></td>
<td><?php if($r['status']!=='approved'): ?><a href="?approve=<?= (int)$r['id'] ?>" class="btn btn-primary btn-sm">Approve</a><?php endif; ?>
<a href="<?= adminUrl('reviews/edit.php?id='.(int)$r['id']) ?>" class="btn btn-outline btn-sm">Edit</a></td></tr><?php endforeach; ?>
</tbody></table></div><?php require dirname(__DIR__).'/includes/layout-end.php';
