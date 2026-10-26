<?php

declare(strict_types=1);

use App\Bootstrap;
use Zana\Http\HttpKernel;

// The folder that holds the project when only public/ is uploaded to a fixed document root
// (public_html on shared hosting), with the project beside it. release:build sets it.
const PROJECT_FOLDER = 'website';

$base = is_file(dirname(__DIR__) . '/vendor/autoload.php') ? dirname(__DIR__) : dirname(__DIR__) . '/' . PROJECT_FOLDER;
require $base . '/vendor/autoload.php';

// If the application cannot start (missing configuration, a fatal deployment check), visitors
// get 503.html and the reason goes to PHP's error log.
HttpKernel::run(
    Bootstrap::builder($base)
        ->withContainerCache($base . '/var/cache/container.php')
        ->withConfiguration(['view.assets.path' => __DIR__]),
    __DIR__ . '/503.html',
);
