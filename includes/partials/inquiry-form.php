<?php
/** Inquiry form partial — requires csrf */
$inquirySource = $inquirySource ?? 'website';
$inquiryDestination = $inquiryDestination ?? '';
?>
<form id="inquiry-form" class="form-card" method="POST" action="api/submit-inquiry.php" data-recaptcha="inquiry">
  <?= csrfField() ?>
  <input type="hidden" name="source" value="<?= e($inquirySource) ?>">
  <?php if (!empty($inquiryPackage)): ?><input type="hidden" name="package_name" value="<?= e($inquiryPackage) ?>"><?php endif; ?>
  <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem">
    <div class="form-group">
      <label for="inq-name">Full Name *</label>
      <input type="text" id="inq-name" name="name" class="form-control" placeholder="Your name" required>
    </div>
    <div class="form-group">
      <label for="inq-phone">Mobile *</label>
      <input type="tel" id="inq-phone" name="phone" class="form-control" placeholder="10-digit mobile" required>
    </div>
  </div>
  <div class="form-group">
    <label for="inq-email">Email *</label>
    <input type="email" id="inq-email" name="email" class="form-control" placeholder="your@email.com" required>
  </div>
  <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem">
    <div class="form-group">
      <label for="inq-destination">Destination</label>
      <input type="text" id="inq-destination" name="destination" class="form-control" value="<?= e($inquiryDestination) ?>" placeholder="e.g. Rann of Kutch">
    </div>
    <div class="form-group">
      <label for="inq-date">Travel date</label>
      <input type="date" id="inq-date" name="travel_date" class="form-control">
    </div>
  </div>
  <div class="form-group">
    <label for="inq-message">Message</label>
    <textarea id="inq-message" name="message" class="form-control" rows="3" placeholder="Tell us about your trip…"></textarea>
  </div>
  <input type="hidden" name="recaptcha_token" id="recaptcha_token">
  <button type="submit" class="btn btn-gold w-full" style="justify-content:center">Send Inquiry →</button>
</form>
