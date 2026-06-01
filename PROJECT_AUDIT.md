# Journey Karo — Project Audit

**Audit date:** June 1, 2026  
**Auditor:** Development continuation (automated codebase scan)  
**Rule:** Extend existing work — do not rebuild from scratch.

---

## 1. Executive summary

| Metric | Assessment |
|--------|------------|
| Overall completion (vs. 22-phase roadmap) | ~35–40% at audit start |
| Production readiness | Not ready — no DB schema, no admin UI |
| Primary risk | Dual homepage (`index.html` vs PHP stack), CMS not wired |
| Strength | Solid `includes/` layer, inquiry API, public page shells |

---

## 2. Completed modules

### Core infrastructure
| Module | Files | Status |
|--------|-------|--------|
| App configuration | `includes/config.php` | Complete (placeholders for secrets) |
| PDO connection | `includes/db.php` | Complete |
| Helper / data layer | `includes/functions.php` | Complete (expects DB tables) |
| CSRF | `includes/csrf.php` | Complete |
| Email notifications | `includes/mailer.php` | Complete (`mail()`, not SMTP class) |
| Inquiry API | `api/submit-inquiry.php` | Complete |

### Public frontend (static / fallback)
| Page | File | Status |
|------|------|--------|
| About | `about.php` | Complete (static) |
| Destinations list | `destinations.php` | UI complete; DB fetch unused in loop |
| Packages list | `packages.php` | UI complete; static data only |
| Gallery | `gallery.php` | UI complete; static only |
| Blog list | `blog.php` | UI + DB fallback pattern |
| FAQ | `faq.php` | Complete (hardcoded) |
| Reviews | `reviews.php` | UI + submit handler |
| Contact | `contact.php` | Form + server POST |
| Custom tour planner | `custom-tour-planner.php` | Form + server POST |

### Assets
| Asset | Status |
|-------|--------|
| `assets/css/style.css` | Complete (~2k lines, deep blue + gold) |
| `assets/js/main.js` | Complete (forms, nav, FAQ, gallery) |
| `index.html` | Rich homepage; separate design stack (Tailwind CDN) |

### Security (code-level, not yet enforced in admin)
- PDO prepared statements
- CSRF on forms
- XSS helpers (`e()`, `clean()`)
- Rate limiting on inquiries
- reCAPTCHA hooks (keys not set)
- Session config in `config.php`
- `includes/auth.php` login logic (no UI before this continuation)

---

## 3. Incomplete modules

| Module | Gap |
|--------|-----|
| Database | No `schema.sql` / `seeds.sql` (empty `database/` folder) |
| `includes/database.php` | Requested; was `db.php` only |
| Admin panel | Empty `admin/` folder |
| CMS wiring | `getDestinations()` etc. not used on list pages |
| Detail pages | No destination/package/blog detail routes |
| Homepage | `index.html` not integrated with PHP/DB/API |
| Services hub | `services.php` linked but missing |
| Legal pages | Footer links 404; content only in `index.html` modals |
| SEO files | No `sitemap.xml`, `robots.txt`, `.htaccess` |
| Uploads / images | `uploads/`, `assets/images/` missing |
| SMTP | Config exists; PHPMailer not implemented |
| Analytics | Not integrated |

---

## 4. Missing modules (roadmap)

| Phase | Deliverable | Pre-audit |
|-------|-------------|-----------|
| 1 | `database/schema.sql` | Missing |
| 2 | `includes/database.php` | Missing |
| 3–5 | Admin auth, dashboard, leads | Missing |
| 6–11 | Destination, package, itinerary, blog, review, gallery CMS | Missing |
| 12 | SEO admin + sitemap/robots | Missing |
| 13–14 | Dynamic frontend + unified homepage | Missing |
| 15 | `custom-tour.php` | Only `custom-tour-planner.php` |
| 16 | Service sub-pages | Missing |
| 17 | Legal pages (7) | Missing |
| 18–20 | Performance, security hardening, analytics | Partial / missing |
| 21–22 | Deployment guide, QA checklist | Missing |

---

## 5. Bugs & technical debt

### Bugs
1. **`destinations.php`** — calls `getDestinations()` but renders `$allDestinations` static array only.
2. **`packages.php`** — never calls `getPackages()`.
3. **`gallery.php`** — never calls `getGallery()`.
4. **Broken nav links** — `services.php`, legal pages, `sitemap.xml` return 404.
5. **`index.html` forms** — use `mailto:` instead of `api/submit-inquiry.php`; leads not stored.
6. **Dual form handlers** — `contact.php` / `custom-tour-planner.php` have server POST + JS `fetch` (JS wins on submit); redundant but functional.
7. **`#inquiry-form`** — referenced in `main.js` but absent on PHP pages.
8. **`APP_URL` in asset paths** — may break local dev if URL is production-only.
9. **reCAPTCHA** — script loads with placeholder keys (console errors possible).

### Technical debt
1. **Two design systems** — `index.html` (teal/saffron + Tailwind) vs `style.css` (blue/gold).
2. **Two homepages** — `index.html` vs no `index.php`.
3. **`login_attempts` table** — used in `auth.php` but not in original table spec (added in schema).
4. **Static Unsplash URLs** — not self-hosted; performance/SEO debt.
5. **No migration/versioning** for DB.
6. **Admin `requireAdmin()`** — redirects to `../admin/login.php` (path-sensitive).

---

## 6. File inventory (pre-continuation)

```
21 files with content
admin/          (empty)
database/       (empty)
docs/           (empty)
api/            submit-inquiry.php
includes/       8 PHP files
assets/css/     style.css
assets/js/      main.js
*.php           9 public pages
index.html      standalone homepage
```

---

## 7. Recommendations (priority order)

1. **Phase 1–2** — Schema + seeds + `database.php` (blocks everything).
2. **Phase 3–5** — Admin login + leads (business value).
3. **Phase 6–11** — CMS CRUD (content management).
4. **Phase 13–14** — Wire frontend to DB; `index.php` homepage.
5. **Phase 12, 17** — SEO + legal (compliance & discoverability).
6. **Phase 16** — Services pages (conversion).
7. **Phase 18–22** — Optimize, deploy docs, QA.

---

## 8. Continuation plan

Development continues per master roadmap Phases 1–22.  
Status tracked in `PROJECT_STATUS.md` (updated after each phase).

---

*This audit reflects the codebase state at the start of the continuation sprint.*
