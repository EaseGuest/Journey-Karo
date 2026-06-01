<?php
require_once dirname(__DIR__) . '/includes/init.php';
requireAdmin();
$id = (int)($_GET['id'] ?? 0);
$pkg = (int)($_GET['package_id'] ?? 0);
if ($id) dbSoftDelete('itineraries', $id);
header('Location: ' . adminUrl('itineraries/index.php?package_id=' . $pkg));
exit;
