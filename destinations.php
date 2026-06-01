<?php
require_once 'includes/config.php';
require_once 'includes/db.php';
require_once 'includes/functions.php';
require_once 'includes/content-helpers.php';

$page        = 'destinations';
$filterPlace = clean($_GET['place'] ?? '');
$pageTitle   = 'Gujarat Destinations | Bhuj, Dwarka, Gir, Diu | Journey Karo';
$meta        = getSeoMeta($page);

$destinations_db = tryDb(fn() => getDestinations(false, 20), []);

// Static destination data (used as fallback / supplemental)
$allDestinations = [
  ['bhuj',    '🏜️','Rann of Kutch (Bhuj)',  'Experience the magical white salt desert, traditional handicrafts, and rich Kutchi culture. Stay in luxury tent camps under the stars.',
   '3N/4D','All Year','Mild','https://images.unsplash.com/photo-1590050752117-238cb0fb12b1?auto=format&fit=crop&w=800&q=80',
   ['Tent Safari','Craft Villages','White Rann Moonlight','Kalo Dungar'],'₹12,999'],
  ['dwarka',  '🛕','Dwarka',                 'One of the 4 sacred Dhams of India. Visit the ancient Dwarkadhish temple, Beyt Dwarka island, and the stunning coastal beauty.',
   '2N/3D','Oct–Mar','Warm','https://images.unsplash.com/photo-1605649487212-47bdab064df7?auto=format&fit=crop&w=800&q=80',
   ['Dwarkadhish Temple','Nageshwar Jyotirlinga','Beyt Dwarka','Rukmini Devi Temple'],'₹9,499'],
  ['somnath', '🕌','Somnath',                'The first of the 12 Jyotirlingas. A pilgrimage of immense historical significance, set against the beautiful Arabian Sea coast.',
   '1N/2D','All Year','Warm','https://images.unsplash.com/photo-1581799764979-dcf3fbc9f52e?auto=format&fit=crop&w=800&q=80',
   ['Somnath Temple','Light & Sound Show','Triveni Ghat','Bhalka Teerth'],'₹6,999'],
  ['gir',     '🦁','Gir National Park',      'Home to the last surviving Asiatic lions. An unparalleled wildlife experience in one of India\'s premier national parks.',
   '2N/3D','Nov–Jun','Warm','https://images.unsplash.com/photo-1615959189197-48400be37e26?auto=format&fit=crop&w=800&q=80',
   ['Asiatic Lion Safari','Bird Watching','Sinh Sadan Forest Lodge','Crocodile Breeding Center'],'₹14,500'],
  ['diu',     '🏖️','Diu',                    'A serene island of Portuguese forts, palm-fringed beaches, and vibrant seafood culture. A perfect beach getaway near Ahmedabad.',
   '2N/3D','Oct–Mar','Warm','https://images.unsplash.com/photo-1590523277543-a94d2e4eb00b?auto=format&fit=crop&w=800&q=80',
   ['Nagoa Beach','Diu Fort','St. Paul\'s Church','Sea Shell Museum'],'₹12,499'],
  ['sou',     '🗽','Statue of Unity',         'The world\'s tallest statue at 182 metres. Experience the Valley of Flowers, the Sardar Sarovar Dam, and an iconic laser show.',
   '1N/2D','All Year','Warm','https://images.unsplash.com/photo-1627856013091-fed6e4e30025?auto=format&fit=crop&w=800&q=80',
   ['Statue of Unity','Observation Deck','Valley of Flowers','Jungle Safari'],'₹10,999'],
  ['kutch',   '🎨','White Rann of Kutch',     'The vast white salt marshland of Kutch becomes a surreal dreamscape under the full moon. Visit during the Rann Utsav festival.',
   '3N/4D','Nov–Feb','Cool','https://images.unsplash.com/photo-1507525428034-b723cf961d3e?auto=format&fit=crop&w=800&q=80',
   ['Rann Utsav Festival','Full Moon Night','Handicraft Market','Flamingo Spotting'],'₹15,999'],
];

