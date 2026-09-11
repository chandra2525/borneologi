<?php
require "../../app/core/session.php";
secureSessionStart();
require "../../app/core/csrf.php";
require "../../app/config/database.php";
require "../../app/models/PohonObservasi.php";
require "../../app/core/permission.php";
require '../../app/core/activity_log.php';

Permission::authorize($pdo, 'Pohon Observasi', 'update');
verifyCsrfToken();

$pohonObservasiModel = new PohonObservasi($pdo);

$data = [
    "id_tanah" => $_POST["id_tanah"],
    "periode_pengecekan" => $_POST["periode_pengecekan"],
    "id_jenis_pohon" => $_POST["id_jenis_pohon"],
    "id_fungsi_pohon" => $_POST["id_fungsi_pohon"],
    "jumlah_pohon" => $_POST["jumlah_pohon"],
    "diameter_rata2_cm" => $_POST["diameter_rata2_cm"],
    "tinggi_rata2_m" => $_POST["tinggi_rata2_m"],
    "kondisi" => $_POST["kondisi"],
    "catatan" => $_POST["catatan"],
    "updated_by" => $_SESSION["user_id"]
];

$oldData = $pohonObservasiModel->findById($_POST["id"]);
$result = $pohonObservasiModel->update($_POST["id"], $data);

if ($result) {
    $newData = $pohonObservasiModel->findById($_POST["id"]);
    logActivity(
        $pdo,
        'UPDATE',
        'Pohon Observasi',
        $_POST["id"],
        't_pohon_observasi',
        $oldData,
        $newData,
        'Mengubah data Pohon Observasi',
        'SUCCESS'
    );
}

header("Location: index.php?success=updated");