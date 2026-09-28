# Moving images to Google Cloud Storage + Cloud CDN — dookadmin

Written 28 Sep 2026. This is a plan, not a change log — no application code was modified to produce it.

Goal: serve every user-uploaded image from Google Cloud Storage through Cloud CDN, on a clean folder structure, stop writing images to the application server's disk, and stop shipping 2.8 GB of images inside the git repo.

The important thing to know before reading: **this migration is already about 60% done and nobody finished it.** The plan below is about finishing and consolidating.

---

## 1. Where we are today

### 1.1 GCP is the platform, but two legacy call sites never got moved

AWS is retired. The code has not caught up:

| System | Used by | Status |
|---|---|---|
| **Google Cloud Storage** (bucket `dooktravels`, prefix `com/`) | Country, Region, Home, Landing, Top destinations, About, Activity, Experience, Destination, Departure, Existing POI, Package, POI | **89 live upload blocks across 16 controllers** — the real system |
| **Local disk** (`public/…`) | POI, packages, signatures, optional activities, PDF attachments | ~30 active write sites. The 2.8 GB problem. |
| **`Storage::disk('s3')`** | Banner (×2), one Home setting path, itinerary PDFs (×2) | ⚠️ **5 orphaned call sites — see below** |
| **`Storage::disk('spaces')`** (DigitalOcean) | Group package POI images (×2) | ⚠️ **2 orphaned call sites** |

⚠️ **These 7 call sites are a live bug, not a migration task.** `AWS_ENDPOINT` is not set in `.env`, so the `s3` disk resolves to real AWS at `ap-south-1` — not to GCS. If the AWS account is closed, then **banner image uploads and itinerary PDF uploads are failing right now**, either throwing or writing into a bucket nobody reads:

- [BannerController.php:78](../app/Http/Controllers/BannerController.php#L78) and [:143](../app/Http/Controllers/BannerController.php#L143) — `com/banner/`
- [HomeSettingController.php:509](../app/Http/Controllers/HomeSettingController.php#L509) — `com/home/`
- [ItineraryPdfController.php:56](../app/Http/Controllers/Departure/ItineraryPdfController.php#L56) and [:94](../app/Http/Controllers/Departure/ItineraryPdfController.php#L94) — itinerary PDFs
- [GroupPointOfInterestController.php:309](../app/Http/Controllers/Departure/GroupPointOfInterestController.php#L309) and [:322](../app/Http/Controllers/Departure/GroupPointOfInterestController.php#L322) — POI images to DigitalOcean

Check whether banners have uploaded successfully in the last few weeks before planning anything else. This wants fixing independently of the migration.

### 1.2 What is still on local disk

| Folder | Images | Size | Written by |
|---|---|---|---|
| `public/dook/images/poi/` | 10,047 | 1.7 GB | Package POI screens, PDF builder |
| `public/dook/images/package/` | 4,663 | 883 MB | Group package + fixed departure basic details |
| `public/dook/images/` (country, activities, home, landing, region, experience, banner, about, …) | ~1,483 | ~250 MB | Legacy — these modules write to GCS now |
| `public/signature/` | 613 | 16 MB | Signature upload |
| `public/images/restaurants/` | 403 | 34 MB | Static, committed once |
| `public/country/flag/` | 401 | 7.1 MB | Static, committed once |
| **Subtotal to migrate** | **~17,610** | **~2.86 GB** | |
| `public/bower_components/`, `dist/`, `plugins/`, `media/itinerary/`, and the 18 loose placeholder files in `dook/images/` | ~2,170 | ~72 MB | Application assets — **stay in the repo** |

That last row matters for the structure: `poi-no-image.jpg`, `package-no-image.jpg`, `Airport.png`, `Climate-Type.png` and friends are application chrome, not content. They ship with the code and do not belong in a content bucket.

### 1.3 The existing upload pattern, and its four defects

All 89 blocks are copies of this, e.g. [RegionController.php:96-119](../app/Http/Controllers/RegionController.php#L96-L119):

```php
$originalName = $image->getClientOriginalName();
$path = 'com/region/' . $originalName;
$storage = new StorageClient([
    'projectId'   => env('GOOGLE_CLOUD_PROJECT_ID'),
    'keyFilePath' => env('GOOGLE_CLOUD_KEY_FILE'),
]);
$bucket = $storage->bucket(env('BUCKET_NAME'));
$object = $bucket->upload(fopen($image->getRealPath(), 'r'), [
    'name' => $path,
    'metadata' => ['contentType' => $image->getMimeType()],
]);
$regions->image = $originalName;
```

1. **`getClientOriginalName()` means collisions.** Two people uploading `banner.jpg` to the same module silently overwrite each other. The older local code does not have this bug — it uses `Str::random(5).time()`.
2. **No `Cache-Control` on upload**, which directly undercuts the CDN.
3. **A `StorageClient` per upload**, re-reading the key file 89 different ways.
4. **Replaced objects are never deleted.** This is how 10,047 POI files accumulated on disk; in a bucket the same accumulation is metered.

### 1.4 Reads are the hard part

- **286 of 293** image references in Blade views hardcode `https://adm.dookinternational.com/dook/images/…`, across **50 files**, mostly the PDF templates in `resources/views/pdffiles/` and `resources/views/pdf-pages/`.
- Controllers add ~30 more via `url('/dook/images/…')`.
- **Nothing in this repo builds a read URL for the `com/…` objects.** The `dookwebsite` repo owns that, so the URL format is a cross-repo contract and any folder rename is a two-repo change.
- [PublishItineraryToFinderController.php](../app/Http/Controllers/PublishItineraryToFinderController.php) and [PublishItineraryToAppolyteController.php](../app/Http/Controllers/PublishItineraryToAppolyteController.php) push **absolute** `adm.dookinternational.com` URLs into external systems, which stored them. Those must keep resolving indefinitely.

### 1.5 Config that is already wrong

| Key | Value | Problem |
|---|---|---|
| `BUCKET_NAME` | `dooktravels` | The one actually used by all 89 uploads |
| `GOOGLE_CLOUD_STORAGE_BUCKET` | `dooktravels/com` | **Not a legal bucket name** — a slash is a path, not a bucket. Any code using this key fails. |
| `GOOGLE_CLOUD_KEY_FILE` | `/var/www/dookadmin/…json` | Absolute server path; breaks local development |
| `AWS_*` | Live-looking AWS keys | Dead platform. Remove from `.env` and from `config/filesystems.php` once the 5 call sites are fixed. |

[Helper.php:10](../app/Helpers/Helper.php#L10) `generateSignedUrl()` reads the broken key and is called by nothing. `uploadToGoogleCloud()` appears in commented-out code in [HomeSettingController.php:717](../app/Http/Controllers/HomeSettingController.php#L717) but **is not defined anywhere** — uncommenting those lines produces a fatal error.

---

## 2. Bucket and folder structure

### 2.1 Two buckets, split by who may read them

| Bucket | Contents | Access |
|---|---|---|
| `dook-images-prod` | All public content images | Public read, fronted by Cloud CDN |
| `dook-images-private` | Signatures, agent itineraries, itinerary PDFs | Private, signed URLs, no CDN |

Signatures are employee photographs and signatures. They are "protected" today only because the folder is unlisted, which is not access control. They should not go in a public bucket.

Add `dook-images-staging` if there is a staging environment. **Separate environments by bucket, not by prefix** — a prefix is one typo away from staging writing into production.

### 2.2 The public structure

Four top-level areas, matching how the content is actually bifurcated, then one folder per module:

```
gs://dook-images-prod/
│
├── catalog/                    the sellable product
│   ├── package/                package + departure images, galleries, banners
│   ├── poi/                    points of interest
│   └── inclusion/              inclusion icons
│
├── geo/                        places
│   ├── country/
│   ├── country-flag/
│   ├── region/
│   ├── destination/
│   └── restaurant/
│
├── activity/                   things to do
│   ├── activity/
│   └── experience/
│
├── editorial/                  marketing surfaces
│   ├── home/
│   ├── home-slider/
│   ├── home-video/
│   ├── landing/
│   ├── banner/
│   ├── promotion/
│   ├── about/
│   └── mailer/
│
└── _renditions/                derived sizes, generated (see 2.5)
    └── {preset}/{same path as the original}
```

```
gs://dook-images-private/
├── signature/
│   ├── employee/
│   └── scan/
├── itinerary/
│   ├── pdf/
│   └── agent/
└── _tmp/                       staging area for uploads, 1-day lifecycle rule
```

Why grouped rather than 15 folders at the root: the top level then answers "what kind of thing is this" at a glance, IAM conditions and lifecycle rules can be written per area (`catalog/*` is business-critical, `editorial/*` is replaceable), and new modules have an obvious home. A flat layout is cheaper to adopt but reads as a junk drawer at 15+ entries, and it is what we have now.

### 2.3 Naming rules

- **Lowercase, kebab-case, singular.** `home-slider`, not `homeslider` or `Home_Slider`.
- **No `com/` prefix.** It carries no meaning inside a bucket that only serves this business, and it makes every path two characters longer for nothing.
- **Folder is implied by the module, filename is stored in the database.** Keep it that way — the DB stores bare filenames (`$departure->image = $imageName`), so the folder structure lives entirely in code and **no database migration is needed**.
- **Object names stay unique and opaque:** `Str::random(5).time().'.'.$ext`, as the local code already does. Extend this to the GCS paths currently using `getClientOriginalName()`.
- **No date sharding.** It buys nothing — GCS has no per-folder limits — and it would force the DB to store full paths. Revisit only if one folder passes ~100k objects.

### 2.4 Old path → new path

| Today (local) | Today (GCS) | New |
|---|---|---|
| `dook/images/package/` | `com/package/` | `catalog/package/` |
| `dook/images/poi/` | `com/poi/` | `catalog/poi/` |
| `dook/images/inclusions/` | — | `catalog/inclusion/` |
| `dook/images/country/` | `com/country/` | `geo/country/` |
| `country/flag/` | — | `geo/country-flag/` |
| `dook/images/region/` | `com/region/` | `geo/region/` |
| `dook/images/destinations/` | `com/destinations/` | `geo/destination/` |
| `images/restaurants/` | — | `geo/restaurant/` |
| `dook/images/activities/` | `com/activities/` | `activity/activity/` |
| `dook/images/experience/` | `com/experience/` | `activity/experience/` |
| `dook/images/home/` | `com/home/` | `editorial/home/` |
| `dook/images/homeslider/` | — | `editorial/home-slider/` |
| `dook/images/home_videos/` | — | `editorial/home-video/` |
| `dook/images/landing/` | `com/landing/` | `editorial/landing/` |
| `dook/images/banner/` | `com/banner/` | `editorial/banner/` |
| `dook/images/about/` | `com/about/` | `editorial/about/` |
| `promotions/` | — | `editorial/promotion/` |
| `mailer/images/` | — | `editorial/mailer/` |
| `signature/images/` | — | `signature/employee/` *(private)* |
| `signature/images/emp_sign/` | — | `signature/scan/` *(private)* |
| — | `cloud_departure/itinerary/pdf/` | `itinerary/pdf/` *(private)* |
| `dook/images/*.jpg` (18 loose placeholders), `media/itinerary/`, `bower_components/`, `dist/`, `plugins/` | — | **Stay in the repo** |

Renaming the 11 existing `com/…` prefixes is a server-side copy (no download, no egress) plus an edit to the 89 call sites — which are being consolidated into one service anyway. Doing it now, while the structure is already being touched, is much cheaper than doing it later.

### 2.5 Renditions

Reserve `_renditions/{preset}/{original path}` now even if nothing writes there yet. Some code already converts to WebP at quality 60 on upload, which destroys the original. Keeping originals intact and deriving sizes underneath means a future size change is a re-render, not a re-upload — and retrofitting this namespace later is painful. Suggested presets: `thumb` (400px), `card` (800px), `hero` (1920px).

### 2.6 The 351 bad filenames

351 existing files contain spaces or special characters, some with a trailing space before the extension, such as `Azerbaijan Cultural .jpg`. Two options:

- **Encode them** — `rawurlencode` in the URL helper, filenames unchanged, no DB edit. Lowest risk.
- **Normalize them** — slugify during the move, then `UPDATE` the affected rows. Cleaner permanently, contained to 351 rows, needs a verified before/after mapping.

Recommendation: encode during the migration, normalize afterwards as its own task. Do not attempt both at once.

Watch for names differing only by case — the local disk is case-insensitive, GCS is not.

---

## 3. URL contract

One config value and one helper, used everywhere:

```php
// config/images.php
'base_url' => env('IMAGE_BASE_URL', 'https://img.dookinternational.com'),

// helper
function img_url(string $path): string {
    return config('images.base_url').'/'.implode('/', array_map('rawurlencode', explode('/', $path)));
}
```

Serve from a custom domain (`img.dookinternational.com`), never `storage.googleapis.com` directly, so the backend can change later without another 286-file edit.

---

## 4. Cloud CDN

Cloud CDN in front of a bucket needs an external Application Load Balancer with a backend bucket:

```bash
gcloud storage buckets create gs://dook-images-prod \
  --location=asia-south1 --uniform-bucket-level-access

gcloud storage buckets add-iam-policy-binding gs://dook-images-prod \
  --member=allUsers --role=roles/storage.objectViewer

gcloud compute backend-buckets create dook-images-backend \
  --gcs-bucket-name=dook-images-prod \
  --enable-cdn --cache-mode=CACHE_ALL_STATIC \
  --default-ttl=86400 --max-ttl=31536000 --negative-caching

gcloud compute url-maps create dook-images-lb --default-backend-bucket=dook-images-backend
gcloud compute ssl-certificates create dook-images-cert \
  --domains=img.dookinternational.com --global
gcloud compute target-https-proxies create dook-images-proxy \
  --url-map=dook-images-lb --ssl-certificates=dook-images-cert
gcloud compute addresses create dook-images-ip --global
gcloud compute forwarding-rules create dook-images-fr \
  --global --target-https-proxy=dook-images-proxy --ports=443 --address=dook-images-ip
```

Point `img.dookinternational.com` at the reserved IP. The managed certificate takes 15–60 minutes to provision, so do this a day before cutover.

Set `Cache-Control: public, max-age=31536000, immutable` on upload — safe because object names are unique. Invalidation is then rarely needed:

```bash
gcloud compute url-maps invalidate-cdn-cache dook-images-lb --path="/catalog/poi/FILENAME.jpg"
```

It is slow and rate-limited, which is the practical argument for fixing the `getClientOriginalName()` collision bug first.

---

## 5. Implementation phases

Each phase ships and reverts independently. Do not merge them.

### Phase 0 — Prerequisites (blocking)

- [ ] **Rotate the service account key.** `application-project-dook-int-9801963164ab.json` is committed to git (see `exposers.md`, item 5). Do not grant a leaked identity write access to new buckets.
- [ ] **Check whether banner and itinerary-PDF uploads are currently failing** (section 1.1). Fix those 7 call sites regardless of migration timing.
- [ ] Confirm what is in bucket `dooktravels` today, and that the website reads from GCS.
- [ ] Check the GCP org policy for **public access prevention** and domain-restricted sharing. If `allUsers` is blocked, the public-bucket design is dead and we need signed URLs or an authenticated CDN backend — decide before building.
- [ ] Agree the `img.dookinternational.com` URL format with whoever owns `dookwebsite`.

### Phase 1 — URL indirection (no behaviour change)

- [ ] Add `config/images.php` and the `img_url()` helper.
- [ ] Replace the 286 hardcoded URLs across 50 Blade files and ~30 controller `url('/dook/images/…')` calls.
- [ ] Point `IMAGE_BASE_URL` at `https://adm.dookinternational.com` so nothing changes yet.
- [ ] Deploy and verify. **After this, the cutover is a one-line env change.**

### Phase 2 — Infrastructure

- [ ] Create both buckets, the backend bucket, the load balancer and the certificate (section 4).
- [ ] Enable **object versioning** on the prod bucket — a 30-day undelete window is worth the pennies.
- [ ] Add the `_tmp/` 1-day lifecycle rule on the private bucket.
- [ ] Verify a hand-uploaded test object serves over `https://img.dookinternational.com/…` with the expected cache headers.

### Phase 3 — Inventory and bulk copy

- [ ] **Build the inventory from the database, not the disk.** Select every image column from every table and diff against the file listing. Expect a large share of the 14,700 POI and package files to be orphans — that is where 2.8 GB shrinks. Keep both lists.
- [ ] Copy referenced files into the new structure, one folder at a time, dry-run first:
  ```bash
  gcloud storage rsync -r -n /var/www/dookadmin/public/dook/images/poi \
    gs://dook-images-prod/catalog/poi
  ```
- [ ] Server-side copy the existing `com/…` objects to their new paths:
  ```bash
  gcloud storage cp -r gs://dooktravels/com/region gs://dook-images-prod/geo/region
  ```
- [ ] Set cache metadata:
  ```bash
  gcloud storage objects update "gs://dook-images-prod/**" \
    --cache-control="public, max-age=31536000, immutable"
  ```
- [ ] Copy signatures to the **private** bucket.
- [ ] Verify object counts and total bytes per folder against source, and spot-check ~20 files with odd names.

### Phase 4 — Cut reads over

- [ ] Update the module→folder map in the helper to the new paths.
- [ ] Set `IMAGE_BASE_URL=https://img.dookinternational.com` and deploy, with `dookwebsite`.
- [ ] Verify admin screens, then **PDF generation specifically** — see risk R3.
- [ ] Leave local files in place. Reverts by changing one env value back.

### Phase 5 — Cut writes over

- [ ] Write one `ImageStorageService` with `put()`, `delete()` and `url()`. Do not add a 90th copy-pasted upload block.
- [ ] Migrate the ~30 local write sites: [PdfController.php](../app/Http/Controllers/PdfController.php) (9), [GroupDepartureController.php](../app/Http/Controllers/Departure/GroupDepartureController.php) (8), [SignatureController.php](../app/Http/Controllers/SignatureController.php) (6), [FixedDepartureController.php](../app/Http/Controllers/Departure/FixedDepartureController.php) (3), [PointOfInterestController.php](../app/Http/Controllers/Departure/PointOfInterestController.php) (2), [OptionalActivityController.php](../app/Http/Controllers/OptionalActivityController.php) (2).
- [ ] Fold the 89 `StorageClient` blocks into the same service, fixing the collision bug and the new prefixes together.
- [ ] Retire the 7 S3/Spaces call sites and delete the `s3` and `spaces` disks from `config/filesystems.php`, plus the `AWS_*` and `DO_*` keys from `.env`.
- [ ] **Add deletion of the replaced object on every update path.**
- [ ] Re-run the Phase 3 sync to catch anything uploaded mid-transition.

### Phase 6 — Keep old URLs alive

- [ ] Add nginx rules on `adm.dookinternational.com` mapping each old folder to its new path, e.g. `/dook/images/poi/…` → `https://img.dookinternational.com/catalog/poi/…` (301).
- [ ] This is **permanent**, not transitional — external systems hold these URLs and we do not control when they refresh.

### Phase 7 — Reclaim the space

- [ ] After a 30-day soak, delete the local folders from the server.
- [ ] Add `public/dook/images/` and `public/signature/` to `.gitignore` and `git rm -r --cached` them.
- [ ] Removing them from git *history* needs a force-push rewrite — decide separately, it breaks every existing clone.
- [ ] Delete the Phase 3 orphans from the bucket, after re-confirming against the DB.

---

## 6. Risks and blockers

| # | Risk | Impact | Mitigation |
|---|---|---|---|
| R1 | Org policy blocks public buckets | Redesign around signed URLs; breaks CDN caching and published URLs | Check in Phase 0, before any build |
| R2 | External systems hold absolute `adm.` URLs | Broken images on partner sites, outside our control | Permanent nginx redirect (Phase 6) |
| R3 | **PDF generation** | dompdf runs with `enable_remote` on, so every image becomes an HTTPS fetch — much slower, fails on timeout. [PdfController.php](../app/Http/Controllers/PdfController.php) also does local `file_exists` / `Image::make(public_path(…))` in 10 places | Test PDFs first in Phase 4. Consider a local cache directory for rendering |
| R4 | Leaked service account key | Bucket write access for anyone with repo history | Rotate in Phase 0 — blocking |
| R5 | 351 filenames with spaces/special characters | 404s from any path built by concatenation | `rawurlencode` in the helper (section 2.6) |
| R6 | Banner/PDF uploads may be broken right now | Silent data loss on those screens | Verify in Phase 0 (section 1.1) |
| R7 | `dookwebsite` owns the read URLs | Cutover breaks the public site if uncoordinated | Agree in Phase 0; deploy both repos together |
| R8 | Folder rename touches 89 call sites | Half-migrated prefixes, images 404 | Do the rename inside the Phase 5 consolidation, never piecemeal |
| R9 | No automated tests; duplicated, half-commented image code | Regressions land unnoticed | Manual checklist per screen; ship phases separately |
| R10 | Deletion still missing everywhere | Metered accumulation instead of free accumulation | Fix in Phase 5, not "later" |

---

## 7. Decisions needed before Phase 1

1. Adopt the grouped structure in 2.2, or keep a flat one-folder-per-module layout?
2. Is `img.dookinternational.com` the image domain?
3. Do signatures move to a private bucket (recommended), or stay public-by-obscurity?
4. Are the 7 orphaned S3/Spaces call sites fixed now, or folded into Phase 5?
5. Who owns the matching change in `dookwebsite`, and when can both deploy together?
6. Do we rewrite git history to reclaim repo size, or only stop tracking images going forward?