if ($destinations_db) {
    $filtered = [];
    foreach ($destinations_db as $d) {
        $filtered[] = [
            $d['slug'],
            $d['icon'] ?? '📍',
            $d['name'],
            $d['short_description'] ?? $d['description'] ?? '',
            $d['duration_label'] ?? '',
            $d['best_time'] ?? '',
            $d['climate'] ?? '',
            destinationImage($d),
            contentList($d['highlights'] ?? ''),
            formatPrice((int)($d['starting_price'] ?? 0)),
        ];
    }
    if ($filterPlace) {
        $filtered = array_values(array_filter($filtered, fn($d) => $d[0] === $filterPlace));
    }
} else {
    $filtered = $filterPlace
      ? array_filter($allDestinations, fn($d) => $d[0] === $filterPlace)
      : $allDestinations;
}

include 'includes/header.php';
?>

<section class="page-hero">
  <div class="container">
    <div class="section-badge">🗺️ Explore Gujarat</div>
    <h1>Discover Beautiful Gujarat</h1>
    <p>7 extraordinary destinations. Thousands of unforgettable memories. All expertly guided by our local team.</p>
  </div>
</section>

<!-- Filter Tabs -->
<section style="background:white;padding-block:2rem;border-bottom:1px solid var(--color-border);position:sticky;top:72px;z-index:99">
  <div class="container">
    <div style="display:flex;gap:0.75rem;flex-wrap:wrap;align-items:center">
      <span style="font-size:0.8rem;font-weight:700;color:var(--color-text-muted);text-transform:uppercase;letter-spacing:0.06em;margin-right:0.5rem">Filter:</span>
      <a href="destinations.php"              class="btn btn-sm <?= !$filterPlace ? 'btn-primary' : 'btn-outline' ?>">All Destinations</a>
      <a href="?place=bhuj"    class="btn btn-sm <?= $filterPlace==='bhuj'    ? 'btn-primary':'btn-outline' ?>">🏜️ Bhuj</a>
      <a href="?place=dwarka"  class="btn btn-sm <?= $filterPlace==='dwarka'  ? 'btn-primary':'btn-outline' ?>">🛕 Dwarka</a>
      <a href="?place=gir"     class="btn btn-sm <?= $filterPlace==='gir'     ? 'btn-primary':'btn-outline' ?>">🦁 Gir</a>
      <a href="?place=diu"     class="btn btn-sm <?= $filterPlace==='diu'     ? 'btn-primary':'btn-outline' ?>">🏖️ Diu</a>
      <a href="?place=somnath" class="btn btn-sm <?= $filterPlace==='somnath' ? 'btn-primary':'btn-outline' ?>">🕌 Somnath</a>
      <a href="?place=sou"     class="btn btn-sm <?= $filterPlace==='sou'     ? 'btn-primary':'btn-outline' ?>">🗽 Statue of Unity</a>
    </div>
  </div>
</section>

