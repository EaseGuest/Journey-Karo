<?php
require_once 'includes/config.php';
require_once 'includes/database.php';
require_once 'includes/functions.php';
require_once 'includes/csrf.php';

$page = 'services';
$pageTitle = 'Our Travel Services | Journey Karo Gujarat';
$meta = getSeoMeta($page);

include 'includes/header.php';
?>

<section class="page-hero">
  <div class="container">
    <div class="section-badge">⭐ Services</div>
    <h1>Complete Gujarat Travel Services</h1>
    <p>Tour packages, hotels, cars, flights & custom planning — one trusted Bhuj team.</p>
  </div>
</section>

<section class="section-padding">
  <div class="container services-grid">
    <?php
    $items = [
      ['Tour Packages','packages.php','📦','Curated Gujarat circuits with transparent pricing.'],
      ['Hotel Booking','services/hotel-booking.php','🏨','Bhuj, Rann tents, Dwarka dharamshalas & beach resorts.'],
      ['Car Rental','services/car-rental.php','🚗','Sedan, SUV & tempo with experienced local drivers.'],
      ['Flight Assistance','services/flight-assistance.php','✈️','Domestic flights to Bhuj, Ahmedabad & Rajkot.'],
      ['Custom Tour Planning','custom-tour-planner.php','🗺️','Free bespoke itineraries for your dream trip.'],
    ];
    foreach ($items as [$t,$href,$icon,$d]):
    ?>
    <a href="<?= e($href) ?>" class="service-card" data-reveal>
      <span class="service-icon"><?= $icon ?></span>
      <h3><?= e($t) ?></h3>
      <p><?= e($d) ?></p>
    </a>
    <?php endforeach; ?>
  </div>
</section>

<section class="section-padding" style="background:var(--color-bg)">
  <div class="container" style="max-width:560px">
    <h2 class="text-center">Request a Quote</h2>
    <?php $inquirySource = 'services'; include 'includes/partials/inquiry-form.php'; ?>
  </div>
</section>

<?php include 'includes/footer.php'; ?>
