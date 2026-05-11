<?php

/**
 * Upload this file, open https://yoursite/check.php once.
 * Plain text — no secrets. Delete check.php after troubleshooting.
 */
header('Content-Type: text/plain; charset=UTF-8');

$root = __DIR__;
echo "=== Site check ===\n\n";
echo 'PHP version: ' . PHP_VERSION . "\n";
echo 'pdo_mysql: ' . (extension_loaded('pdo_mysql') ? 'yes' : 'NO — enable in MultiPHP / cPanel') . "\n";
echo 'mbstring: ' . (extension_loaded('mbstring') ? 'yes' : 'NO') . "\n\n";

$need = ['index.php', 'lib/debug_bootstrap.php', 'lib/config.php', 'lib/functions.php', 'libs/Smarty.class.php'];
foreach ($need as $rel) {
    $path = $root . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $rel);
    echo str_pad($rel, 32) . (is_file($path) ? 'OK' : 'MISSING') . "\n";
}

echo "\n.env readable: " . (is_readable($root . '/.env') ? 'yes' : 'no') . "\n";
echo 'DEBUG_ON file: ' . (is_file($root . '/DEBUG_ON') ? 'yes (forces errors on)' : 'no') . "\n";

if (is_readable($root . '/.env')) {
    echo "\n.env lines (keys only):\n";
    foreach (file($root . '/.env', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) ?: [] as $line) {
        $line = trim($line);
        if ($line === '' || str_starts_with($line, '#')) {
            continue;
        }
        if (str_contains($line, '=')) {
            $key = trim(explode('=', $line, 2)[0]);
            echo '  - ' . $key . "\n";
        }
    }
}

echo "\nTry DB (same as app):\n";
if (!is_readable($root . '/.env')) {
    echo "No .env — cannot test PDO.\n";
    exit;
}

$vars = [];
foreach (file($root . '/.env', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) ?: [] as $line) {
    $line = trim($line);
    if ($line === '' || str_starts_with($line, '#') || !str_contains($line, '=')) {
        continue;
    }
    [$k, $v] = explode('=', $line, 2);
    $k = trim($k);
    $v = trim($v);
    if ($v !== '' && ($v[0] === '"' || $v[0] === "'")) {
        $v = trim($v, "\"'");
    }
    if ($k !== '') {
        $vars[$k] = $v;
    }
}

$host = $vars['DB_HOST'] ?? 'localhost';
$db = $vars['DB_NAME'] ?? '';
$user = $vars['DB_USER'] ?? '';
$pass = $vars['DB_PASSWORD'] ?? '';

try {
    $pdo = new PDO(
        "mysql:host={$host};dbname={$db};charset=utf8mb4",
        $user,
        $pass,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
    echo "PDO connect: OK\n";
} catch (PDOException $e) {
    echo 'PDO connect: FAILED — ' . $e->getMessage() . "\n";
}
