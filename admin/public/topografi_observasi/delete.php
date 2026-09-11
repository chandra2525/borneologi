<?php
require "../../app/core/session.php";
secureSessionStart();
require "../../app/config/database.php";
require "../../app/models/TopografiObservasi.php";
require "../../app/core/permission.php";
require '../../app/core/activity_log.php';

Permission::authorize($pdo, 'Topografi Observasi', 'delete');

$topografiObservasiModel = new TopografiObservasi($pdo);

$oldData = $topografiObservasiModel->findById($_POST["id"]);
$result = $topografiObservasiModel->softDelete($_POST["id"], $_SESSION["user_id"]);

if ($result) {
    logActivity(
        $pdo,
        'DELETE',
        'Topografi Observasi',
        $_POST["id"],
        't_topografi_observasi',
        $oldData,
        null,
        'Menghapus data Topografi Observasi',
        'SUCCESS'
    );
}

header("Location: index.php?success=deleted");