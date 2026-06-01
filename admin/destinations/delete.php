<?php
require_once dirname(__DIR__) . '/includes/init.php';
require_once dirname(__DIR__) . '/includes/crud-helpers.php';
requireAdmin();
$id = (int)($_GET['id'] ?? 0);
if ($id) dbSoftDelete('destinations', $id);
adminFlash('success', 'Destination deleted.');
header('Location: ' . adminUrl('destinations/index.php'));
exit;
