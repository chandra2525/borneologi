<?php
require "../../app/core/session.php";
secureSessionStart();
require "../../app/core/csrf.php";
require "../../app/config/database.php";
require "../../app/models/Kaleka.php";
require "../../app/core/permission.php";
require '../../app/core/activity_log.php';

Permission::authorize($pdo, 'Kaleka', 'update');
verifyCsrfToken();

$kalekaModel = new Kaleka($pdo);

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
    "updated_by" => $_SESSION["user_id"]
];

$oldData = $kalekaModel->findById($_POST["id"]);
$result = $kalekaModel->update($_POST["id"], $data);

if ($result) {
    $newData = $kalekaModel->findById($_POST["id"]);
    logActivity(
        $pdo,
        'UPDATE',
        'Kaleka',
        $_POST["id"],
        't_kaleka',
        $oldData,
        $newData,
        'Mengubah data Kaleka',
        'SUCCESS'
    );
}

header("Location: index.php?success=updated");