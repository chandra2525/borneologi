<?php
require "../../app/core/session.php";
secureSessionStart();
require "../../app/config/database.php";
require "../../app/models/JabatanKelompok.php";
require "../../app/core/permission.php";
require '../../app/core/activity_log.php';

Permission::authorize($pdo, 'Jabatan Kelompok', 'delete');

$jabatanKelompokModel = new JabatanKelompok($pdo);

$oldData = $jabatanKelompokModel->findById($_POST["id"]);
$result = $jabatanKelompokModel->softDelete($_POST["id"], $_SESSION["user_id"]);

if ($result) {
    logActivity(
        $pdo,
        'DELETE',
        'Jabatan Kelompok',
        $_POST["id"],
        'm_jabatan_kelompok',
        $oldData,
        null,
        'Menghapus data Jabatan Kelompok',
        'SUCCESS'
    );
}

// header("Location: index.php");
header("Location: index.php?success=deleted");