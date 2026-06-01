<?php
require_once dirname(__DIR__) . '/includes/init.php';
require_once dirname(__DIR__) . '/includes/crud-helpers.php';
requireAdmin();
$id = (int)($_GET['id'] ?? 0);
if ($id) dbSoftDelete('packages', $id);
adminFlash('success', 'Package deleted.');
header('Location: ' . adminUrl('packages/index.php'));
exit;
