<?php
/**
 * Journey Karo — Reusable Site Header
 * Include at the top of every public page.
 *
 * Expects: $meta (array from getSeoMeta()), $pageTitle (string)
 */

if (!defined('APP_NAME')) {
    require_once __DIR__ . '/config.php';
    require_once __DIR__ . '/functions.php';
}

$meta      = $meta      ?? getSeoMeta($page ?? '');
$pageTitle = $pageTitle ?? ($meta['meta_title'] ?? DEFAULT_META_TITLE);
$metaDesc  = $meta['meta_description'] ?? DEFAULT_META_DESCRIPTION;
$metaKeys  = $meta['meta_keywords']    ?? DEFAULT_META_KEYWORDS;
$ogImage   = $meta['og_image']         ?? APP_URL . '/assets/images/og-default.jpg';
$canonicalUrl = APP_URL . '/' . ltrim($_SERVER['REQUEST_URI'] ?? '', '/');
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <!-- Primary SEO -->
  <title><?= e($pageTitle) ?></title>
  <meta name="description" content="<?= e($metaDesc) ?>">
  <meta name="keywords"    content="<?= e($metaKeys) ?>">
  <meta name="robots"      content="index, follow">
  <link rel="canonical"    href="<?= e($canonicalUrl) ?>">
  <meta name="author"      content="Journey Karo">
  <meta name="theme-color" content="#0d2b6e">

  <!-- Open Graph -->
  <meta property="og:type"        content="website">
  <meta property="og:url"         content="<?= e($canonicalUrl) ?>">
  <meta property="og:title"       content="<?= e($pageTitle) ?>">
  <meta property="og:description" content="<?= e($metaDesc) ?>">
  <meta property="og:image"       content="<?= e($ogImage) ?>">
  <meta property="og:site_name"   content="Journey Karo">

  <!-- Twitter -->
  <meta property="twitter:card"        content="summary_large_image">
  <meta property="twitter:url"         content="<?= e($canonicalUrl) ?>">
  <meta property="twitter:title"       content="<?= e($pageTitle) ?>">
  <meta property="twitter:description" content="<?= e($metaDesc) ?>">
  <meta property="twitter:image"       content="<?= e($ogImage) ?>">

  <!-- Favicon -->
  <link rel="icon" type="image/svg+xml" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><rect width='100' height='100' rx='20' fill='%230d2b6e'/><text y='.9em' font-size='72' x='14'>✈️</text></svg>">

  <!-- Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800;900&family=Playfair+Display:wght@400;600;700&display=swap" rel="stylesheet">

  <!-- Main CSS -->
  <link rel="stylesheet" href="/assets/css/style.css">

  <!-- Schema.org JSON-LD -->
  <script type="application/ld+json">
  {
    "@context": "https://schema.org",
    "@type": "TravelAgency",
    "@id": "<?= APP_URL ?>/#agency",
    "name": "Journey Karo",
    "url": "<?= APP_URL ?>",
    "logo": "<?= APP_URL ?>/assets/images/logo.png",
    "telephone": "+91-<?= APP_PHONE ?>",
    "email": "<?= APP_EMAIL ?>",
    "description": "<?= e($metaDesc) ?>",
    "priceRange": "$$",
    "address": {
      "@type": "PostalAddress",
      "streetAddress": "Near Science Center",
      "addressLocality": "Bhuj",
      "postalCode": "370001",
      "addressRegion": "Gujarat",
      "addressCountry": "IN"
    },
    "geo": {
      "@type": "GeoCoordinates",
      "latitude": 23.2505,
      "longitude": 69.6696
    },
    "sameAs": [
      "https://www.facebook.com/journeykaro",
      "https://www.instagram.com/journeykaro"
    ]
  }
  </script>

  <?php if (defined('RECAPTCHA_SITE_KEY') && RECAPTCHA_SITE_KEY !== 'YOUR_RECAPTCHA_SITE_KEY'): ?>
  <script src="https://www.google.com/recaptcha/api.js?render=<?= RECAPTCHA_SITE_KEY ?>" async defer></script>
  <?php endif; ?>
  <?php require_once __DIR__ . '/analytics.php'; ?>
</head>
<body>

<!-- ===== FLOATING WHATSAPP ===== -->
<a href="https://wa.me/<?= APP_WHATSAPP ?>?text=Hello%20Journey%20Karo!%20I%20am%20interested%20in%20a%20Gujarat%20tour%20package.%20Please%20guide%20me."
   class="whatsapp-float" target="_blank" rel="noopener" aria-label="Chat on WhatsApp" id="whatsapp-float">
  <span class="whatsapp-tooltip">Chat on WhatsApp</span>
  <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" fill="white" viewBox="0 0 448 512" aria-hidden="true">
    <path d="M380.9 97.1C339 55.1 283.2 32 223.9 32c-122.4 0-222 99.6-222 222 0 39.1 10.2 77.3 29.6 111L3 480l117.7-30.9c32.4 17.7 68.9 27 106.1 27h.1c122.3 0 224.1-99.6 224.1-222 0-59.3-25.2-115-67.1-157zm-157 341.6c-33.2 0-65.7-8.9-94-25.7l-6.7-4-69.8 18.3L72 359.2l-4.4-7c-18.5-29.4-28.2-63.3-28.2-98.2 0-101.7 82.8-184.5 184.6-184.5 49.3 0 95.6 19.2 130.4 54.1 34.8 34.9 56.2 81.2 56.1 130.5 0 101.8-84.9 184.6-186.6 184.6zm101.2-138.2c-5.5-2.8-32.8-16.2-37.9-18-5.1-1.9-8.8-2.8-12.5 2.8-3.7 5.6-14.3 18-17.6 21.8-3.2 3.7-6.5 4.2-12 1.4-32.6-16.3-54-29.1-75.5-66-5.7-9.8 5.7-9.1 16.3-30.3 1.8-3.7.9-6.9-.5-9.7-1.4-2.8-12.5-30.1-17.1-41.2-4.5-10.8-9.1-9.3-12.5-9.5-3.2-.2-6.9-.2-10.6-.2-3.7 0-9.7 1.4-14.8 6.9-5.1 5.6-19.4 19-19.4 46.3 0 27.3 19.9 53.7 22.6 57.4 2.8 3.7 39.1 59.7 94.8 83.8 35.2 15.2 49 16.5 66.6 13.9 10.7-1.6 32.8-13.4 37.4-26.4 4.6-13 4.6-24.1 3.2-26.4-1.3-2.5-5-3.9-10.5-6.6z"/>
  </svg>
