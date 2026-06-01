<?php
require_once dirname(__DIR__) . '/includes/config.php';
require_once dirname(__DIR__) . '/includes/database.php';
require_once dirname(__DIR__) . '/includes/functions.php';
require_once dirname(__DIR__) . '/includes/csrf.php';
$page = 'services-hotel'; $meta = getSeoMeta('services');
$pageTitle = 'Hotel Booking Bhuj & Gujarat | Journey Karo';
include dirname(__DIR__) . '/includes/header.php';
?>
<section class="page-hero"><div class="container"><div class="section-badge">🏨 Hotels</div><h1>Hotel Booking Across Gujarat</h1><p>From Bhuj business hotels to Rann tent cities and Diu beach resorts.</p></div></section>
<section class="section-padding"><div class="container" style="max-width:720px"><p>We book verified stays with best available rates — standalone or as part of your tour package. Tell us your dates and budget.</p></div></section>
<section class="section-padding" style="background:var(--color-bg)"><div class="container" style="max-width:560px"><?php $inquirySource='hotel_booking'; $inquiryDestination='Hotel Booking'; include dirname(__DIR__).'/includes/partials/inquiry-form.php'; ?></div></section>
<?php include dirname(__DIR__) . '/includes/footer.php'; ?>
