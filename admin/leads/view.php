<?php
require_once dirname(__DIR__) . '/includes/init.php';
require_once dirname(__DIR__) . '/includes/crud-helpers.php';
requireAdmin();

$id = (int)($_GET['id'] ?? 0);
$lead = dbFetch("SELECT * FROM inquiries WHERE id = ? AND deleted_at IS NULL", [$id]);
if (!$lead) {
    adminFlash('error', 'Lead not found.');
    header('Location: ' . adminUrl('leads/index.php'));
    exit;
}

$pageTitle = 'Lead #' . $id;
$activeNav = 'leads';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    requireCsrfForm();
    $action = $_POST['action'] ?? '';

    if ($action === 'update_status') {
        $newStatus = clean($_POST['status'] ?? '');
        if (in_array($newStatus, ['new','contacted','quotation_sent','confirmed','lost'], true)) {
            dbQuery("UPDATE inquiries SET status = ?, updated_at = NOW() WHERE id = ?", [$newStatus, $id]);
            adminFlash('success', 'Status updated.');
        }
    } elseif ($action === 'save_notes') {
        dbQuery("UPDATE inquiries SET admin_notes = ?, updated_at = NOW() WHERE id = ?", [clean($_POST['admin_notes'] ?? ''), $id]);
        adminFlash('success', 'Notes saved.');
    } elseif ($action === 'delete') {
        dbSoftDelete('inquiries', $id);
        adminFlash('success', 'Lead deleted.');
        header('Location: ' . adminUrl('leads/index.php'));
        exit;
    }

    header('Location: ' . adminUrl('leads/view.php?id=' . $id));
    exit;
}

$lead = dbFetch("SELECT * FROM inquiries WHERE id = ?", [$id]);

require dirname(__DIR__) . '/includes/layout-start.php';
adminShowFlash();
?>

<div style="display:grid;grid-template-columns:1fr 320px;gap:1.25rem">
  <div class="admin-card">
    <h2 style="margin:0 0 1rem"><?= e($lead['name']) ?></h2>
    <table class="admin-table">
      <tr><th>Email</th><td><a href="mailto:<?= e($lead['email']) ?>"><?= e($lead['email']) ?></a></td></tr>
      <tr><th>Phone</th><td><a href="tel:+91<?= e(preg_replace('/\D/', '', $lead['phone'])) ?>"><?= e($lead['phone']) ?></a></td></tr>
      <tr><th>Destination</th><td><?= e($lead['destination'] ?: '—') ?></td></tr>
      <tr><th>Package</th><td><?= e($lead['package_name'] ?: '—') ?></td></tr>
      <tr><th>Travel date</th><td><?= $lead['travel_date'] ? e(formatDate($lead['travel_date'])) : 'Flexible' ?></td></tr>
      <tr><th>Guests</th><td><?= (int)$lead['num_guests'] ?></td></tr>
      <tr><th>Budget</th><td><?= e($lead['budget'] ?: '—') ?></td></tr>
      <tr><th>Source</th><td><?= e($lead['source']) ?></td></tr>
      <tr><th>IP</th><td><?= e($lead['ip_address'] ?: '—') ?></td></tr>
      <tr><th>Received</th><td><?= e(formatDate($lead['created_at'], 'j M Y, h:i A')) ?></td></tr>
    </table>
    <?php if ($lead['message']): ?>
    <h3 style="margin:1.5rem 0 0.5rem">Message</h3>
    <p style="background:#f8fafc;padding:1rem;border-radius:8px;line-height:1.6"><?= nl2br(e($lead['message'])) ?></p>
    <?php endif; ?>
    <a href="https://wa.me/<?= APP_WHATSAPP ?>?text=<?= rawurlencode("Hi {$lead['name']}, regarding your inquiry #" . $id) ?>" target="_blank" class="btn btn-primary" style="margin-top:1rem">💬 WhatsApp</a>
  </div>

  <div>
    <div class="admin-card">
      <h3 style="margin:0 0 1rem">Update status</h3>
      <form method="POST">
        <?= csrfField() ?>
        <input type="hidden" name="action" value="update_status">
        <select name="status" class="form-control" style="margin-bottom:0.75rem">
          <?php foreach (['new','contacted','quotation_sent','confirmed','lost'] as $s): ?>
          <option value="<?= $s ?>" <?= $lead['status'] === $s ? 'selected' : '' ?>><?= e(ucwords(str_replace('_', ' ', $s))) ?></option>
          <?php endforeach; ?>
        </select>
        <button type="submit" class="btn btn-primary" style="width:100%">Save status</button>
      </form>
    </div>

    <div class="admin-card">
      <h3 style="margin:0 0 1rem">Admin notes</h3>
      <form method="POST">
        <?= csrfField() ?>
        <input type="hidden" name="action" value="save_notes">
        <textarea name="admin_notes" class="form-control" rows="4"><?= e($lead['admin_notes'] ?? '') ?></textarea>
        <button type="submit" class="btn btn-outline" style="margin-top:0.75rem;width:100%">Save notes</button>
      </form>
    </div>

    <form method="POST" onsubmit="return confirm('Delete this lead?');">
      <?= csrfField() ?>
      <input type="hidden" name="action" value="delete">
      <button type="submit" class="btn btn-danger" style="width:100%">Delete lead</button>
    </form>

    <a href="<?= adminUrl('leads/index.php') ?>" class="btn btn-outline" style="margin-top:1rem;width:100%;justify-content:center">← Back to leads</a>
  </div>
</div>

<?php require dirname(__DIR__) . '/includes/layout-end.php'; ?>
