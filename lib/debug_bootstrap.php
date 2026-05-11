<?php

declare(strict_types=1);

/**
 * Debug sources (any one enables verbose errors + php_error.log):
 * - Empty file `DEBUG_ON` in project root (easiest when .env is wrong)
 * - APP_DEBUG=true in `.env`
 *
 * Remove DEBUG_ON / set APP_DEBUG=false when finished — never leave on public sites.
 */
$projectRoot = dirname(__DIR__);
$appDebug = is_file($projectRoot . DIRECTORY_SEPARATOR . 'DEBUG_ON');
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
        if ($key !== 'APP_DEBUG') {
            continue;
        }
        $lower = strtolower((string) $value);
        $envOn = $lower === '1' || $lower === 'true' || $lower === 'yes' || $lower === 'on';
        $appDebug = $appDebug || $envOn;
        break;
    }
}

// Optional log file next to index.php (FTP download if browser stays blank)
if ($appDebug) {
    $logFile = $projectRoot . DIRECTORY_SEPARATOR . 'php_error.log';
    ini_set('log_errors', '1');
    ini_set('error_log', $logFile);
}

if (!defined('APP_DEBUG')) {
    define('APP_DEBUG', $appDebug);
}

if (APP_DEBUG) {
    ini_set('display_errors', '1');
    ini_set('display_startup_errors', '1');
    ini_set('html_errors', '1');
    error_reporting(E_ALL);

    set_exception_handler(static function (Throwable $e): void {
        if (!headers_sent()) {
            http_response_code(500);
            header('Content-Type: text/html; charset=UTF-8');
        }
        echo '<h1 style="font-family:sans-serif;">Uncaught exception</h1>'
            . '<pre style="white-space:pre-wrap;font-family:monospace;background:#f8f8f8;padding:12px;border:1px solid #ccc;">';
        echo htmlspecialchars(
            $e->getMessage() . "\n\n" . $e->getFile() . ':' . $e->getLine() . "\n\n" . $e->getTraceAsString(),
            ENT_QUOTES | ENT_SUBSTITUTE,
            'UTF-8'
        );
        echo '</pre>';
        exit(1);
    });

    register_shutdown_function(static function (): void {
        $err = error_get_last();
        if ($err === null) {
            return;
        }
        $fatalTypes = [E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR, E_USER_ERROR];
        if (!in_array($err['type'], $fatalTypes, true)) {
            return;
        }
        if (!headers_sent()) {
            http_response_code(500);
            header('Content-Type: text/html; charset=UTF-8');
            echo '<h1 style="font-family:sans-serif;">PHP fatal error</h1>';
        }
        echo '<pre style="white-space:pre-wrap;font-family:monospace;background:#fee;padding:12px;border:1px solid #c00;">';
        echo htmlspecialchars(
            $err['message'] . "\n" . $err['file'] . ':' . $err['line'],
            ENT_QUOTES | ENT_SUBSTITUTE,
            'UTF-8'
        );
        echo '</pre>';
    });
}
