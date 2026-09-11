<?php
require "../../app/core/session.php";
secureSessionStart();
require "../../app/config/database.php";
require "../../app/models/InfrastrukturObservasi.php";
require "../../app/core/permission.php";
require '../../app/core/activity_log.php';

Permission::authorize($pdo, 'Infrastruktur Observasi', 'delete');

$infrastrukturObservasiModel = new InfrastrukturObservasi($pdo);

$oldData = $infrastrukturObservasiModel->findById($_POST["id"]);
$result = $infrastrukturObservasiModel->softDelete($_POST["id"], $_SESSION["user_id"]);

if ($result) {
    logActivity(
        $pdo,
        'DELETE',
        'Infrastruktur Observasi',
        $_POST["id"],
        't_infrastruktur_observasi',
        $oldData,
        null,
        'Menghapus data Infrastruktur Observasi',
        'SUCCESS'
    );
}

header("Location: index.php?success=deleted");