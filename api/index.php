<?php

// Forward Vercel serverless requests to Laravel public/index.php
// Prepare temporary directories in /tmp because Vercel filesystem is read-only

$tmpStorage = '/tmp/storage';
$dirs = [
    "$tmpStorage/app/public",
    "$tmpStorage/framework/views",
    "$tmpStorage/framework/cache/data",
    "$tmpStorage/framework/sessions",
    "$tmpStorage/logs",
    '/tmp/bootstrap/cache',
];

foreach ($dirs as $dir) {
    if (!is_dir($dir)) {
        @mkdir($dir, 0755, true);
    }
}

require __DIR__ . '/../public/index.php';
