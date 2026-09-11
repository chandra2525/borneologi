<?php
require "../../app/core/session.php";
secureSessionStart();
require "../../app/config/database.php";
require "../../app/models/HutanAdat.php";
require "../../app/core/permission.php";
require '../../app/core/activity_log.php';

Permission::authorize($pdo, 'Hutan Adat', 'delete');

$hutanAdatModel = new HutanAdat($pdo);

$oldData = $hutanAdatModel->findById($_POST["id"]);
$result = $hutanAdatModel->softDelete($_POST["id"], $_SESSION["user_id"]);

if ($result) {
    logActivity(
        $pdo,
        'DELETE',
        'Hutan Adat',
        $_POST["id"],
        't_hutan_adat',
        $oldData,
        null,
        'Menghapus data Hutan Adat',
        'SUCCESS'
    );
}

header("Location: index.php?success=deleted");