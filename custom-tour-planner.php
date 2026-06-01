<?php
require_once 'includes/config.php';
require_once 'includes/db.php';
require_once 'includes/functions.php';
require_once 'includes/csrf.php';

$page      = 'custom-tour-planner';
$pageTitle = 'Custom Tour Planner | Design Your Perfect Gujarat Trip | Journey Karo';
$meta      = getSeoMeta($page);

$success = $error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    validateCsrf();
    if (!checkRateLimit('custom_tour', 3)) {
        $error = 'Too many requests. Please call us directly at +91 9586605635.';
    } else {
        require_once 'includes/mailer.php';
        $name        = clean($_POST['name'] ?? '');
        $email       = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
        $phone       = clean($_POST['phone'] ?? '');
        $destinations= implode(', ', array_map('clean', (array)($_POST['destinations'] ?? [])));
        $travelDate  = $_POST['travel_date'] ?? null;
        $duration    = clean($_POST['duration'] ?? '');
        $numGuests   = (int)($_POST['num_guests'] ?? 1);
        $budget      = clean($_POST['budget'] ?? '');
        $accommodation = clean($_POST['accommodation'] ?? '');
        $interests   = implode(', ', array_map('clean', (array)($_POST['interests'] ?? [])));
        $specialReqs = clean($_POST['special_requirements'] ?? '');

        $message = "Custom Tour Request:\nDestinations: $destinations\nDuration: $duration\nBudget: $budget\nAccommodation: $accommodation\nInterests: $interests\nSpecial: $specialReqs";

        if (!$name || !$email || !$phone) {
            $error = 'Please fill in all required fields.';
        } else {
            $id = saveInquiry([
                'name'=>$name,'email'=>$email,'phone'=>$phone,
                'destination'=>$destinations,'message'=>$message,
                'travel_date'=>$travelDate,'num_guests'=>$numGuests,'source'=>'custom_planner',
            ]);
            if ($id) {
                sendInquiryNotification(['name'=>$name,'email'=>$email,'phone'=>$phone,'destination'=>'Custom: '.$destinations,'message'=>$message,'travel_date'=>$travelDate,'num_guests'=>$numGuests,'package_name'=>'Custom Tour Plan']);
                regenerateCsrf();
                $success = 'Your custom tour request has been submitted! Our Gujarat expert will call you within 2 hours to discuss your itinerary.';
            } else {
                $error = 'Submission failed. Please WhatsApp us directly.';
            }
        }
    }
}

include 'includes/header.php';
?>

<section class="page-hero">
  <div class="container">
    <div class="section-badge">✈️ Custom Tour Planner</div>
    <h1>Design Your Perfect<br>Gujarat Adventure</h1>
    <p>Tell us your dream Gujarat trip. We'll build a tailored itinerary that fits your schedule, budget, and interests — completely free of charge.</p>
  </div>
</section>

