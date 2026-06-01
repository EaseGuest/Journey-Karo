<?php
require_once dirname(__DIR__) . '/includes/init.php';
require_once dirname(__DIR__) . '/includes/crud-helpers.php';
requireAdmin();
$pageTitle = 'Itineraries'; $activeNav = 'itineraries';
$pkgId = (int)($_GET['package_id'] ?? 0);
$packages = dbFetchAll("SELECT id, name FROM packages WHERE deleted_at IS NULL ORDER BY name");
$where = 'i.deleted_at IS NULL'; $params = [];
if ($pkgId) { $where .= ' AND i.package_id = ?'; $params[] = $pkgId; }
$rows = dbFetchAll("SELECT i.*, p.name AS package_name FROM itineraries i JOIN packages p ON i.package_id=p.id WHERE {$where} ORDER BY p.name, i.day_number", $params);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'save') {
    requireCsrfForm();
    $iid = (int)($_POST['id'] ?? 0);
    $pdata = [(int)$_POST['package_id'], (int)$_POST['day_number'], clean($_POST['title']??''), clean($_POST['description']??''), clean($_POST['activities']??''), clean($_POST['meals']??'')];
    if ($iid) {
        dbQuery("UPDATE itineraries SET package_id=?,day_number=?,title=?,description=?,activities=?,meals=?,updated_at=NOW() WHERE id=?", array_merge($pdata, [$iid]));
    } else {
        dbQuery("INSERT INTO itineraries (package_id,day_number,title,description,activities,meals) VALUES (?,?,?,?,?,?)", $pdata);
    }
    adminFlash('success', 'Itinerary saved.');
    header('Location: ' . adminUrl('itineraries/index.php?package_id=' . (int)$_POST['package_id'])); exit;
}

require dirname(__DIR__) . '/includes/layout-start.php'; adminShowFlash();
?>
<form method="GET" class="filters"><select name="package_id" class="form-control" style="max-width:280px" onchange="this.form.submit()">
<option value="">All packages</option>
<?php foreach ($packages as $p): ?><option value="<?= (int)$p['id'] ?>" <?= $pkgId===(int)$p['id']?'selected':'' ?>><?= e($p['name']) ?></option><?php endforeach; ?>
</select></form>

<div class="admin-card">
<h3>Add / Edit day</h3>
<form method="POST" style="margin-top:1rem">
<?= csrfField() ?><input type="hidden" name="action" value="save">
<input type="hidden" name="id" id="it-id" value="0">
<div class="form-group"><label>Package</label><select name="package_id" class="form-control" required>
<?php foreach ($packages as $p): ?><option value="<?= (int)$p['id'] ?>" <?= $pkgId===(int)$p['id']?'selected':'' ?>><?= e($p['name']) ?></option><?php endforeach; ?>
</select></div>
<div class="form-group"><label>Day #</label><input name="day_number" type="number" class="form-control" value="1" required></div>
<div class="form-group"><label>Title</label><input name="title" class="form-control" required></div>
<div class="form-group"><label>Description</label><textarea name="description" class="form-control" rows="3"></textarea></div>
<div class="form-group"><label>Activities</label><textarea name="activities" class="form-control" rows="2"></textarea></div>
<div class="form-group"><label>Meals</label><input name="meals" class="form-control"></div>
<button class="btn btn-primary">Save day</button>
</form>
</div>

<div class="admin-card" style="padding:0;overflow:hidden;margin-top:1rem">
<table class="admin-table"><thead><tr><th>Package</th><th>Day</th><th>Title</th><th></th></tr></thead><tbody>
<?php foreach ($rows as $r): ?>
<tr><td><?= e($r['package_name']) ?></td><td><?= (int)$r['day_number'] ?></td><td><?= e($r['title']) ?></td>
<td><a href="<?= adminUrl('itineraries/delete.php?id='.(int)$r['id'].'&package_id='.$pkgId) ?>" class="btn btn-danger btn-sm" onclick="return confirm('Delete?')">Delete</a></td></tr>
<?php endforeach; ?></tbody></table></div>
<?php require dirname(__DIR__) . '/includes/layout-end.php'; ?>
