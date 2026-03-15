<?php
/**
 * Aerial Predator — PHP Reverse Proxy
 *
 * USE THIS if your host runs PHP and you cannot modify nginx/Apache directly.
 *
 * HOW IT WORKS:
 *   All requests to proxy.php are forwarded to http://72.62.200.123:3000/
 *   with the framing-related response headers stripped, then relayed back
 *   to the browser. The iframe on platform.html points here instead of
 *   the direct IP, so the browser treats it as same-origin content.
 *
 * USAGE:
 *   1. Upload proxy.php to your web root (same folder as index.html)
 *   2. In platform.html the iframe src is already set to '/proxy.php'
 *   3. Sub-resources (JS/CSS/images) at the platform must be served via
 *      proxy.php?path=... — this script handles that via the `path` param
 *      or PATH_INFO if your server supports it.
 *
 * REQUIREMENTS:
 *   - PHP 7.4+
 *   - curl extension enabled (standard on most hosts)
 *   - allow_url_fopen = On  (or curl fallback is used)
 */

// ── Config ────────────────────────────────────────────────────────────────
define('AP_TARGET_BASE', 'http://72.62.200.123:3000');

// ── Resolve the target path ───────────────────────────────────────────────
$path  = '/';

// Support PATH_INFO (e.g. proxy.php/some/asset)
if (!empty($_SERVER['PATH_INFO'])) {
    $path = $_SERVER['PATH_INFO'];
}

// Support ?path= query param for asset sub-requests
if (isset($_GET['path'])) {
    $path = '/' . ltrim($_GET['path'], '/');
    unset($_GET['path']);
}

// Pass through remaining query string
$qs = http_build_query($_GET);
$url = AP_TARGET_BASE . $path . ($qs ? '?' . $qs : '');

// ── Build request ─────────────────────────────────────────────────────────
$method  = $_SERVER['REQUEST_METHOD'];
$ch      = curl_init($url);

curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_HEADER         => true,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_MAXREDIRS      => 5,
    CURLOPT_TIMEOUT        => 20,
    CURLOPT_CONNECTTIMEOUT => 10,
    CURLOPT_CUSTOMREQUEST  => $method,
    CURLOPT_USERAGENT      => 'AerialPredator-Proxy/1.0',
    // Forward the original IP
    CURLOPT_HTTPHEADER     => [
        'X-Real-IP: '       . ($_SERVER['REMOTE_ADDR'] ?? ''),
        'X-Forwarded-For: ' . ($_SERVER['REMOTE_ADDR'] ?? ''),
        'X-Forwarded-Proto: https',
    ],
    CURLOPT_SSL_VERIFYPEER => false,
    CURLOPT_SSL_VERIFYHOST => false,
]);

if ($method === 'POST') {
    curl_setopt($ch, CURLOPT_POSTFIELDS, file_get_contents('php://input'));
}

$response  = curl_exec($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$header_sz = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
curl_close($ch);

if ($response === false) {
    http_response_code(502);
    header('Content-Type: text/plain');
    echo '[AP-PROXY ERROR] Could not connect to platform endpoint.';
    exit;
}

// ── Split headers and body ────────────────────────────────────────────────
$raw_headers = substr($response, 0, $header_sz);
$body        = substr($response, $header_sz);

// ── Relay headers, stripping framing-blockers ─────────────────────────────
$skip_headers = [
    'x-frame-options',
    'content-security-policy',
    'content-security-policy-report-only',
    'transfer-encoding',  // Let PHP handle its own chunking
    'connection',
];

foreach (explode("\r\n", $raw_headers) as $line) {
    if (preg_match('/^HTTP\//i', $line)) continue;  // Skip HTTP status line
    if (empty(trim($line)))              continue;

    $colon = strpos($line, ':');
    if ($colon === false) continue;

    $name = strtolower(trim(substr($line, 0, $colon)));

    if (in_array($name, $skip_headers, true)) continue;  // Drop blocked headers

    header($line, false);
}

// ── Set permissive framing header ─────────────────────────────────────────
header('X-Frame-Options: SAMEORIGIN');
http_response_code($http_code);

// ── Output body ───────────────────────────────────────────────────────────
echo $body;
