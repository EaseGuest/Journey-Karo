<?php
require_once dirname(__DIR__) . '/includes/init.php';
require_once dirname(__DIR__) . '/includes/crud-helpers.php';
requireAdmin();
$id=(int)($_GET['id']??0);
$row=$id?dbFetch("SELECT * FROM blogs WHERE id=? AND deleted_at IS NULL",[$id]):null;
$pageTitle=$row?'Edit Blog':'New Blog'; $activeNav='blogs';
$cats=['Travel Guides','Pilgrimage','Wildlife','Festivals','Local Experiences'];
if($_SERVER['REQUEST_METHOD']==='POST'){
 requireCsrfForm();
 $title=clean($_POST['title']??''); $slug=clean($_POST['slug']??'')?:makeSlug($title);
 $img=clean($_POST['featured_image']??''); if($u=adminUploadImage($_FILES['image']??[],'blogs'))$img=imgUrl($u); elseif($row)$img=$row['featured_image'];
 $pub=clean($_POST['status']??'draft')==='published'?($_POST['published_at']??date('Y-m-d H:i:s')):null;
 $f=[$title,$slug,clean($_POST['excerpt']??''),$_POST['content']??'',$img,clean($_POST['author_name']??''),clean($_POST['category']??''),(int)($_POST['read_time_minutes']??5),clean($_POST['meta_title']??''),clean($_POST['meta_description']??''),!empty($_POST['is_featured'])?1:0,clean($_POST['status']??'draft'),$pub];
 if($id&&$row){dbQuery("UPDATE blogs SET title=?,slug=?,excerpt=?,content=?,featured_image=?,author_name=?,category=?,read_time_minutes=?,meta_title=?,meta_description=?,is_featured=?,status=?,published_at=?,updated_at=NOW() WHERE id=?",array_merge($f,[$id]));}
 else{dbQuery("INSERT INTO blogs (title,slug,excerpt,content,featured_image,author_name,category,read_time_minutes,meta_title,meta_description,is_featured,status,published_at) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?)",$f);}
 adminFlash('success','Blog saved.'); header('Location: '.adminUrl('blogs/index.php')); exit;
}
$r=$row?:[]; require dirname(__DIR__).'/includes/layout-start.php';
?>
<form method="POST" enctype="multipart/form-data" class="admin-card" style="max-width:800px"><?= csrfField() ?>
<div class="form-group"><label>Title *</label><input name="title" class="form-control" required value="<?= e($r['title']??'') ?>"></div>
<div class="form-group"><label>Slug</label><input name="slug" class="form-control" value="<?= e($r['slug']??'') ?>"></div>
<div class="form-group"><label>Category</label><select name="category" class="form-control"><?php foreach($cats as $c): ?><option <?= ($r['category']??'')===$c?'selected':'' ?>><?= e($c) ?></option><?php endforeach; ?></select></div>
<div class="form-group"><label>Excerpt</label><textarea name="excerpt" class="form-control" rows="2"><?= e($r['excerpt']??'') ?></textarea></div>
<div class="form-group"><label>Content (HTML)</label><textarea name="content" class="form-control" rows="12"><?= e($r['content']??'') ?></textarea></div>
<div class="form-group"><label>Featured image URL</label><input name="featured_image" class="form-control" value="<?= e($r['featured_image']??'') ?>"><input type="file" name="image" accept="image/*" style="margin-top:0.5rem"></div>
<div class="form-group"><label>Author</label><input name="author_name" class="form-control" value="<?= e($r['author_name']??'Journey Karo Team') ?>"></div>
<div class="form-group"><label>Read time (min)</label><input name="read_time_minutes" type="number" class="form-control" value="<?= (int)($r['read_time_minutes']??5) ?>"></div>
<div class="form-group"><label>Meta title / description</label><input name="meta_title" class="form-control" value="<?= e($r['meta_title']??'') ?>"><textarea name="meta_description" class="form-control" rows="2" style="margin-top:0.5rem"><?= e($r['meta_description']??'') ?></textarea></div>
<div class="form-group"><label>Status</label><select name="status" class="form-control"><option value="draft">Draft</option><option value="published" <?= ($r['status']??'')==='published'?'selected':'' ?>>Published</option></select></div>
<div class="form-group"><label><input type="checkbox" name="is_featured" value="1" <?= !empty($r['is_featured'])?'checked':'' ?>> Featured</label></div>
<button class="btn btn-primary">Save</button></form>
<?php require dirname(__DIR__).'/includes/layout-end.php';
