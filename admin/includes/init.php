<?php
/**
 * Admin bootstrap
 */
require_once dirname(__DIR__, 2) . '/includes/config.php';
require_once dirname(__DIR__, 2) . '/includes/database.php';
require_once dirname(__DIR__, 2) . '/includes/functions.php';
require_once dirname(__DIR__, 2) . '/includes/csrf.php';
require_once dirname(__DIR__, 2) . '/includes/auth.php';

function adminUrl(string $path = ''): string {
    $base = rtrim(APP_URL, '/') . '/admin';
    return $path ? $base . '/' . ltrim($path, '/') : $base;
}

function adminAsset(string $file): string {
    return adminUrl('assets/' . ltrim($file, '/'));
}
