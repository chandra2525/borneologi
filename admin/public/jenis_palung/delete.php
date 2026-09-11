<?php
require "../../app/core/session.php";
secureSessionStart();
require "../../app/config/database.php";
require "../../app/models/JenisPalung.php";
require "../../app/core/permission.php";
require '../../app/core/activity_log.php';

Permission::authorize($pdo, 'Jenis Palung', 'delete');

$jenisPalungModel = new JenisPalung($pdo);

$oldData = $jenisPalungModel->findById($_POST["id"]);
$result = $jenisPalungModel->softDelete($_POST["id"], $_SESSION["user_id"]);

if ($result) {
    logActivity(
        $pdo,
        'DELETE',
        'Jenis Palung',
        $_POST["id"],
        'm_jenis_palung',
        $oldData,
        null,
        'Menghapus data Jenis Palung',
        'SUCCESS'
    );
}

header("Location: index.php?success=deleted");