</a>

<!-- ===== HEADER ===== -->
<header class="site-header" id="site-header" role="banner">
  <div class="container">
    <div class="header-inner">

      <!-- Logo -->
      <a href="/" class="site-logo" id="site-logo" aria-label="Journey Karo - Home">
        <div class="logo-icon"></div>
        <div class="logo-text">
          <span class="logo-name">Journey Karo</span>
          <span class="logo-tagline">Explore Gujarat with Local Experts</span>
        </div>
      </a>

      <!-- Desktop Navigation -->
      <nav class="nav-desktop" aria-label="Main navigation">
        <div class="nav-dropdown">
          <a href="destinations.php" class="nav-link" id="nav-destinations">Destinations ▾</a>
          <div class="nav-dropdown-menu">
            <a href="destinations.php?place=bhuj"     class="nav-dropdown-item"><span class="icon">🏜️</span> Bhuj & Rann of Kutch</a>
            <a href="destinations.php?place=dwarka"   class="nav-dropdown-item"><span class="icon">🛕</span> Dwarka</a>
            <a href="destinations.php?place=somnath"  class="nav-dropdown-item"><span class="icon">🕌</span> Somnath</a>
            <a href="destinations.php?place=gir"      class="nav-dropdown-item"><span class="icon">🦁</span> Gir National Park</a>
            <a href="destinations.php?place=diu"      class="nav-dropdown-item"><span class="icon">🏖️</span> Diu</a>
            <a href="destinations.php?place=sou"      class="nav-dropdown-item"><span class="icon">🗽</span> Statue of Unity</a>
          </div>
        </div>
        <a href="packages.php"     class="nav-link" id="nav-packages">Packages</a>
        <a href="services.php"     class="nav-link" id="nav-services">Services</a>
        <a href="gallery.php"      class="nav-link" id="nav-gallery">Gallery</a>
        <a href="about.php"        class="nav-link" id="nav-about">About Us</a>
        <a href="blog.php"         class="nav-link" id="nav-blog">Blog</a>
        <a href="contact.php"      class="nav-link" id="nav-contact">Contact</a>
      </nav>

      <!-- Header Actions -->
      <div class="header-cta">
        <a href="tel:+91<?= APP_PHONE ?>" class="btn btn-outline btn-sm hide-mobile" id="header-call-btn" aria-label="Call Journey Karo">
          📞 <?= APP_PHONE ?>
        </a>
        <a href="custom-tour-planner.php" class="btn btn-gold btn-sm" id="header-book-btn">Plan My Trip</a>
        <button class="hamburger" id="hamburger" aria-label="Toggle navigation" aria-expanded="false" aria-controls="mobile-menu">
          <span></span><span></span><span></span>
        </button>
      </div>

    </div>
  </div>
</header>

<!-- Mobile Menu Overlay -->
<div id="menu-overlay" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.5);z-index:998;"></div>

<!-- Mobile Menu -->
<nav class="mobile-menu" id="mobile-menu" aria-label="Mobile navigation" role="dialog" aria-modal="true">
  <a href="/"                          class="mobile-nav-link" id="m-nav-home">🏠 Home</a>
  <a href="destinations.php"           class="mobile-nav-link" id="m-nav-dest">🗺️ Destinations</a>
  <a href="packages.php"               class="mobile-nav-link" id="m-nav-pkg">📦 Tour Packages</a>
  <a href="services.php"               class="mobile-nav-link" id="m-nav-svc">⭐ Services</a>
  <a href="gallery.php"                class="mobile-nav-link" id="m-nav-gallery">🖼️ Gallery</a>
  <a href="reviews.php"                class="mobile-nav-link" id="m-nav-reviews">💬 Reviews</a>
  <a href="about.php"                  class="mobile-nav-link" id="m-nav-about">ℹ️ About Us</a>
  <a href="blog.php"                   class="mobile-nav-link" id="m-nav-blog">📝 Blog</a>
  <a href="faq.php"                    class="mobile-nav-link" id="m-nav-faq">❓ FAQ</a>
  <a href="contact.php"                class="mobile-nav-link" id="m-nav-contact">📧 Contact</a>
  <div class="mobile-nav-divider"></div>
  <a href="tel:+91<?= APP_PHONE ?>"    class="mobile-nav-link" id="m-nav-call">📞 Call: <?= APP_PHONE ?></a>
  <a href="custom-tour-planner.php"    class="btn btn-gold w-full" style="margin-top:0.5rem;justify-content:center" id="m-nav-plan">✈️ Plan My Custom Trip</a>
</nav>

<!-- Page Content Starts Here -->
<main id="main-content">
