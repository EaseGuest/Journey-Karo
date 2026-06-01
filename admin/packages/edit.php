<?php
require_once dirname(__DIR__) . '/includes/init.php';
require_once dirname(__DIR__) . '/includes/crud-helpers.php';
requireAdmin();
$id = (int)($_GET['id'] ?? 0);
$row = $id ? dbFetch("SELECT * FROM packages WHERE id=? AND deleted_at IS NULL", [$id]) : null;
$destinations = dbFetchAll("SELECT id, name FROM destinations WHERE deleted_at IS NULL ORDER BY name");
$pageTitle = $row ? 'Edit Package' : 'Add Package'; $activeNav = 'packages';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    requireCsrfForm();
    $name = clean($_POST['name'] ?? '');
    $slug = clean($_POST['slug'] ?? '') ?: makeSlug($name);
    $img = clean($_POST['featured_image'] ?? '');
    if ($u = adminUploadImage($_FILES['image'] ?? [], 'packages')) $img = imgUrl($u);
    elseif ($row) $img = $row['featured_image'];

    $fields = [
        (int)($_POST['destination_id'] ?? 0) ?: null, $name, $slug,
        clean($_POST['short_description'] ?? ''), clean($_POST['description'] ?? ''),
        clean($_POST['category'] ?? ''), (int)($_POST['days'] ?? 1), (int)($_POST['nights'] ?? 0),
        (int)($_POST['price'] ?? 0), (float)($_POST['rating'] ?? 4.8), (int)($_POST['review_count'] ?? 0),
        $img, clean($_POST['inclusions'] ?? ''), clean($_POST['exclusions'] ?? ''),
        clean($_POST['meta_title'] ?? ''), clean($_POST['meta_description'] ?? ''),
        !empty($_POST['is_featured']) ? 1 : 0, (int)($_POST['sort_order'] ?? 0), clean($_POST['status'] ?? 'active'),
    ];

    if ($id && $row) {
        dbQuery("UPDATE packages SET destination_id=?,name=?,slug=?,short_description=?,description=?,category=?,days=?,nights=?,price=?,rating=?,review_count=?,featured_image=?,inclusions=?,exclusions=?,meta_title=?,meta_description=?,is_featured=?,sort_order=?,status=?,updated_at=NOW() WHERE id=?", array_merge($fields, [$id]));
        adminFlash('success', 'Package updated.');
    } else {
        dbQuery("INSERT INTO packages (destination_id,name,slug,short_description,description,category,days,nights,price,rating,review_count,featured_image,inclusions,exclusions,meta_title,meta_description,is_featured,sort_order,status) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)", $fields);
        adminFlash('success', 'Package created.');
    }
    header('Location: ' . adminUrl('packages/index.php')); exit;
}
$r = $row ?: [];
require dirname(__DIR__) . '/includes/layout-start.php';
?>
<form method="POST" enctype="multipart/form-data" class="admin-card" style="max-width:720px">
<?= csrfField() ?>
<div class="form-group"><label>Name *</label><input name="name" class="form-control" required value="<?= e($r['name']??'') ?>"></div>
<div class="form-group"><label>Slug</label><input name="slug" class="form-control" value="<?= e($r['slug']??'') ?>"></div>
<div class="form-group"><label>Destination</label><select name="destination_id" class="form-control"><option value="">—</option>
<?php foreach ($destinations as $d): ?><option value="<?= (int)$d['id'] ?>" <?= (int)($r['destination_id']??0)===(int)$d['id']?'selected':'' ?>><?= e($d['name']) ?></option><?php endforeach; ?></select></div>
<div class="form-group"><label>Category</label><input name="category" class="form-control" value="<?= e($r['category']??'') ?>"></div>
<div class="form-group"><label>Days / Nights</label><div style="display:flex;gap:0.5rem"><input name="days" type="number" class="form-control" value="<?= (int)($r['days']??1) ?>"><input name="nights" type="number" class="form-control" value="<?= (int)($r['nights']??0) ?>"></div></div>
<div class="form-group"><label>Price (₹)</label><input name="price" type="number" class="form-control" value="<?= (int)($r['price']??0) ?>"></div>
<div class="form-group"><label>Rating / Reviews</label><div style="display:flex;gap:0.5rem"><input name="rating" step="0.1" class="form-control" value="<?= e($r['rating']??'4.8') ?>"><input name="review_count" type="number" class="form-control" value="<?= (int)($r['review_count']??0) ?>"></div></div>
<div class="form-group"><label>Short description</label><textarea name="short_description" class="form-control" rows="2"><?= e($r['short_description']??'') ?></textarea></div>
<div class="form-group"><label>Description</label><textarea name="description" class="form-control" rows="4"><?= e($r['description']??'') ?></textarea></div>
<div class="form-group"><label>Inclusions (JSON/lines)</label><textarea name="inclusions" class="form-control" rows="2"><?= e($r['inclusions']??'') ?></textarea></div>
<div class="form-group"><label>Exclusions</label><textarea name="exclusions" class="form-control" rows="2"><?= e($r['exclusions']??'') ?></textarea></div>
<div class="form-group"><label>Image URL</label><input name="featured_image" class="form-control" value="<?= e($r['featured_image']??'') ?>"></div>
<div class="form-group"><label>Upload</label><input type="file" name="image" accept="image/*"></div>
<div class="form-group"><label>Meta title / description</label><input name="meta_title" class="form-control" value="<?= e($r['meta_title']??'') ?>"><textarea name="meta_description" class="form-control" rows="2" style="margin-top:0.5rem"><?= e($r['meta_description']??'') ?></textarea></div>
<div class="form-group"><label><input type="checkbox" name="is_featured" value="1" <?= !empty($r['is_featured'])?'checked':'' ?>> Featured</label></div>
<div class="form-group"><label>Status</label><select name="status" class="form-control"><option value="active">Active</option><option value="inactive">Inactive</option><option value="draft">Draft</option></select></div>
<button class="btn btn-primary">Save</button> <a href="<?= adminUrl('packages/index.php') ?>" class="btn btn-outline">Cancel</a>
</form>
<?php require dirname(__DIR__) . '/includes/layout-end.php'; ?>
