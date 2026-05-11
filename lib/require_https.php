<?php

declare(strict_types=1);

/**
 * Redirect HTTP → HTTPS using only what PHP sees from the web server (no “is it encrypted” heuristics).
 * Optional: HTTPS_TRUST_PROXY=true when SSL terminates before Apache (avoids loops).
 */
function ep_require_https_or_redirect(): void
{
    if (PHP_SAPI === 'cli') {
        return;
    }

    if (strtolower(ep_env('FORCE_HTTP', '')) === 'true') {
        return;
    }

    $host = $_SERVER['HTTP_HOST'] ?? '';
    if ($host === '' || strcasecmp($host, 'localhost') === 0 || str_starts_with($host, '127.')
        || str_starts_with($host, '192.168.') || str_starts_with($host, '10.')) {
        return;
    }

    $uri = $_SERVER['REQUEST_URI'] ?? '/';
    if (str_starts_with($uri, '/.well-known/')) {
        return;
    }

    if (! ep_connection_is_plain_http()) {
        return;
    }

    $target = 'https://' . $host . $uri;
    header('Location: ' . $target, true, 301);
    exit;
}

ep_require_https_or_redirect();
