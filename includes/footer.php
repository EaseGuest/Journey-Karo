<?php
/**
 * Journey Karo — Reusable Site Footer
 */
if (!defined('APP_NAME')) {
    require_once __DIR__ . '/config.php';
}
$year = date('Y');
?>
</main><!-- /main-content -->

<!-- ===== MOBILE STICKY CTA ===== -->
<div class="mobile-cta-bar" id="mobile-cta-bar">
  <a href="tel:+91<?= APP_PHONE ?>" class="btn btn-outline" id="mobile-call-cta">📞 Call Now</a>
  <a href="https://wa.me/<?= APP_WHATSAPP ?>?text=Hello%20Journey%20Karo!%20I%20want%20to%20book%20a%20Gujarat%20tour.%20Please%20help%20me."
     target="_blank" class="btn btn-whatsapp" id="mobile-wa-cta">💬 WhatsApp</a>
  <a href="custom-tour-planner.php" class="btn btn-gold" id="mobile-plan-cta">✈️ Plan Trip</a>
</div>

<!-- ===== FOOTER ===== -->
<footer class="site-footer" id="site-footer">
  <div class="container">
    <div class="footer-grid">

      <!-- Brand Column -->
      <div class="footer-brand">
        <div class="footer-logo">
          <div class="logo-icon" style="background:linear-gradient(135deg,#c9972a,#a57a1e)"></div>
          <span class="footer-logo-text">Journey Karo</span>
        </div>
        <p class="footer-desc">
          Gujarat's most trusted travel agency based in Bhuj. We specialize in curated tour packages
          to Rann of Kutch, Dwarka, Somnath, Gir Safari, Diu & Statue of Unity. Local experts, premium experience.
        </p>

        <a href="tel:+91<?= APP_PHONE ?>" class="footer-contact-item" id="footer-phone">
          <span class="icon">📞</span> +91 <?= APP_PHONE ?>
        </a>
        <a href="mailto:<?= APP_EMAIL ?>" class="footer-contact-item" id="footer-email">
          <span class="icon">✉️</span> <?= APP_EMAIL ?>
        </a>
        <div class="footer-contact-item" id="footer-address">
          <span class="icon">📍</span> Near Science Center, Bhuj, Gujarat 370001
        </div>

        <div class="footer-socials">
          <a href="https://www.facebook.com/journeykaro"   class="footer-social" target="_blank" rel="noopener" id="footer-facebook"  aria-label="Facebook">f</a>
          <a href="https://www.instagram.com/journeykaro"  class="footer-social" target="_blank" rel="noopener" id="footer-instagram" aria-label="Instagram">📸</a>
          <a href="https://wa.me/<?= APP_WHATSAPP ?>"      class="footer-social" target="_blank" rel="noopener" id="footer-whatsapp" aria-label="WhatsApp">💬</a>
          <a href="https://www.youtube.com/@journeykaro"   class="footer-social" target="_blank" rel="noopener" id="footer-youtube"  aria-label="YouTube">▶</a>
        </div>
      </div>

      <!-- Quick Links -->
      <div>
        <h3 class="footer-col-title">Quick Links</h3>
        <ul class="footer-links">
          <li><a href="/"                            class="footer-link">→ Home</a></li>
          <li><a href="about.php"                    class="footer-link">→ About Us</a></li>
          <li><a href="destinations.php"             class="footer-link">→ Destinations</a></li>
          <li><a href="packages.php"                 class="footer-link">→ Tour Packages</a></li>
          <li><a href="services.php"                 class="footer-link">→ Our Services</a></li>
          <li><a href="gallery.php"                  class="footer-link">→ Gallery</a></li>
          <li><a href="reviews.php"                  class="footer-link">→ Reviews</a></li>
          <li><a href="blog.php"                     class="footer-link">→ Blog</a></li>
          <li><a href="faq.php"                      class="footer-link">→ FAQ</a></li>
          <li><a href="contact.php"                  class="footer-link">→ Contact Us</a></li>
          <li><a href="custom-tour-planner.php"      class="footer-link">→ Custom Tour Planner</a></li>
        </ul>
      </div>

      <!-- Destinations -->
      <div>
        <h3 class="footer-col-title">Top Destinations</h3>
        <ul class="footer-links">
          <li><a href="destinations.php?place=bhuj"    class="footer-link">🏜️ Bhuj & Rann of Kutch</a></li>
          <li><a href="destinations.php?place=dwarka"  class="footer-link">🛕 Dwarka</a></li>
          <li><a href="destinations.php?place=somnath" class="footer-link">🕌 Somnath</a></li>
          <li><a href="destinations.php?place=gir"     class="footer-link">🦁 Gir National Park</a></li>
          <li><a href="destinations.php?place=diu"     class="footer-link">🏖️ Diu</a></li>
          <li><a href="destinations.php?place=sou"     class="footer-link">🗽 Statue of Unity</a></li>
        </ul>
      </div>

      <!-- Legal & Info -->
      <div>
        <h3 class="footer-col-title">Legal & Info</h3>
        <ul class="footer-links">
          <li><a href="privacy-policy.php"       class="footer-link">→ Privacy Policy</a></li>
          <li><a href="terms-conditions.php"     class="footer-link">→ Terms & Conditions</a></li>
          <li><a href="refund-policy.php"        class="footer-link">→ Refund Policy</a></li>
          <li><a href="cancellation-policy.php"  class="footer-link">→ Cancellation Policy</a></li>
          <li><a href="cookie-policy.php"        class="footer-link">→ Cookie Policy</a></li>
          <li><a href="disclaimer.php"           class="footer-link">→ Disclaimer</a></li>
          <li><a href="safety-guidelines.php"    class="footer-link">→ Safety Guidelines</a></li>
        </ul>

        <div style="margin-top:2rem">
          <h3 class="footer-col-title">We Accept</h3>
          <div style="display:flex;gap:0.5rem;flex-wrap:wrap;margin-top:0.75rem">
            <?php foreach(['💳 UPI','🏦 NEFT','💰 Cash','📱 GPay'] as $pay): ?>
            <span style="background:rgba(255,255,255,0.08);border:1px solid rgba(255,255,255,0.1);padding:4px 10px;border-radius:6px;font-size:0.75rem;color:rgba(255,255,255,0.65)"><?= $pay ?></span>
            <?php endforeach ?>
          </div>
        </div>
      </div>

    </div>

    <!-- Footer Bottom -->
    <div class="footer-bottom">
      <p class="footer-bottom-text">
        &copy; <?= $year ?> Journey Karo. All rights reserved. | Made with ❤️ in Bhuj, Gujarat.
      </p>
      <div class="footer-bottom-links">
        <a href="sitemap.xml"           class="footer-bottom-link">Sitemap</a>
        <a href="privacy-policy.php"    class="footer-bottom-link">Privacy</a>
        <a href="terms-conditions.php"  class="footer-bottom-link">Terms</a>
      </div>
    </div>
  </div>
</footer>

<!-- ===== LIGHTBOX ===== -->
<div id="lightbox" class="lightbox" role="dialog" aria-modal="true" aria-label="Image lightbox">
  <img id="lightbox-img" class="lightbox-img" src="" alt="">
  <button id="lightbox-close" class="lightbox-close" aria-label="Close lightbox">✕</button>
</div>

<!-- ===== MAIN JS ===== -->
<script src="/assets/js/main.js" defer></script>

</body>
</html>
