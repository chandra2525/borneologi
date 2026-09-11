<?php
require "../../app/core/session.php";
secureSessionStart();
require "../../app/config/database.php";
require "../../app/models/LandCoverObservasi.php";
require "../../app/core/permission.php";
require '../../app/core/activity_log.php';

Permission::authorize($pdo, 'Land Cover Observasi', 'delete');

$landCoverObservasiModel = new LandCoverObservasi($pdo);

$oldData = $landCoverObservasiModel->findById($_POST["id"]);
$result = $landCoverObservasiModel->softDelete($_POST["id"], $_SESSION["user_id"]);

if ($result) {
    logActivity(
        $pdo,
        'DELETE',
        'Land Cover Observasi',
        $_POST["id"],
        't_land_cover_observasi',
        $oldData,
        null,
        'Menghapus data Land Cover Observasi',
        'SUCCESS'
    );
}

header("Location: index.php?success=deleted");