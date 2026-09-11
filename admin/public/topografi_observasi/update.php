<?php
require "../../app/core/session.php";
secureSessionStart();
require "../../app/core/csrf.php";
require "../../app/config/database.php";
require "../../app/models/TopografiObservasi.php";
require "../../app/core/permission.php";
require '../../app/core/activity_log.php';

Permission::authorize($pdo, 'Topografi Observasi', 'update');
verifyCsrfToken();

$topografiObservasiModel = new TopografiObservasi($pdo);

$data = [
    "id_tanah" => $_POST["id_tanah"],
    "periode_pengecekan" => $_POST["periode_pengecekan"],
    "id_lanskap" => $_POST["id_lanskap"],
    "id_fitur_tambahan" => $_POST["id_fitur_tambahan"],
    "elevasi_mdpl" => $_POST["elevasi_mdpl"],
    "kemiringan_derajat" => $_POST["kemiringan_derajat"],
    "rawan_erosi" => $_POST["rawan_erosi"],
    "arah_lereng" => $_POST["arah_lereng"],
    "catatan" => $_POST["catatan"],
    "updated_by" => $_SESSION["user_id"]
];

$oldData = $topografiObservasiModel->findById($_POST["id"]);
$result = $topografiObservasiModel->update($_POST["id"], $data);

if ($result) {
    $newData = $topografiObservasiModel->findById($_POST["id"]);
    logActivity(
        $pdo,
        'UPDATE',
        'Topografi Observasi',
        $_POST["id"],
        't_topografi_observasi',
        $oldData,
        $newData,
        'Mengubah data Topografi Observasi',
        'SUCCESS'
    );
}

header("Location: index.php?success=updated");