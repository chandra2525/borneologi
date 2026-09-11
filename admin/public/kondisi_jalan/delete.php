<?php
require "../../app/core/session.php";
secureSessionStart();
require "../../app/config/database.php";
require "../../app/models/KondisiJalan.php";
require "../../app/core/permission.php";
require '../../app/core/activity_log.php';

Permission::authorize($pdo, 'Kondisi Jalan', 'delete');

$kondisiJalanModel = new KondisiJalan($pdo);

$oldData = $kondisiJalanModel->findById($_POST["id"]);
$result = $kondisiJalanModel->softDelete($_POST["id"], $_SESSION["user_id"]);

if ($result) {
    logActivity(
        $pdo,
        'DELETE',
        'Kondisi Jalan',
        $_POST["id"],
        'm_kondisi_jalan',
        $oldData,
        null,
        'Menghapus data Kondisi Jalan',
        'SUCCESS'
    );
}

header("Location: index.php?success=deleted");