<?php
require "../../app/core/session.php";
secureSessionStart();
require "../../app/config/database.php";
require "../../app/models/JenisPohon.php";
require "../../app/core/permission.php";
require '../../app/core/activity_log.php';

Permission::authorize($pdo, 'Jenis Pohon', 'delete');

$jenisPohonModel = new JenisPohon($pdo);

$oldData = $jenisPohonModel->findById($_POST["id"]);
$result = $jenisPohonModel->softDelete($_POST["id"], $_SESSION["user_id"]);

if ($result) {
    logActivity(
        $pdo,
        'DELETE',
        'Jenis Pohon',
        $_POST["id"],
        'm_jenis_pohon',
        $oldData,
        null,
        'Menghapus data Jenis Pohon',
        'SUCCESS'
    );
}

header("Location: index.php?success=deleted");