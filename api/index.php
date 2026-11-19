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

// PENTING: file ini berada di folder /api, sehingga Symfony/Laravel mengira
// "/api" adalah base URL dan memotongnya dari path. Akibatnya /api/buku dibaca
// sebagai /buku (halaman HTML), bukan route API. Arahkan SCRIPT_NAME ke
// public/index.php supaya path asli (/api/buku) tetap utuh.
$_SERVER['SCRIPT_NAME']     = '/index.php';
$_SERVER['PHP_SELF']        = '/index.php';
$_SERVER['SCRIPT_FILENAME'] = __DIR__ . '/../public/index.php';

// Forward Vercel requests to normal Laravel public index
require __DIR__ . '/../public/index.php';