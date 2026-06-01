<?php
require_once dirname(__DIR__) . '/includes/init.php';
require_once dirname(__DIR__) . '/includes/crud-helpers.php';
requireAdmin();
$pageTitle='Gallery'; $activeNav='gallery';
if($_SERVER['REQUEST_METHOD']==='POST'){requireCsrfForm();
 $path=clean($_POST['image_path']??''); if($u=adminUploadImage($_FILES['image']??[],'gallery'))$path=imgUrl($u);
 dbQuery("INSERT INTO gallery (title,image_path,category,destination_id,sort_order,status) VALUES (?,?,?,?,?,?)",
 [clean($_POST['title']??''),$path,clean($_POST['category']??'general'),(int)($_POST['destination_id']??0)?:null,(int)($_POST['sort_order']??0),'active']);
 adminFlash('success','Image added.'); header('Location: '.adminUrl('gallery/index.php')); exit;}
$rows=dbFetchAll("SELECT g.*,d.name AS dest FROM gallery g LEFT JOIN destinations d ON g.destination_id=d.id WHERE g.deleted_at IS NULL ORDER BY g.sort_order");
$dests=dbFetchAll("SELECT id,name FROM destinations WHERE deleted_at IS NULL");
require dirname(__DIR__).'/includes/layout-start.php'; adminShowFlash();
?>
<div class="admin-card"><h3>Upload image</h3><form method="POST" enctype="multipart/form-data"><?= csrfField() ?>
<div class="form-group"><label>Title</label><input name="title" class="form-control" required></div>
<div class="form-group"><label>File</label><input type="file" name="image" accept="image/*"></div>
<div class="form-group"><label>Or image URL</label><input name="image_path" class="form-control"></div>
<div class="form-group"><label>Category</label><input name="category" class="form-control" value="general"></div>
<div class="form-group"><label>Destination</label><select name="destination_id" class="form-control"><option value="">—</option><?php foreach($dests as $d): ?><option value="<?= (int)$d['id'] ?>"><?= e($d['name']) ?></option><?php endforeach; ?></select></div>
<button class="btn btn-primary">Add</button></form></div>
<div class="admin-card" style="padding:0;overflow:hidden;margin-top:1rem"><table class="admin-table"><thead><tr><th>Preview</th><th>Title</th><th>Category</th><th></th></tr></thead><tbody>
<?php foreach($rows as $r): ?><tr><td><img src="<?= e($r['image_path']) ?>" alt="" style="width:64px;height:48px;object-fit:cover;border-radius:4px"></td><td><?= e($r['title']) ?></td><td><?= e($r['category']) ?></td>
<td><a href="<?= adminUrl('gallery/delete.php?id='.(int)$r['id']) ?>" class="btn btn-danger btn-sm" onclick="return confirm('Delete?')">Delete</a></td></tr><?php endforeach; ?>
</tbody></table></div><?php require dirname(__DIR__).'/includes/layout-end.php';
