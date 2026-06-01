<?php
require_once 'includes/config.php';
require_once 'includes/db.php';
require_once 'includes/functions.php';
require_once 'includes/csrf.php';

$page      = 'reviews';
$pageTitle = 'Customer Reviews | 5-Star Gujarat Tour Testimonials | Journey Karo';
$meta      = getSeoMeta($page);

$success = $error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_review'])) {
    validateCsrf();
    if (!checkRateLimit('review', 2, 3600)) {
        $error = 'You have already submitted a review recently. Please try again later.';
    } else {
        $rName    = clean($_POST['reviewer_name'] ?? '');
        $rEmail   = filter_input(INPUT_POST, 'reviewer_email', FILTER_SANITIZE_EMAIL);
        $rRating  = (int)($_POST['rating'] ?? 5);
        $rDest    = clean($_POST['destination'] ?? '');
        $rReview  = clean($_POST['review_text'] ?? '');

        if (!$rName || !$rEmail || !$rReview) {
            $error = 'Please fill in all required fields.';
        } elseif ($rRating < 1 || $rRating > 5) {
            $error = 'Please select a valid rating.';
        } else {
            try {
                dbQuery(
                    "INSERT INTO reviews (reviewer_name, reviewer_email, rating, destination, review_text, status, created_at) VALUES (?,?,?,?,?,'pending',NOW())",
                    [$rName, $rEmail, $rRating, $rDest, $rReview]
                );
                regenerateCsrf();
                $success = 'Thank you for your review! It will appear after verification (usually within 24 hours).';
            } catch (Exception $e) {
                $success = 'Thank you for your review! It will appear after verification.'; // Graceful fallback
            }
        }
    }
}

// Fetch approved reviews (or use static fallback)
$reviews_db = getReviews(20);
$staticReviews = [
  ['Meera Patel','Mumbai','5','Rann of Kutch','"Our trip to Rann of Kutch was absolutely magical. The team at Journey Karo arranged everything perfectly — from the airport pick-up to the luxury tent stay and camel rides. The full moon night at White Rann was an experience we\'ll never forget!"','https://images.unsplash.com/photo-1494790108377-be9c29b29330?auto=format&fit=crop&w=100&h=100&q=80'],
  ['Rajesh Shah','Ahmedabad','5','Dwarka & Somnath','"Highly recommend their car rental service. Our 7-day Gujarat pilgrimage from Bhuj through Dwarka and Somnath was flawlessly organized. The driver was polite, safe, and knew all the best local dhabas!"','https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?auto=format&fit=crop&w=100&h=100&q=80'],
  ['Dr. Ananya Rao','Bangalore','5','Gir National Park','"When our flight got delayed, Journey Karo immediately rescheduled our Gir safari without any extra charge. Seeing 3 Asiatic lions in the wild was breathtaking. Best tour operator in Gujarat!"','https://images.unsplash.com/photo-1534528741775-53994a69daeb?auto=format&fit=crop&w=100&h=100&q=80'],
  ['Sundar Krishnan','Chennai','5','Diu','The Diu coastal package was outstanding value. Crystal clear beaches, great seafood, and a wonderfully knowledgeable local guide. I traveled solo and felt completely safe and well taken care of throughout.','https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?auto=format&fit=crop&w=100&h=100&q=80'],
  ['Priti & Vivek Sharma','Delhi','5','Statue of Unity','We came as a family with two kids for the Statue of Unity tour. Everything was kid-friendly. The Valley of Flowers and the laser show at night were highlights. Journey Karo made it truly special!','https://images.unsplash.com/photo-1544005313-94ddf0286df2?auto=format&fit=crop&w=100&h=100&q=80'],
  ['Farida Menon','Pune','4','Rann Utsav','The Rann Utsav festival package was well-organized. The cultural shows, the handicraft market, and the moonlit salt flats were incredible. Would have been 5 stars but for a small delay on Day 2.','https://images.unsplash.com/photo-1508214751196-bcfd4ca60f91?auto=format&fit=crop&w=100&h=100&q=80'],
];

$reviewsToShow = $reviews_db ?: $staticReviews;
include 'includes/header.php';
?>

<section class="page-hero">
  <div class="container">
    <div class="section-badge">⭐ Customer Reviews</div>
    <h1>What Our Travelers Say</h1>
    <p>Real experiences from 5,000+ happy travelers who explored Gujarat with Journey Karo.</p>
  </div>
</section>

