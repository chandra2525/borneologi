<?php
require "../../app/core/session.php";
secureSessionStart();
require "../../app/config/database.php";
require "../../app/models/Provinsi.php";
require "../../app/core/permission.php";
require '../../app/core/activity_log.php';

Permission::authorize($pdo, 'Provinsi', 'delete');

$provinsiModel = new Provinsi($pdo);

$oldData = $provinsiModel->findById($_POST["id"]);
$result = $provinsiModel->softDelete($_POST["id"], $_SESSION["user_id"]);

if ($result) {
    logActivity(
        $pdo,
        'DELETE',
        'Provinsi',
        $_POST["id"],
        'm_provinsi',
        $oldData,
        null,
        'Menghapus data Provinsi',
        'SUCCESS'
    );
}

header("Location: index.php?success=deleted");