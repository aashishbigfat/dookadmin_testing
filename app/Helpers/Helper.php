<?php

use Google\Cloud\Storage\StorageClient;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;


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