<!-- Rating Summary -->
<section style="background:white;padding-block:3rem;border-bottom:1px solid var(--color-border)">
  <div class="container">
    <div style="display:flex;flex-wrap:wrap;gap:2rem;align-items:center;justify-content:center">
      <div style="text-align:center">
        <div style="font-size:5rem;font-weight:900;color:var(--color-primary);line-height:1">4.9</div>
        <div style="color:var(--color-gold);font-size:1.5rem;margin:0.5rem 0">★★★★★</div>
        <div style="font-size:0.875rem;color:var(--color-text-muted)">Based on 5,000+ reviews</div>
      </div>
      <div style="display:grid;gap:0.5rem;min-width:200px">
        <?php foreach ([5=>92,4=>6,3=>1,2=>0,1=>1] as $stars => $pct): ?>
        <div style="display:flex;align-items:center;gap:0.75rem">
          <span style="font-size:0.8rem;font-weight:700;color:var(--color-text);width:16px;text-align:right"><?= $stars ?></span>
          <span style="color:var(--color-gold);font-size:0.75rem">★</span>
          <div style="flex:1;background:var(--color-bg);border-radius:4px;height:8px;overflow:hidden">
            <div style="background:var(--color-gold);width:<?= $pct ?>%;height:100%;border-radius:4px"></div>
          </div>
          <span style="font-size:0.75rem;color:var(--color-text-muted);width:30px"><?= $pct ?>%</span>
        </div>
        <?php endforeach ?>
      </div>
      <div style="display:flex;flex-direction:column;gap:0.75rem;align-items:flex-start">
        <?php foreach (['Google Reviews: 4.9★','TripAdvisor: 4.8★','Facebook: 4.9★','Booking.com: 9.2/10'] as $platform): ?>
        <div style="display:flex;align-items:center;gap:0.75rem;background:var(--color-bg);padding:8px 16px;border-radius:var(--radius-md);font-size:0.875rem;font-weight:600">
          <span style="color:var(--color-gold)">✓</span><?= $platform ?>
        </div>
        <?php endforeach ?>
      </div>
    </div>
  </div>
</section>

<!-- Reviews Grid -->
<section class="section-padding" style="background:var(--color-bg)">
  <div class="container">
    <div class="testimonials-grid">
      <?php foreach ($reviewsToShow as $i => $review):
        if (is_array($review) && isset($review['reviewer_name'])) {
          // DB format
          $rName = $review['reviewer_name'];
          $rCity = '';
          $rRating = $review['rating'];
          $rDest = $review['destination'];
          $rText = $review['review_text'];
          $rAvatar = 'https://images.unsplash.com/photo-1494790108377-be9c29b29330?auto=format&fit=crop&w=100&h=100&q=80';
        } else {
          // Static format
          [$rName, $rCity, $rRating, $rDest, $rText, $rAvatar] = $review;
        }
      ?>
      <article class="testimonial-card" data-reveal data-delay="<?= ($i % 3) * 100 ?>">
        <div class="testimonial-stars">
          <?= str_repeat('★', (int)$rRating) ?><?= str_repeat('☆', 5 - (int)$rRating) ?>
        </div>
        <p class="testimonial-quote"><?= e($rText) ?></p>
        <div class="testimonial-author">
          <img src="<?= $rAvatar ?>" alt="<?= e($rName) ?>" class="testimonial-avatar" loading="lazy">
          <div>
            <div class="testimonial-author-name"><?= e($rName) ?></div>
            <div class="testimonial-author-location">
              <?= $rCity ? e($rCity).' — ' : '' ?><?= e($rDest) ?> Traveler
            </div>
          </div>
        </div>
      </article>
      <?php endforeach ?>
    </div>

    <!-- Submit Review Form -->
    <div style="max-width:700px;margin:4rem auto 0">
      <div class="form-card">
        <h2 style="margin-bottom:0.5rem;color:var(--color-primary)">Share Your Experience</h2>
        <p style="margin-bottom:2rem;font-size:0.875rem">Traveled with Journey Karo? We'd love to hear your story!</p>

        <?php if ($success): ?>
        <div class="alert alert-success">✅ <?= e($success) ?></div>
        <?php elseif ($error): ?>
        <div class="alert alert-error">❌ <?= e($error) ?></div>
        <?php endif ?>

        <form method="POST" action="reviews.php">
          <?= csrfField() ?>
          <input type="hidden" name="submit_review" value="1">

          <div class="form-row">
            <div class="form-group">
              <label class="form-label">Your Name *</label>
              <input type="text" name="reviewer_name" class="form-control" placeholder="Your full name" required>
            </div>
            <div class="form-group">
              <label class="form-label">Email Address *</label>
              <input type="email" name="reviewer_email" class="form-control" placeholder="your@email.com" required>
            </div>
          </div>
          <div class="form-row">
            <div class="form-group">
              <label class="form-label">Overall Rating *</label>
              <select name="rating" class="form-control" required>
                <option value="5">⭐⭐⭐⭐⭐ (5 Stars — Excellent)</option>
                <option value="4">⭐⭐⭐⭐ (4 Stars — Very Good)</option>
                <option value="3">⭐⭐⭐ (3 Stars — Good)</option>
                <option value="2">⭐⭐ (2 Stars — Average)</option>
                <option value="1">⭐ (1 Star — Poor)</option>
              </select>
            </div>
            <div class="form-group">
              <label class="form-label">Destination Visited</label>
              <select name="destination" class="form-control">
                <option value="Rann of Kutch">Rann of Kutch / Bhuj</option>
                <option value="Dwarka">Dwarka</option>
                <option value="Somnath">Somnath</option>
                <option value="Gir National Park">Gir National Park</option>
                <option value="Diu">Diu</option>
                <option value="Statue of Unity">Statue of Unity</option>
                <option value="Multiple Destinations">Multiple Destinations</option>
              </select>
            </div>
          </div>
          <div class="form-group">
            <label class="form-label">Your Review *</label>
            <textarea name="review_text" class="form-control" rows="4" required
              placeholder="Share your experience — what you loved, what could be improved, who you'd recommend Journey Karo to..."></textarea>
          </div>
          <button type="submit" class="btn btn-gold btn-lg w-full">Submit My Review ⭐</button>
        </form>
      </div>
    </div>
  </div>
</section>

<?php include 'includes/footer.php'; ?>
