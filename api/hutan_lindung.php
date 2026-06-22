<?php

header('Content-Type: application/json');

require_once '../admin/app/config/database.php';
require_once '../admin/app/controllers/PolygonController.php';
require_once '../admin/app/core/api_security.php';

verifyApiKey();

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405);

    echo json_encode([
        'error' => 'Method Not Allowed'
    ]);

    exit;
}

$ip = $_SERVER['REMOTE_ADDR'];

$file = sys_get_temp_dir() . '/api_' . md5($ip);

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
        'error' => 'Too Many Requests'
    ]);

    exit;
}

$requests[] = $now;

file_put_contents(
    $file,
    json_encode($requests)
);


try {

    $controller = new PolygonController($pdo);

    $data = $controller->getPolygonHutanLindungData();

    // format ulang biar sama seperti data.php lama
    $result = [
        "hutan_lindung" => []
    ];

    foreach ($data as $row) {
        $result["hutan_lindung"][] = [
            "id" => $row["id"],
            "id_polygon" => $row["id_polygon"],
            "geom_area" => $row["geom_area"]
        ];
    }

    echo json_encode($result, JSON_PRETTY_PRINT);

} catch (Exception $e) {

    http_response_code(500);

    echo json_encode([
        'success' => false,
        'message' => 'Internal Server Error'
    ]);
}