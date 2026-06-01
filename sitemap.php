<?php
/**
 * Dynamic XML sitemap
 */
require_once 'includes/config.php';
require_once 'includes/database.php';
require_once 'includes/functions.php';

header('Content-Type: application/xml; charset=utf-8');
$base = rtrim(APP_URL, '/');
$urls = [
    ['/', 'daily', '1.0'],
    ['/index.php', 'daily', '1.0'],
    ['/about.php', 'monthly', '0.8'],
    ['/destinations.php', 'weekly', '0.9'],
    ['/packages.php', 'weekly', '0.9'],
    ['/services.php', 'monthly', '0.8'],
    ['/gallery.php', 'weekly', '0.7'],
    ['/blog.php', 'weekly', '0.8'],
    ['/reviews.php', 'weekly', '0.7'],
    ['/faq.php', 'monthly', '0.6'],
    ['/contact.php', 'monthly', '0.8'],
    ['/custom-tour-planner.php', 'monthly', '0.9'],
];

try {
    if (dbIsConnected()) {
        foreach (dbFetchAll("SELECT slug, updated_at FROM destinations WHERE status='active' AND deleted_at IS NULL") as $r) {
            $urls[] = ['/destination-detail.php?slug=' . urlencode($r['slug']), 'weekly', '0.8'];
        }
        foreach (dbFetchAll("SELECT slug, updated_at FROM packages WHERE status='active' AND deleted_at IS NULL") as $r) {
            $urls[] = ['/package-detail.php?slug=' . urlencode($r['slug']), 'weekly', '0.8'];
        }
        foreach (dbFetchAll("SELECT slug, updated_at FROM blogs WHERE status='published' AND deleted_at IS NULL") as $r) {
            $urls[] = ['/blog-detail.php?slug=' . urlencode($r['slug']), 'monthly', '0.7'];
        }
    }
} catch (Throwable) {}

echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
foreach ($urls as [$path, $freq, $prio]) {
    echo "  <url><loc>{$base}{$path}</loc><changefreq>{$freq}</changefreq><priority>{$prio}</priority></url>\n";
}
echo '</urlset>';
