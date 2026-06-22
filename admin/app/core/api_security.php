<?php

/* Force HTTPS */
if (
    !isset($_SERVER['HTTPS']) ||
    $_SERVER['HTTPS'] !== 'on'
) {
    http_response_code(403);

    echo json_encode([
        'error' => 'HTTPS Required'
    ]);

    exit;
}

/* Security Headers */
header('Content-Type: application/json; charset=UTF-8');
header('X-Content-Type-Options: nosniff');
header('X-Frame-Options: DENY');
header('Referrer-Policy: no-referrer');
header('Permissions-Policy: camera=(), microphone=(), geolocation=()');

define('API_KEY', 'kvjfeSD23*9ASDiO9)#22');

function verifyApiKey()
{
    $apiKey = '';

    // 1. Coba dari getallheaders()
    if (function_exists('getallheaders')) {
        $headers = getallheaders();
        if (isset($headers['X-API-KEY'])) {
            $apiKey = $headers['X-API-KEY'];
        } elseif (isset($headers['x-api-key'])) {
            $apiKey = $headers['x-api-key'];
        }
    }

    // 2. Fallback ke $_SERVER (INI YANG PENTING DI HOSTING)
    if (empty($apiKey) && isset($_SERVER['HTTP_X_API_KEY'])) {
        $apiKey = $_SERVER['HTTP_X_API_KEY'];
    }

    if (!hash_equals(API_KEY, $apiKey)) {
        http_response_code(401);
        echo json_encode([
            'error' => 'Unauthorized',
            'debug' => $apiKey // sementara untuk debug
        ]);
        exit;
    }
}