<!-- Destinations Grid -->
<section class="section-padding" style="background:var(--color-bg)">
  <div class="container">
    <div style="display:grid;grid-template-columns:1fr;gap:3rem">
      <?php foreach ($filtered as $i => [$slug, $icon, $name, $desc, $duration, $bestTime, $climate, $img, $highlights, $startingPrice]): ?>
      <div style="display:grid;grid-template-columns:1fr;gap:2rem;background:white;border-radius:var(--radius-2xl);overflow:hidden;box-shadow:var(--shadow-sm);border:1px solid var(--color-border)" class="dest-detail-card" data-reveal>
        <div style="display:grid;grid-template-columns:1fr;gap:0" class="dest-inner">
          <div style="position:relative;height:300px;overflow:hidden">
            <img src="<?= $img ?>" alt="<?= e($name) ?> Gujarat" style="width:100%;height:100%;object-fit:cover" loading="lazy">
            <div style="position:absolute;inset:0;background:linear-gradient(to top,rgba(8,29,78,0.7) 0%,transparent 50%)"></div>
            <div style="position:absolute;bottom:1.5rem;left:1.5rem">
              <div style="font-size:0.65rem;font-weight:700;letter-spacing:0.08em;text-transform:uppercase;color:var(--color-gold-light);margin-bottom:4px"><?= $icon ?> Destination</div>
              <h2 style="color:white;font-size:1.75rem;font-weight:900;margin:0"><?= e($name) ?></h2>
            </div>
            <div style="position:absolute;top:1.5rem;right:1.5rem;background:var(--color-gold);color:white;padding:6px 14px;border-radius:var(--radius-full);font-size:0.75rem;font-weight:700">
              From <?= $startingPrice ?>
            </div>
          </div>
          <div style="padding:2rem">
            <p style="margin-bottom:1.5rem;font-size:0.95rem;line-height:1.75"><?= e($desc) ?></p>

            <div style="display:flex;flex-wrap:wrap;gap:1rem;margin-bottom:1.5rem">
              <div style="display:flex;align-items:center;gap:0.5rem;background:var(--color-primary-50);padding:8px 14px;border-radius:var(--radius-md)">
                <span>📅</span><span style="font-size:0.8rem;font-weight:700;color:var(--color-primary)"><?= $duration ?></span>
              </div>
              <div style="display:flex;align-items:center;gap:0.5rem;background:var(--color-gold-pale);padding:8px 14px;border-radius:var(--radius-md)">
                <span>🌤️</span><span style="font-size:0.8rem;font-weight:700;color:var(--color-gold-dark)">Best: <?= $bestTime ?></span>
              </div>
              <div style="display:flex;align-items:center;gap:0.5rem;background:#f0fdf4;padding:8px 14px;border-radius:var(--radius-md)">
                <span>🌡️</span><span style="font-size:0.8rem;font-weight:700;color:#15803d">Climate: <?= $climate ?></span>
              </div>
            </div>

            <h4 style="font-size:0.8rem;font-weight:700;text-transform:uppercase;letter-spacing:0.06em;color:var(--color-text-muted);margin-bottom:0.75rem">Key Highlights</h4>
            <div style="display:flex;flex-wrap:wrap;gap:0.5rem;margin-bottom:2rem">
              <?php foreach ($highlights as $hl): ?>
              <span class="badge badge-primary"><?= e($hl) ?></span>
              <?php endforeach ?>
            </div>

            <div style="display:flex;gap:1rem;flex-wrap:wrap">
              <a href="destination-detail.php?slug=<?= e($slug) ?>" class="btn btn-primary">Explore →</a>
              <a href="packages.php?dest=<?= $slug ?>" class="btn btn-outline">Packages</a>
              <button onclick="inquirePackage('<?= e($name) ?> Tour',<?= (int)str_replace(['₹',','],'',$startingPrice) ?>)"
                      class="btn btn-outline">Get Custom Quote</button>
            </div>
          </div>
        </div>
      </div>
      <?php endforeach ?>
    </div>
  </div>
</section>
<style>
@media(min-width:900px){
  .dest-inner { grid-template-columns: 400px 1fr !important; }
  .dest-detail-card:nth-child(even) .dest-inner { direction:rtl; }
  .dest-detail-card:nth-child(even) .dest-inner > * { direction:ltr; }
}
</style>

<!-- CTA -->
<section class="cta-strip">
  <div class="container">
    <h2>Can't Find What You're Looking For?</h2>
    <p>We create fully custom Gujarat itineraries tailored to your dates, budget, and interests.</p>
    <div class="cta-strip-buttons">
      <a href="custom-tour-planner.php" class="btn btn-primary btn-lg">Plan My Custom Trip ✈️</a>
      <a href="https://wa.me/<?= APP_WHATSAPP ?>?text=Hello!%20I%20want%20to%20plan%20a%20custom%20Gujarat%20tour."
         class="btn btn-whatsapp btn-lg" target="_blank">💬 WhatsApp Us</a>
    </div>
  </div>
</section>

<?php include 'includes/footer.php'; ?>
