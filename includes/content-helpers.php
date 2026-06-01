<?php
/**
 * Content rendering helpers (DB rows → view data)
 */

function contentList(?string $jsonOrText): array {
    if (!$jsonOrText) return [];
    $decoded = json_decode($jsonOrText, true);
    if (is_array($decoded)) return $decoded;
    return array_values(array_filter(array_map('trim', preg_split('/\r\n|\r|\n/', $jsonOrText))));
}

function destinationImage(array $d): string {
    $img = $d['featured_image'] ?? '';
    return $img ? (str_starts_with($img, 'http') ? $img : imgUrl($img)) : '';
}

function packageImage(array $p): string {
    $img = $p['featured_image'] ?? '';
    return $img ? (str_starts_with($img, 'http') ? $img : imgUrl($img)) : '';
}

function tryDb(callable $fn, $fallback) {
    try {
        if (!function_exists('dbIsConnected') || !dbIsConnected()) return $fallback;
        return $fn();
    } catch (Throwable) {
        return $fallback;
    }
}
