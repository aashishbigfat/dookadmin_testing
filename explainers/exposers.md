# Exposed secrets and access points — dookadmin

Found on 19 Sep 2026 while preparing the GitHub push. This file lists **where** each secret is exposed, never the secret itself. Don't paste secret values into it.

"Live" below means the value was checked against the server's current `.env` and matches.

## Summary

| # | Where | What's exposed | Who can see it | Severity |
|---|---|---|---|---|
| 1 | `https://adm.dookinternational.com/info` | 12 live secrets from `.env`, in plain text | Anyone on the internet, no login | Critical |
| 2 | GitHub repo `aashishbigfat/dookadmin_testing` (public since 12 Sep 2026) | Live DB password, live CRM token, AWS keys, mail passwords, Meta tokens | Anyone on the internet | Critical |
| 3 | `https://adm.dookinternational.com/signature/main.ed24b2b42882bb60cf56.js` | Meta/Facebook tokens, Google API keys | Anyone on the internet | High |
| 4 | Website code (`dookwebsite`) | Live CRM token hardcoded | Anyone with the code | Medium |
| 5 | Local git commit in `/var/www/dookadmin/.git` (not pushed) | Google Cloud service account private key | Anyone the repo is pushed to | Medium |
| 6 | Website routes `/route-clear` and `/view-clear` | Clear Laravel caches | Anyone on the internet | Low |

---

## 1. Public `/info` page shows `phpinfo()` (critical)

**Where:** `routes/web.php` lines 29–31, outside the `auth` group, so no login is needed:

```php
Route::get('/info',function(){
return phpinfo();
});
```

`phpinfo()` prints every environment variable, and Laravel loads `.env` into them. So these live values are shown in plain text at `https://adm.dookinternational.com/info`:

- `APP_KEY`
- `DB_PASSWORD`
- `AWS_ACCESS_KEY_ID`, `AWS_SECRET_ACCESS_KEY`
- `DO_SPACES_KEY`, `DO_SPACES_SECRET`
- `META_APP_SECRET`, `META_LONG_LIVED_PAGE_TOKEN`, `META_USER_ACCESS_TOKEN`, `META_VERIFY_TOKEN`
- `TUTTERFLY_CRM_TOKEN`
- `GOOGLE_CLOUD_KEY_FILE` (the path to the key file, not the key)

There's also a standalone `info.php` in the project root. It isn't reachable from the web because it sits outside `public/`, but it serves no purpose.

**Fix:**
- [ ] Delete the `/info` route from `routes/web.php`.
- [ ] Delete `info.php` from the project root.
- [ ] Rotate everything in the list above (see the checklist at the end). Removing the page doesn't make values that were already visible safe again.

---

## 2. Public GitHub repo `aashishbigfat/dookadmin_testing` (critical)

The repo is public and has two commits (12 Sep "Initial commit: Dook admin (Laravel 9)" and 14 Sep "docker files added"), 6,277 files, no images.

Not in the repo: `.env` and the Google Cloud key file `application-project-dook-int-9801963164ab.json`. The `APP_KEY` in `.env.example` is not the live one.

Hardcoded secrets in the pushed code:

| File | Lines | What | Live? |
|---|---|---|---|
| `public/signature/db_connect.php` | 4, 7 | Database password | **Yes, the production `DB_PASSWORD`** |
| `app/Http/Controllers/EnquiryController.php` | 327 | Tutterfly CRM token | **Yes, the live `TUTTERFLY_CRM_TOKEN`** |
| `app/Http/Controllers/MetaLeadControllerbackup.php` | 849 | Same CRM token, as the `env()` fallback | Yes |
| `app/Http/Controllers/backupMetaLeadController.php` | 579 | Same CRM token, as the `env()` fallback | Yes |
| `app/Http/Controllers/ImageCompressController.php` | 39–40, 109–110, 179–180 | AWS access key ID and secret | Not the pair in `.env`; may still be valid |
| `app/Traits/TinyPngImageCompress.php` | 37–38 | AWS access key ID and secret | Not the pair in `.env`; may still be valid |
| `config/mail.php` | 43, 53, 64 | Hardcoded mail passwords (two different values; one is 16 characters, the length of a Gmail app password) | Not the ones in `.env`; may still be valid |
| `public/signature/main.ed24b2b42882bb60cf56.js` | (minified) | 15 Meta/Facebook tokens, 2 Google API keys | Not checked |
| `resources/views/packages/destination_poi_create.blade.php` | 940, 941, 971 | Google Maps API key | Browser key, visible to users anyway |
| `resources/views/grouppackages/destination_poi_create.blade.php` | 925, 956 | Google Maps API key | Browser key, visible to users anyway |
| `resources/views/departure/destination_poi_create.blade.php` | 1008, 1040 | Google Maps API key | Browser key, visible to users anyway |

