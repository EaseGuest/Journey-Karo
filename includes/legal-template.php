<?php
/**
 * Legal page wrapper — set $legalTitle and $legalContent before include
 */
if (!isset($legalTitle, $legalContent)) return;
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/database.php';
require_once __DIR__ . '/functions.php';

$page = $legalSlug ?? makeSlug($legalTitle);
$meta = getSeoMeta($page);
$pageTitle = $meta['meta_title'] ?? ($legalTitle . ' | Journey Karo');
include __DIR__ . '/header.php';
?>
<section class="page-hero"><div class="container"><h1><?= e($legalTitle) ?></h1><p>Journey Karo — Bhuj, Gujarat</p></div></section>
<section class="section-padding"><div class="container legal-content" style="max-width:800px;line-height:1.8"><?= $legalContent ?></div></section>
<?php include __DIR__ . '/footer.php'; ?>
