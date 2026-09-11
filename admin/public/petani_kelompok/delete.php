<?php
require "../../app/core/session.php";
secureSessionStart();
require "../../app/config/database.php";
require "../../app/models/PetaniKelompok.php";
require "../../app/core/permission.php";
require '../../app/core/activity_log.php';

Permission::authorize($pdo, 'Kelompok Penerima', 'delete');

$petaniKelompokModel = new PetaniKelompok($pdo);

$oldData = $petaniKelompokModel->findById($_POST["id"]);
$result = $petaniKelompokModel->softDelete($_POST["id"], $_SESSION["user_id"]);

if ($result) {
    logActivity(
        $pdo,
        'DELETE',
        'Kelompok Penerima',
        $_POST["id"],
        't_petani_kelompok',
        $oldData,
        null,
        'Menghapus data Kelompok Penerima',
        'SUCCESS'
    );
}

header("Location: index.php?success=deleted");