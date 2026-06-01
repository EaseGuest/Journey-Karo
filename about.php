<?php
require_once 'includes/config.php';
require_once 'includes/db.php';
require_once 'includes/functions.php';

$page      = 'about';
$pageTitle = 'About Us | Journey Karo — Gujarat\'s Most Trusted Travel Agency';
$meta      = getSeoMeta($page);
include 'includes/header.php';
?>

<!-- Page Hero -->
<section class="page-hero">
  <div class="container">
    <div class="section-badge">🏆 About Us</div>
    <h1>Gujarat's Most Trusted<br>Local Travel Experts</h1>
    <p>Born in Bhuj. Rooted in Gujarat. Passionate about sharing our home with the world.</p>
  </div>
</section>

<!-- Story Section -->
<section class="section-padding" style="background:white">
  <div class="container">
    <div class="why-choose-grid">
      <div>
        <div class="section-badge">🌟 Our Story</div>
        <h2 style="margin-bottom:1.5rem">We Know Gujarat Like the Back of Our Hand</h2>
        <p style="margin-bottom:1.25rem">Journey Karo was founded with a simple belief — that the people who live and breathe Gujarat are the best guides to explore it. Based in the heart of Bhuj, our team of local travel experts has been curating unforgettable journeys across the state for thousands of happy travelers.</p>
        <p style="margin-bottom:1.25rem">From the surreal white salt flats of the Rann of Kutch to the roaring wilderness of Gir National Park, from the sacred shores of Dwarka to the Portuguese-era forts of Diu — we don't just book trips, we craft experiences that become lifelong memories.</p>
        <p style="margin-bottom:2rem">Every tour we design carries the warmth of Gujarati hospitality, meticulous planning, and a genuine passion for our homeland.</p>
        <div style="display:flex;gap:1rem;flex-wrap:wrap">
          <a href="packages.php"            class="btn btn-primary btn-lg">View Our Packages</a>
          <a href="custom-tour-planner.php" class="btn btn-outline  btn-lg">Plan Custom Tour</a>
        </div>
      </div>
      <div class="why-image-block">
        <img src="https://images.unsplash.com/photo-1506905925346-21bda4d32df4?auto=format&fit=crop&w=800&q=80"
             alt="Journey Karo team at White Rann of Kutch" loading="lazy">
        <div class="why-stats-card">
          <div class="why-stat">
            <div class="why-stat-value" data-count="5000" data-suffix="+">5000+</div>
            <div class="why-stat-label">Happy Travelers</div>
          </div>
          <div class="why-stat">
            <div class="why-stat-value" data-count="7" data-suffix="">7</div>
            <div class="why-stat-label">Destinations</div>
          </div>
          <div class="why-stat">
            <div class="why-stat-value" data-count="8" data-suffix=" Yrs">8 Yrs</div>
            <div class="why-stat-label">Experience</div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Mission & Vision -->
<section class="section-padding" style="background:var(--color-bg)">
  <div class="container">
    <div class="section-header" data-reveal>
      <div class="section-badge">💡 Our Mission & Vision</div>
      <h2>What Drives Us Every Day</h2>
    </div>
    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(280px,1fr));gap:2rem">
      <?php
      $values = [
        ['🎯','Our Mission','To make Gujarat\'s extraordinary beauty accessible to every traveler by offering expert-guided, affordable, and premium tour experiences that honor local culture and create lasting memories.'],
        ['🌅','Our Vision','To become India\'s leading Gujarat-focused travel brand, recognized for authenticity, reliability, and transforming first-time visitors into lifelong Gujarat enthusiasts.'],
        ['💎','Our Values','Integrity in every promise we make. Warmth in every interaction. Excellence in every itinerary. Respect for local communities and environments.'],
        ['🤝','Our Promise','24/7 support throughout your journey. Transparent pricing with zero hidden costs. Full refunds if we cancel. Your satisfaction is our only KPI.'],
      ];
      foreach ($values as [$icon, $title, $desc]): ?>
      <div class="info-card" data-reveal>
        <div style="font-size:2.5rem;margin-bottom:1rem"><?= $icon ?></div>
        <h3 style="font-size:1.1rem;margin-bottom:0.75rem;color:var(--color-primary)"><?= $title ?></h3>
        <p style="font-size:0.875rem;line-height:1.75"><?= $desc ?></p>
      </div>
      <?php endforeach ?>
    </div>
  </div>
