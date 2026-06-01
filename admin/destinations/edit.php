<?php
require_once dirname(__DIR__) . '/includes/init.php';
require_once dirname(__DIR__) . '/includes/crud-helpers.php';
requireAdmin();

$id = (int)($_GET['id'] ?? 0);
$row = $id ? dbFetch("SELECT * FROM destinations WHERE id = ? AND deleted_at IS NULL", [$id]) : null;
$pageTitle = $row ? 'Edit Destination' : 'Add Destination';
$activeNav = 'destinations';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    requireCsrfForm();
    $name = clean($_POST['name'] ?? '');
    $slug = clean($_POST['slug'] ?? '') ?: makeSlug($name);
    $data = [
        $name, $slug, clean($_POST['icon'] ?? ''),
        clean($_POST['short_description'] ?? ''), clean($_POST['description'] ?? ''),
        clean($_POST['attractions'] ?? ''), clean($_POST['highlights'] ?? ''),
        clean($_POST['best_time'] ?? ''), clean($_POST['climate'] ?? ''),
        clean($_POST['duration_label'] ?? ''), (int)($_POST['starting_price'] ?? 0),
        clean($_POST['featured_image'] ?? ''),
        clean($_POST['meta_title'] ?? ''), clean($_POST['meta_description'] ?? ''),
        !empty($_POST['is_featured']) ? 1 : 0, (int)($_POST['sort_order'] ?? 0),
        clean($_POST['status'] ?? 'active'),
    ];

    if ($upload = adminUploadImage($_FILES['image'] ?? [], 'destinations')) {
        $data[10] = imgUrl($upload);
    } elseif (!empty($_POST['featured_image'])) {
        $data[10] = clean($_POST['featured_image']);
    } elseif ($row) {
        $data[10] = $row['featured_image'];
    }

    if ($id && $row) {
        dbQuery(
            "UPDATE destinations SET name=?,slug=?,icon=?,short_description=?,description=?,attractions=?,highlights=?,best_time=?,climate=?,duration_label=?,starting_price=?,featured_image=?,meta_title=?,meta_description=?,is_featured=?,sort_order=?,status=?,updated_at=NOW() WHERE id=?",
            array_merge($data, [$id])
        );
        adminFlash('success', 'Destination updated.');
    } else {
        dbQuery(
            "INSERT INTO destinations (name,slug,icon,short_description,description,attractions,highlights,best_time,climate,duration_label,starting_price,featured_image,meta_title,meta_description,is_featured,sort_order,status) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)",
            $data
        );
        adminFlash('success', 'Destination created.');
    }
    header('Location: ' . adminUrl('destinations/index.php'));
    exit;
}

$r = $row ?: [];
require dirname(__DIR__) . '/includes/layout-start.php';
?>
<form method="POST" enctype="multipart/form-data" class="admin-card" style="max-width:720px">
  <?= csrfField() ?>
  <div class="form-group"><label>Name *</label><input name="name" class="form-control" required value="<?= e($r['name'] ?? '') ?>"></div>
  <div class="form-group"><label>Slug</label><input name="slug" class="form-control" value="<?= e($r['slug'] ?? '') ?>"></div>
  <div class="form-group"><label>Icon (emoji)</label><input name="icon" class="form-control" value="<?= e($r['icon'] ?? '') ?>"></div>
  <div class="form-group"><label>Short description</label><textarea name="short_description" class="form-control" rows="2"><?= e($r['short_description'] ?? '') ?></textarea></div>
  <div class="form-group"><label>Description</label><textarea name="description" class="form-control" rows="5"><?= e($r['description'] ?? '') ?></textarea></div>
  <div class="form-group"><label>Attractions (JSON or lines)</label><textarea name="attractions" class="form-control" rows="3"><?= e($r['attractions'] ?? '') ?></textarea></div>
  <div class="form-group"><label>Highlights (JSON or lines)</label><textarea name="highlights" class="form-control" rows="3"><?= e($r['highlights'] ?? '') ?></textarea></div>
  <div class="form-group"><label>Best time</label><input name="best_time" class="form-control" value="<?= e($r['best_time'] ?? '') ?>"></div>
  <div class="form-group"><label>Climate</label><input name="climate" class="form-control" value="<?= e($r['climate'] ?? '') ?>"></div>
  <div class="form-group"><label>Duration label</label><input name="duration_label" class="form-control" value="<?= e($r['duration_label'] ?? '') ?>"></div>
  <div class="form-group"><label>Starting price (₹)</label><input name="starting_price" type="number" class="form-control" value="<?= (int)($r['starting_price'] ?? 0) ?>"></div>
  <div class="form-group"><label>Featured image URL</label><input name="featured_image" class="form-control" value="<?= e($r['featured_image'] ?? '') ?>"></div>
  <div class="form-group"><label>Upload image</label><input type="file" name="image" accept="image/*"></div>
  <div class="form-group"><label>Meta title</label><input name="meta_title" class="form-control" value="<?= e($r['meta_title'] ?? '') ?>"></div>
  <div class="form-group"><label>Meta description</label><textarea name="meta_description" class="form-control" rows="2"><?= e($r['meta_description'] ?? '') ?></textarea></div>
  <div class="form-group"><label>Sort order</label><input name="sort_order" type="number" class="form-control" value="<?= (int)($r['sort_order'] ?? 0) ?>"></div>
  <div class="form-group"><label><input type="checkbox" name="is_featured" value="1" <?= !empty($r['is_featured']) ? 'checked' : '' ?>> Featured on homepage</label></div>
  <div class="form-group"><label>Status</label><select name="status" class="form-control"><option value="active" <?= ($r['status'] ?? '') === 'active' ? 'selected' : '' ?>>Active</option><option value="inactive" <?= ($r['status'] ?? '') === 'inactive' ? 'selected' : '' ?>>Inactive</option><option value="draft" <?= ($r['status'] ?? '') === 'draft' ? 'selected' : '' ?>>Draft</option></select></div>
  <button type="submit" class="btn btn-primary">Save</button>
  <a href="<?= adminUrl('destinations/index.php') ?>" class="btn btn-outline">Cancel</a>
</form>
<?php require dirname(__DIR__) . '/includes/layout-end.php'; ?>
