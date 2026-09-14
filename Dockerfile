# syntax=docker/dockerfile:1
#
# dookadmin (adm.dook.bigfat.ai) - the Laravel 9 admin panel behind the Dook
# travel site: packages, departures, landing pages, leads (including the Meta
# lead sync), PDFs and signatures. It reads and writes the SAME `dookweb`
# database dookwebsite serves from, over the same private-IP Cloud SQL host
# (192.168.4.7) reachable only through the VM's OpenVPN tunnel.
#
# Built to run as a THIRD container on the Compute Engine VM that already
# hosts dookwebsite and dookblog.
#
# --- Ports: unique across all three apps, deliberately --------------------
#
#     app           nginx       php-fpm
#     dookwebsite   80 / 443    9000
#     dookblog      8001        9001
#     dookadmin     8002        9002      <- this image
#
# Nothing calls this app over loopback - dookwebsite links to adm.* by public
# URL - so unlike dookblog it has no need to share dookwebsite's network
# namespace, and 80/9000 would work in a namespace of its own. The unique
# ports are there because dookblog proved what happens otherwise: a shared
# namespace shares EVERY port, and a clash does not fail loudly. dookblog's
# php-fpm could not bind 9000, nginx started anyway, and blog URLs were served
# by dookwebsite's php-fpm executing dookwebsite's index.php - confident 200s
# and 302s from the wrong application. With unique ports this image is correct
# under whichever wiring is chosen at deploy time.
#
# --- TLS ------------------------------------------------------------------
#
# Not terminated here. Host port 443 belongs to dookwebsite's container, so
# adm.dook.bigfat.ai is expected to arrive through a TLS-terminating proxy in
# front of this one. Nothing sets `fastcgi_param HTTPS on`: the app learns the
# real scheme from the proxy's X-Forwarded-Proto header, which
# app/Http/Middleware/TrustProxies.php already trusts ($proxies = '*').
#
# --- No Node stage --------------------------------------------------------
#
# webpack.mix.js exists, but no view calls mix(); the admin UI's assets
# (public/css, js, dist, plugins, bower_components) are committed.
#
# No application source file is modified to produce this image.


# ---------------------------------------------------------------------
# Stage 1: PHP dependencies (no dev packages)
# ---------------------------------------------------------------------
# Installed before the source is copied, so editing a controller does not
# invalidate the composer layer.
#
# The source IS needed before dump-autoload: composer.json autoloads
# app/Helpers/Helper.php as a `files` entry.
#
# app/Http/Controllers contains two backup copies of MetaLeadController
# (backupMetaLeadController.php, MetaLeadControllerbackup.php), each declaring
# the same class. An authoritative classmap could in principle bind the wrong
# one; Composer 2 instead skips files whose name does not match the class they
# declare ("does not comply with psr-4 autoloading standard"), so the real
# controller wins. Those warnings in the build log are expected.
FROM composer:2 AS vendor
WORKDIR /app
COPY composer.json composer.lock ./
RUN composer install \
        --no-dev \
        --no-scripts \
        --no-autoloader \
        --prefer-dist \
        --ignore-platform-reqs
COPY . .
RUN composer dump-autoload --no-dev --optimize --classmap-authoritative


# ---------------------------------------------------------------------
# Stage 2: runtime
# ---------------------------------------------------------------------
# PHP 8.2 matches the other two images on this VM, and unlike dookblog's
# Laravel 8 this is inside the framework's supported range: Laravel 9
# supports PHP 8.0-8.2.
FROM php:8.2-fpm-bookworm AS runtime

