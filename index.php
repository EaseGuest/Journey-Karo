<?php
/**
 * Journey Karo — Homepage (PHP, DB-driven with static fallback)
 */
require_once 'includes/config.php';
require_once 'includes/database.php';
require_once 'includes/functions.php';
require_once 'includes/content-helpers.php';
require_once 'includes/csrf.php';

$page = 'home';
$meta = getSeoMeta($page);
$pageTitle = $meta['meta_title'] ?? DEFAULT_META_TITLE;

$featuredDestinations = tryDb(fn() => getDestinations(true, 7), []);
$featuredPackages     = tryDb(fn() => getPackages(0, true, 6), []);
$featuredReviews      = tryDb(fn() => getReviews(4), []);
$galleryItems         = tryDb(fn() => getGallery('', 8), []);
$faqsHome = [
  ['Where is Journey Karo located?', 'Our office is at Near Science Center, Bhuj, Gujarat 370001. We serve travelers across Gujarat and India.'],
  ['What services do you offer?', 'Tour packages, hotel booking, car rental with driver, flight assistance, and free custom tour planning.'],
  ['How do I book a Gujarat tour?', 'Fill the inquiry form below, call +91 9586605635, or WhatsApp us — we respond within 2 hours.'],
  ['What is your cancellation policy?', 'Free cancellation up to 7 days before departure on most packages. See our Cancellation Policy for details.'],
];

include 'includes/header.php';
?>

<section class="hero" id="hero-section">
  <div class="hero-bg" style="background-image:url('https://images.unsplash.com/photo-1590050752117-238cb0fb12b1?auto=format&fit=crop&w=1600&q=80')"></div>
  <div class="container hero-content">
    <div class="section-badge">✈️ Explore Gujarat with Local Experts</div>
    <h1>Discover the Magic of<br><span class="text-gold">Beautiful Gujarat</span></h1>
    <p class="hero-subtitle">Premium tours to Rann of Kutch, Dwarka, Somnath, Gir Safari, Diu & Statue of Unity — from our Bhuj office.</p>
    <div class="hero-cta">
      <a href="custom-tour-planner.php" class="btn btn-gold btn-lg">Plan My Trip</a>
      <a href="https://wa.me/<?= APP_WHATSAPP ?>?text=Hello%20Journey%20Karo!" target="_blank" class="btn btn-outline btn-lg" style="border-color:#fff;color:#fff">💬 WhatsApp</a>
    </div>
  </div>
</section>

<?php if ($featuredDestinations): ?>
<section class="section-padding" id="destinations-section">
  <div class="container">
    <div class="section-header text-center">
      <div class="section-badge">🗺️ Featured Destinations</div>
      <h2>Explore Iconic Gujarat</h2>
      <p>Seven unforgettable destinations curated by local experts</p>
    </div>
    <div class="destinations-grid">
      <?php foreach ($featuredDestinations as $d):
        $highlights = contentList($d['highlights'] ?? '');
        $img = destinationImage($d);
      ?>
      <a href="destination-detail.php?slug=<?= e($d['slug']) ?>" class="destination-card" data-reveal>
        <div class="destination-card-img">
          <img src="<?= e($img) ?>" alt="<?= e($d['name']) ?>" loading="lazy" width="400" height="280">
        </div>
        <div class="destination-card-body">
          <span class="destination-icon"><?= e($d['icon'] ?? '📍') ?></span>
          <h3><?= e($d['name']) ?></h3>
          <p><?= e(truncate($d['short_description'] ?? '', 100)) ?></p>
          <span class="destination-price">From <?= formatPrice((int)($d['starting_price'] ?? 0)) ?></span>
        </div>
      </a>
      <?php endforeach; ?>
    </div>
    <div class="text-center" style="margin-top:2rem">
      <a href="destinations.php" class="btn btn-outline">View All Destinations</a>
    </div>
  </div>
</section>
<?php endif; ?>

<section class="section-padding" style="background:var(--color-bg)" id="packages-section">
  <div class="container">
    <div class="section-header text-center">
      <div class="section-badge">📦 Tour Packages</div>
      <h2>Popular Gujarat Packages</h2>
    </div>
    <div class="packages-grid">
      <?php
      $packagesShow = $featuredPackages;
      if (!$packagesShow) {
        $packagesShow = []; // static fallback handled below
      }
      if ($packagesShow):
        foreach ($packagesShow as $p):
          $inc = contentList($p['inclusions'] ?? '');
          $img = packageImage($p);
      ?>
      <article class="package-card" data-reveal>
        <div class="package-card-img">
          <img src="<?= e($img) ?>" alt="<?= e($p['name']) ?>" loading="lazy">
          <?php if (!empty($p['category'])): ?><span class="package-badge"><?= e($p['category']) ?></span><?php endif; ?>
        </div>
        <div class="package-card-body">
          <h3><a href="package-detail.php?slug=<?= e($p['slug']) ?>"><?= e($p['name']) ?></a></h3>
          <p class="package-dest"><?= e($p['destination_name'] ?? 'Gujarat') ?> · <?= (int)$p['days'] ?>D/<?= (int)$p['nights'] ?>N</p>
          <div class="package-footer">
            <span class="package-price"><?= formatPrice((int)$p['price']) ?></span>
            <a href="package-detail.php?slug=<?= e($p['slug']) ?>" class="btn btn-primary btn-sm">View Details</a>
          </div>
        </div>
      </article>
      <?php endforeach; else: ?>
      <p class="text-center" style="grid-column:1/-1">Browse our <a href="packages.php">tour packages</a> — database seed pending.</p>
      <?php endif; ?>
    </div>
    <div class="text-center" style="margin-top:2rem"><a href="packages.php" class="btn btn-outline">All Packages</a></div>
  </div>
