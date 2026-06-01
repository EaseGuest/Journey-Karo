<?php
/** @var string $pageTitle */
/** @var string $activeNav */
if (!isset($pageTitle)) $pageTitle = 'Admin';
if (!isset($activeNav)) $activeNav = '';
$nav = [
    'dashboard' => ['Dashboard', 'dashboard.php', '📊'],
    'leads' => ['Leads', 'leads/index.php', '📥'],
    'destinations' => ['Destinations', 'destinations/index.php', '🗺️'],
    'packages' => ['Packages', 'packages/index.php', '📦'],
    'itineraries' => ['Itineraries', 'itineraries/index.php', '📅'],
    'blogs' => ['Blogs', 'blogs/index.php', '📝'],
    'reviews' => ['Reviews', 'reviews/index.php', '⭐'],
    'gallery' => ['Gallery', 'gallery/index.php', '🖼️'],
    'seo' => ['SEO', 'seo/index.php', '🔍'],
    'settings' => ['Settings', 'settings/index.php', '⚙️'],
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= e($pageTitle) ?> | Journey Karo Admin</title>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="<?= adminAsset('admin.css') ?>">
</head>
<body class="admin-body">
<div class="admin-wrap">
  <aside class="admin-sidebar">
    <div class="admin-brand">✈️ Journey Karo<span>Admin Panel</span></div>
    <nav class="admin-nav">
      <?php foreach ($nav as $key => [$label, $href, $icon]): ?>
      <a href="<?= adminUrl($href) ?>" class="<?= $activeNav === $key ? 'active' : '' ?>"><?= $icon ?> <?= e($label) ?></a>
      <?php endforeach; ?>
      <a href="<?= APP_URL ?>" target="_blank">🌐 View Website</a>
      <a href="<?= adminUrl('logout.php') ?>">🚪 Logout</a>
    </nav>
  </aside>
  <div class="admin-main">
    <header class="admin-topbar">
      <h1 style="margin:0;font-size:1.15rem;font-weight:800"><?= e($pageTitle) ?></h1>
      <div style="font-size:0.8rem;color:var(--adm-muted)">👤 <?= e($_SESSION['admin_name'] ?? 'Admin') ?></div>
    </header>
    <div class="admin-content">
