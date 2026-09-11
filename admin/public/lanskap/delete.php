<?php
require "../../app/core/session.php";
secureSessionStart();
require "../../app/config/database.php";
require "../../app/models/Lanskap.php";
require "../../app/core/permission.php";
require '../../app/core/activity_log.php';

Permission::authorize($pdo, 'Lanskap', 'delete');

$lanskapModel = new Lanskap($pdo);

$oldData = $lanskapModel->findById($_POST["id"]);
$result = $lanskapModel->softDelete($_POST["id"], $_SESSION["user_id"]);

if ($result) {
    logActivity(
        $pdo,
        'DELETE',
        'Lanskap',
        $_POST["id"],
        'm_lanskap',
        $oldData,
        null,
        'Menghapus data Lanskap',
        'SUCCESS'
    );
}

header("Location: index.php?success=deleted");