<?php
require_once 'includes/config.php';
require_once 'includes/db.php';
require_once 'includes/functions.php';

$page      = 'blog';
$pageTitle = 'Gujarat Travel Blog | Tips, Guides & Stories | Journey Karo';
$meta      = getSeoMeta($page);

$perPage = 6;
$currentPage = max(1,(int)($_GET['page'] ?? 1));
$offset = ($currentPage - 1) * $perPage;

// Try DB first, fallback to static
require_once 'includes/content-helpers.php';
$blogs_db = tryDb(fn() => getBlogs($perPage, $offset), []);
$totalBlogs = tryDb(fn() => getBlogCount(), 0);

// Static blog data fallback
$staticBlogs = [
  ['rann-of-kutch-guide-2025','The Ultimate Guide to Rann of Kutch 2025','https://images.unsplash.com/photo-1590050752117-238cb0fb12b1?auto=format&fit=crop&w=800&q=80','Everything you need to know about visiting the White Rann of Kutch — best time to visit, how to reach, where to stay, what to eat, and the magical full moon experience.','Rajesh Vaghasiya','2025-01-15','Gujarat Guides',15,'rann'],
  ['top-10-gir-safari-tips','Top 10 Tips for Your First Gir Lion Safari','https://images.unsplash.com/photo-1615959189197-48400be37e26?auto=format&fit=crop&w=800&q=80','Planning your first Gir National Park safari? Here\'s everything you need to know to maximize your chances of spotting the majestic Asiatic lion.','Mehul Raval','2025-01-08','Wildlife',10,'wildlife'],
  ['dwarka-pilgrimage-complete','Complete Dwarka Pilgrimage Guide — Temples, Tips & Travel','https://images.unsplash.com/photo-1605649487212-47bdab064df7?auto=format&fit=crop&w=800&q=80','Dwarka is one of India\'s most sacred cities. This comprehensive guide covers the Dwarkadhish temple, Nageshwar Jyotirlinga, Beyt Dwarka, and all the rituals to observe.','Priya Joshi','2024-12-20','Spiritual',12,'temples'],
  ['diu-best-beaches','Diu Island: Best Beaches, Things to Do & Where to Stay','https://images.unsplash.com/photo-1590523277543-a94d2e4eb00b?auto=format&fit=crop&w=800&q=80','Diu is Gujarat\'s best-kept secret — a tranquil island with colonial forts, palm-lined beaches, and the freshest seafood you\'ll ever taste. Here\'s our complete travel guide.','Kavita Shah','2024-12-05','Beach Travel',8,'beaches'],
  ['statue-of-unity-visiting','Visiting Statue of Unity — Complete Visitor Guide 2025','https://images.unsplash.com/photo-1627856013091-fed6e4e30025?auto=format&fit=crop&w=800&q=80','The world\'s tallest statue at 182 metres towers over the Narmada River. Here\'s everything you need to know before you visit — tickets, timings, what to see & do.','Rajesh Vaghasiya','2024-11-18','Tourist Guides',9,'monuments'],
  ['gujarat-food-guide','Gujarat Food Guide: 20 Must-Try Dishes & Where to Find Them','https://images.unsplash.com/photo-1567364729-bb0eb28a2918?auto=format&fit=crop&w=800&q=80','From the iconic Kutchi thali to Bhuj\'s famous mawa sweets and Mandvi\'s beachside seafood — a complete food lover\'s guide to eating your way through Gujarat.','Priya Joshi','2024-11-02','Food & Culture',11,'food'],
];

$blogsToShow = $blogs_db ?: $staticBlogs;
$paginateInfo = paginate($totalBlogs ?: count($staticBlogs), $perPage, $currentPage, 'blog.php');

include 'includes/header.php';
?>

<section class="page-hero">
  <div class="container">
    <div class="section-badge">📝 Travel Blog</div>
    <h1>Gujarat Travel Stories & Guides</h1>
    <p>Insider tips, destination guides, and travel stories from our local experts and happy travelers.</p>
  </div>
</section>

