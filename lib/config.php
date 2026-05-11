<?php

declare(strict_types=1);

$projectRoot = dirname(__DIR__);
$envFile = $projectRoot . DIRECTORY_SEPARATOR . '.env';
if (is_readable($envFile)) {
    foreach (file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) ?: [] as $line) {
        $line = trim($line);
        if ($line === '' || str_starts_with($line, '#')) {
            continue;
        }
        if (!str_contains($line, '=')) {
            continue;
        }
        [$key, $value] = explode('=', $line, 2);
        $key = trim($key);
        $value = trim($value);
        if ($value !== '' && ($value[0] === '"' || $value[0] === "'")) {
            $value = trim($value, "\"'");
        }
        if ($key !== '') {
            putenv("$key=$value");
            $_ENV[$key] = $value;
        }
    }
}

// URL path segment after the domain (no slashes). Empty string = app lives at domain root.
// Example subfolder: BASE_PATH=energy-providers  → https://example.org/energy-providers/home
// Example root:      BASE_PATH=               → https://example.org/home
if (array_key_exists('BASE_PATH', $_ENV)) {
    $base_dir = trim((string) $_ENV['BASE_PATH'], '/');
} elseif (getenv('BASE_PATH') !== false) {
    $base_dir = trim((string) getenv('BASE_PATH'), '/');
} else {
    $base_dir = 'energy-providers';
}

// Database — set in `.env` (see `.env.example`)
// Shared hosting: use `localhost` unless your host documents a different DB hostname.
$servername = getenv('DB_HOST') ?: 'localhost';
$database = getenv('DB_NAME') ?: 'energy_providers';
$username = getenv('DB_USER') ?: 'root';
$password = getenv('DB_PASSWORD') !== false ? (string) getenv('DB_PASSWORD') : '';

$blocks = [
    'articles6_last',
];

$images_folder = 'img';
$span = 4;
$footer_height = 6;

try {
    $conn = new PDO(
        "mysql:host={$servername};dbname={$database};charset=utf8mb4",
        $username,
        $password,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]
    );
} catch (PDOException $e) {
    http_response_code(503);
    header('Content-Type: text/html; charset=UTF-8');
    $msg = htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8');
    $hints = '<p>Copy <code>.env.example</code> to <code>.env</code> and set <code>DB_HOST</code>, <code>DB_NAME</code>, <code>DB_USER</code>, <code>DB_PASSWORD</code> to match your hosting panel.</p>';
    if (str_contains($e->getMessage(), '1045')) {
        $hints .= '<p><strong>Access denied (1045)</strong> usually means wrong username/password, or the MySQL user is not linked to that database.</p>'
            . '<ul>'
            . '<li>In cPanel → <strong>MySQL® Databases</strong>: create the user and password, then <strong>Add User To Database</strong> with <strong>ALL PRIVILEGES</strong>.</li>'
            . '<li>Username is often <strong>accountprefix_username</strong> (exact spelling from the panel).</li>'
            . '<li>If the password has <code>#</code> or spaces, wrap it in quotes in <code>.env</code>: <code>DB_PASSWORD="your#pass"</code>.</li>'
            . '</ul>';
    }
    $debugBlock = '';
    if (defined('APP_DEBUG') && APP_DEBUG) {
        $debugBlock = '<h3>Debug (APP_DEBUG)</h3><pre style="white-space:pre-wrap;font-size:12px;">'
            . htmlspecialchars(
                'DSN host=' . $servername . ' db=' . $database . ' user=' . $username . "\n\n"
                . $e->getTraceAsString(),
                ENT_QUOTES | ENT_SUBSTITUTE,
                'UTF-8'
            )
            . '</pre>';
    }
    exit('<h2>Database connection failed</h2><p>' . $msg . '</p>' . $hints . $debugBlock);
}

require_once __DIR__ . '/schema_submissions.php';
try {
    energy_providers_ensure_submissions_schema($conn);
} catch (PDOException $e) {
    if (defined('APP_DEBUG') && APP_DEBUG) {
        error_log('energy_providers_ensure_submissions_schema: ' . $e->getMessage());
    }
}
