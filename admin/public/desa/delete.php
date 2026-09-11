<?php
require "../../app/core/session.php";
secureSessionStart();
require "../../app/config/database.php";
require "../../app/models/Desa.php";
require "../../app/core/permission.php";
require '../../app/core/activity_log.php';

Permission::authorize($pdo, 'Desa', 'delete');

$desaModel = new Desa($pdo);

$oldData = $desaModel->findById($_POST["id"]);
$result = $desaModel->softDelete($_POST["id"], $_SESSION["user_id"]);

if ($result) {
    logActivity(
        $pdo,
        'DELETE',
        'Desa',
        $_POST["id"],
        'm_desa',
        $oldData,
        null,
        'Menghapus data Desa',
        'SUCCESS'
    );
}

header("Location: index.php?success=deleted");