# gd: intervention/image has no published config/image.php, so it uses the GD
#     driver, and dompdf needs it to embed raster images in PDFs.
# exif: intervention reads photo orientation from it.
# dom, mbstring, iconv, fileinfo and libxml - required by dompdf and friends -
# are already compiled into the base image.
RUN apt-get update && apt-get install -y --no-install-recommends \
        nginx \
        supervisor \
        curl \
    && curl -sSLf -o /usr/local/bin/install-php-extensions \
        https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions \
    && chmod +x /usr/local/bin/install-php-extensions \
    && install-php-extensions gd exif pdo_mysql opcache \
    && rm -rf /var/lib/apt/lists/* /tmp/pear

WORKDIR /var/www/html

# Source first, then the resolved vendor/ on top, so a stray host vendor/ can
# never overwrite the dependency set this image built.
COPY . .
COPY --from=vendor /app/vendor ./vendor

# --- php.ini -----------------------------------------------------------
COPY <<'PHPINI' /usr/local/etc/php/conf.d/zz-app.ini
; memory_limit: dompdf renders whole itineraries with images in memory, and
; Intervention holds decoded bitmaps. 768M covers both; pm.max_children = 4
; bounds the worst case at ~3G on a VM shared with two other containers.
memory_limit = 768M

; Package media is uploaded to PHP before being pushed to S3 / Spaces, and
; public/dook/images/home_videos shows video uploads happen. Keep in step
; with client_max_body_size in the nginx config below.
upload_max_filesize = 128M
post_max_size = 128M

; The default for ordinary requests. PdfController and ImageCompressController
; raise their own limits with ini_set() (720s and 540s) - which only helps if
; nginx waits that long too; see fastcgi_read_timeout.
max_execution_time = 300
max_input_time = 300

; Admin forms (itineraries, departures) post many fields. Past this limit PHP
; does not error - it silently DROPS the remaining fields, which saves a
; half-filled record. Generous on purpose.
max_input_vars = 5000

expose_php = Off
date.timezone = UTC

error_reporting = E_ALL & ~E_DEPRECATED & ~E_STRICT
log_errors = On
display_errors = Off

opcache.enable = 1
opcache.enable_cli = 0
opcache.memory_consumption = 192
opcache.interned_strings_buffer = 16
opcache.max_accelerated_files = 20000
opcache.validate_timestamps = 1
opcache.revalidate_freq = 2
PHPINI

# --- php-fpm pool ------------------------------------------------------
COPY <<'FPMPOOL' /usr/local/etc/php-fpm.d/zz-www.conf
; Overrides the base image's www pool (loaded after it, alphabetically).
[www]
user = www-data
group = www-data

; 9002 - see the port table at the top of the Dockerfile.
listen = 127.0.0.1:9002

; An admin panel for a handful of staff, sharing the VM. 'ondemand' costs
; nothing when idle. max_requests recycles workers regularly because dompdf
; is not frugal with memory across long-lived processes.
pm = ondemand
pm.max_children = 4
pm.process_idle_timeout = 30s
pm.max_requests = 200

catch_workers_output = yes
decorate_workers_output = no
php_admin_value[error_log] = /proc/self/fd/2
php_admin_flag[log_errors] = on

; Load-bearing. The entrypoint runs `artisan config:cache`, after which
; Laravel stops reading .env, and this app calls env() directly from
; application code roughly 300 times - GOOGLE_CLOUD_KEY_FILE alone 78 times,
; GOOGLE_CLOUD_PROJECT_ID and BUCKET_NAME 76 each, pullIt_BaseUrl 45, plus the
; META_*, TINIFY_* and TUTTERFLY_CRM_TOKEN values. Those resolve from the
; worker's real process environment or not at all; clear_env = yes would
; strip them and break storage, leads and the CRM integration at once.
clear_env = no
FPMPOOL

# --- nginx site --------------------------------------------------------
COPY <<'NGINXCONF' /etc/nginx/sites-available/default
# Serves the Laravel front controller out of public/. nginx never reads
# .htaccess, so public/.htaccess's rules are restated below.

server {
    listen 8002 default_server;
    server_name _;
    root /var/www/html/public;
    index index.php;

    # Must stay in step with upload_max_filesize / post_max_size.
    client_max_body_size 128M;

    # Relative Location headers. Behind a TLS-terminating proxy, nginx's
    # default absolute redirects are rebuilt from ITS OWN port and scheme and
    # would send browsers to http://host:8002/... - learned on dookblog.
    absolute_redirect off;

    access_log /dev/stdout;
    error_log  /dev/stderr warn;

    gzip on;
    gzip_vary on;
    gzip_types
        text/plain text/css text/xml text/javascript
        application/json application/javascript application/x-javascript
        application/xml application/rss+xml application/xhtml+xml
        image/svg+xml font/woff font/woff2;

    # --- Block the public phpinfo() route --------------------------------
    # routes/web.php registers, OUTSIDE the auth group:
    #
    #     Route::get('/info', function () { return phpinfo(); });
    #
    # phpinfo() prints the process environment, and in this container the
    # secrets ARE the process environment (they must be - see clear_env in
    # the pool config). On a public hostname that one page would hand anyone
    # DB_PASSWORD, AWS_SECRET_ACCESS_KEY, the Meta tokens and APP_KEY.
    #
    # Blocked here because this image must not ship that open. The real fix
    # is deleting the route. The pattern also covers /info/, any letter case,
    # and /index.php/info - that last form reaches the same Laravel route via
    # PATH_INFO, and a plain `location = /info` would miss it. Must stay the
    # FIRST regex location, since nginx takes the first regex that matches.
    location ~* ^/(index\.php/+)?info/*$ {
        return 404;
    }

    # .htaccess: "Redirect Trailing Slashes If Not A Folder". The `.+` keeps
    # "/" itself from matching.
    location ~ ^(?<no_slash>.+)/$ {
        return 301 $no_slash$is_args$args;
    }

    # The app writes files into these public directories at runtime
    # (PdfController, signature uploads, profile images, TinyPNG output).
    # Refuse to execute PHP from any of them, so an upload can never become
    # code, whatever it is named.
    location ~ ^/(pdffiles|signature|dook|storage|images/uploads)/.*\.php$ {
        deny all;
    }

    location ~ /\.(?!well-known) {
        deny all;
    }

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        # 9002 - this container's own php-fpm (see the port table).
        fastcgi_pass 127.0.0.1:9002;
        fastcgi_index index.php;
        include fastcgi_params;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;

        # PdfController allows itself 720s. nginx's default read timeout is
        # 60s, so without this every large PDF would come back as a 504
        # while PHP carried on rendering it for nobody.
        fastcgi_read_timeout 900;
        fastcgi_send_timeout 900;
    }

    location ~* \.(css|js|map|jpg|jpeg|png|gif|webp|svg|ico|woff|woff2|ttf|otf|eot|mp4|webm|pdf)$ {
        try_files $uri =404;
        expires 30d;
        add_header Cache-Control "public";
        access_log off;
    }
}
NGINXCONF

# --- supervisor --------------------------------------------------------
COPY <<'SUPERVISORD' /etc/supervisor/conf.d/supervisord.conf
; nginx + php-fpm in one container, matching the other two apps on the VM.
; The Laravel scheduler is added by the entrypoint only when RUN_SCHEDULER is
; true - see the note there on why it is off by default.

[supervisord]
nodaemon=true
user=root
logfile=/dev/stdout
logfile_maxbytes=0
pidfile=/var/run/supervisord.pid

; Supervisor reads only the file passed with -c. Debian's stock config has an
; [include] for conf.d/, but this image starts supervisord with THIS file, so
; without the section below a program the entrypoint drops in is never
; loaded. Verified the hard way: RUN_SCHEDULER=true logged "ENABLED" while no
; scheduler process ever started. A separate directory, because including
; conf.d/*.conf would load this file a second time.
[include]
files = /etc/supervisor/extra/*.conf

[program:php-fpm]
command=php-fpm -F
autostart=true
autorestart=true
priority=5
stdout_logfile=/dev/stdout
stdout_logfile_maxbytes=0
stderr_logfile=/dev/stderr
stderr_logfile_maxbytes=0

[program:nginx]
command=nginx -g "daemon off;"
autostart=true
autorestart=true
priority=10
stdout_logfile=/dev/stdout
stdout_logfile_maxbytes=0
stderr_logfile=/dev/stderr
stderr_logfile_maxbytes=0
SUPERVISORD

# --- entrypoint --------------------------------------------------------
COPY --chmod=755 <<'ENTRYPOINT_SH' /usr/local/bin/entrypoint.sh
#!/usr/bin/env bash
# Prepare the Laravel app, then hand off to CMD (supervisord).
set -euo pipefail

cd /var/www/html

fail() { echo "[entrypoint] ERROR: $*" >&2; exit 1; }

[ -f vendor/autoload.php ] || fail "vendor/autoload.php is missing from the image."
[ -n "${APP_KEY:-}" ]      || fail "APP_KEY is not set in the container environment."

# Host-compiled caches can reference dev-only packages; all are rebuilt below.
rm -f bootstrap/cache/*.php

echo "[entrypoint] Ensuring writable directories exist..."
# storage/fonts holds COMMITTED dompdf font files (including the Dancing
# Script signature font) that must not be touched - only made writable,
# because dompdf also caches font metrics there.
mkdir -p \
    storage/framework/cache/data \
    storage/framework/sessions \
    storage/framework/views \
    storage/framework/testing \
    storage/logs \
    storage/app/public \
    storage/fonts \
    bootstrap/cache

# Every public directory the app writes into at runtime. None of these are in
# git (or only partly), so a clean build does not have them.
mkdir -p \
    public/pdffiles \
    public/signature/images/emp_sign \
    public/dook/pdf \
    public/storage/profile_images \
    public/images/uploads/tiny

# --- Google Cloud Storage key -------------------------------------------
# 78 call sites pass env('GOOGLE_CLOUD_KEY_FILE') to StorageClient as
# keyFilePath. Without a readable key they fall back to the VM's own service
# account, which cannot reach the dooktravels bucket, and storage calls fail.
#
# Preferred source: GOOGLE_CLOUD_KEY_JSON_BASE64 (a GitHub secret on this
# repo). It is decoded to a file owned by www-data, mode 400 - owned by the
# user the php-fpm WORKERS run as, not root. dookwebsite shipped a root-owned
# key once: `docker exec` checks passed (they run as root) while every real
# request failed with "Permission denied".
DEFAULT_KEY_FILE=/var/www/secrets/gcs-key.json
if [ -n "${GOOGLE_CLOUD_KEY_JSON_BASE64:-}" ]; then
    # Honour a supplied container path; ignore anything else, such as the
    # Windows path a developer's local .env carries.
    case "${GOOGLE_CLOUD_KEY_FILE:-}" in
        /*) key_file="$GOOGLE_CLOUD_KEY_FILE" ;;
        *)  key_file="$DEFAULT_KEY_FILE" ;;
    esac
    mkdir -p "$(dirname "$key_file")"
    # umask in a SUBSHELL: set globally it would also apply to the config and
    # route caches written below as root, making them unreadable to www-data.
    ( umask 077
      printf '%s' "$GOOGLE_CLOUD_KEY_JSON_BASE64" | tr -d ' \r\n' | base64 -d > "$key_file" 2>/dev/null ) \
        || fail "GOOGLE_CLOUD_KEY_JSON_BASE64 is not valid base64."
    grep -q '"private_key"' "$key_file" && grep -q '"client_email"' "$key_file" \
        || fail "GOOGLE_CLOUD_KEY_JSON_BASE64 decoded, but not to a service-account key."
    chown www-data:www-data "$(dirname "$key_file")" "$key_file"
    chmod 500 "$(dirname "$key_file")"
    chmod 400 "$key_file"
    export GOOGLE_CLOUD_KEY_FILE="$key_file"
    echo "[entrypoint] GCS auth: key decoded to $key_file (www-data, 0400)"
elif [ -n "${GOOGLE_CLOUD_KEY_FILE:-}" ]; then
    # A mounted key. Test readability AS www-data - root can read anything,
    # which is exactly how a root-owned key slips through.
    if setpriv --reuid=www-data --regid=www-data --clear-groups test -r "$GOOGLE_CLOUD_KEY_FILE"; then
        echo "[entrypoint] GCS auth: key file at $GOOGLE_CLOUD_KEY_FILE (readable by www-data)"
    else
        echo "[entrypoint] WARNING: $GOOGLE_CLOUD_KEY_FILE is not readable by www-data." >&2
        echo "[entrypoint]          Cloud Storage calls will fail. chown it to uid 33." >&2
    fi
else
    echo "[entrypoint] WARNING: no GOOGLE_CLOUD_KEY_JSON_BASE64 or GOOGLE_CLOUD_KEY_FILE." >&2
    echo "[entrypoint]          Cloud Storage calls will fail." >&2
fi

# The raw key must not outlive this script. Everything started below inherits
# this environment, and /info (blocked in nginx) is not the only way a
# process environment gets printed.
unset GOOGLE_CLOUD_KEY_JSON_BASE64

echo "[entrypoint] Discovering packages..."
php artisan package:discover --ansi

# Rebuilt at start, not baked in: config values only exist as real process
# environment variables at runtime.
echo "[entrypoint] Caching config and views..."
php artisan config:clear >/dev/null
php artisan config:cache
php artisan view:cache

# NOTE: `php artisan route:cache` is deliberately NOT run - verified, not
# assumed. Laravel 9 can serialise the closure routes in routes/web.php, but
# route caching also refuses DUPLICATE ROUTE NAMES, and web.php has them:
#
#     Unable to prepare route [dep-banner-image-crop] for serialization.
#     Another route has already been assigned name [dep_featured_image_crop].
#
# Under `set -e` that failure kills the container at startup. Duplicate names
# are also a latent bug on their own: route('dep_featured_image_crop') can
# only ever resolve to ONE of the routes sharing it. Re-enable caching once
# the names in routes/web.php are unique.
#
# (Each of the three apps on this VM skips route caching for a different
# reason: dookblog's Laravel 8 cannot serialise closures, dookwebsite builds
# its route table from a database query, and this one has duplicate names.)

# LAST, after every artisan command. Anything above that writes a file as root
# - above all storage/logs/laravel.log if a command logs - would otherwise
# leave php-fpm workers unable to write it, and every request would fail.
chown -R www-data:www-data \
    storage bootstrap/cache \
    public/pdffiles public/signature/images public/dook public/storage public/images/uploads
chmod -R ug+rwX storage bootstrap/cache

# --- Scheduler: OFF unless RUN_SCHEDULER=true ----------------------------
# app/Console/Kernel.php runs the Meta lead sync every five minutes. The
# production admin at adm.dookinternational.com writes the same `dookweb`
# database, and presumably runs the same schedule. ->withoutOverlapping() is
# no protection across servers: its lock lives in this container's FILE
# cache, which the other server cannot see. Two schedulers would import the
# same leads twice. Turn it on here only when this deployment is the one
# meant to own the sync.
mkdir -p /etc/supervisor/extra
if [ "${RUN_SCHEDULER:-false}" = "true" ]; then
    cat > /etc/supervisor/extra/scheduler.conf <<'SCHEDULER'
[program:scheduler]
command=php /var/www/html/artisan schedule:work
user=www-data
autostart=true
autorestart=true
priority=20
stdout_logfile=/dev/stdout
stdout_logfile_maxbytes=0
stderr_logfile=/dev/stderr
stderr_logfile_maxbytes=0
SCHEDULER
    echo "[entrypoint] Scheduler: ENABLED (Meta lead sync every 5 minutes)"
else
    rm -f /etc/supervisor/extra/scheduler.conf
    echo "[entrypoint] Scheduler: disabled (set RUN_SCHEDULER=true to enable)"
fi

echo "[entrypoint] Ready. Starting: $*"
exec "$@"
ENTRYPOINT_SH

EXPOSE 8002

# The login page renders without a database, so a dropped VPN tunnel does not
# restart-loop a healthy container. Asserts the login FORM is present rather
# than trusting a bare 200.
HEALTHCHECK --interval=30s --timeout=5s --start-period=40s --retries=3 \
    CMD curl -fsS --max-time 4 http://127.0.0.1:8002/ | grep -q 'name="password"' || exit 1

ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]
CMD ["/usr/bin/supervisord", "-n", "-c", "/etc/supervisor/conf.d/supervisord.conf"]
