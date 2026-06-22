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

    $id = $_GET['id'] ?? null;
    $tipe = $_GET['tipe'] ?? null;

    if (!$id) {
        echo json_encode([
            "status" => false,
            "message" => "ID tidak ditemukan"
        ]);
        exit;
    }

    if ($tipe === 'provinsi') {
        $data = $controller->getDetailPolygonProv($id);
    } else if ($tipe === 'kabupaten') {
        $data = $controller->getDetailPolygonKab($id);
    } else if ($tipe === 'kecamatan') {
        $data = $controller->getDetailPolygonKec($id);
    } else if ($tipe === 'hutan_adat') {
        $data = $controller->getDetailPolygonHa($id);
    } else if ($tipe === 'kaleka') {
        $data = $controller->getDetailPolygonKaleka($id);
    } else if ($tipe === 'bank_benih') {
        $data = $controller->getDetailPolygonBankBenih($id);
    } else {
        echo json_encode([
            "status" => false,
            "message" => "Tipe tidak valid"
        ]);
        exit;
    }

    echo json_encode([
        "status" => true,
        "data" => $data
    ]);

} catch (Exception $e) {

    http_response_code(500);

    echo json_encode([
        'success' => false,
        'message' => 'Internal Server Error'
    ]);
}