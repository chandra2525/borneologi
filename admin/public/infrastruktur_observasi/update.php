<?php
require "../../app/core/session.php";
secureSessionStart();
require "../../app/core/csrf.php";
require "../../app/config/database.php";
require "../../app/models/InfrastrukturObservasi.php";
require "../../app/core/permission.php";
require '../../app/core/activity_log.php';

Permission::authorize($pdo, 'Infrastruktur Observasi', 'update');
verifyCsrfToken();

$infrastrukturObservasiModel = new InfrastrukturObservasi($pdo);

$data = [
    "id_tanah" => $_POST["id_tanah"],
    "periode_pengecekan" => $_POST["periode_pengecekan"],
    "id_akses_perjalanan" => $_POST["id_akses_perjalanan"],
    "id_kondisi_jalan" => $_POST["id_kondisi_jalan"],
    "jarak_ke_jalan_km" => $_POST["jarak_ke_jalan_km"],
    "ada_jembatan" => $_POST["ada_jembatan"],
    "ada_listrik" => $_POST["ada_listrik"],
    "ada_internet" => $_POST["ada_internet"],
    "sinyal_seluler" => $_POST["sinyal_seluler"],
    "catatan" => $_POST["catatan"],
    "updated_by" => $_SESSION["user_id"]
];

$oldData = $infrastrukturObservasiModel->findById($_POST["id"]);
$result = $infrastrukturObservasiModel->update($_POST["id"], $data);

if ($result) {
    $newData = $infrastrukturObservasiModel->findById($_POST["id"]);
    logActivity(
        $pdo,
        'UPDATE',
        'Infrastruktur Observasi',
        $_POST["id"],
        't_infrastruktur_observasi',
        $oldData,
        $newData,
        'Mengubah data Infrastruktur Observasi',
        'SUCCESS'
    );
}

header("Location: index.php?success=updated");