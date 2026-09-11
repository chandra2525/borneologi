<?php
require "../../app/core/session.php";
secureSessionStart();
require "../../app/config/database.php";
require "../../app/models/Kaleka.php";
require "../../app/core/permission.php";
require '../../app/core/activity_log.php';

Permission::authorize($pdo, 'Kaleka', 'delete');

$kalekaModel = new Kaleka($pdo);

$oldData = $kalekaModel->findById($_POST["id"]);
$result = $kalekaModel->softDelete($_POST["id"], $_SESSION["user_id"]);

if ($result) {
    logActivity(
        $pdo,
        'DELETE',
        'Kaleka',
        $_POST["id"],
        't_kaleka',
        $oldData,
        null,
        'Menghapus data Kaleka',
        'SUCCESS'
    );
}

header("Location: index.php?success=deleted");