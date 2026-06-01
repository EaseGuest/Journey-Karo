<?php
require_once 'includes/config.php';
require_once 'includes/database.php';
require_once 'includes/functions.php';
require_once 'includes/content-helpers.php';

$slug = clean($_GET['slug'] ?? '');
$post = $slug ? tryDb(fn() => getBlogBySlug($slug), null) : null;

if (!$post) {
    http_response_code(404);
    header('Location: blog.php');
    exit;
}

$pageTitle = $post['meta_title'] ?: $post['title'];
$meta = [
    'meta_title' => $pageTitle,
    'meta_description' => $post['meta_description'] ?? truncate($post['excerpt'] ?? '', 160),
    'og_image' => $post['featured_image'] ?? '',
];

include 'includes/header.php';
?>

<article class="section-padding">
  <div class="container" style="max-width:800px">
    <div class="section-badge"><?= e($post['category'] ?? 'Blog') ?></div>
    <h1><?= e($post['title']) ?></h1>
    <p style="color:var(--color-text-muted);margin-bottom:2rem">
      By <?= e($post['author_name'] ?? 'Journey Karo') ?> · <?= e(formatDate($post['published_at'] ?? $post['created_at'])) ?>
      · <?= (int)($post['read_time_minutes'] ?? 5) ?> min read
    </p>
    <?php if (!empty($post['featured_image'])): ?>
    <img src="<?= e($post['featured_image']) ?>" alt="<?= e($post['title']) ?>" style="width:100%;border-radius:var(--radius-xl);margin-bottom:2rem" loading="lazy">
    <?php endif; ?>
    <div class="blog-content"><?= $post['content'] ?></div>
    <a href="blog.php" class="btn btn-outline" style="margin-top:2rem">← Back to Blog</a>
  </div>
</article>

<?php include 'includes/footer.php'; ?>