<section class="section-padding" style="background:var(--color-bg)">
  <div class="container">

    <!-- Featured Post -->
    <?php $featured = $blogsToShow[0] ?? null; if ($featured): ?>
    <div style="display:grid;grid-template-columns:1fr;gap:2rem;background:white;border-radius:var(--radius-2xl);overflow:hidden;box-shadow:var(--shadow-md);margin-bottom:3rem;border:1px solid var(--color-border)" class="featured-blog" data-reveal>
      <div style="position:relative;height:300px;overflow:hidden">
        <img src="<?= $featured['image'] ?? $featured[1] ?>" alt="<?= e($featured['title'] ?? $featured[0]) ?>"
             style="width:100%;height:100%;object-fit:cover" loading="lazy">
        <div style="position:absolute;inset:0;background:linear-gradient(to top,rgba(8,29,78,0.6),transparent 50%)"></div>
        <span style="position:absolute;top:1.5rem;left:1.5rem;background:var(--color-gold);color:white;padding:6px 14px;border-radius:var(--radius-full);font-size:0.75rem;font-weight:700">Featured</span>
      </div>
      <div style="padding:2rem">
        <div style="display:flex;gap:1rem;flex-wrap:wrap;margin-bottom:1rem;font-size:0.8rem;color:var(--color-text-muted)">
          <span>📂 <?= e($featured['category'] ?? $featured[5]) ?></span>
          <span>👤 <?= e($featured['author'] ?? $featured[3]) ?></span>
          <span>📅 <?= formatDate($featured['published_at'] ?? $featured[4]) ?></span>
          <span>⏱️ <?= $featured['reading_time'] ?? $featured[6] ?> min read</span>
        </div>
        <h2 style="font-size:1.5rem;font-weight:900;color:var(--color-primary);margin-bottom:1rem">
          <?= e($featured['title'] ?? $featured[1]) ?>
        </h2>
        <p style="margin-bottom:1.5rem"><?= e(truncate($featured['excerpt'] ?? $featured[2], 200)) ?></p>
        <a href="blog-detail.php?slug=<?= e($featured['slug'] ?? $featured[0]) ?>" class="btn btn-primary">Read Full Article →</a>
      </div>
    </div>
    <style>
    @media(min-width:1024px){ .featured-blog { grid-template-columns: 1fr 1fr !important; } .featured-blog > :first-child { height: auto !important; min-height: 400px; } }
    </style>
    <?php endif ?>

    <!-- Blog Grid -->
    <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(300px,1fr));gap:2rem">
      <?php foreach (array_slice($blogsToShow, 1) as $i => $blog): ?>
      <?php
        $bSlug    = $blog['slug']         ?? $blog[0];
        $bTitle   = $blog['title']        ?? $blog[1];
        $bImg     = $blog['featured_image'] ?? $blog['cover_image'] ?? $blog[2] ?? '';
        $bExcerpt = $blog['excerpt']      ?? $blog[3] ?? '';
        $bAuthor  = $blog['author_name']  ?? $blog['author'] ?? $blog[4] ?? '';
        $bDate    = $blog['published_at'] ?? $blog[5] ?? '';
        $bCat     = $blog['category']     ?? $blog[6] ?? '';
        $bReadTime= $blog['read_time_minutes'] ?? $blog['reading_time'] ?? $blog[7] ?? '';
      ?>
      <article class="package-card" data-reveal data-delay="<?= ($i % 3) * 100 ?>">
        <div class="package-card-img" style="height:200px">
          <img src="<?= $bImg ?>" alt="<?= e($bTitle) ?>" loading="lazy">
          <span class="package-badge"><?= e($bCat) ?></span>
        </div>
        <div class="package-card-body">
          <div style="display:flex;gap:0.75rem;font-size:0.75rem;color:var(--color-text-muted);margin-bottom:0.75rem;flex-wrap:wrap">
            <?php if ($bAuthor)   echo "<span>👤 {$bAuthor}</span>"; ?>
            <?php if ($bDate)     echo "<span>📅 ".formatDate($bDate)."</span>"; ?>
            <?php if ($bReadTime) echo "<span>⏱️ {$bReadTime} min</span>"; ?>
          </div>
          <h3 class="package-card-title"><?= e($bTitle) ?></h3>
          <p style="font-size:0.825rem;color:var(--color-text-muted);margin-bottom:1.25rem;line-height:1.65">
            <?= e(truncate($bExcerpt, 120)) ?>
          </p>
          <div class="package-card-footer">
            <a href="blog-detail.php?slug=<?= e($bSlug) ?>" class="btn btn-outline btn-sm">Read More →</a>
          </div>
        </div>
      </article>
      <?php endforeach ?>
    </div>

    <!-- Pagination -->
    <?php if ($paginateInfo['total_pages'] > 1): ?>
    <div class="pagination">
      <?php if ($paginateInfo['prev']): ?>
      <a href="<?= $paginateInfo['prev'] ?>" class="page-btn">← Prev</a>
      <?php endif ?>
      <?php for ($p = 1; $p <= $paginateInfo['total_pages']; $p++): ?>
      <a href="blog.php?page=<?= $p ?>" class="page-btn <?= $p === $currentPage ? 'active' : '' ?>"><?= $p ?></a>
      <?php endfor ?>
      <?php if ($paginateInfo['next']): ?>
      <a href="<?= $paginateInfo['next'] ?>" class="page-btn">Next →</a>
      <?php endif ?>
    </div>
    <?php endif ?>

  </div>
</section>

<?php include 'includes/footer.php'; ?>
