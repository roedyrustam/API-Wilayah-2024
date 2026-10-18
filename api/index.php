<?php

/**
 * ==============================================================================
 * API Wilayah Indonesia 2026 - Vercel Serverless Function Bridge
 * Adapts Laravel 10 for Vercel's Serverless PHP Runtime (AWS Lambda)
 * 
 * @author Roedy Rustam <roedyrustam@gmail.com>
 * @repository https://github.com/roedyrustam/API-Wilayah-2024
 * @license MIT
 * ==============================================================================
 */

// 1. Prepare writable directories in /tmp for Serverless environment
$storagePath = '/tmp/storage';
$requiredDirs = [
    $storagePath . '/framework/views',
    $storagePath . '/framework/cache/data',
    $storagePath . '/framework/sessions',
    $storagePath . '/logs',
    $storagePath . '/app/exports',
    $storagePath . '/bootstrap/cache',
];

foreach ($requiredDirs as $dir) {
    if (!is_dir($dir)) {
        @mkdir($dir, 0755, true);
    }
}

// 2. Set environment variables to redirect Laravel writes to /tmp
putenv("LARAVEL_STORAGE_PATH={$storagePath}");
putenv("VIEW_COMPILED_PATH={$storagePath}/framework/views");
putenv("APP_CONFIG_CACHE={$storagePath}/bootstrap/cache/config.php");
putenv("APP_ROUTES_CACHE={$storagePath}/bootstrap/cache/routes.php");
putenv("APP_EVENTS_CACHE={$storagePath}/bootstrap/cache/events.php");
putenv("APP_PACKAGES_CACHE={$storagePath}/bootstrap/cache/packages.php");
putenv("APP_SERVICES_CACHE={$storagePath}/bootstrap/cache/services.php");

$_ENV['LARAVEL_STORAGE_PATH'] = $storagePath;
$_ENV['VIEW_COMPILED_PATH'] = "{$storagePath}/framework/views";
$_SERVER['LARAVEL_STORAGE_PATH'] = $storagePath;
$_SERVER['VIEW_COMPILED_PATH'] = "{$storagePath}/framework/views";

// 3. Setup SQLite database in /tmp if external database is not configured
$sqliteSource = dirname(__DIR__) . '/database/database.sqlite';
$sqliteTarget = '/tmp/database.sqlite';
if (file_exists($sqliteSource) && !file_exists($sqliteTarget)) {
    @copy($sqliteSource, $sqliteTarget);
}

$dbConnection = getenv('DB_CONNECTION') ?: ($_ENV['DB_CONNECTION'] ?? null);
if (!$dbConnection || $dbConnection === 'sqlite') {
    putenv('DB_CONNECTION=sqlite');
    putenv("DB_DATABASE={$sqliteTarget}");
    $_ENV['DB_CONNECTION'] = 'sqlite';
    $_ENV['DB_DATABASE'] = $sqliteTarget;
    $_SERVER['DB_CONNECTION'] = 'sqlite';
    $_SERVER['DB_DATABASE'] = $sqliteTarget;
}

// 4. Normalize SCRIPT_NAME and SCRIPT_FILENAME so Symfony's Request doesn't strip '/api' prefix from URLs
$_SERVER['SCRIPT_NAME'] = '/index.php';
$_SERVER['SCRIPT_FILENAME'] = dirname(__DIR__) . '/public/index.php';

// 5. Forward execution to Laravel's public entrypoint
require __DIR__ . '/../public/index.php';
