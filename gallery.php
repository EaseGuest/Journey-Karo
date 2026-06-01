<?php
require_once 'includes/config.php';
require_once 'includes/db.php';
require_once 'includes/functions.php';
require_once 'includes/content-helpers.php';

$page      = 'gallery';
$pageTitle = 'Photo Gallery | Gujarat Travel Photos | Journey Karo';
$meta      = getSeoMeta($page);
$category  = clean($_GET['cat'] ?? '');

$categories = ['all'=>'All Photos','rann'=>'Rann of Kutch','wildlife'=>'Wildlife','temples'=>'Temples & Pilgrimage','beaches'=>'Beaches','culture'=>'Culture & Art','food'=>'Food'];

$gallery_db = tryDb(fn() => getGallery($category && $category !== 'all' ? $category : '', 50), []);
if ($gallery_db) {
    $gallery = array_map(fn($g) => [$g['image_path'], $g['title'], $g['category']], $gallery_db);
} else {
// Static gallery fallback
$gallery = [
  ['https://images.unsplash.com/photo-1590050752117-238cb0fb12b1?auto=format&fit=crop&w=800&q=80','White Rann of Kutch at Sunset','rann'],
  ['https://images.unsplash.com/photo-1507525428034-b723cf961d3e?auto=format&fit=crop&w=800&q=80','Rann Utsav Tent City','rann'],
  ['https://images.unsplash.com/photo-1615959189197-48400be37e26?auto=format&fit=crop&w=800&q=80','Asiatic Lion in Gir National Park','wildlife'],
  ['https://images.unsplash.com/photo-1575550959106-5a7defe28b56?auto=format&fit=crop&w=800&q=80','Deer at Gir Sanctuary','wildlife'],
  ['https://images.unsplash.com/photo-1605649487212-47bdab064df7?auto=format&fit=crop&w=800&q=80','Dwarkadhish Temple Gujarat','temples'],
  ['https://images.unsplash.com/photo-1581799764979-dcf3fbc9f52e?auto=format&fit=crop&w=800&q=80','Somnath Temple at Dawn','temples'],
  ['https://images.unsplash.com/photo-1590523277543-a94d2e4eb00b?auto=format&fit=crop&w=800&q=80','Nagoa Beach Diu','beaches'],
  ['https://images.unsplash.com/photo-1505228395891-9a51e7e86bf6?auto=format&fit=crop&w=800&q=80','Diu Island Sunset','beaches'],
  ['https://images.unsplash.com/photo-1582213782179-e0d53f98f2ca?auto=format&fit=crop&w=800&q=80','Kutchi Embroidery Artisan','culture'],
  ['https://images.unsplash.com/photo-1567016432779-094069958ea5?auto=format&fit=crop&w=800&q=80','Traditional Kutch Bhunga Hut','culture'],
  ['https://images.unsplash.com/photo-1627856013091-fed6e4e30025?auto=format&fit=crop&w=800&q=80','Statue of Unity, Kevadia','temples'],
  ['https://images.unsplash.com/photo-1469854523086-cc02fe5d8800?auto=format&fit=crop&w=800&q=80','Camel ride at White Rann','rann'],
  ['https://images.unsplash.com/photo-1567364729-bb0eb28a2918?auto=format&fit=crop&w=800&q=80','Gujarat Thali — Traditional Food','food'],
  ['https://images.unsplash.com/photo-1555939594-58d7cb561ad1?auto=format&fit=crop&w=800&q=80','Street Food in Bhuj','food'],
  ['https://images.unsplash.com/photo-1509909756405-be0199881695?auto=format&fit=crop&w=800&q=80','Garba Dance Festival','culture'],
  ['https://images.unsplash.com/photo-1537640538966-79f369143f8f?auto=format&fit=crop&w=800&q=80','Flamingos at Little Rann','wildlife'],
];
}

if ($category && $category !== 'all' && !$gallery_db) {
    $gallery = array_filter($gallery, fn($g) => $g[2] === $category);
}

include 'includes/header.php';
?>

<section class="page-hero">
  <div class="container">
    <div class="section-badge">🖼️ Photo Gallery</div>
    <h1>Moments from Gujarat</h1>
    <p>A visual journey through the landscapes, culture, and wildlife of Beautiful Gujarat — captured by our travelers.</p>
  </div>
</section>

<!-- Category Filter -->
<section style="background:white;padding-block:1.5rem;border-bottom:1px solid var(--color-border);position:sticky;top:72px;z-index:99">
  <div class="container">
    <div style="display:flex;gap:0.5rem;flex-wrap:wrap">
      <?php foreach ($categories as $cat => $label): ?>
      <a href="gallery.php<?= $cat === 'all' ? '' : '?cat='.$cat ?>"
         class="btn btn-sm <?= ($category === $cat || (!$category && $cat === 'all')) ? 'btn-primary' : 'btn-outline' ?>">
        <?= $label ?>
      </a>
      <?php endforeach ?>
    </div>
  </div>
</section>

<!-- Gallery Masonry -->
<section class="section-padding" style="background:var(--color-bg)">
  <div class="container">
    <div style="display:grid;grid-template-columns:repeat(2,1fr);gap:0.75rem" id="gallery-grid">
      <?php foreach (array_values($gallery) as $i => [$img, $alt, $cat]): ?>
      <div class="gallery-item" data-reveal data-delay="<?= ($i % 4) * 80 ?>"
           style="<?= $i === 0 ? 'grid-column:span 2;height:400px;' : 'height:250px;' ?>border-radius:var(--radius-lg)">
        <img src="<?= $img ?>" alt="<?= e($alt) ?> — Journey Karo Gujarat" loading="lazy">
        <div class="gallery-item-overlay">
          <div style="text-align:center;padding:1rem">
            <div style="font-size:1.5rem;margin-bottom:0.5rem">🔍</div>
            <div style="font-size:0.875rem;font-weight:600"><?= e($alt) ?></div>
          </div>
        </div>
      </div>
      <?php endforeach ?>
    </div>

    <?php if (empty($gallery)): ?>
    <div style="text-align:center;padding:4rem;color:var(--color-text-muted)">
      <div style="font-size:3rem;margin-bottom:1rem">🖼️</div>
      <h3>No photos in this category yet.</h3>
      <p>Check back soon — we're adding more beautiful Gujarat photos!</p>
    </div>
    <?php endif ?>
  </div>
</section>

<!-- Submit Your Photos -->
<section style="background:white;padding-block:4rem">
  <div class="container">
    <div style="max-width:600px;margin:auto;text-align:center">
      <div class="section-badge">📸 Share Your Memories</div>
      <h2 style="margin-bottom:1rem">Traveled with Journey Karo?</h2>
      <p style="margin-bottom:2rem">Share your best Gujarat photos with us! We'd love to feature your memories on our gallery and social media.</p>
      <a href="https://wa.me/<?= APP_WHATSAPP ?>?text=Hello%20Journey%20Karo!%20I%20want%20to%20share%20my%20Gujarat%20travel%20photos%20with%20you."
         class="btn btn-whatsapp btn-lg" target="_blank">💬 Share Photos on WhatsApp</a>
    </div>
  </div>
</section>

<style>
@media(min-width:768px){
  #gallery-grid { grid-template-columns: repeat(4,1fr) !important; }
  #gallery-grid > :first-child { grid-column: span 2; grid-row: span 2; height:100% !important; min-height:450px; }
  #gallery-grid > :nth-child(5) { grid-column: span 2; }
  #gallery-grid > :not(:first-child) { height: 220px !important; }
}
</style>

<?php include 'includes/footer.php'; ?>
