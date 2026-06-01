<?php
/**
 * Journey Karo — Helper Functions
 * Utility functions used across the website.
 */

require_once __DIR__ . '/config.php';
require_once __DIR__ . '/db.php';

// ─── XSS Protection ───────────────────────────────────────
function e(string $str): string {
    return htmlspecialchars($str, ENT_QUOTES | ENT_HTML5, 'UTF-8');
}

function clean(string $input): string {
    return trim(strip_tags($input));
}

// ─── SEO Meta ─────────────────────────────────────────────
function getSeoMeta(string $page): array {
    $meta = dbFetch("SELECT * FROM seo_meta WHERE page_slug = ?", [$page]);
    return $meta ?: [
        'meta_title'       => DEFAULT_META_TITLE,
        'meta_description' => DEFAULT_META_DESCRIPTION,
        'meta_keywords'    => DEFAULT_META_KEYWORDS,
        'og_image'         => APP_URL . '/assets/images/og-default.jpg',
    ];
}

// ─── Settings ─────────────────────────────────────────────
function getSetting(string $key, string $default = ''): string {
    $row = dbFetch("SELECT setting_value FROM settings WHERE setting_key = ?", [$key]);
    return $row ? $row['setting_value'] : $default;
}

// ─── Destinations ─────────────────────────────────────────
function getDestinations(bool $featuredOnly = false, int $limit = 100): array {
    $sql = "SELECT * FROM destinations WHERE status = 'active' AND deleted_at IS NULL";
    if ($featuredOnly) $sql .= " AND is_featured = 1";
    $sql .= " ORDER BY sort_order ASC, id DESC LIMIT ?";
    return dbFetchAll($sql, [$limit]);
}

function getDestinationBySlug(string $slug): ?array {
    return dbFetch("SELECT * FROM destinations WHERE slug = ? AND status = 'active' AND deleted_at IS NULL", [$slug]);
}

// ─── Packages ─────────────────────────────────────────────
function getPackages(int $destinationId = 0, bool $featuredOnly = false, int $limit = 50): array {
    $params = [];
    $sql = "SELECT p.*, d.name AS destination_name, d.slug AS destination_slug
            FROM packages p
            LEFT JOIN destinations d ON p.destination_id = d.id
            WHERE p.status = 'active' AND p.deleted_at IS NULL";
    if ($destinationId) { $sql .= " AND p.destination_id = ?"; $params[] = $destinationId; }
    if ($featuredOnly)   { $sql .= " AND p.is_featured = 1"; }
    $sql .= " ORDER BY p.sort_order ASC, p.id DESC LIMIT ?";
    $params[] = $limit;
    return dbFetchAll($sql, $params);
}

function getPackageBySlug(string $slug): ?array {
    return dbFetch(
        "SELECT p.*, d.name AS destination_name, d.slug AS destination_slug
         FROM packages p LEFT JOIN destinations d ON p.destination_id = d.id
         WHERE p.slug = ? AND p.status = 'active' AND p.deleted_at IS NULL",
        [$slug]
    );
}

function getItineraries(int $packageId): array {
    return dbFetchAll("SELECT * FROM itineraries WHERE package_id = ? ORDER BY day_number ASC", [$packageId]);
}

// ─── Blogs ────────────────────────────────────────────────
function getBlogs(int $limit = 10, int $offset = 0): array {
    return dbFetchAll(
        "SELECT * FROM blogs WHERE status = 'published' AND deleted_at IS NULL ORDER BY published_at DESC LIMIT ? OFFSET ?",
        [$limit, $offset]
    );
}

function getBlogBySlug(string $slug): ?array {
    return dbFetch("SELECT * FROM blogs WHERE slug = ? AND status = 'published' AND deleted_at IS NULL", [$slug]);
}

function getBlogCount(): int {
    $row = dbFetch("SELECT COUNT(*) AS cnt FROM blogs WHERE status = 'published' AND deleted_at IS NULL");
    return (int)($row['cnt'] ?? 0);
}

// ─── Reviews ──────────────────────────────────────────────
function getReviews(int $limit = 10): array {
    return dbFetchAll(
        "SELECT * FROM reviews WHERE status = 'approved' AND deleted_at IS NULL ORDER BY created_at DESC LIMIT ?",
        [$limit]
    );
}

// ─── Gallery ──────────────────────────────────────────────
function getGallery(string $category = '', int $limit = 50): array {
    $params = [];
    $sql = "SELECT * FROM gallery WHERE status = 'active' AND deleted_at IS NULL";
    if ($category) { $sql .= " AND category = ?"; $params[] = $category; }
    $sql .= " ORDER BY sort_order ASC, id DESC LIMIT ?";
    $params[] = $limit;
    return dbFetchAll($sql, $params);
}

