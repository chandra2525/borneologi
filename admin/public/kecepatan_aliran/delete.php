<?php
require "../../app/core/session.php";
secureSessionStart();
require "../../app/config/database.php";
require "../../app/models/KecepatanAliran.php";
require "../../app/core/permission.php";
require '../../app/core/activity_log.php';

Permission::authorize($pdo, 'Kecepatan Aliran', 'delete');

$kecepatanAliranModel = new KecepatanAliran($pdo);

$oldData = $kecepatanAliranModel->findById($_POST["id"]);
$result = $kecepatanAliranModel->softDelete($_POST["id"], $_SESSION["user_id"]);

if ($result) {
    logActivity(
        $pdo,
        'DELETE',
        'Kecepatan Aliran',
        $_POST["id"],
        'm_kecepatan_aliran',
        $oldData,
        null,
        'Menghapus data Kecepatan Aliran',
        'SUCCESS'
    );
}

header("Location: index.php?success=deleted");