# EUROPA FUSION – Study Abroad Website (Laravel)

A full rebuild and redesign of [europafusion.io](https://www.europafusion.io/) as a Laravel 13
application: a Germany & Europe study-abroad advisory site with 23 public pages, working enquiry
and partner forms, email notifications and a password-protected lead desk.

**No database is needed.** Page content is read from JSON/HTML files, form submissions and admin
accounts are saved as files in `storage/app/private`, and sessions/cache use files in `storage/`.
There is no MySQL, no SQL import and no migrations.

- **Deployment guide (servers, Apache/Nginx, SSL, email, backups):** [`docs/DEPLOYMENT.md`](docs/DEPLOYMENT.md)
- **Content model for country / pathway pages:** [`docs/CONTENT_SCHEMA.md`](docs/CONTENT_SCHEMA.md)

---

## 1. Run it on a new computer (Windows, macOS or Linux)

**You need:** PHP **8.3 or newer** and [Composer 2](https://getcomposer.org/download/).
The PHP extensions Laravel needs (`mbstring`, `openssl`, `tokenizer`, `xml`/`dom`, `ctype`,
`fileinfo`, `session`) are included in standard PHP builds, XAMPP, Laragon and Herd.
MySQL and Node.js are **not** needed (the compiled CSS/JS is already in `public/build`).

```bash
# 1. Unzip and enter the folder
unzip europafusion-website.zip
cd europafusion

# 2. Install PHP dependencies (creates the vendor/ folder, needs internet once)
composer install

# 3. Create the environment file and app key
cp .env.example .env            # Windows (cmd): copy .env.example .env
php artisan key:generate

# 4. Optional – create a login for the /admin lead desk
php artisan admin:create        # asks for name, email, password

# 5. Start the site
php artisan serve               # → http://127.0.0.1:8000
```

`composer run setup` does steps 2–3 in one go.

For local testing you may set `APP_ENV=local` and `APP_DEBUG=true` in `.env` (shows detailed
errors). For a live server keep the defaults (`production` / `false`) and follow
[`docs/DEPLOYMENT.md`](docs/DEPLOYMENT.md).

> **Keep `storage/` and `bootstrap/cache/` writable** by the user that runs PHP – that is
> where submissions, admin accounts, sessions and logs are written.

## 2. What is included

| Module | URL(s) | Notes |
|---|---|---|
| Home / About | `/` (`/about` redirects) | Hero, university marquee, about, mission & vision, what we do, 5-stage approach, destinations |
| Destinations | `/destinations` | 9 country cards, "View details" modal per country, profile-evaluation call to action |
| Germany hub | `/study-in-germany` | Landing page linking the four Germany pathways |
| Country pages (8) | `/study-in-{austria,finland,italy,latvia,lithuania,netherlands,poland,spain}` | Rendered from JSON content blocks |
| Germany pathways (4) | `/germany/{studienkolleg,bachelor,master,medicine}` | Same block renderer |
| Services | `/services` (+ `#counselling`, `#visa-guidance`, …) | 11 services in two groups, sticky section navigation |
| Exam Prep | `/exam-prep` (`/exams` redirects) | Benefits, 6 language tests, 5 standardized tests, FAQ |
| Contact | `/contact` | Enquiry form (general / counselling / evaluation) |
| B2B Partner | `/b2b` | Partner registration form |
| Legal | `/privacy-policy`, `/terms-conditions`, `/disclaimer`, `/refund-policy` | Old short URLs redirect |
| Enquiry modal | every page | "Book Free Counselling" / "Free Profile Evaluation" buttons |
| Admin lead desk | `/admin` | Login, dashboard, enquiries & partner requests (filter, search, status, notes, CSV export, delete) |
| SEO | `/sitemap.xml`, `/robots.txt` | Canonical URLs, Open Graph image, JSON-LD organisation data |

Every form validates on the server, saves the submission, emails the team
(`ENQUIRY_NOTIFY_EMAIL`), sends an acknowledgement to the applicant, and is protected by
CSRF, a honeypot field and rate limiting (5 per minute / 40 per day per IP). The forms also
work without JavaScript.

## 3. Where the data lives (no database)

| What | Location | Notes |
|---|---|---|
| Website content | `resources/content/` | `destinations.json`, `services.json`, `exam-prep.json`, `universities.json`, `legal.json`, `pages/*.json`, `legal/*.html` |
| Enquiries | `storage/app/private/leads/enquiries/000001.json` … | One file per submission; reference `EF-00001` = file `000001.json` |
| Partner requests | `storage/app/private/leads/partners/000001.json` … | One file per submission |
| Admin accounts | `storage/app/private/admins.json` | Passwords stored as bcrypt hashes; manage with `php artisan admin:create` |
| Sessions, cache, rate limits | `storage/framework/` | Temporary, safe to clear |
| Logs | `storage/logs/` | `laravel-*.log` (errors), `mail-*.log` (emails while `MAIL_MAILER=log`) |

`storage/app/private` is outside the public web folder, so these files can't be downloaded
from the website (as long as the web server's document root is `public/`).

**Backups / moving to another machine:** copy `storage/app/private/` (submissions and admin
accounts) and your `.env`. Everything else comes from the ZIP.

## 4. Technology

- PHP 8.3+ (tested on 8.4), Laravel 13 – no database layer in use
- Blade templates, Bootstrap 5.3 (Sass, customised), Bootstrap Icons, vanilla JavaScript (ES modules)
- Vite 8 for asset bundling; self-hosted Fraunces + Manrope fonts (no Google Fonts requests)
- Hand-made SVG illustrations, flags and landmark skylines in `public/images`

## 5. Editing content

Changes to the content files show on the next page load – there is nothing to import or re-seed.

- **Company details** (phone, WhatsApp, email, social links, notification inbox):
  `.env` (`SITE_*`, `SOCIAL_*`, `ENQUIRY_*`) and `config/site.php`. Run `php artisan config:clear`
  afterwards (or `php artisan optimize` again on a server that uses it).
- **Country & pathway pages:** `resources/content/pages/*.json` (schema in `docs/CONTENT_SCHEMA.md`).
- **Destinations, services, exams & FAQ, universities:** the JSON files in `resources/content/`.
- **Legal pages:** `resources/content/legal/*.html` (titles and "last updated" date in `resources/content/legal.json`).
- **Design:** Sass in `resources/scss` (design tokens in `_tokens.scss`), JavaScript in
  `resources/js/modules`, then `npm install && npm run build` (Node.js 20+ needed only for this).

## 6. Admin lead desk

1. `php artisan admin:create` – enter name, email and a password (min. 10 characters with letters,
   numbers and symbols). Run it again with the same email to reset that password.
2. Open `/admin` and sign in.

Features: dashboard KPIs, enquiries by type/country, filter and search, status workflow
(New → Contacted → In progress → Closed), internal notes, CSV export (Excel-safe), delete.

## 7. Email

Until SMTP details are added, `MAIL_MAILER=log` writes every email in full into
`storage/logs/mail-YYYY-MM-DD.log` – submissions are still saved and visible in `/admin`.
To send real email set `MAIL_MAILER=smtp` and the `MAIL_*` values in `.env` (see `docs/DEPLOYMENT.md`). If the mail server is down, the
submission is still saved and the failure is logged.

## 8. Tests

```bash
php artisan test
```

56 feature tests (no database required – the test configuration deliberately has none, so any
database call would fail): every public page, redirects, 404s, sitemap/robots, enquiry & partner
validation/saving/emails, reference numbering, honeypot, rate limiting, mail-failure handling,
admin accounts, login/logout, filtering, pagination, status updates, CSV export and deletion.

## 9. Project structure (key files)

```
app/Http/Controllers/          Public page + form controllers
app/Http/Controllers/Admin/    Lead desk (auth, dashboard, enquiries, partners)
app/Http/Requests/             Form validation rules
app/Models/                    Destination, Page, Service, Exam, Faq, University (read from resources/content)
                               Enquiry, PartnerApplication (saved by App\Support\LeadStore)
app/Auth/                      File-based admin accounts + Laravel user provider
app/Support/                   Content loader, LeadStore, CSV export, mail helper, HTML sanitiser
app/Mail/ + resources/views/mail/   Notification & acknowledgement emails
app/Console/Commands/CreateAdmin.php
config/site.php                Company / contact settings, submissions folder
resources/content/             All website content (JSON + HTML)
resources/views/pages/         Page templates
resources/views/blocks/        Content block partials for country/pathway pages
resources/views/partials/      Header (mega menus), footer, enquiry modal
resources/scss/, resources/js/ Front-end source
public/images/                 SVG illustrations, flags, skylines, icons
```

## 10. Things the business still needs to provide

1. **SMTP credentials** for real email delivery (`MAIL_*` in `.env`). Until then emails are written
   to `storage/logs/mail-YYYY-MM-DD.log`; submissions are still saved and visible in `/admin`.
2. **YouTube channel URL** (`SOCIAL_YOUTUBE`) – the original site linked to `#`; the icon is hidden until set.
3. **Verification of marketing figures** carried over from the original site (e.g. "98.4% visa rate",
   "1,200+ partners", B2B "100+ profiles guided / 3 office locations") and of fees/visa figures on country pages.
4. **University logos**, if official logos should replace the typographic university tiles
   (logos are trademarks of each university and were deliberately not copied).
