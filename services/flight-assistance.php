<?php
require_once dirname(__DIR__) . '/includes/config.php';
require_once dirname(__DIR__) . '/includes/database.php';
require_once dirname(__DIR__) . '/includes/functions.php';
require_once dirname(__DIR__) . '/includes/csrf.php';
$pageTitle = 'Flight Booking Assistance | Journey Karo Bhuj';
$meta = getSeoMeta('services');
include dirname(__DIR__) . '/includes/header.php';
?>
<section class="page-hero"><div class="container"><div class="section-badge">✈️ Flights</div><h1>Flight Assistance</h1><p>Domestic flights to Bhuj (BHJ), Ahmedabad, Rajkot & more.</p></div></section>
<section class="section-padding"><div class="container" style="max-width:720px"><p>We assist with flight search, booking, and coordination with your ground package — ideal for travelers flying into Kutch or Saurashtra.</p></div></section>
<section class="section-padding" style="background:var(--color-bg)"><div class="container" style="max-width:560px"><?php $inquirySource='flight_assistance'; $inquiryDestination='Flight Booking'; include dirname(__DIR__).'/includes/partials/inquiry-form.php'; ?></div></section>
<?php include dirname(__DIR__) . '/includes/footer.php'; ?>
