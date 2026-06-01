<?php
require_once dirname(__DIR__) . '/includes/init.php';
require_once dirname(__DIR__) . '/includes/crud-helpers.php';
requireAdmin();
$id=(int)($_GET['id']??0); $r=dbFetch("SELECT * FROM seo_meta WHERE id=?",[$id]);
if(!$r){header('Location: '.adminUrl('seo/index.php'));exit;}
if($_SERVER['REQUEST_METHOD']==='POST'){requireCsrfForm();
 dbQuery("UPDATE seo_meta SET page_slug=?,meta_title=?,meta_description=?,meta_keywords=?,canonical_url=?,og_image=?,og_title=?,og_description=?,updated_at=NOW() WHERE id=?",
 [clean($_POST['page_slug']??''),clean($_POST['meta_title']??''),clean($_POST['meta_description']??''),clean($_POST['meta_keywords']??''),clean($_POST['canonical_url']??''),clean($_POST['og_image']??''),clean($_POST['og_title']??''),clean($_POST['og_description']??''),$id]);
 header('Location: '.adminUrl('seo/index.php'));exit;}
require dirname(__DIR__).'/includes/layout-start.php';
?><form method="POST" class="admin-card" style="max-width:640px"><?= csrfField() ?>
<div class="form-group"><label>Page slug</label><input name="page_slug" class="form-control" value="<?= e($r['page_slug']) ?>"></div>
<div class="form-group"><label>Meta title</label><input name="meta_title" class="form-control" value="<?= e($r['meta_title']??'') ?>"></div>
<div class="form-group"><label>Meta description</label><textarea name="meta_description" class="form-control" rows="2"><?= e($r['meta_description']??'') ?></textarea></div>
<div class="form-group"><label>Keywords</label><input name="meta_keywords" class="form-control" value="<?= e($r['meta_keywords']??'') ?>"></div>
<div class="form-group"><label>Canonical</label><input name="canonical_url" class="form-control" value="<?= e($r['canonical_url']??'') ?>"></div>
<div class="form-group"><label>OG title / description</label><input name="og_title" class="form-control" value="<?= e($r['og_title']??'') ?>"><textarea name="og_description" class="form-control" rows="2" style="margin-top:0.5rem"><?= e($r['og_description']??'') ?></textarea></div>
<div class="form-group"><label>OG image</label><input name="og_image" class="form-control" value="<?= e($r['og_image']??'') ?>"></div>
<button class="btn btn-primary">Save</button></form><?php require dirname(__DIR__).'/includes/layout-end.php';
