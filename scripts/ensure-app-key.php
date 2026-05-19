<?php

declare(strict_types=1);

$projectRoot = dirname(__DIR__);
$envPath = $projectRoot . DIRECTORY_SEPARATOR . '.env';
$envExamplePath = $projectRoot . DIRECTORY_SEPARATOR . '.env.example';

if (!file_exists($envPath)) {
    if (!file_exists($envExamplePath)) {
        fwrite(STDERR, ".env.example not found; cannot create .env\n");
        exit(1);
    }

    if (!copy($envExamplePath, $envPath)) {
        fwrite(STDERR, "Failed to copy .env.example to .env\n");
        exit(1);
    }

    echo "Created .env from .env.example\n";
}

$env = file_get_contents($envPath);
$env = $env === false ? '' : $env;

// If APP_KEY already exists, do nothing.
if (preg_match('/^APP_KEY=base64:/m', $env) === 1) {
    echo "APP_KEY already set\n";
    exit(0);
}

// Generate APP_KEY only when missing.
passthru('php artisan key:generate --ansi', $exitCode);
exit((int) $exitCode);
