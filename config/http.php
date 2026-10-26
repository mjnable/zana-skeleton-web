<?php

use Zana\Http\SecurityHeadersMiddleware;
use Zana\Http\TrustedProxiesMiddleware;
use Zana\Translation\LocaleMiddleware;

return [
    'middleware' => [TrustedProxiesMiddleware::class, SecurityHeadersMiddleware::class, LocaleMiddleware::class],

    // Behind a CDN or a load balancer, list its addresses (CIDR ranges work) so that the client
    // address and HTTPS are read from its headers.
    'trusted_proxies' => [],

    'security_headers' => [
        'csp' => [
            'script-src' => ["'self'", '{nonce}'],
            'img-src' => ["'self'", 'data:'],
        ],
    ],
];
