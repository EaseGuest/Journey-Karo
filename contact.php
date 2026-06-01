<?php
require_once 'includes/config.php';
require_once 'includes/db.php';
require_once 'includes/functions.php';
require_once 'includes/csrf.php';
require_once 'includes/mailer.php';

$page      = 'contact';
$pageTitle = 'Contact Us | Journey Karo — Bhuj, Gujarat | +91 9586605635';
$meta      = getSeoMeta($page);

// Handle form submission
$success = $error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    validateCsrf();
    if (!checkRateLimit('contact', 3)) {
        $error = 'Too many requests. Please try again after 10 minutes.';
    } else {
        $name    = clean($_POST['name']    ?? '');
        $email   = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
        $phone   = clean($_POST['phone']   ?? '');
        $subject = clean($_POST['subject'] ?? '');
        $message = clean($_POST['message'] ?? '');

        if (!$name || !$email || !$phone || !$message) {
            $error = 'Please fill in all required fields.';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error = 'Please enter a valid email address.';
        } else {
            $id = saveInquiry([
                'name' => $name, 'email' => $email, 'phone' => $phone,
                'destination' => $subject, 'message' => $message, 'source' => 'contact_page',
            ]);
            if ($id) {
                sendInquiryNotification(['name'=>$name,'email'=>$email,'phone'=>$phone,'destination'=>$subject,'message'=>$message]);
                regenerateCsrf();
                $success = 'Thank you! We have received your message and will contact you within 2 hours.';
            } else {
                $error = 'Something went wrong. Please email us directly at ' . APP_EMAIL;
            }
        }
    }
}

include 'includes/header.php';
?>

<section class="page-hero">
  <div class="container">
    <div class="section-badge">📞 Get In Touch</div>
    <h1>Contact Journey Karo</h1>
    <p>We're based in Bhuj, Gujarat — and we're always ready to help you plan the perfect trip.</p>
  </div>
</section>

