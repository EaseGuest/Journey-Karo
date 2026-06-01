<?php
require_once dirname(__DIR__) . '/includes/init.php';
require_once dirname(__DIR__) . '/includes/crud-helpers.php';
requireAdmin();
$id=(int)($_GET['id']??0); $row=$id?dbFetch("SELECT * FROM reviews WHERE id=?",[$id]):null;
if($_SERVER['REQUEST_METHOD']==='POST'){
 requireCsrfForm();
 $f=[clean($_POST['reviewer_name']??''),clean($_POST['reviewer_email']??''),(int)($_POST['rating']??5),clean($_POST['destination']??''),clean($_POST['review_text']??''),clean($_POST['status']??'pending'),clean($_POST['reviewer_image']??'')];
 if($id){dbQuery("UPDATE reviews SET reviewer_name=?,reviewer_email=?,rating=?,destination=?,review_text=?,status=?,reviewer_image=?,updated_at=NOW() WHERE id=?",array_merge($f,[$id]));}
 else{dbQuery("INSERT INTO reviews (reviewer_name,reviewer_email,rating,destination,review_text,status,reviewer_image) VALUES (?,?,?,?,?,?,?)",$f);}
 header('Location: '.adminUrl('reviews/index.php')); exit;
}
$r=$row?:[]; require dirname(__DIR__).'/includes/layout-start.php';
?><form method="POST" class="admin-card" style="max-width:560px"><?= csrfField() ?>
<div class="form-group"><label>Name</label><input name="reviewer_name" class="form-control" required value="<?= e($r['reviewer_name']??'') ?>"></div>
<div class="form-group"><label>Email</label><input name="reviewer_email" class="form-control" value="<?= e($r['reviewer_email']??'') ?>"></div>
<div class="form-group"><label>Rating</label><select name="rating" class="form-control"><?php for($i=5;$i>=1;$i--): ?><option value="<?= $i ?>" <?= (int)($r['rating']??5)===$i?'selected':'' ?>><?= $i ?></option><?php endfor; ?></select></div>
<div class="form-group"><label>Destination</label><input name="destination" class="form-control" value="<?= e($r['destination']??'') ?>"></div>
<div class="form-group"><label>Review</label><textarea name="review_text" class="form-control" rows="4" required><?= e($r['review_text']??'') ?></textarea></div>
<div class="form-group"><label>Image URL</label><input name="reviewer_image" class="form-control" value="<?= e($r['reviewer_image']??'') ?>"></div>
<div class="form-group"><label>Status</label><select name="status" class="form-control"><option value="pending">Pending</option><option value="approved">Approved</option><option value="rejected">Rejected</option></select></div>
<button class="btn btn-primary">Save</button></form><?php require dirname(__DIR__).'/includes/layout-end.php';
