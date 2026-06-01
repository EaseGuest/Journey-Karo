# Journey Karo — QA Checklist

## Public pages

- [ ] `/` (index.php) loads — hero, destinations, packages, services, reviews, gallery, FAQ, inquiry form
- [ ] `about.php`, `destinations.php`, `destination-detail.php?slug=bhuj`
- [ ] `packages.php`, `package-detail.php?slug=white-desert-safari`
- [ ] `services.php`, `services/hotel-booking.php`, `car-rental.php`, `flight-assistance.php`
- [ ] `blog.php`, `blog-detail.php` (published post slug)
- [ ] `gallery.php`, `reviews.php`, `faq.php`, `contact.php`
- [ ] `custom-tour-planner.php`, `custom-tour.php` (redirect)
- [ ] Legal: privacy, terms, refund, cancellation, cookie, disclaimer, safety

## Forms & leads

- [ ] Homepage inquiry → success toast → row in `inquiries` table
- [ ] Contact form saves + emails admin
- [ ] Custom tour planner saves with extended message
- [ ] API `POST /api/submit-inquiry.php` returns JSON success
- [ ] Review submission → `reviews` status `pending`

## Admin

- [ ] Login / logout / session timeout
- [ ] Dashboard stats match DB
- [ ] Leads: list, filter, status update, notes, soft delete
- [ ] CRUD: destinations, packages, itineraries, blogs, reviews, gallery
- [ ] SEO meta edit; settings save (GA4 ID)

## SEO & technical

- [ ] `sitemap.php` valid XML
- [ ] `robots.txt` accessible
- [ ] Meta title/description per page
- [ ] Schema JSON-LD on header (TravelAgency)
- [ ] Mobile sticky CTA + WhatsApp float
- [ ] No PHP errors in `logs/error.log`
- [ ] No critical console errors (reCAPTCHA only if keys missing)

## Performance (target)

- [ ] Mobile PageSpeed 90+ (optimize images to WebP on Hostinger)
- [ ] Desktop 95+ (lazy loading on images — `loading="lazy"`)

## Security

- [ ] `/admin/` requires login
- [ ] CSRF on admin + public forms
- [ ] Upload only images in admin
- [ ] HTTPS enforced in production
