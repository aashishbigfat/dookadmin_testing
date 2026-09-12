<?php
/**
 * Standalone (non-Laravel) DB bootstrap for the legacy signature app.
 * Credentials are read from the project .env rather than hardcoded here.
 */
function signature_env($key, $default = null) {
    static $vars = null;
    if ($vars === null) {
        $vars = [];
        $path = __DIR__ . '/../../.env';
        if (is_readable($path)) {
            foreach (file($path) as $line) {
                $line = trim($line);
                if ($line === '' || $line[0] === '#') continue;
                $parts = explode('=', $line, 2);
                if (count($parts) === 2) $vars[trim($parts[0])] = trim(trim($parts[1]), '"');
            }
        }
    }
    return array_key_exists($key, $vars) ? $vars[$key] : $default;
}

$servername = signature_env('DB_HOST');
$username   = signature_env('DB_USERNAME');
$password   = signature_env('DB_PASSWORD');
$dbname     = signature_env('DB_DATABASE');

$conn = mysqli_connect($servername, $username, $password, $dbname)
    or die("Connection failed: " . mysqli_connect_error());

if (mysqli_connect_errno()) {
    printf("Connect failed: %s\n", mysqli_connect_error());
    exit();
}
