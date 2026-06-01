<?php
require_once 'includes/config.php';
require_once 'includes/db.php';
require_once 'includes/functions.php';
require_once 'includes/content-helpers.php';

$page      = 'packages';
$pageTitle = 'Gujarat Tour Packages | Rann of Kutch, Gir Safari, Dwarka | Journey Karo';
$meta      = getSeoMeta($page);

$filterDest = clean($_GET['dest'] ?? '');
$sort       = clean($_GET['sort'] ?? 'popular');

$packages_db = tryDb(fn() => getPackages(0, false, 50), []);
if ($packages_db) {
    $packages = array_map(function ($p) {
        return [
            $p['slug'],
            $p['name'],
            ($p['destination_name'] ?? 'Gujarat') . ($p['destination_slug'] ? '' : ''),
            $p['destination_slug'] ?? 'all',
            (int)$p['days'],
            (int)$p['nights'],
            (int)$p['price'],
            (float)$p['rating'],
            (int)$p['review_count'],
            packageImage($p),
            contentList($p['inclusions'] ?? ''),
            $p['category'] ?? 'Tour',
            $p['short_description'] ?? '',
        ];
    }, $packages_db);
} else {
// Static fallback
$packages = [
  ['white-desert-safari',  'White Desert Safari',      'Rann of Kutch, Bhuj',   'bhuj',    4, 3, 12999, 4.9, 212, 'https://images.unsplash.com/photo-1590050752117-238cb0fb12b1?auto=format&fit=crop&w=800&q=80',   ['Tent Stay','Cultural Show','Camel Ride','Village Tour'],   'Popular',  'Suitable for families, couples & groups. White Rann full moon experience included.'],
  ['asiatic-lion-trail',   'Asiatic Lion Trail',        'Sasan Gir National Park','gir',     3, 2, 14500, 4.8, 187, 'https://images.unsplash.com/photo-1615959189197-48400be37e26?auto=format&fit=crop&w=800&q=80',  ['Jeep Safari','Wildlife Guide','Bird Watch','Lodge Stay'],   'Premium',  'Two Gir jungle safaris included. Asiatic lion sighting almost guaranteed.'],
  ['dwarka-devotion-tour', 'Dwarka Devotion Tour',      'Dwarka & Nageshwar',    'dwarka',  3, 2,  9499, 4.9, 256, 'https://images.unsplash.com/photo-1605649487212-47bdab064df7?auto=format&fit=crop&w=800&q=80',   ['Temple Darshan','Beyt Dwarka','Nageshwar','Coastal Drive'], 'Spiritual', 'Perfect for pilgrims. All temple darshan arrangements included.'],
  ['somnath-special',      'Somnath Special Package',   'Somnath & Veraval',     'somnath', 2, 1,  6999, 4.7, 143, 'https://images.unsplash.com/photo-1581799764979-dcf3fbc9f52e?auto=format&fit=crop&w=800&q=80',   ['Light & Sound Show','Ghat Puja','Temple Tour','Seafood'],   'Value',    'The fastest Jyotirlinga pilgrimage package. Light & Sound show every evening.'],
  ['diu-coastal-escape',   'Diu Coastal Escape',        'Diu Island Beaches',    'diu',     3, 2, 12499, 4.8, 178, 'https://images.unsplash.com/photo-1590523277543-a94d2e4eb00b?auto=format&fit=crop&w=800&q=80',   ['Nagoa Beach','Diu Fort','Water Sports','Seafood Dinner'],   'Popular',  'Ideal beach holiday. Portuguese heritage, pristine beaches, and zero alcohol tax.'],
  ['statue-of-unity',      'Statue of Unity Tour',      'Kevadia Colony',        'sou',     2, 1, 10999, 4.6, 198, 'https://images.unsplash.com/photo-1627856013091-fed6e4e30025?auto=format&fit=crop&w=800&q=80',   ['Observation Deck','Valley of Flowers','Laser Show','Dam'],  'Iconic',   'Full access to the world\'s tallest statue with observation deck views.'],
  ['culture-of-kutch',     'Culture of Kutch',          'Bhujodi & Hodka Village','bhuj',   4, 3,  8750, 4.7, 134, 'https://images.unsplash.com/photo-1582213782179-e0d53f98f2ca?auto=format&fit=crop&w=800&q=80',   ['Artisan Workshops','Ajrakh Prints','Rogan Art','Bhunga Hut'],'Cultural', 'Deep-dive into UNESCO-heritage Kutch arts, crafts, and village life.'],
  ['gujarat-grand-circuit','Gujarat Grand Circuit',     'Bhuj → Dwarka → Gir → Diu','all', 9, 8, 34999, 5.0, 98,  'https://images.unsplash.com/photo-1469854523086-cc02fe5d8800?auto=format&fit=crop&w=800&q=80',   ['Rann Safari','Lion Safari','Pilgrimage','Beach Holiday'],   'Best Value','The ultimate Gujarat experience — cover all major destinations in one epic journey.'],
];
}