<!-- Contact Info Cards -->
<section style="padding-block:3rem;background:white">
  <div class="container">
    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:1.5rem;margin-bottom:4rem">
      <?php
      $contacts = [
        ['📞','Call / WhatsApp','+91 9586605635','tel:+919586605635','Call Now'],
        ['✉️','Email Us','booking@journeykaro.com','mailto:'.APP_EMAIL,'Send Email'],
        ['📍','Visit Our Office','Near Science Center, Bhuj, Gujarat 370001','https://maps.google.com/?q=Bhuj+Gujarat','Get Directions'],
        ['🕐','Working Hours','Mon–Sun: 8:00 AM – 9:00 PM','#','We\'re Available'],
      ];
      foreach ($contacts as [$icon,$title,$info,$link,$cta]): ?>
      <div class="info-card" style="text-align:center" data-reveal>
        <div style="font-size:2.5rem;margin-bottom:1rem"><?= $icon ?></div>
        <h3 style="font-size:1rem;font-weight:800;color:var(--color-primary);margin-bottom:0.5rem"><?= $title ?></h3>
        <p style="font-size:0.875rem;margin-bottom:1rem;font-weight:500;color:var(--color-text)"><?= e($info) ?></p>
        <a href="<?= $link ?>" class="btn btn-primary btn-sm" target="<?= str_starts_with($link,'http')?'_blank':'_self' ?>"><?= $cta ?></a>
      </div>
      <?php endforeach ?>
    </div>

    <!-- Contact Form + Map -->
    <div style="display:grid;grid-template-columns:1fr;gap:3rem" class="contact-grid">
      <div>
        <h2 style="margin-bottom:0.5rem;color:var(--color-primary)">Send Us a Message</h2>
        <p style="margin-bottom:2rem">Fill out the form below and our team will get back to you within 2 hours during business hours.</p>

        <?php if ($success): ?>
        <div class="alert alert-success">✅ <?= e($success) ?></div>
        <?php elseif ($error): ?>
        <div class="alert alert-error">❌ <?= e($error) ?></div>
        <?php endif ?>

        <form id="contact-form" action="contact.php" method="POST" data-recaptcha="contact">
          <?= csrfField() ?>
          <input type="hidden" name="source" value="contact_page">

          <div class="form-row">
            <div class="form-group">
              <label for="c-name" class="form-label">Full Name *</label>
              <input type="text" id="c-name" name="name" class="form-control" placeholder="Your full name" required>
            </div>
            <div class="form-group">
              <label for="c-phone" class="form-label">Phone / WhatsApp *</label>
              <input type="tel" id="c-phone" name="phone" class="form-control" placeholder="10-digit mobile number" required>
            </div>
          </div>
          <div class="form-group">
            <label for="c-email" class="form-label">Email Address *</label>
            <input type="email" id="c-email" name="email" class="form-control" placeholder="your@email.com" required>
          </div>
          <div class="form-group">
            <label for="c-subject" class="form-label">Subject / Destination</label>
            <select id="c-subject" name="subject" class="form-control">
              <option value="General Inquiry">General Inquiry</option>
              <option value="Rann of Kutch / Bhuj">Rann of Kutch / Bhuj Package</option>
              <option value="Dwarka Somnath">Dwarka & Somnath Package</option>
              <option value="Gir Safari">Gir National Park Safari</option>
              <option value="Diu">Diu Tour Package</option>
              <option value="Statue of Unity">Statue of Unity Package</option>
              <option value="Custom Tour">Custom Tour Planning</option>
              <option value="Car Rental">Car Rental Inquiry</option>
              <option value="Hotel Booking">Hotel Booking</option>
              <option value="Flight Assistance">Flight Assistance</option>
              <option value="Other">Other</option>
            </select>
          </div>
          <div class="form-group">
            <label for="c-message" class="form-label">Your Message *</label>
            <textarea id="c-message" name="message" class="form-control" rows="5"
              placeholder="Tell us about your travel dates, number of guests, preferences..." required></textarea>
          </div>
          <input type="hidden" name="recaptcha_token" id="recaptcha_token">
          <button type="submit" class="btn btn-primary btn-lg w-full" id="contact-submit-btn">
            Send Message ✉️
          </button>
          <p style="font-size:0.75rem;color:var(--color-text-light);margin-top:0.75rem;text-align:center">
            🔒 We respect your privacy. Your data is never shared with third parties.
          </p>
        </form>
      </div>

      <div>
        <h2 style="margin-bottom:1rem;color:var(--color-primary)">Find Us on Map</h2>
        <div style="border-radius:var(--radius-xl);overflow:hidden;box-shadow:var(--shadow-md);height:400px;background:var(--color-bg);display:flex;align-items:center;justify-content:center">
          <iframe
            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3663.0316820717!2d69.6696!3d23.2505!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x39511e42090eb79b%3A0x2a67c0b2d2847720!2sBhuj%2C%20Gujarat!5e0!3m2!1sen!2sin!4v1"
            width="100%" height="400" style="border:0;display:block" allowfullscreen loading="lazy"
            referrerpolicy="no-referrer-when-downgrade" title="Journey Karo Office Location in Bhuj, Gujarat">
          </iframe>
        </div>

        <!-- Quick WhatsApp CTAs -->
        <div style="margin-top:2rem;display:flex;flex-direction:column;gap:0.75rem">
          <a href="https://wa.me/<?= APP_WHATSAPP ?>?text=Hello%20Journey%20Karo!%20I%20want%20to%20inquire%20about%20Gujarat%20tour%20packages."
             class="btn btn-whatsapp btn-lg" target="_blank" rel="noopener">
            💬 WhatsApp Us Instantly
          </a>
          <a href="tel:+91<?= APP_PHONE ?>" class="btn btn-outline btn-lg">
            📞 Call: +91 <?= APP_PHONE ?>
          </a>
        </div>
      </div>
    </div>
  </div>
</section>

<style>
@media(min-width:1024px){ .contact-grid { grid-template-columns: 1fr 1fr !important; } }
</style>

<?php include 'includes/footer.php'; ?>
