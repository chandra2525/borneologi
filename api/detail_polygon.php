<?php

header('Content-Type: application/json');

require_once '../admin/app/config/database.php';
require_once '../admin/app/controllers/PolygonController.php';

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