Git history keeps these values even after the files change, so rotating the credentials is what actually fixes this.

**Fix:**
- [ ] Decide whether the repo should stay public (Settings → General → Danger Zone → Change visibility).
- [ ] Move each hardcoded value into `.env` and read it with `env()` / `config()`.
- [ ] Delete the two backup controllers (`MetaLeadControllerbackup.php`, `backupMetaLeadController.php`) if they aren't used.
- [ ] Rotate the DB password, CRM token, AWS keys and mail passwords (checklist below).
- [ ] Restrict the Google Maps keys to the `dookinternational.com` domains in Google Cloud Console → APIs & Services → Credentials.
- [ ] Optional: rewrite the repo history to remove the old values (e.g. with `git filter-repo`), then force-push.

---

## 3. Tokens in a public JavaScript file (high)

**Where:** `public/signature/main.ed24b2b42882bb60cf56.js` (4 MB). It's served to anyone at `https://adm.dookinternational.com/signature/main.ed24b2b42882bb60cf56.js`, so the tokens are exposed whether or not the repo is private.

It contains 15 strings shaped like Meta/Facebook access tokens and 2 Google API keys. Their validity hasn't been checked.

**Fix:**
- [ ] Find out what `public/signature/` is used for, and whether this built file needs to be public.
- [ ] Check the Meta tokens in Meta's Access Token Debugger and revoke any that are valid.
- [ ] Restrict or replace the Google API keys.

---

## 4. CRM token hardcoded in the website (medium)

**Where:** `/var/www/dookwebsite/app/Http/Controllers/Frontend/InquiryController.php` line 418. It's the same live Tutterfly CRM token as in item 2.

**Fix:**
- [ ] Read the token from `.env` instead, and update it here when the token is rotated.

---

## 5. Google Cloud private key in a local commit (medium, not pushed)

**Where:** `/var/www/dookadmin/.git`, commit `4199be5e` ("Initial commit (Cleaned history and ignored files)"). It includes `application-project-dook-int-9801963164ab.json`, a Google Cloud service account key (`...@application-project-dook-int.iam.gserviceaccount.com`) with its private key.

This commit was never pushed; the push failed because it was 2.38 GB.

**Fix:**
- [ ] Add `application-project-dook-int-9801963164ab.json` to `.gitignore`.
- [ ] Don't push commit `4199be5e`. Build new commits without the key file instead.

---

## 6. Public cache-clearing routes on the website (low)

**Where:** `/var/www/dookwebsite/routes/web.php` lines 24–31. `/route-clear` and `/view-clear` run `artisan route:clear` and `artisan view:clear` for anyone who opens them. They don't expose data, but anyone can force the site to rebuild its caches.

**Fix:**
- [ ] Remove these routes, or move them behind a login.

---

## Rotation checklist

Once a value has been visible, changing the code doesn't make it safe again. Replace each credential, then update `.env` (and the code, where it was hardcoded).

| Credential | Exposed via | Where to rotate |
|---|---|---|
| `DB_PASSWORD` | `/info`, GitHub | Google Cloud SQL (DB host `192.168.4.7`) → Users |
| `APP_KEY` | `/info` | `php artisan key:generate`, then run the same in `dookwebsite` if it shares the key. This logs everyone out and invalidates encrypted values. |
| `AWS_ACCESS_KEY_ID` / `AWS_SECRET_ACCESS_KEY` | `/info` | AWS IAM → Users → Security credentials |
| AWS keys hardcoded in `ImageCompressController.php` / `TinyPngImageCompress.php` | GitHub | AWS IAM (deactivate them if no longer used) |
| `DO_SPACES_KEY` / `DO_SPACES_SECRET` | `/info` | DigitalOcean → API → Spaces keys |
| `META_APP_SECRET`, `META_LONG_LIVED_PAGE_TOKEN`, `META_USER_ACCESS_TOKEN`, `META_VERIFY_TOKEN` | `/info` | Meta for Developers → App settings → reset app secret; generate new tokens |
| Meta tokens in `public/signature/main...js` | Public URL, GitHub | Meta Access Token Debugger → revoke |
| `TUTTERFLY_CRM_TOKEN` | `/info`, GitHub | Tutterfly CRM settings |
| Mail passwords in `config/mail.php` | GitHub | The mail provider (for Gmail app passwords: Google Account → Security → App passwords) |
| Google Maps API keys | Views, GitHub, public JS | Google Cloud Console → Credentials → restrict or regenerate |
