<?php
require "../../app/core/session.php";
secureSessionStart();
require "../../app/config/database.php";
require "../../app/models/PenggunaanPertanian.php";
require "../../app/core/permission.php";
require '../../app/core/activity_log.php';

Permission::authorize($pdo, 'Penggunaan Pertanian', 'delete');

$penggunaanPertanianModel = new PenggunaanPertanian($pdo);

$oldData = $penggunaanPertanianModel->findById($_POST["id"]);
$result = $penggunaanPertanianModel->softDelete($_POST["id"], $_SESSION["user_id"]);

if ($result) {
    logActivity(
        $pdo,
        'DELETE',
        'Penggunaan Pertanian',
        $_POST["id"],
        'm_penggunaan_pertanian',
        $oldData,
        null,
        'Menghapus data Penggunaan Pertanian',
        'SUCCESS'
    );
}

header("Location: index.php?success=deleted");