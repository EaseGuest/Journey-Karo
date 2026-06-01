# Journey Karo — Hostinger Deployment Guide

## 1. Prerequisites

- Hostinger Premium hosting with PHP 8.x and MySQL
- Domain pointed to Hostinger (`www.journeykaro.com`)
- SSL certificate enabled (AutoSSL)

## 2. Database setup

1. Open **hPanel → Databases → MySQL Databases**
2. Create database: `journeykaro_db`
3. Create user with full privileges; note host, username, password
4. Open **phpMyAdmin** → Import:
   - `database/schema.sql`
   - `database/seeds.sql`
5. SSH or File Manager — run (once):

```bash
php database/set-admin-password.php
```

Default admin: `journeykaro_admin` / `Admin@2025` (change after first login)

## 3. Configuration

Edit `includes/config.php`:

| Constant | Value |
|----------|--------|
| `DB_HOST` | Usually `localhost` |
| `DB_NAME` | Your database name |
| `DB_USER` | MySQL username |
| `DB_PASS` | MySQL password |
| `APP_URL` | `https://www.journeykaro.com` |
| `APP_ENV` | `production` |
| `RECAPTCHA_SITE_KEY` / `RECAPTCHA_SECRET_KEY` | From Google reCAPTCHA admin |
| `SMTP_PASS` | Hostinger email password (if using SMTP later) |

## 4. Upload files

Upload entire project to `public_html/`:

- All `.php` files and folders (`admin`, `api`, `assets`, `includes`, `services`, `uploads`)
- `.htaccess`, `robots.txt`
- Do **not** upload `.env` with secrets to public repos

Set permissions:

- `uploads/` → **755** (writable by PHP)
- `logs/` → create folder **755** if using error logs

## 5. Homepage

`.htaccess` sets `DirectoryIndex index.php index.html`.  
Production homepage: **`index.php`** (database-driven).  
Legacy `index.html` remains as fallback; remove or redirect when satisfied.

## 6. Cron jobs (optional)

Daily DB backup (adjust path):

```bash
0 3 * * * mysqldump -u USER -p'PASS' journeykaro_db > /home/user/backups/jk_$(date +\%F).sql
```

## 7. Email

Inquiry emails use PHP `mail()`. For better deliverability on Hostinger, configure SMTP in `includes/mailer.php` (PHPMailer recommended).

## 8. Post-deploy checklist

- [ ] Admin login: `/admin/login.php`
- [ ] Submit test inquiry on homepage
- [ ] Verify email to `booking@journeykaro.com`
- [ ] Submit sitemap in Google Search Console: `https://www.journeykaro.com/sitemap.php`
- [ ] Add GA4 ID in Admin → Settings
- [ ] Uncomment HTTPS redirect in `.htaccess`

## 9. Backup strategy

- Weekly: full files + MySQL dump via hPanel backups
- Before updates: export DB + zip `uploads/`

## 10. Support contacts

- Phone / WhatsApp: +91 9586605635
- Email: booking@journeykaro.com
