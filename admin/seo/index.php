<?php
require_once dirname(__DIR__) . '/includes/init.php';
require_once dirname(__DIR__) . '/includes/crud-helpers.php';
requireAdmin();
$pageTitle='SEO Management'; $activeNav='seo';
if($_SERVER['REQUEST_METHOD']==='POST'){requireCsrfForm();
 $id=(int)($_POST['id']??0);
 $f=[clean($_POST['page_slug']??''),clean($_POST['meta_title']??''),clean($_POST['meta_description']??''),clean($_POST['meta_keywords']??''),clean($_POST['canonical_url']??''),clean($_POST['og_image']??'')];
 if($id){dbQuery("UPDATE seo_meta SET page_slug=?,meta_title=?,meta_description=?,meta_keywords=?,canonical_url=?,og_image=?,updated_at=NOW() WHERE id=?",array_merge($f,[$id]));}
 else{dbQuery("INSERT INTO seo_meta (page_slug,meta_title,meta_description,meta_keywords,canonical_url,og_image) VALUES (?,?,?,?,?,?)",$f);}
 adminFlash('success','SEO saved.'); header('Location: '.adminUrl('seo/index.php')); exit;}
$rows=dbFetchAll("SELECT * FROM seo_meta ORDER BY page_slug");
require dirname(__DIR__).'/includes/layout-start.php'; adminShowFlash();
?>
<div class="admin-card"><form method="POST"><?= csrfField() ?><input type="hidden" name="id" value="0">
<div class="form-group"><label>Page slug</label><input name="page_slug" class="form-control" placeholder="home, about, packages…" required></div>
<div class="form-group"><label>Meta title</label><input name="meta_title" class="form-control"></div>
<div class="form-group"><label>Meta description</label><textarea name="meta_description" class="form-control" rows="2"></textarea></div>
<div class="form-group"><label>Keywords</label><input name="meta_keywords" class="form-control"></div>
<div class="form-group"><label>Canonical URL</label><input name="canonical_url" class="form-control"></div>
<div class="form-group"><label>OG image URL</label><input name="og_image" class="form-control"></div>
<button class="btn btn-primary">Add SEO entry</button></form></div>
<div class="admin-card" style="padding:0;overflow:hidden;margin-top:1rem"><table class="admin-table"><thead><tr><th>Slug</th><th>Title</th><th></th></tr></thead><tbody>
<?php foreach($rows as $r): ?><tr><td><?= e($r['page_slug']) ?></td><td><?= e(truncate($r['meta_title']??'',50)) ?></td>
<td><a href="<?= adminUrl('seo/edit.php?id='.(int)$r['id']) ?>" class="btn btn-outline btn-sm">Edit</a></td></tr><?php endforeach; ?>
</tbody></table></div>
<p style="margin-top:1rem"><a href="<?= APP_URL ?>/sitemap.php" target="_blank" class="btn btn-outline">View sitemap.php</a></p>
<?php require dirname(__DIR__).'/includes/layout-end.php';
