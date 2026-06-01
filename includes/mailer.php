<?php
/**
 * Journey Karo — Email Notifications (using PHP mail() or SMTP)
 * Sends inquiry notifications to admin and confirmation to customer.
 */

require_once __DIR__ . '/config.php';

/**
 * Send inquiry notification email to admin & customer
 */
function sendInquiryNotification(array $data): void {
    $adminEmail    = APP_EMAIL;
    $customerEmail = filter_var($data['email'] ?? '', FILTER_SANITIZE_EMAIL);
    $customerName  = htmlspecialchars($data['name'] ?? 'Traveler', ENT_QUOTES);
    $destination   = htmlspecialchars($data['destination'] ?? 'Gujarat', ENT_QUOTES);
    $phone         = htmlspecialchars($data['phone'] ?? '', ENT_QUOTES);
    $message       = htmlspecialchars($data['message'] ?? '', ENT_QUOTES);
    $travelDate    = htmlspecialchars($data['travel_date'] ?? 'Not specified', ENT_QUOTES);
    $guests        = (int)($data['num_guests'] ?? 1);
    $package       = htmlspecialchars($data['package_name'] ?? 'Not specified', ENT_QUOTES);
    $timestamp     = date('d M Y, h:i A');

    // ── Email to Admin ──────────────────────────────────
    $adminSubject = "🔔 New Inquiry from {$customerName} — Journey Karo";
    $adminBody = <<<HTML
<!DOCTYPE html>
<html>
<head><meta charset="UTF-8"><style>
  body { font-family: 'Segoe UI', sans-serif; background: #f4f4f4; margin: 0; padding: 20px; }
  .card { background: white; border-radius: 12px; max-width: 600px; margin: auto; overflow: hidden; box-shadow: 0 4px 20px rgba(0,0,0,0.1); }
  .header { background: linear-gradient(135deg, #0d2b6e, #1a3d8f); padding: 28px 32px; }
  .header h1 { color: white; font-size: 22px; margin: 0; }
  .header p  { color: rgba(255,255,255,0.75); margin: 4px 0 0; font-size: 14px; }
  .body { padding: 32px; }
  .row { display: flex; gap: 16px; margin-bottom: 16px; padding-bottom: 16px; border-bottom: 1px solid #f0f0f0; }
  .label { font-size: 12px; color: #888; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 4px; }
  .value { font-size: 15px; color: #1e293b; font-weight: 600; }
  .message-box { background: #f8f9fc; border-radius: 8px; padding: 16px; margin-top: 16px; font-size: 14px; color: #64748b; line-height: 1.6; }
  .cta { display: inline-block; margin-top: 24px; padding: 12px 28px; background: #25D366; color: white; border-radius: 8px; font-weight: 700; text-decoration: none; font-size: 15px; }
  .footer { background: #f8f9fc; padding: 20px 32px; text-align: center; font-size: 12px; color: #94a3b8; }
</style></head>
<body>
<div class="card">
  <div class="header">
    <h1>✈️ New Travel Inquiry</h1>
    <p>Received on {$timestamp}</p>
  </div>
  <div class="body">
    <div class="row">
      <div style="flex:1"><div class="label">Customer Name</div><div class="value">{$customerName}</div></div>
      <div style="flex:1"><div class="label">Phone</div><div class="value">{$phone}</div></div>
    </div>
    <div class="row">
      <div style="flex:1"><div class="label">Email</div><div class="value">{$customerEmail}</div></div>
      <div style="flex:1"><div class="label">Guests</div><div class="value">{$guests} Person(s)</div></div>
    </div>
    <div class="row">
      <div style="flex:1"><div class="label">Destination</div><div class="value">{$destination}</div></div>
      <div style="flex:1"><div class="label">Travel Date</div><div class="value">{$travelDate}</div></div>
    </div>
    <div><div class="label">Package Interest</div><div class="value">{$package}</div></div>
    <div class="message-box">{$message}</div>
    <a class="cta" href="https://wa.me/{$phone}?text=Hello%20{$customerName}!%20Thank%20you%20for%20your%20interest%20in%20Journey%20Karo.%20We%20received%20your%20inquiry%20for%20{$destination}.%20Please%20allow%20us%20to%20share%20the%20details.">
      📱 Reply on WhatsApp
    </a>
  </div>
  <div class="footer">Journey Karo &bull; Bhuj, Gujarat &bull; +91 95866 05635 &bull; booking@journeykaro.com</div>
</div>
</body>
</html>
HTML;

    sendMail($adminEmail, $adminSubject, $adminBody, $customerEmail, $customerName);

    // ── Confirmation to Customer ─────────────────────────
    if ($customerEmail) {
        $custSubject = "✅ Your Journey Karo Inquiry — We'll Contact You Soon!";
        $custBody = <<<HTML
<!DOCTYPE html>
<html>
<head><meta charset="UTF-8"><style>
  body { font-family: 'Segoe UI', sans-serif; background: #f4f4f4; margin: 0; padding: 20px; }
  .card { background: white; border-radius: 12px; max-width: 600px; margin: auto; overflow: hidden; box-shadow: 0 4px 20px rgba(0,0,0,0.1); }
  .header { background: linear-gradient(135deg, #0d2b6e, #1a3d8f); padding: 32px; text-align: center; }
  .logo { font-size: 28px; font-weight: 900; color: white; letter-spacing: -0.02em; }
  .tagline { color: #c9972a; font-size: 13px; font-weight: 600; margin-top: 4px; }
  .body { padding: 40px 32px; text-align: center; }
  .hi { font-size: 22px; font-weight: 800; color: #0d2b6e; margin-bottom: 12px; }
  .para { font-size: 15px; color: #64748b; line-height: 1.7; margin-bottom: 24px; }
  .highlights { display: flex; gap: 12px; justify-content: center; flex-wrap: wrap; margin-bottom: 32px; }
  .hl { background: #eef2ff; border-radius: 8px; padding: 12px 20px; font-size: 13px; font-weight: 700; color: #0d2b6e; }
  .cta-wa { display: inline-block; padding: 14px 32px; background: #25D366; color: white; border-radius: 10px; font-weight: 800; text-decoration: none; font-size: 15px; margin-bottom: 16px; }
  .cta-call { display: inline-block; padding: 14px 32px; background: #0d2b6e; color: white; border-radius: 10px; font-weight: 800; text-decoration: none; font-size: 15px; margin-left: 12px; margin-bottom: 16px; }
  .footer { background: #f8f9fc; padding: 24px 32px; text-align: center; font-size: 12px; color: #94a3b8; }
</style></head>
<body>
<div class="card">
  <div class="header">
    <div class="logo">✈️ JOURNEY KARO</div>
    <div class="tagline">Explore Gujarat with Local Experts</div>
  </div>
  <div class="body">
    <div class="hi">Hello, {$customerName}! 🙏</div>
    <p class="para">
      Thank you for reaching out to us! We've received your inquiry for <strong>{$destination}</strong> 
      and our travel expert will contact you within <strong>2 hours</strong> via WhatsApp and Email.
    </p>
    <div class="highlights">
      <div class="hl">📍 Destination: {$destination}</div>
      <div class="hl">📅 Travel Date: {$travelDate}</div>
      <div class="hl">👥 Guests: {$guests}</div>
    </div>
    <p class="para">Need immediate assistance? Connect with us right now:</p>
    <a class="cta-wa" href="https://wa.me/919586605635?text=Hi!%20I'm%20{$customerName}.%20I%20submitted%20an%20inquiry%20for%20{$destination}.">💬 WhatsApp Us</a>
    <a class="cta-call" href="tel:+919586605635">📞 Call Us</a>
  </div>
  <div class="footer">
    Journey Karo &bull; Near Science Center, Bhuj, Gujarat 370001<br>
    +91 95866 05635 &bull; booking@journeykaro.com &bull; www.journeykaro.com<br>
    <br>© 2025 Journey Karo. All Rights Reserved.
  </div>
</div>
</body>
</html>
HTML;
        sendMail($customerEmail, $custSubject, $custBody);
    }
}

/**
 * Core mail sending function using PHP mail() with MIME headers.
 * For production, replace with PHPMailer/SMTP for reliability.
 */
function sendMail(string $to, string $subject, string $htmlBody, string $replyTo = '', string $replyName = ''): bool {
    $from     = SMTP_FROM;
    $fromName = SMTP_FROM_NAME;

    $headers  = "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
    $headers .= "From: {$fromName} <{$from}>\r\n";
    $headers .= "X-Mailer: PHP/" . phpversion() . "\r\n";

    if ($replyTo) {
        $replyName = $replyName ?: $replyTo;
        $headers .= "Reply-To: {$replyName} <{$replyTo}>\r\n";
    }

    return mail($to, $subject, $htmlBody, $headers);
}
