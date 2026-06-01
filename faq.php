<?php
require_once 'includes/config.php';
require_once 'includes/db.php';
require_once 'includes/functions.php';

$page      = 'faq';
$pageTitle = 'Frequently Asked Questions | Journey Karo Gujarat Tours';
$meta      = getSeoMeta($page);
include 'includes/header.php';

$faqs = [
  ['General','What is Journey Karo?','Journey Karo is a premium Gujarat-based travel agency headquartered in Bhuj. We specialize in curated tour packages covering Rann of Kutch, Dwarka, Somnath, Gir National Park, Diu, and Statue of Unity. Our team of local experts has over 8 years of experience creating unforgettable Gujarat experiences.'],
  ['General','How do I book a tour with Journey Karo?','You can book through multiple channels: (1) Fill our online inquiry form on the website, (2) WhatsApp us at +91 9586605635, (3) Call us directly, or (4) Use our Custom Tour Planner for a personalized itinerary. Our team will confirm your booking within 2 hours.'],
  ['General','Do you offer tours from Ahmedabad?','Yes! We pick up from Ahmedabad Airport, Ahmedabad Railway Station, and all major Gujarat cities. We have tie-ups with trusted transport providers across the state.'],
  ['Booking','What is the booking process?','Step 1: Submit your inquiry or call us. Step 2: Receive your custom itinerary within 2 hours. Step 3: Confirm with 30% advance payment. Step 4: Receive all vouchers & tour details. Step 5: Travel & enjoy with our 24/7 support.'],
  ['Booking','How much advance payment is required?','We require a 30% advance payment to confirm your booking. The remaining 70% can be paid on the day of departure or upon arrival. We accept UPI, NEFT, bank transfer, and cash.'],
  ['Booking','Is online payment secure?','Yes, absolutely. All payments are processed through secure, encrypted channels. We accept Paytm, Google Pay, PhonePe, NEFT, RTGS, and cash. We never store your card details.'],
  ['Cancellation','What is your cancellation policy?','Our standard cancellation policy: Cancel 15+ days before: Full refund. Cancel 8-14 days before: 75% refund. Cancel 4-7 days before: 50% refund. Cancel within 3 days: No refund. Please refer to our Cancellation Policy page for full details.'],
  ['Cancellation','What if the tour is cancelled due to weather or other reasons?','If Journey Karo cancels a tour due to unforeseen circumstances (severe weather, natural disasters, government orders), you will receive a 100% refund or free rescheduling — your choice.'],
  ['Destinations','What is the best time to visit Rann of Kutch?','The best time is October to March. The famous Rann Utsav festival runs from November to February. Avoid visiting during May-August (extreme heat, 40-48°C). The full moon nights in the Rann are absolutely magical!'],
  ['Destinations','Is Gir National Park open year-round?','No. Sasan Gir is typically closed from June 16 to October 15 (monsoon season). The best safari months are November to June. Book early as safari permits are limited.'],
  ['Destinations','Can I do a same-day visit to the Statue of Unity?','Yes! The Statue of Unity is about 200km from Ahmedabad (3-4 hour drive). We offer same-day tour packages. However, we recommend at least an overnight stay to experience the laser show and Valley of Flowers properly.'],
  ['Packages','Are meals included in tour packages?','It depends on the package. Most of our packages include breakfast and one major meal per day. Our premium packages include all meals. Please check the specific inclusions listed on each package page, or ask our team to customize for you.'],
  ['Packages','Do you offer honeymoon or special occasion packages?','Yes! We create beautiful customized honeymoon packages for Diu (beach), Rann of Kutch (romantic tent stay under stars), and Dwarka (spiritual journey). We can arrange flower decorations, candlelit dinners, and personalized experiences.'],
  ['Services','Do you provide car rental services without a guide?','Yes. We offer clean, well-maintained AC cars with experienced drivers for self-exploration. Our cars come with GPS navigation and 24/7 driver support. Rates start from ₹1,500 per day (hatchback) to ₹3,500 per day (premium SUV).'],
  ['Services','Do you help with flight bookings?','Yes! We assist with domestic flight bookings from any Indian city to Bhuj, Rajkot, Surat, or Ahmedabad. We search across all airlines for the best fares. Call or WhatsApp us with your travel dates for instant fare quotes.'],
];

$categories = array_unique(array_column($faqs, 0));
?>

<section class="page-hero">
  <div class="container">
    <div class="section-badge">❓ FAQ</div>
    <h1>Frequently Asked Questions</h1>
    <p>Everything you need to know about traveling Gujarat with Journey Karo.</p>
  </div>
</section>

<section class="section-padding" style="background:var(--color-bg)">
  <div class="container" style="max-width:900px">

    <!-- Category Tabs -->
    <div class="tabs" style="margin-bottom:2.5rem">
      <button class="tab-btn active" data-filter="all">All Questions</button>
      <?php foreach ($categories as $cat): ?>
      <button class="tab-btn" data-filter="<?= strtolower($cat) ?>"><?= e($cat) ?></button>
      <?php endforeach ?>
    </div>

    <div class="faq-list" id="faq-list">
      <?php foreach ($faqs as $i => [$cat, $q, $a]): ?>
      <div class="faq-item" data-category="<?= strtolower($cat) ?>">
        <button class="faq-question" aria-expanded="false">
          <span><?= e($q) ?></span>
          <span class="faq-icon">+</span>
        </button>
        <div class="faq-answer"><?= e($a) ?></div>
      </div>
      <?php endforeach ?>
    </div>

    <!-- Still have questions? -->
    <div style="background:white;border-radius:var(--radius-xl);padding:2.5rem;text-align:center;margin-top:3rem;box-shadow:var(--shadow-sm)">
      <div style="font-size:2.5rem;margin-bottom:1rem">💬</div>
      <h3 style="font-size:1.2rem;font-weight:800;color:var(--color-primary);margin-bottom:0.75rem">Still Have Questions?</h3>
      <p style="margin-bottom:1.5rem;font-size:0.9rem">Our Gujarat travel experts are available 8 AM – 9 PM, 7 days a week.</p>
      <div style="display:flex;gap:1rem;justify-content:center;flex-wrap:wrap">
        <a href="https://wa.me/<?= APP_WHATSAPP ?>?text=Hello%20Journey%20Karo!%20I%20have%20a%20question%20about%20your%20Gujarat%20tours."
           class="btn btn-whatsapp" target="_blank">💬 WhatsApp Us</a>
        <a href="tel:+91<?= APP_PHONE ?>" class="btn btn-primary">📞 Call: +91 <?= APP_PHONE ?></a>
        <a href="contact.php" class="btn btn-outline">✉️ Send Email</a>
      </div>
    </div>
  </div>
</section>

<script>
// FAQ category filter
document.querySelectorAll('[data-filter]').forEach(btn => {
  btn.addEventListener('click', () => {
    document.querySelectorAll('[data-filter]').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');
    const filter = btn.dataset.filter;
    document.querySelectorAll('.faq-item').forEach(item => {
      item.style.display = (filter === 'all' || item.dataset.category === filter) ? '' : 'none';
    });
  });
});
</script>

<!-- FAQ Schema Markup -->
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "FAQPage",
  "mainEntity": [
    <?php echo implode(',', array_map(function($faq) {
      return json_encode([
        "@type"          => "Question",
        "name"           => $faq[1],
        "acceptedAnswer" => ["@type" => "Answer", "text" => $faq[2]]
      ]);
    }, $faqs)) ?>
  ]
}
</script>

<?php include 'includes/footer.php'; ?>
