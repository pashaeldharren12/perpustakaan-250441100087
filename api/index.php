<?php

// Buat direktori sementara di /tmp jika belum ada (wajib untuk serverless Vercel)
$dirs = [
    '/tmp/views',
    '/tmp/storage/framework/views',
    '/tmp/storage/framework/cache',
    '/tmp/storage/framework/sessions',
    '/tmp/storage/logs',
];

foreach ($dirs as $dir) {
    if (!is_dir($dir)) {
        mkdir($dir, 0755, true);
    }
}

// Forward Vercel requests to normal Laravel public index
require __DIR__ . '/../public/index.php';
