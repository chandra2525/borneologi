<?php
require "../../app/core/session.php";
secureSessionStart();
require "../../app/config/database.php";
require "../../app/models/KelompokTani.php";
require "../../app/core/permission.php";
require '../../app/core/activity_log.php';

Permission::authorize($pdo, 'Kelompok CBO', 'delete');

$kelompokTaniModel = new KelompokTani($pdo);

$oldData = $kelompokTaniModel->findById($_POST["id"]);
$result = $kelompokTaniModel->softDelete($_POST["id"], $_SESSION["user_id"]);

if ($result) {
    logActivity(
        $pdo,
        'DELETE',
        'Kelompok CBO',
        $_POST["id"],
        't_kelompok_tani',
        $oldData,
        null,
        'Menghapus data Kelompok CBO',
        'SUCCESS'
    );
}

header("Location: index.php?success=deleted");