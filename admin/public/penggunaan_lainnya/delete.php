<?php
require "../../app/core/session.php";
secureSessionStart();
require "../../app/config/database.php";
require "../../app/models/PenggunaanLainnya.php";
require "../../app/core/permission.php";
require '../../app/core/activity_log.php';

Permission::authorize($pdo, 'Penggunaan Lainnya', 'delete');

$penggunaanLainnyaModel = new PenggunaanLainnya($pdo);

$oldData = $penggunaanLainnyaModel->findById($_POST["id"]);
$result = $penggunaanLainnyaModel->softDelete($_POST["id"], $_SESSION["user_id"]);

if ($result) {
    logActivity(
        $pdo,
        'DELETE',
        'Penggunaan Lainnya',
        $_POST["id"],
        'm_penggunaan_lainnya',
        $oldData,
        null,
        'Menghapus data Penggunaan Lainnya',
        'SUCCESS'
    );
}

header("Location: index.php?success=deleted");