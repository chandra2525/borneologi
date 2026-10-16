<?php

header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/../admin/app/config/database.php';
require_once __DIR__ . '/../admin/app/controllers/WilayahController.php';
require_once __DIR__ . '/../admin/app/core/api_security.php';

verifyApiKey();

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405);

    echo json_encode([
        'status' => false,
        'message' => 'Method Not Allowed'
    ]);

    exit;
}

$ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
$file = sys_get_temp_dir() . '/api_wilayah_' . md5($ip);

$requests = [];

if (file_exists($file)) {
    $requests = json_decode(
        file_get_contents($file),
        true
    ) ?: [];
}

$now = time();

$requests = array_filter(
    $requests,
    fn($timestamp) => ($now - $timestamp) < 30
);

if (count($requests) >= 30) {
    http_response_code(429);

    echo json_encode([
        'status' => false,
        'message' => 'Too Many Requests'
    ]);

    exit;
}

$requests[] = $now;

file_put_contents(
    $file,
    json_encode(array_values($requests)),
    LOCK_EX
);

$controller = new WilayahController($pdo);

/**
 * Validasi ID positif.
 */
function getPositiveId($value)
{
    if (
        !is_scalar($value) ||
        !ctype_digit((string) $value) ||
        (int) $value < 1
    ) {
        return null;
    }

    return (int) $value;
}

/**
 * Respons error JSON.
 */
function apiError($statusCode, $message)
{
    http_response_code($statusCode);

    echo json_encode([
        'status' => false,
        'message' => $message
    ]);

    exit;
}