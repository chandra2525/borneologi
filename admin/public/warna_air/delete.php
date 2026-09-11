<?php
require "../../app/core/session.php";
secureSessionStart();
require "../../app/config/database.php";
require "../../app/models/WarnaAir.php";
require "../../app/core/permission.php";
require '../../app/core/activity_log.php';

Permission::authorize($pdo, 'Warna Air', 'delete');

$warnaAirModel = new WarnaAir($pdo);

$oldData = $warnaAirModel->findById($_POST["id"]);
$result = $warnaAirModel->softDelete($_POST["id"], $_SESSION["user_id"]);

if ($result) {
    logActivity(
        $pdo,
        'DELETE',
        'Warna Air',
        $_POST["id"],
        'm_warna_air',
        $oldData,
        null,
        'Menghapus data Warna Air',
        'SUCCESS'
    );
}

header("Location: index.php?success=deleted");