// ─── Inquiries ────────────────────────────────────────────
function saveInquiry(array $data): int|false {
    try {
        dbQuery(
            "INSERT INTO inquiries (name, email, phone, destination, package_name, travel_date, num_guests, budget, message, source, status, ip_address, created_at)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'new', ?, NOW())",
            [
                clean($data['name'] ?? ''),
                filter_var($data['email'] ?? '', FILTER_SANITIZE_EMAIL),
                clean($data['phone'] ?? ''),
                clean($data['destination'] ?? ''),
                clean($data['package_name'] ?? ''),
                $data['travel_date'] ?? null,
                (int)($data['num_guests'] ?? 1),
                clean($data['budget'] ?? ''),
                clean($data['message'] ?? ''),
                clean($data['source'] ?? 'website'),
                $_SERVER['REMOTE_ADDR'] ?? '',
            ]
        );
        return (int)dbLastId();
    } catch (PDOException $e) {
        error_log('Inquiry save error: ' . $e->getMessage());
        return false;
    }
}

// ─── Slug Generator ───────────────────────────────────────
function makeSlug(string $text): string {
    $text = strtolower($text);
    $text = preg_replace('/[^a-z0-9\s-]/', '', $text);
    $text = preg_replace('/[\s-]+/', '-', trim($text));
    return $text;
}

// ─── Image URL helper ─────────────────────────────────────
function imgUrl(string $path): string {
    if (str_starts_with($path, 'http')) return $path;
    return APP_URL . '/uploads/' . ltrim($path, '/');
}

// ─── Pagination ───────────────────────────────────────────
function paginate(int $total, int $perPage, int $currentPage, string $baseUrl): array {
    $totalPages = (int)ceil($total / $perPage);
    return [
        'total'        => $total,
        'per_page'     => $perPage,
        'current_page' => $currentPage,
        'total_pages'  => $totalPages,
        'prev'         => $currentPage > 1 ? $baseUrl . '?page=' . ($currentPage - 1) : null,
        'next'         => $currentPage < $totalPages ? $baseUrl . '?page=' . ($currentPage + 1) : null,
    ];
}

// ─── Format price ─────────────────────────────────────────
function formatPrice(int $amount): string {
    return '₹' . number_format($amount, 0, '.', ',');
}

// ─── Date Formatter ───────────────────────────────────────
function formatDate(string $date, string $format = 'j M Y'): string {
    return date($format, strtotime($date));
}

// ─── Redirect ─────────────────────────────────────────────
function redirect(string $url, int $code = 302): never {
    header("Location: $url", true, $code);
    exit;
}

// ─── JSON Response ────────────────────────────────────────
function jsonResponse(array $data, int $code = 200): never {
    http_response_code($code);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($data);
    exit;
}

// ─── Verify reCAPTCHA ─────────────────────────────────────
function verifyRecaptcha(string $token): bool {
    if (empty($token)) return false;
    $response = file_get_contents(
        'https://www.google.com/recaptcha/api/siteverify?secret=' .
        RECAPTCHA_SECRET_KEY . '&response=' . urlencode($token)
    );
    $data = json_decode($response, true);
    return ($data['success'] ?? false) && ($data['score'] ?? 0) >= 0.5;
}

// ─── Get Rating Stars HTML ────────────────────────────────
function starRating(float $rating): string {
    $stars = '';
    for ($i = 1; $i <= 5; $i++) {
        $stars .= $i <= $rating ? '★' : '☆';
    }
    return '<span style="color:var(--color-gold)">' . $stars . '</span>';
}

// ─── Truncate Text ────────────────────────────────────────
function truncate(string $text, int $length = 150): string {
    if (mb_strlen($text) <= $length) return $text;
    return mb_substr($text, 0, $length) . '…';
}

// ─── Anti-spam (simple rate limit by IP) ─────────────────
function checkRateLimit(string $action, int $maxAttempts = 5, int $windowSeconds = 600): bool {
    $key = 'rate_' . $action . '_' . md5($_SERVER['REMOTE_ADDR'] ?? '');
    $now = time();

    if (!isset($_SESSION[$key])) {
        $_SESSION[$key] = ['count' => 0, 'window_start' => $now];
    }

    if (($now - $_SESSION[$key]['window_start']) > $windowSeconds) {
        $_SESSION[$key] = ['count' => 0, 'window_start' => $now];
    }

    $_SESSION[$key]['count']++;
    return $_SESSION[$key]['count'] <= $maxAttempts;
}