<section class="section-padding" style="background:var(--color-bg)">
  <div class="container">
    <div style="display:grid;grid-template-columns:1fr;gap:3rem;max-width:1100px;margin:auto" class="planner-grid">

      <!-- Form -->
      <div>
        <?php if ($success): ?>
        <div class="alert alert-success" style="margin-bottom:2rem">
          ✅ <?= e($success) ?>
          <br><br>
          <a href="https://wa.me/<?= APP_WHATSAPP ?>?text=Hello!%20I%20just%20submitted%20a%20custom%20tour%20request." target="_blank" class="btn btn-whatsapp btn-sm">💬 Also WhatsApp Us</a>
        </div>
        <?php elseif ($error): ?>
        <div class="alert alert-error"><?= e($error) ?></div>
        <?php endif ?>

        <div class="form-card">
          <h2 style="margin-bottom:0.5rem;color:var(--color-primary)">Plan My Custom Gujarat Tour</h2>
          <p style="margin-bottom:2rem;font-size:0.875rem">Fill in your preferences below. Our expert will create a personalised itinerary within 2 hours.</p>

          <form id="custom-tour-form" action="custom-tour-planner.php" method="POST">
            <?= csrfField() ?>
            <input type="hidden" name="source" value="custom_planner">

            <!-- Step 1: Personal Info -->
            <div style="margin-bottom:2rem;padding-bottom:2rem;border-bottom:1px solid var(--color-border)">
              <h4 style="font-size:0.8rem;font-weight:800;text-transform:uppercase;letter-spacing:0.08em;color:var(--color-text-muted);margin-bottom:1.25rem">
                Step 1: Your Contact Information
              </h4>
              <div class="form-row">
                <div class="form-group">
                  <label for="p-name" class="form-label">Full Name *</label>
                  <input type="text" id="p-name" name="name" class="form-control" placeholder="Your full name" required>
                </div>
                <div class="form-group">
                  <label for="p-phone" class="form-label">Phone / WhatsApp *</label>
                  <input type="tel" id="p-phone" name="phone" class="form-control" placeholder="10-digit mobile number" required>
                </div>
              </div>
              <div class="form-group">
                <label for="p-email" class="form-label">Email Address *</label>
                <input type="email" id="p-email" name="email" class="form-control" placeholder="your@email.com" required>
              </div>
            </div>

            <!-- Step 2: Destinations -->
            <div style="margin-bottom:2rem;padding-bottom:2rem;border-bottom:1px solid var(--color-border)">
              <h4 style="font-size:0.8rem;font-weight:800;text-transform:uppercase;letter-spacing:0.08em;color:var(--color-text-muted);margin-bottom:1.25rem">
                Step 2: Choose Destinations (select all that interest you)
              </h4>
              <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(180px,1fr));gap:0.75rem">
                <?php
                $dests = [
                  ['bhuj','🏜️ Rann of Kutch (Bhuj)'],['dwarka','🛕 Dwarka'],
                  ['somnath','🕌 Somnath'],['gir','🦁 Gir National Park'],
                  ['diu','🏖️ Diu'],['sou','🗽 Statue of Unity'],
                  ['kutch','🎨 Kutch Villages'],['ahmedabad','🏙️ Ahmedabad'],
                ];
                foreach ($dests as [$val, $label]): ?>
                <label style="display:flex;align-items:center;gap:0.75rem;padding:0.75rem 1rem;background:var(--color-bg);border:1.5px solid var(--color-border);border-radius:var(--radius-md);cursor:pointer;transition:all 0.2s;font-size:0.875rem;font-weight:500" class="dest-checkbox-label">
                  <input type="checkbox" name="destinations[]" value="<?= $val ?>" style="accent-color:var(--color-primary)">
                  <?= $label ?>
                </label>
                <?php endforeach ?>
              </div>
            </div>

            <!-- Step 3: Trip Details -->
            <div style="margin-bottom:2rem;padding-bottom:2rem;border-bottom:1px solid var(--color-border)">
              <h4 style="font-size:0.8rem;font-weight:800;text-transform:uppercase;letter-spacing:0.08em;color:var(--color-text-muted);margin-bottom:1.25rem">
                Step 3: Trip Details
              </h4>
              <div class="form-row">
                <div class="form-group">
                  <label for="p-date" class="form-label">Travel Start Date</label>
                  <input type="date" id="p-date" name="travel_date" class="form-control">
                </div>
                <div class="form-group">
                  <label for="p-duration" class="form-label">Trip Duration</label>
                  <select id="p-duration" name="duration" class="form-control">
                    <option value="">Select duration</option>
                    <option>1-2 Days (Weekend Getaway)</option>
                    <option>3-4 Days (Short Trip)</option>
                    <option>5-7 Days (Standard Tour)</option>
                    <option>8-10 Days (Grand Tour)</option>
                    <option>10+ Days (Extended Circuit)</option>
                    <option>Flexible (Let us decide)</option>
                  </select>
                </div>
              </div>
              <div class="form-row">
                <div class="form-group">
                  <label for="p-guests" class="form-label">Number of Travelers</label>
                  <select id="p-guests" name="num_guests" class="form-control">
                    <?php for($i=1;$i<=20;$i++) echo "<option value='$i'>$i Person".($i>1?'s':'')."</option>"; ?>
                  </select>
                </div>
                <div class="form-group">
                  <label for="p-budget" class="form-label">Total Budget (per person)</label>
                  <select id="p-budget" name="budget" class="form-control">
                    <option value="">Select budget range</option>
                    <option>Under ₹5,000</option>
                    <option>₹5,000 – ₹10,000</option>
                    <option>₹10,000 – ₹20,000</option>
                    <option>₹20,000 – ₹35,000</option>
                    <option>₹35,000+ (Premium/Luxury)</option>
                    <option>Flexible — Show me best options</option>
                  </select>
                </div>
              </div>
              <div class="form-group">
                <label for="p-accommodation" class="form-label">Accommodation Preference</label>
                <select id="p-accommodation" name="accommodation" class="form-control">
                  <option value="">Select preference</option>
                  <option>Budget (Guesthouses / Dharamshalas)</option>
                  <option>Comfort (3-star Hotels)</option>
                  <option>Premium (4-star Hotels)</option>
                  <option>Luxury (5-star / Heritage Hotels)</option>
                  <option>Tents / Camps (Rann experience)</option>
                  <option>Mix of all</option>
                </select>
              </div>
            </div>

            <!-- Step 4: Interests -->
            <div style="margin-bottom:2rem;padding-bottom:2rem;border-bottom:1px solid var(--color-border)">
              <h4 style="font-size:0.8rem;font-weight:800;text-transform:uppercase;letter-spacing:0.08em;color:var(--color-text-muted);margin-bottom:1.25rem">
                Step 4: Travel Interests
              </h4>
              <div style="display:flex;flex-wrap:wrap;gap:0.5rem">
                <?php foreach (['🛕 Pilgrimage/Temples','🦁 Wildlife Safari','🏖️ Beaches','🎨 Arts & Culture','🍽️ Food & Cuisine','🏛️ Heritage & History','🌿 Nature & Trekking','📸 Photography','🎉 Festivals','🛍️ Shopping','🏝️ Island Tour','🌅 Sunsets'] as $interest): ?>
                <label style="display:flex;align-items:center;gap:6px;padding:6px 14px;background:var(--color-bg);border:1.5px solid var(--color-border);border-radius:var(--radius-full);cursor:pointer;font-size:0.8rem;font-weight:600;transition:all 0.2s">
                  <input type="checkbox" name="interests[]" value="<?= $interest ?>" style="accent-color:var(--color-primary)">
                  <?= $interest ?>
                </label>
                <?php endforeach ?>
              </div>
            </div>

            <!-- Step 5: Special Requirements -->
            <div class="form-group">
              <label for="p-special" class="form-label">Special Requirements or Notes</label>
              <textarea id="p-special" name="special_requirements" class="form-control" rows="4"
                placeholder="Vegetarian meals, wheelchair accessibility, senior travelers, honeymoon package, children-friendly activities, birthday celebration..."></textarea>
            </div>

            <button type="submit" class="btn btn-gold btn-lg w-full" style="margin-top:1rem">
              Submit My Custom Tour Request ✈️
            </button>
            <p style="font-size:0.75rem;color:var(--color-text-light);text-align:center;margin-top:0.75rem">
              ✅ 100% Free. No obligation. Our expert will call within 2 hours.
            </p>
          </form>
        </div>
      </div>

      <!-- Sidebar Info -->
      <div>
        <div class="info-card" style="margin-bottom:1.5rem">
          <h3 style="font-size:1.1rem;font-weight:800;color:var(--color-primary);margin-bottom:1rem">📞 Talk to an Expert</h3>
          <p style="font-size:0.875rem;margin-bottom:1.5rem">Prefer to discuss your trip over a call? Our Gujarat travel experts are available 8 AM – 9 PM, 7 days a week.</p>
          <a href="tel:+91<?= APP_PHONE ?>" class="btn btn-primary w-full" style="margin-bottom:0.75rem">📞 Call: +91 <?= APP_PHONE ?></a>
          <a href="https://wa.me/<?= APP_WHATSAPP ?>?text=Hi!%20I%20want%20to%20plan%20a%20custom%20Gujarat%20tour." target="_blank" class="btn btn-whatsapp w-full">💬 Chat on WhatsApp</a>
        </div>

        <div class="info-card" style="margin-bottom:1.5rem">
          <h3 style="font-size:1.1rem;font-weight:800;color:var(--color-primary);margin-bottom:1rem">✅ What's Included</h3>
          <?php foreach (['Free personalized itinerary (within 2 hours)','All-inclusive pricing with zero hidden costs','Hotel & transport recommendations','Best time to visit advice','Local cuisine & activity suggestions','24/7 support during your trip'] as $item): ?>
          <div style="display:flex;align-items:flex-start;gap:0.75rem;margin-bottom:0.75rem;font-size:0.875rem">
            <span style="color:var(--color-success);font-size:1rem;flex-shrink:0">✅</span>
            <span><?= $item ?></span>
          </div>
          <?php endforeach ?>
        </div>

        <div class="info-card" style="background:var(--color-primary);color:white">
          <h3 style="font-size:1.1rem;font-weight:800;color:white;margin-bottom:0.75rem">⭐ Our Promise</h3>
          <p style="font-size:0.875rem;color:rgba(255,255,255,0.8)">We've helped 5,000+ travelers explore Gujarat. Our custom plans always come with best-price guarantee, flexible cancellation, and 24/7 local support.</p>
        </div>
      </div>

    </div>
  </div>
</section>
<style>
@media(min-width:1024px){ .planner-grid { grid-template-columns: 2fr 1fr !important; } }
.dest-checkbox-label:hover { border-color: var(--color-primary); background: var(--color-primary-50); }
</style>

<?php include 'includes/footer.php'; ?>
