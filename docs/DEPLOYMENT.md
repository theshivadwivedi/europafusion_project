# Deployment Guide

For the IT/Admin team putting the EUROPA FUSION Laravel site on a server.
**No database is required** – there is nothing to create, import or migrate.

## 1. Server requirements

| Component | Version / notes |
|---|---|
| PHP | **8.3 or newer** (tested on 8.4) with `mbstring`, `openssl`, `tokenizer`, `xml`/`dom`, `ctype`, `fileinfo`, `session` (all standard) |
| Web server | Apache 2.4 (mod_rewrite) or Nginx + PHP-FPM |
| Composer | 2.x |
| Disk | `storage/` and `bootstrap/cache/` writable by the PHP user |
| Database / Redis / queue worker | **Not needed** |
| Node.js | Only if you rebuild front-end assets (20+). Built assets ship in `public/build`. |

## 2. Install the code

```bash
cd /var/www
unzip europafusion-website.zip              # creates ./europafusion
cd europafusion
composer install --no-dev --optimize-autoloader
cp .env.example .env
php artisan key:generate
```

Edit `.env` – at minimum:

```dotenv
APP_ENV=production
APP_DEBUG=false
APP_URL=https://www.your-domain.com
MAIL_MAILER=smtp            # plus MAIL_HOST / MAIL_PORT / MAIL_USERNAME / MAIL_PASSWORD / MAIL_SCHEME
MAIL_FROM_ADDRESS=no-reply@your-domain.com
ENQUIRY_NOTIFY_EMAIL=contact@europafusion.io
SESSION_SECURE_COOKIE=true  # once HTTPS is active
```

## 3. Create the admin account

```bash
php artisan admin:create
# Name, email and password are prompted (min 10 chars, letters, numbers & symbols).
# Running it again with an existing email resets that admin's password.
```

The account is saved (password hashed) in `storage/app/private/admins.json`.
The lead desk is at `https://your-domain/admin`.

## 4. Permissions & optimisation

```bash
chown -R www-data:www-data storage bootstrap/cache
chmod -R ug+rwX storage bootstrap/cache
php artisan optimize          # caches config, routes, views and events
```

If you run `php artisan admin:create` as another user (e.g. root), re-run the `chown` above so
the web server can still read and update `storage/app/private/admins.json`.

After every later change to `.env` or code: `php artisan optimize:clear && php artisan optimize`.
Content edits in `resources/content/` show immediately and need no command.

## 5. Web server

Point the document root to **`public/`** – never the project root. This also keeps
`storage/app/private` (submissions and admin accounts) out of reach of the web.

**Apache** – the included `public/.htaccess` handles rewrites. Example vhost:

```apache
<VirtualHost *:80>
    ServerName www.your-domain.com
    DocumentRoot /var/www/europafusion/public
    <Directory /var/www/europafusion/public>
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>
```

**Nginx**:

```nginx
server {
    listen 80;
    server_name www.your-domain.com;
    root /var/www/europafusion/public;
    index index.php;
    location / { try_files $uri $uri/ /index.php?$query_string; }
    location ~ \.php$ {
        include fastcgi_params;
        fastcgi_pass unix:/run/php/php8.3-fpm.sock;   # match your PHP version
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
    }
    location ~* \.(?:css|js|woff2?|svg|png|jpg|ico)$ { expires 30d; access_log off; }
}
```

Enable HTTPS (e.g. `certbot --nginx` / `certbot --apache`) and then set `SESSION_SECURE_COOKIE=true`.

## 6. Where data is stored

| Data | Location |
|---|---|
| Enquiries | `storage/app/private/leads/enquiries/*.json` |
| Partner requests | `storage/app/private/leads/partners/*.json` |
| Admin accounts | `storage/app/private/admins.json` |
| Sessions / cache / rate limits | `storage/framework/` |
| Logs | `storage/logs/` |

To keep submissions elsewhere (e.g. a separate disk), set `LEADS_STORAGE_PATH=/absolute/path` in `.env`.

## 7. Email

Enquiry and B2B forms send two emails each: a notification to `ENQUIRY_NOTIFY_EMAIL` and an
acknowledgement to the applicant (`ENQUIRY_SEND_ACK=false` disables the latter). If SMTP fails,
the submission is still saved and visible in `/admin`; the failure is logged in `storage/logs`.

## 8. Updating assets (only when CSS/JS changes)

```bash
npm ci
npm run build      # writes public/build
```

## 9. Smoke test after deployment

1. Open `/`, `/destinations`, `/study-in-finland`, `/germany/master`, `/services`, `/exam-prep`, `/contact`, `/b2b`.
2. Submit the contact form → success message with a reference (EF-00001) → the enquiry shows in
   `/admin/enquiries` → a file appears in `storage/app/private/leads/enquiries/` → emails arrive.
3. Submit the B2B form → appears in `/admin/partners`.
4. `/sitemap.xml` lists your production domain (comes from `APP_URL`).

## 10. Backups

Back up `storage/app/private/` (submissions and admin accounts) and `.env`, for example nightly:

```bash
tar -czf europafusion-data-$(date +%F).tar.gz -C /var/www/europafusion storage/app/private .env
```

Everything else can be redeployed from the ZIP. To move the site to another server, copy the
same two items into the new installation.