if ($filterDest) {
    $packages = array_filter($packages, fn($p) => $p[3] === $filterDest || $p[3] === 'all');
}

include 'includes/header.php';
?>

<section class="page-hero">
  <div class="container">
    <div class="section-badge">📦 Tour Packages</div>
    <h1>Gujarat Tour Packages</h1>
    <p>Hand-crafted itineraries by local experts. All-inclusive prices. Guaranteed departures.</p>
  </div>
</section>

<!-- Filters & Sort -->
<section style="background:white;padding-block:1.5rem;border-bottom:1px solid var(--color-border);position:sticky;top:72px;z-index:99">
  <div class="container">
    <div style="display:flex;flex-wrap:wrap;gap:0.75rem;align-items:center;justify-content:space-between">
      <div style="display:flex;gap:0.5rem;flex-wrap:wrap">
        <a href="packages.php"              class="btn btn-sm <?= !$filterDest ? 'btn-primary':'btn-outline' ?>">All</a>
        <a href="?dest=bhuj"    class="btn btn-sm <?= $filterDest==='bhuj'    ? 'btn-primary':'btn-outline' ?>">🏜️ Bhuj</a>
        <a href="?dest=dwarka"  class="btn btn-sm <?= $filterDest==='dwarka'  ? 'btn-primary':'btn-outline' ?>">🛕 Dwarka</a>
        <a href="?dest=gir"     class="btn btn-sm <?= $filterDest==='gir'     ? 'btn-primary':'btn-outline' ?>">🦁 Gir</a>
        <a href="?dest=diu"     class="btn btn-sm <?= $filterDest==='diu'     ? 'btn-primary':'btn-outline' ?>">🏖️ Diu</a>
        <a href="?dest=somnath" class="btn btn-sm <?= $filterDest==='somnath' ? 'btn-primary':'btn-outline' ?>">🕌 Somnath</a>
        <a href="?dest=sou"     class="btn btn-sm <?= $filterDest==='sou'     ? 'btn-primary':'btn-outline' ?>">🗽 SoU</a>
      </div>
      <div style="font-size:0.85rem;color:var(--color-text-muted);font-weight:600">
        <?= count($packages) ?> package(s) found
      </div>
    </div>
  </div>
</section>