</section>

<!-- Team -->
<section class="section-padding" style="background:white">
  <div class="container">
    <div class="section-header" data-reveal>
      <div class="section-badge">👥 Our Team</div>
      <h2>Meet the Local Experts</h2>
      <p>Our team of passionate Gujaratis who make every journey extraordinary.</p>
    </div>
    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:2rem">
      <?php
      $team = [
        ['https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?auto=format&fit=crop&w=300&h=300&q=80','Rajesh Vaghasiya','Founder & CEO','12+ years guiding Gujarat tours. Born in Bhuj. Expert in Kutch culture & Rann safari.'],
        ['https://images.unsplash.com/photo-1494790108377-be9c29b29330?auto=format&fit=crop&w=300&h=300&q=80','Priya Joshi','Head of Operations','Manages all bookings, hotel tie-ups, and ensures every package runs flawlessly.'],
        ['https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?auto=format&fit=crop&w=300&h=300&q=80','Mehul Raval','Senior Tour Guide','Wildlife expert & Gir safari specialist. Also fluent in Hindi, English & Gujarati.'],
        ['https://images.unsplash.com/photo-1534528741775-53994a69daeb?auto=format&fit=crop&w=300&h=300&q=80','Kavita Shah','Customer Relations','Your 24/7 support contact. Ensures every traveler has a seamless experience.'],
      ];
      foreach ($team as [$img, $name, $role, $bio]): ?>
      <div class="info-card" style="text-align:center" data-reveal>
        <img src="<?= $img ?>" alt="<?= e($name) ?>" style="width:80px;height:80px;border-radius:50%;object-fit:cover;margin:0 auto 1rem;border:3px solid var(--color-gold-pale)">
        <h4 style="font-size:1rem;font-weight:800;color:var(--color-primary);margin-bottom:4px"><?= e($name) ?></h4>
        <div style="font-size:0.75rem;font-weight:700;color:var(--color-gold);margin-bottom:0.75rem;text-transform:uppercase;letter-spacing:0.05em"><?= e($role) ?></div>
        <p style="font-size:0.8rem;line-height:1.65"><?= e($bio) ?></p>
      </div>
      <?php endforeach ?>
    </div>
  </div>
</section>

<!-- Achievements / Trust Signals -->
<section style="background:linear-gradient(135deg,var(--color-primary-dark),var(--color-primary));padding-block:4rem">
  <div class="container">
    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(150px,1fr));gap:2rem;text-align:center">
      <?php
      $achievements = [
        ['5,000+','Happy Travelers','✈️'],['4.9 ★','Google Rating','⭐'],
        ['7','Destinations','📍'],['100%','Verified Agency','🏅'],
        ['24/7','Customer Support','📞'],['8+','Years of Experience','🎖️'],
      ];
      foreach ($achievements as [$val, $label, $icon]): ?>
      <div data-reveal>
        <div style="font-size:2rem;margin-bottom:0.5rem"><?= $icon ?></div>
        <div style="font-size:1.75rem;font-weight:900;color:var(--color-gold-light);margin-bottom:4px"><?= $val ?></div>
        <div style="font-size:0.8rem;color:rgba(255,255,255,0.7);font-weight:500"><?= $label ?></div>
      </div>
      <?php endforeach ?>
    </div>
  </div>
</section>

<!-- CTA -->
<section class="cta-strip">
  <div class="container">
    <h2>Ready to Explore Gujarat?</h2>
    <p>Let our local experts craft the perfect Gujarat adventure for you and your family.</p>
    <div class="cta-strip-buttons">
      <a href="packages.php"            class="btn btn-outline-white btn-lg">View Packages</a>
      <a href="custom-tour-planner.php" class="btn btn-primary    btn-lg">Plan My Custom Trip ✈️</a>
    </div>
  </div>
</section>

<?php include 'includes/footer.php'; ?>
