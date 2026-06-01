<?php
require_once 'includes/config.php';
require_once 'includes/database.php';
require_once 'includes/functions.php';
require_once 'includes/content-helpers.php';

$slug = clean($_GET['slug'] ?? '');
$dest = $slug ? tryDb(fn() => getDestinationBySlug($slug), null) : null;

if (!$dest) {
    http_response_code(404);
    header('Location: destinations.php');
    exit;
}

$page = 'destinations';
$pageTitle = $dest['meta_title'] ?: ($dest['name'] . ' Tour | Journey Karo');
$meta = [
    'meta_title' => $pageTitle,
    'meta_description' => $dest['meta_description'] ?? truncate($dest['short_description'] ?? '', 160),
    'meta_keywords' => DEFAULT_META_KEYWORDS,
    'og_image' => destinationImage($dest),
];
$highlights = contentList($dest['highlights'] ?? '');
$attractions = contentList($dest['attractions'] ?? '');
$packages = tryDb(fn() => getPackages((int)$dest['id'], false, 12), []);

include 'includes/header.php';
?>

<section class="page-hero" style="background:linear-gradient(135deg,var(--color-primary),var(--color-primary-light));color:#fff">
  <div class="container">
    <div class="section-badge"><?= e($dest['icon'] ?? '📍') ?> Destination</div>
    <h1><?= e($dest['name']) ?></h1>
    <p><?= e($dest['short_description'] ?? '') ?></p>
  </div>
</section>

<section class="section-padding">
  <div class="container" style="display:grid;grid-template-columns:1fr;gap:2rem;max-width:1100px;margin:auto">
    <?php if ($img = destinationImage($dest)): ?>
    <img src="<?= e($img) ?>" alt="<?= e($dest['name']) ?>" style="width:100%;border-radius:var(--radius-xl);max-height:420px;object-fit:cover" loading="lazy">
    <?php endif; ?>
    <div><?= nl2br(e($dest['description'] ?? '')) ?></div>
    <?php if ($highlights): ?>
    <div><h3>Highlights</h3><ul><?php foreach ($highlights as $h): ?><li><?= e($h) ?></li><?php endforeach; ?></ul></div>
    <?php endif; ?>
    <?php if ($attractions): ?>
    <div><h3>Attractions</h3><ul><?php foreach ($attractions as $a): ?><li><?= e($a) ?></li><?php endforeach; ?></ul></div>
    <?php endif; ?>
    <p><strong>Best time:</strong> <?= e($dest['best_time'] ?? '—') ?> · <strong>Duration:</strong> <?= e($dest['duration_label'] ?? '—') ?> · <strong>From:</strong> <?= formatPrice((int)($dest['starting_price'] ?? 0)) ?></p>
  </div>
</section>

<?php if ($packages): ?>
<section class="section-padding" style="background:var(--color-bg)">
  <div class="container">
    <h2>Packages for <?= e($dest['name']) ?></h2>
    <div class="packages-grid" style="margin-top:1.5rem">
      <?php foreach ($packages as $p): ?>
      <article class="package-card">
        <div class="package-card-body">
          <h3><a href="package-detail.php?slug=<?= e($p['slug']) ?>"><?= e($p['name']) ?></a></h3>
          <span class="package-price"><?= formatPrice((int)$p['price']) ?></span>
        </div>
      </article>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<?php endif; ?>

<section class="section-padding">
  <div class="container" style="max-width:560px">
    <?php require_once 'includes/csrf.php'; $inquiryDestination = $dest['name']; $inquirySource = 'destination_detail'; include 'includes/partials/inquiry-form.php'; ?>
  </div>
</section>

<?php include 'includes/footer.php'; ?>