<!-- Packages Grid -->
<section class="section-padding" style="background:var(--color-bg)">
  <div class="container">
    <div class="packages-grid">
      <?php foreach (array_values($packages) as $i => [$slug,$title,$location,$dest,$days,$nights,$price,$rating,$reviews,$img,$inclusions,$badge,$shortDesc]): ?>
      <article class="package-card" data-reveal data-delay="<?= ($i % 3) * 100 ?>">
        <div class="package-card-img">
          <img src="<?= $img ?>" alt="<?= e($title) ?> — Journey Karo Gujarat" loading="lazy">
          <span class="package-badge"><?= e($badge) ?></span>
          <button class="package-wishlist" onclick="toggleWishlist(this,'<?= $slug ?>')" aria-label="Add to wishlist">♡</button>
        </div>
        <div class="package-card-body">
          <div class="package-rating">
            <span class="stars">★★★★★</span>
            <span style="color:var(--color-text-muted);font-weight:400"><?= $rating ?> (<?= $reviews ?> reviews)</span>
          </div>
          <h3 class="package-card-title"><?= e($title) ?></h3>
          <p class="package-card-location">📍 <?= e($location) ?></p>
          <p style="font-size:0.825rem;color:var(--color-text-muted);margin-bottom:1rem;line-height:1.6"><?= e($shortDesc) ?></p>
          <div class="package-features">
            <span class="package-feature">📅 <?= $days ?>D/<?= $nights ?>N</span>
            <?php foreach (array_slice($inclusions,0,2) as $inc): ?>
            <span class="package-feature">✓ <?= e($inc) ?></span>
            <?php endforeach ?>
            <?php if (count($inclusions) > 2): ?>
            <span class="package-feature">+<?= count($inclusions)-2 ?> more</span>
            <?php endif ?>
          </div>
          <div class="package-card-footer">
            <div class="package-price">
              <span class="package-price-value"><?= formatPrice($price) ?></span>
              <span class="package-price-label">per person (all inclusive)</span>
            </div>
            <a href="package-detail.php?slug=<?= e($slug) ?>" class="btn btn-outline btn-sm">Details</a>
            <button onclick="inquirePackage('<?= e($title) ?>',<?= $price ?>)" class="btn btn-gold btn-sm">Book Now</button>
          </div>
        </div>
      </article>
      <?php endforeach ?>
    </div>
  </div>
</section>

<!-- Why Book With Us -->
<section style="background:white;padding-block:4rem">
  <div class="container">
    <div class="section-header" data-reveal>
      <div class="section-badge">✅ Why Book With Us</div>
      <h2>The Journey Karo Advantage</h2>
    </div>
    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:1.5rem;text-align:center">
      <?php foreach ([
        ['🔒','100% Secure Booking','PDO + SSL encrypted. UPI, NEFT, or Cash accepted.'],
        ['💰','Best Price Guarantee','We match any lower price you find. No questions asked.'],
        ['🚗','Free Airport Pickup','Complimentary pickup for packages above ₹10,000.'],
        ['📞','24/7 Travel Support','Our team is available around the clock on WhatsApp.'],
        ['🔄','Flexible Cancellation','Cancel up to 7 days before for a full refund.'],
        ['🏆','Local Expert Guides','Certified guides who know every corner of Gujarat.'],
      ] as [$icon,$title,$desc]): ?>
      <div class="info-card" data-reveal>
        <div style="font-size:2rem;margin-bottom:0.75rem"><?= $icon ?></div>
        <h4 style="font-size:0.95rem;font-weight:800;color:var(--color-primary);margin-bottom:0.5rem"><?= $title ?></h4>
        <p style="font-size:0.8rem;line-height:1.6"><?= $desc ?></p>
      </div>
      <?php endforeach ?>
    </div>
  </div>
</section>

<section class="cta-strip">
  <div class="container">
    <h2>Need a Custom Package?</h2>
    <p>Tell us your dates, budget, and group size — we'll build the perfect Gujarat itinerary.</p>
    <div class="cta-strip-buttons">
      <a href="custom-tour-planner.php"   class="btn btn-primary btn-lg">Plan Custom Trip ✈️</a>
      <a href="https://wa.me/<?= APP_WHATSAPP ?>?text=Hello!%20I%20need%20a%20custom%20Gujarat%20package."
         class="btn btn-whatsapp btn-lg" target="_blank">💬 WhatsApp Us</a>
    </div>
  </div>
</section>

<script>
function toggleWishlist(btn, slug) {
  btn.classList.toggle('active');
  btn.textContent = btn.classList.contains('active') ? '♥' : '♡';
  btn.style.color = btn.classList.contains('active') ? '#e11d48' : '';
}
</script>

<?php include 'includes/footer.php'; ?>
