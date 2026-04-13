<?php

/* Force HTTPS */
if (!isset($_SERVER['HTTPS']) || $_SERVER['HTTPS'] !== 'on') {

    $redirect = "https://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

    header("Location: $redirect", true, 301);
    exit;
}

/* HTTP Security Headers */
header("X-Frame-Options: SAMEORIGIN");
header("X-Content-Type-Options: nosniff");
header("X-XSS-Protection: 1; mode=block");
header("Referrer-Policy: strict-origin-when-cross-origin");
header("Permissions-Policy: camera=(), microphone=(), geolocation=()");

/* Content Security Policy */
header("Content-Security-Policy: default-src 'self'; img-src 'self' data:; script-src 'self'; style-src 'self' 'unsafe-inline'; font-src 'self' data:; connect-src 'self';");

/* HSTS */
header("Strict-Transport-Security: max-age=31536000; includeSubDomains; preload");