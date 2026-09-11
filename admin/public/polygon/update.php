<?php
require "../../app/core/session.php";
secureSessionStart();
require "../../app/core/csrf.php";
require "../../app/config/database.php";
require "../../app/models/Polygon.php";
require "../../app/core/permission.php";
require '../../app/core/activity_log.php';

Permission::authorize($pdo, 'Polygon', 'update');
verifyCsrfToken();

$polygonModel = new Polygon($pdo);

$data = [
    "kode_polygon" => $_POST["kode_polygon"],
    "nama_polygon" => $_POST["nama_polygon"],
    // "geom_area" => $_POST["geom_area"],
    "relasi_id" => $_POST["relasi_id"],
    "relasi_tipe" => $_POST["relasi_tipe"],
    "is_active" => $_POST["is_active"],
    "updated_by" => $_SESSION["user_id"]
];

$oldData = $polygonModel->findById($_POST["id"]);
$result = $polygonModel->update($_POST["id"], $data);

if ($result) {
    $newData = $polygonModel->findById($_POST["id"]);
    logActivity(
        $pdo,
        'UPDATE',
        'Polygon',
        $_POST["id"],
        't_polygon',
        $oldData,
        $newData,
        'Mengubah data Polygon',
        'SUCCESS'
    );
}

header("Location: index.php?success=updated");