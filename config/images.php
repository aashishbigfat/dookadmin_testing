<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Image base URL
    |--------------------------------------------------------------------------
    |
    | Host that serves uploaded images. While 'layout' is "legacy" this is the
    | admin server itself; after the cutover it becomes the CDN domain in front
    | of the Cloud Storage bucket.
    |
    */

    'base_url' => env('IMAGE_BASE_URL', 'https://adm.dookinternational.com'),

    /*
    |--------------------------------------------------------------------------
    | Layout
    |--------------------------------------------------------------------------
    |
    | "legacy" builds <base>/dook/images/<module>/<file>, the paths served off
    | the admin server's public folder.
    |
    | "gcs" builds <base>/<mapped folder>/<file> using the 'folders' map below,
    | which is the structure the images were uploaded into.
    |
    | Cutover is these two env values, and reverting is the same two.
    |
    */

    'layout' => env('IMAGE_LAYOUT', 'legacy'),

    'legacy_path' => 'dook/images',

    /*
    |--------------------------------------------------------------------------
    | Upload bucket
    |--------------------------------------------------------------------------
    |
    | Where gcs_put() writes. Deliberately NOT the BUCKET_NAME env value, which
    | still points at the retired project's bucket and is read by the older
    | StorageClient blocks; this is a separate key so the two can be moved
    | independently.
    |
    */

    'bucket' => env('IMAGE_BUCKET', 'dook-images-prod'),

    'project_id' => env('GOOGLE_CLOUD_PROJECT_ID'),

    /*
    |--------------------------------------------------------------------------
    | Module to bucket folder
    |--------------------------------------------------------------------------
    |
    | Keys are the module names used at call sites, values are the prefixes in
    | gs://dook-images-prod. A module missing from this map falls through to its
    | own name, so an unmapped call still produces a usable path.
    |
    */

    'folders' => [
        'package'      => 'catalog/package',
        'poi'          => 'catalog/poi',
        'inclusions'   => 'catalog/inclusion',

        'country'      => 'geo/country',
        'flag'         => 'geo/country-flag',
        'region'       => 'geo/region',
        'destinations' => 'geo/destination',
        'restaurants'  => 'geo/restaurant',

        'activities'   => 'activity/activity',
        'experience'   => 'activity/experience',

        'home'         => 'editorial/home',
        'homeslider'   => 'editorial/home-slider',
        'home_videos'  => 'editorial/home-video',
        'landing'      => 'editorial/landing',
        'banner'       => 'editorial/banner',
        'about'        => 'editorial/about',
        'promotions'   => 'editorial/promotion',
        'mailer'       => 'editorial/mailer',
    ],

];
