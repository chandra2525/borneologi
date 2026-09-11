<?php
require "../../app/core/session.php";
secureSessionStart();
require "../../app/config/database.php";
require "../../app/models/PerairanObservasi.php";
require "../../app/core/permission.php";
require '../../app/core/activity_log.php';

Permission::authorize($pdo, 'Perairan Observasi', 'delete');

$perairanObservasiModel = new PerairanObservasi($pdo);

$oldData = $perairanObservasiModel->findById($_POST["id"]);
$result = $perairanObservasiModel->softDelete($_POST["id"], $_SESSION["user_id"]);

if ($result) {
    logActivity(
        $pdo,
        'DELETE',
        'Perairan Observasi',
        $_POST["id"],
        't_perairan_observasi',
        $oldData,
        null,
        'Menghapus data Perairan Observasi',
        'SUCCESS'
    );
}

header("Location: index.php?success=deleted");