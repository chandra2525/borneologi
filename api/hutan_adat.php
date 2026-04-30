<?php

header('Content-Type: application/json');

require_once '../admin/app/config/database.php';
require_once '../admin/app/controllers/PolygonController.php';

$controller = new PolygonController($pdo);

$data = $controller->getPolygonHAData();

// format ulang biar sama seperti data.php lama
$result = [
    "hutan_adat" => []
];

foreach ($data as $row) {
    $result["hutan_adat"][] = [
        "id" => $row["id"],
        "id_polygon" => $row["id_polygon"],
        "nama_hutan_adat" => $row["nama_hutan_adat"],
        "tanah" => [
            "geom_area" => $row["geom_area"]
        ]
    ];
}

echo json_encode($result, JSON_PRETTY_PRINT);