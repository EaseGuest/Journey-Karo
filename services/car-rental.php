<?php
require_once dirname(__DIR__) . '/includes/config.php';
require_once dirname(__DIR__) . '/includes/database.php';
require_once dirname(__DIR__) . '/includes/functions.php';
require_once dirname(__DIR__) . '/includes/csrf.php';
$pageTitle = 'Car Rental Bhuj & Gujarat | Journey Karo';
$meta = getSeoMeta('services');
include dirname(__DIR__) . '/includes/header.php';
?>
<section class="page-hero"><div class="container"><div class="section-badge">🚗 Car Rental</div><h1>Car Rental with Driver</h1><p>Safe, licensed drivers who know Gujarat's highways and village roads.</p></div></section>
<section class="section-padding"><div class="container" style="max-width:720px"><p>Sedan, SUV, Innova & tempo traveller — airport pick-up, multi-day circuits, and pilgrimage routes. All-inclusive per-km or package rates.</p></div></section>
<section class="section-padding" style="background:var(--color-bg)"><div class="container" style="max-width:560px"><?php $inquirySource='car_rental'; $inquiryDestination='Car Rental'; include dirname(__DIR__).'/includes/partials/inquiry-form.php'; ?></div></section>
<?php include dirname(__DIR__) . '/includes/footer.php'; ?>
