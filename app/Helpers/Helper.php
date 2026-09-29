<?php

use Google\Cloud\Storage\StorageClient;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;


if (!function_exists('gcs_put')) {
    /**
     * Write an object to the application's own Cloud Storage bucket.
     *
     * Replaces the Storage::disk('s3') and Storage::disk('spaces') calls that
     * used to live in the upload controllers. AWS and DigitalOcean are both
     * retired; those disks wrote to accounts that no longer serve anything.
     *
     * Credentials come from Application Default Credentials - on the VM that is
     * the attached service account, so no key file is involved. The object path
     * is left exactly as the callers had it, so whatever reads these images
     * keeps working; only the bucket behind them changed.
     *
     * @param  string $path      object name, e.g. "com/banner/abc.webp"
     * @param  mixed  $contents  string, stream, or anything Cloud Storage accepts
     * @return bool              true on success; failures are logged, not thrown,
     *                           matching the previous disk()->put() behaviour
     */
    function gcs_put($path, $contents, $contentType = null)
    {
        try {
            // keyFilePath when the entrypoint has materialised one from
            // GOOGLE_CLOUD_KEY_JSON_BASE64, otherwise Application Default
            // Credentials. A key is needed on the VM because its instance
            // service account carries the devstorage.read_only scope, which
            // caps writes no matter what IAM says - and widening that scope
            // means stopping the VM, which also stops dookwebsite and dookblog.
            $storage = new StorageClient(array_filter([
                'projectId'   => config('images.project_id') ?: env('GOOGLE_CLOUD_PROJECT_ID'),
                'keyFilePath' => env('GOOGLE_CLOUD_KEY_FILE') ?: null,
            ]));

            $options = ['name' => ltrim((string) $path, '/')];
            if ($contentType) {
                $options['metadata'] = ['contentType' => $contentType];
            }

            $storage->bucket(config('images.bucket'))->upload($contents, $options);

            return true;
        } catch (\Throwable $e) {
            \Log::error('gcs_put failed for ' . $path . ': ' . $e->getMessage());

            return false;
        }
    }
}

if (!function_exists('img_base')) {
    /**
     * Base URL for a module's image folder, with no trailing slash.
     *
     * Use this only where the filename is appended elsewhere, such as inside a
     * JavaScript string. Prefer img_url() when the filename is known.
     */
    function img_base($module)
    {
        $base = rtrim(config('images.base_url'), '/');

        if (config('images.layout') === 'gcs') {
            $folder = config('images.folders.' . $module, $module);
        } else {
            $folder = trim(config('images.legacy_path'), '/') . '/' . $module;
        }

        return $base . '/' . trim($folder, '/');
    }
}

if (!function_exists('img_url')) {
    /**
     * URL for an uploaded image.
     *
     * Accepts img_url('poi', $row->image) or img_url('poi/'.$row->image).
     * Values that are already absolute URLs are returned untouched, and an
     * empty filename yields the folder URL with a trailing slash, which is what
     * the hardcoded paths this replaced produced.
     */
    function img_url($module, $file = null)
    {
        if ($file === null) {
            $parts  = explode('/', ltrim((string) $module, '/'), 2);
            $module = $parts[0];
            $file   = isset($parts[1]) ? $parts[1] : '';
        }

        $file = trim((string) $file);

        if (preg_match('#^(https?:)?//#i', $file)) {
            return $file;
        }

        $segments = array_filter(explode('/', $file), 'strlen');
        $path     = implode('/', array_map('rawurlencode', $segments));

        return img_base($module) . '/' . $path;
    }
}

if (!function_exists('generateSignedUrl')) {
    function generateSignedUrl($imagePath)
    {
        // Ensure the service account credentials are set
        putenv("GOOGLE_APPLICATION_CREDENTIALS=" . env('GOOGLE_CLOUD_KEY_FILE'));
        // \Log::info("Google Cloud Key File Path: " . env('GOOGLE_CLOUD_KEY_FILE'));

        // Create the Google Cloud Storage client
        $storage = new StorageClient();

        // Retrieve the bucket name from the environment
        $bucketName = env('GOOGLE_CLOUD_STORAGE_BUCKET');
        $bucket = $storage->bucket($bucketName);

        // Access the object (image) from the bucket
        $object = $bucket->object($imagePath);

        try {
            // Generate the signed URL valid for 24 hours
            $signedUrl = $object->signedUrl(
                new \DateTime('tomorrow'),  // URL valid for 24 hours
                ['version' => 'v4']  // Use v4 signed URL version
            );
            return $signedUrl;
        } catch (\Exception $e) {
            \Log::error("Error generating signed URL: " . $e->getMessage());
            return null;
        }
    }
}
