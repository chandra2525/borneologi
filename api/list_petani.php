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
$data = $controller->getPengurusMHA($id);

echo json_encode([
    "status" => true,
    "data" => $data
]);