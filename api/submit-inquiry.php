<?php
/**
 * Journey Karo — Submit Inquiry API
 * Accepts POST requests, validates, saves to DB, sends emails.
 */

header('Content-Type: application/json; charset=utf-8');
header('X-Content-Type-Options: nosniff');

// Only allow POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed.']);
    exit;
}

require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/csrf.php';
require_once __DIR__ . '/../includes/mailer.php';

// ─── Rate Limiting ────────────────────────────────────────
if (!checkRateLimit('inquiry', 5, 600)) {
    http_response_code(429);
    echo json_encode(['success' => false, 'message' => 'Too many submissions. Please try again in 10 minutes or call us directly at +91 9586605635.']);
    exit;
}

// ─── CSRF Validation ─────────────────────────────────────
// Only validate CSRF if token is present (AJAX requests may skip)
if (!empty($_POST[CSRF_TOKEN_NAME])) {
    validateCsrf();
}

// ─── Collect & Sanitize Inputs ───────────────────────────
$name        = clean($_POST['name']        ?? '');
$email       = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
$phone       = clean($_POST['phone']       ?? '');
$destination = clean($_POST['destination'] ?? '');
$package     = clean($_POST['package_name']?? clean($_POST['package'] ?? ''));
$travelDate  = $_POST['travel_date']       ?? null;
$numGuests   = (int)($_POST['num_guests']  ?? 1);
$message     = clean($_POST['message']     ?? '');
$source      = clean($_POST['source']      ?? 'website');

// ─── Validation ───────────────────────────────────────────
$errors = [];

if (mb_strlen($name) < 2)           $errors[] = 'Name must be at least 2 characters.';
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Please provide a valid email address.';
if (!preg_match('/^[6-9]\d{9}$/', preg_replace('/\D/', '', $phone))) $errors[] = 'Please provide a valid 10-digit Indian mobile number.';
if (mb_strlen($message) > 2000)     $errors[] = 'Message is too long (max 2000 characters).';

// Travel date must not be in the past
if ($travelDate && strtotime($travelDate) < strtotime('today')) {
    $travelDate = null; // Reset silently
}

if ($errors) {
    http_response_code(422);
    echo json_encode(['success' => false, 'message' => implode(' ', $errors)]);
    exit;
}

// ─── Verify reCAPTCHA (if token provided) ────────────────
$recaptchaToken = clean($_POST['recaptcha_token'] ?? '');
if ($recaptchaToken && !verifyRecaptcha($recaptchaToken)) {
    http_response_code(422);
    echo json_encode(['success' => false, 'message' => 'Security verification failed. Please refresh the page and try again.']);
    exit;
}

// ─── Save to Database ─────────────────────────────────────
$inquiryId = saveInquiry([
    'name'         => $name,
    'email'        => $email,
    'phone'        => $phone,
    'destination'  => $destination,
    'package_name' => $package,
    'travel_date'  => $travelDate,
    'num_guests'   => $numGuests,
    'message'      => $message,
    'source'       => $source,
]);

if (!$inquiryId) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Failed to save your inquiry. Please call us directly at +91 9586605635.']);
    exit;
}

// ─── Send Email Notifications ─────────────────────────────
sendInquiryNotification([
    'name'         => $name,
    'email'        => $email,
    'phone'        => $phone,
    'destination'  => $destination ?: 'Gujarat',
    'package_name' => $package,
    'travel_date'  => $travelDate ? date('d M Y', strtotime($travelDate)) : 'Flexible',
    'num_guests'   => $numGuests,
    'message'      => $message,
]);

// ─── Regenerate CSRF token ────────────────────────────────
regenerateCsrf();

// ─── Success Response ─────────────────────────────────────
echo json_encode([
    'success'    => true,
    'message'    => 'Thank you! Your inquiry has been received. We will contact you within 2 hours via WhatsApp and Email.',
    'inquiry_id' => $inquiryId,
    'whatsapp_url' => 'https://wa.me/' . APP_WHATSAPP . '?text=' . rawurlencode(
        "Hello Journey Karo! My name is {$name} and I'm interested in " .
        ($destination ?: 'a Gujarat tour') . ". (Inquiry #{$inquiryId})"
    ),
]);