</section>

<section class="section-padding" id="services-section">
  <div class="container">
    <div class="section-header text-center">
      <div class="section-badge">⭐ Our Services</div>
      <h2>Everything You Need to Travel Gujarat</h2>
    </div>
    <div class="services-grid">
      <?php
      $services = [
        ['Tour Packages','packages.php','📦','Curated Rann, Gir, Dwarka & more'],
        ['Hotel Booking','services/hotel-booking.php','🏨','Best stays in Bhuj & across Gujarat'],
        ['Car Rental','services/car-rental.php','🚗','Safe drivers who know every road'],
        ['Flight Assistance','services/flight-assistance.php','✈️','Flights to Bhuj, Ahmedabad & Rajkot'],
        ['Custom Planning','custom-tour-planner.php','🗺️','Free personalised itineraries'],
      ];
      foreach ($services as [$title, $href, $icon, $desc]):
      ?>
      <a href="<?= e($href) ?>" class="service-card" data-reveal>
        <span class="service-icon"><?= $icon ?></span>
        <h3><?= e($title) ?></h3>
        <p><?= e($desc) ?></p>
      </a>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<?php if ($featuredReviews): ?>
<section class="section-padding" style="background:white" id="testimonials-section">
  <div class="container">
    <div class="section-header text-center">
      <div class="section-badge">💬 Testimonials</div>
      <h2>What Travelers Say</h2>
    </div>
    <div class="reviews-grid">
      <?php foreach ($featuredReviews as $rev): ?>
      <blockquote class="review-card" data-reveal>
        <div class="review-stars"><?= starRating((float)$rev['rating']) ?></div>
        <p>"<?= e($rev['review_text']) ?>"</p>
        <footer><strong><?= e($rev['reviewer_name']) ?></strong> — <?= e($rev['destination'] ?? 'Gujarat') ?></footer>
      </blockquote>
      <?php endforeach; ?>
    </div>
    <div class="text-center" style="margin-top:2rem"><a href="reviews.php" class="btn btn-outline">More Reviews</a></div>
  </div>
</section>
<?php endif; ?>

<?php if ($galleryItems): ?>
<section class="section-padding" id="gallery-section">
  <div class="container">
    <div class="section-header text-center">
      <div class="section-badge">🖼️ Gallery</div>
      <h2>Moments from Gujarat</h2>
    </div>
    <div class="gallery-grid">
      <?php foreach ($galleryItems as $g): ?>
      <figure class="gallery-item" data-reveal data-category="<?= e($g['category']) ?>">
        <img src="<?= e($g['image_path']) ?>" alt="<?= e($g['title']) ?>" loading="lazy" data-lightbox="<?= e($g['image_path']) ?>">
      </figure>
      <?php endforeach; ?>
    </div>
    <div class="text-center" style="margin-top:2rem"><a href="gallery.php" class="btn btn-outline">Full Gallery</a></div>
  </div>
</section>
<?php endif; ?>

<section class="section-padding" style="background:var(--color-bg)" id="faq-section">
  <div class="container" style="max-width:800px">
    <div class="section-header text-center">
      <div class="section-badge">❓ FAQ</div>
      <h2>Common Questions</h2>
    </div>
    <div class="faq-list">
      <?php foreach ($faqsHome as $i => [$q, $a]): ?>
      <div class="faq-item">
        <button class="faq-question" aria-expanded="false" data-faq="<?= $i ?>"><?= e($q) ?></button>
        <div class="faq-answer"><p><?= e($a) ?></p></div>
      </div>
      <?php endforeach; ?>
    </div>
    <div class="text-center" style="margin-top:1.5rem"><a href="faq.php" class="btn btn-outline btn-sm">All FAQs</a></div>
  </div>
</section>

<section class="section-padding" id="inquiry-section">
  <div class="container" style="max-width:640px">
    <div class="section-header text-center">
      <div class="section-badge">📩 Get a Quote</div>
      <h2>Plan Your Gujarat Trip</h2>
      <p>Name, mobile & email required — we reply within 2 hours.</p>
    </div>
    <?php $inquirySource = 'homepage'; include 'includes/partials/inquiry-form.php'; ?>
  </div>
</section>

<?php include 'includes/footer.php'; ?>
