<?php

header('Content-Type: application/json');

require_once '../admin/app/config/database.php';
require_once '../admin/app/controllers/PolygonController.php';

$controller = new PolygonController($pdo);

$data = $controller->getPolygonKalekaData();

// format ulang biar sama seperti data.php lama
$result = [
    "kaleka" => []
];

foreach ($data as $row) {
    $result["kaleka"][] = [
        "id" => $row["id"],
        "id_polygon" => $row["id_polygon"],
        "nama_kaleka" => $row["nama_kaleka"],
        "tanah" => [
            "geom_area" => $row["geom_area"]
        ]
    ];
}

echo json_encode($result, JSON_PRETTY_PRINT);