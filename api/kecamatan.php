<?php

header('Content-Type: application/json');

require_once '../admin/app/config/database.php';
require_once '../admin/app/controllers/PolygonController.php';

$controller = new PolygonController($pdo);

$data = $controller->getPolygonKecData();

// format ulang biar sama seperti data.php lama
$result = [
    "kecamatan" => []
];

foreach ($data as $row) {
    $result["kecamatan"][] = [
        "id" => $row["id"],
        "id_polygon" => $row["id_polygon"],
        "nama_kecamatan" => $row["nama_kecamatan"],
        "tanah" => [
            "geom_area" => $row["geom_area"]
        ]
    ];
}

echo json_encode($result, JSON_PRETTY_PRINT);