<?php
// Connection for the signature sub-app (index.php, signature.php, upload_img.php
// and friends all include this).
//
// Credentials come from the container environment, which is the same source
// Laravel uses - php-fpm runs with clear_env = no precisely so these reach the
// workers. Nothing is hardcoded here on purpose: this file lives in a PUBLIC
// repository, and the previous version carried a live database username and
// password in plaintext along with the retired 192.168.4.7 host, which has been
// unreachable since the database moved to Cloud SQL.
//
// Those exposed credentials still need rotating - see explainers/exposers.md.

$servername = getenv('DB_HOST') ?: '127.0.0.1';
$username   = getenv('DB_USERNAME') ?: '';
$password   = getenv('DB_PASSWORD') ?: '';
$dbname     = getenv('DB_DATABASE') ?: 'dookweb';

$conn = mysqli_connect($servername, $username, $password, $dbname);

if (!$conn) {
    // The old version echoed mysqli_connect_error() straight to the browser,
    // which tells an anonymous visitor the host and user it failed with.
    error_log('signature: database connection failed: ' . mysqli_connect_error());
    http_response_code(500);
    exit('Database unavailable');
}
