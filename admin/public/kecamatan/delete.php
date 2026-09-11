<?php
require "../../app/core/session.php";
secureSessionStart();
require "../../app/config/database.php";
require "../../app/models/Kecamatan.php";
require "../../app/core/permission.php";
require '../../app/core/activity_log.php';

Permission::authorize($pdo, 'Kecamatan', 'delete');

$kecamatanModel = new Kecamatan($pdo);

$oldData = $kecamatanModel->findById($_POST["id"]);
$result = $kecamatanModel->softDelete($_POST["id"], $_SESSION["user_id"]);

if ($result) {
    logActivity(
        $pdo,
        'DELETE',
        'Kecamatan',
        $_POST["id"],
        'm_kecamatan',
        $oldData,
        null,
        'Menghapus data Kecamatan',
        'SUCCESS'
    );
}

header("Location: index.php?success=deleted");