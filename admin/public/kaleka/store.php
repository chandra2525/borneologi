<?php
require "../../app/core/session.php";
secureSessionStart();
require "../../app/core/csrf.php";
require "../../app/config/database.php";
require "../../app/models/Kaleka.php";
require "../../app/core/permission.php";
require '../../app/core/activity_log.php';

Permission::authorize($pdo, 'Kaleka', 'create');
verifyCsrfToken();

$kaleka = new Kaleka($pdo);

$data = [
    "kode_kaleka" => $_POST["kode_kaleka"],
    "nama_kaleka" => $_POST["nama_kaleka"],
    "id_petani" => $_POST["id_petani"],
    "id_desa" => $_POST["id_desa"],
    "luas_ha" => $_POST["luas_ha"],
    // "centroid_lat" => $_POST["centroid_lat"],
    // "centroid_lng" => $_POST["centroid_lng"],
    // "geom_area" => $_POST["geom_area"],
    "keterangan" => $_POST["keterangan"],
    "is_active" => $_POST["is_active"],
    "created_by" => $_SESSION["user_id"]
];

$newId = $kaleka->create($data);

if ($newId) {
    logActivity(
        $pdo,
        'CREATE',
        'Kaleka',
        $newId,
        't_kaleka',
        null,
        $data,
        'Menambahkan data Kaleka',
        'SUCCESS'
    );
}

header("Location: index.php?success=created");