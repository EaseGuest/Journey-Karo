<?php
/**
 * Journey Karo — Database & App Configuration
 * Production-ready config for Hostinger Premium Hosting
 */

// ─── Environment ──────────────────────────────────────────
define('APP_ENV', 'production'); // 'development' | 'production'
define('APP_NAME', 'Journey Karo');
define('APP_URL',  'https://www.journeykaro.com');
define('APP_EMAIL','booking@journeykaro.com');
define('APP_PHONE','9586605635');
define('APP_WHATSAPP', '919586605635');

// ─── Database Credentials ─────────────────────────────────
// Update these with your Hostinger MySQL credentials
define('DB_HOST',    'localhost');
define('DB_NAME',    'journeykaro_db');
define('DB_USER',    'journeykaro_user');
define('DB_PASS',    'YOUR_DB_PASSWORD_HERE');
define('DB_CHARSET', 'utf8mb4');

// ─── Email (SMTP via PHPMailer or mail()) ────────────────
define('SMTP_HOST',   'smtp.hostinger.com');
define('SMTP_PORT',    587);
define('SMTP_USER',   'booking@journeykaro.com');
define('SMTP_PASS',   'YOUR_EMAIL_PASSWORD_HERE');
define('SMTP_FROM',   'booking@journeykaro.com');
define('SMTP_FROM_NAME', 'Journey Karo');

// ─── Admin Credentials (change after first login) ─────────
define('ADMIN_EMAIL',    'admin@journeykaro.com');
define('ADMIN_USERNAME', 'journeykaro_admin');

// ─── Google reCAPTCHA v3 ──────────────────────────────────
define('RECAPTCHA_SITE_KEY',   'YOUR_RECAPTCHA_SITE_KEY');
define('RECAPTCHA_SECRET_KEY', 'YOUR_RECAPTCHA_SECRET_KEY');

// ─── Security ─────────────────────────────────────────────
define('CSRF_TOKEN_NAME', 'jk_csrf_token');
define('SESSION_NAME',    'JourneyKaroSession');
define('SESSION_TIMEOUT', 3600); // 1 hour

// ─── Upload Settings ──────────────────────────────────────
define('UPLOAD_DIR',      __DIR__ . '/../uploads/');
define('UPLOAD_URL',      APP_URL . '/uploads/');
define('MAX_UPLOAD_SIZE', 5 * 1024 * 1024); // 5 MB
define('ALLOWED_TYPES',   ['image/jpeg', 'image/png', 'image/webp']);

// ─── SEO Defaults ────────────────────────────────────────
define('DEFAULT_META_TITLE',       'Journey Karo | Gujarat Tour Packages, Car Rental & Hotels in Bhuj');
define('DEFAULT_META_DESCRIPTION', 'Explore Gujarat with Journey Karo — Premium tour packages to Rann of Kutch, Dwarka, Somnath, Gir Safari, Diu & Statue of Unity. Based in Bhuj. Call +91 95866 05635.');
define('DEFAULT_META_KEYWORDS',    'Journey Karo, Gujarat tour, Rann of Kutch, Bhuj travel, Dwarka Somnath, Gir safari, Diu, Statue of Unity, car rental Bhuj');

// ─── Error Handling ───────────────────────────────────────
if (APP_ENV === 'production') {
    error_reporting(0);
    ini_set('display_errors', 0);
    ini_set('log_errors', 1);
    ini_set('error_log', __DIR__ . '/../logs/error.log');
} else {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
}

// ─── Session Configuration ───────────────────────────────
if (session_status() === PHP_SESSION_NONE) {
    session_name(SESSION_NAME);
    session_set_cookie_params([
        'lifetime' => SESSION_TIMEOUT,
        'path'     => '/',
        'domain'   => '',
        'secure'   => (APP_ENV === 'production'),
        'httponly' => true,
        'samesite' => 'Strict',
    ]);
    session_start();
}

// ─── Timezone ─────────────────────────────────────────────
date_default_timezone_set('Asia/Kolkata');
