<?php
require "../../app/core/session.php";
secureSessionStart();
require "../../app/config/database.php";
require "../../app/models/FungsiPohon.php";
require "../../app/core/permission.php";
require '../../app/core/activity_log.php';

Permission::authorize($pdo, 'Fungsi Pohon', 'delete');

$fungsiPohonModel = new FungsiPohon($pdo);

$oldData = $fungsiPohonModel->findById($_POST["id"]);
$result = $fungsiPohonModel->softDelete($_POST["id"], $_SESSION["user_id"]);

if ($result) {
    logActivity(
        $pdo,
        'DELETE',
        'Fungsi Pohon',
        $_POST["id"],
        'm_fungsi_pohon',
        $oldData,
        null,
        'Menghapus data Fungsi Pohon',
        'SUCCESS'
    );
}
header("Location: index.php?success=deleted");