<?php
require "../../app/core/session.php";
secureSessionStart();
require "../../app/config/database.php";
require "../../app/models/PohonObservasi.php";
require "../../app/core/permission.php";
require '../../app/core/activity_log.php';

Permission::authorize($pdo, 'Pohon Observasi', 'delete');

$pohonObservasiModel = new PohonObservasi($pdo);

$oldData = $pohonObservasiModel->findById($_POST["id"]);
$result = $pohonObservasiModel->softDelete($_POST["id"], $_SESSION["user_id"]);

if ($result) {
    logActivity(
        $pdo,
        'DELETE',
        'Pohon Observasi',
        $_POST["id"],
        't_pohon_observasi',
        $oldData,
        null,
        'Menghapus data Pohon Observasi',
        'SUCCESS'
    );
}

header("Location: index.php?success=deleted");