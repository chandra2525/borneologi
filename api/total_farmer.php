<?php

header('Content-Type: application/json');

require_once '../admin/app/config/database.php';
require_once '../admin/app/controllers/PolygonController.php';

$controller = new PolygonController($pdo);

$data = $controller->getTotalFarmer();

$result = [
    "total_farmer" => []
];

foreach ($data as $row) {
    $result["total_farmer"][] = [
        "jenis_kelamin" => $row["jenis_kelamin"],
        "total" => $row["total"]
    ];
}

echo json_encode($result, JSON_PRETTY_PRINT);