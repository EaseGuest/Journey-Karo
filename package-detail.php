<?php
require_once 'includes/config.php';
require_once 'includes/database.php';
require_once 'includes/functions.php';
require_once 'includes/content-helpers.php';
require_once 'includes/csrf.php';

$slug = clean($_GET['slug'] ?? '');
$pkg = $slug ? tryDb(fn() => getPackageBySlug($slug), null) : null;

if (!$pkg) {
    http_response_code(404);
    header('Location: packages.php');
    exit;
}

$pageTitle = $pkg['meta_title'] ?: ($pkg['name'] . ' | Journey Karo');
$meta = [
    'meta_title' => $pageTitle,
    'meta_description' => $pkg['meta_description'] ?? truncate($pkg['short_description'] ?? '', 160),
    'og_image' => packageImage($pkg),
];
$inclusions = contentList($pkg['inclusions'] ?? '');
$exclusions = contentList($pkg['exclusions'] ?? '');
$itinerary = tryDb(fn() => getItineraries((int)$pkg['id']), []);

include 'includes/header.php';
?>

<section class="page-hero">
  <div class="container">
    <div class="section-badge">📦 <?= e($pkg['category'] ?? 'Tour Package') ?></div>
    <h1><?= e($pkg['name']) ?></h1>
    <p><?= e($pkg['destination_name'] ?? 'Gujarat') ?> · <?= (int)$pkg['days'] ?> Days / <?= (int)$pkg['nights'] ?> Nights</p>
  </div>
</section>

<section class="section-padding">
  <div class="container" style="max-width:900px">
    <?php if ($img = packageImage($pkg)): ?>
    <img src="<?= e($img) ?>" alt="<?= e($pkg['name']) ?>" style="width:100%;border-radius:var(--radius-xl);margin-bottom:2rem" loading="lazy">
    <?php endif; ?>
    <p style="font-size:1.5rem;font-weight:800;color:var(--color-primary)"><?= formatPrice((int)$pkg['price']) ?> <span style="font-size:0.9rem;font-weight:600;color:var(--color-text-muted)">per person</span></p>
    <p><?= nl2br(e($pkg['description'] ?? $pkg['short_description'] ?? '')) ?></p>
    <?php if ($inclusions): ?><h3>Inclusions</h3><ul><?php foreach ($inclusions as $i): ?><li><?= e($i) ?></li><?php endforeach; ?></ul><?php endif; ?>
    <?php if ($exclusions): ?><h3>Exclusions</h3><ul><?php foreach ($exclusions as $x): ?><li><?= e($x) ?></li><?php endforeach; ?></ul><?php endif; ?>

    <?php if ($itinerary): ?>
    <h3 style="margin-top:2rem">Day-wise Itinerary</h3>
    <?php foreach ($itinerary as $day): ?>
    <div class="faq-item" style="margin-bottom:1rem">
      <strong>Day <?= (int)$day['day_number'] ?>: <?= e($day['title']) ?></strong>
      <p style="margin:0.5rem 0 0;color:var(--color-text-muted)"><?= nl2br(e($day['description'] ?? '')) ?></p>
    </div>
    <?php endforeach; ?>
    <?php endif; ?>
  </div>
</section>

<section class="section-padding" style="background:var(--color-bg)">
  <div class="container" style="max-width:560px">
    <?php $inquirySource = 'package_detail'; $inquiryDestination = $pkg['destination_name'] ?? ''; $inquiryPackage = $pkg['name']; include 'includes/partials/inquiry-form.php'; ?>
  </div>
</section>

<?php include 'includes/footer.php'; ?>
