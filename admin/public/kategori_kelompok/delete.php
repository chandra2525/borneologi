<?php
require "../../app/core/session.php";
secureSessionStart();
require "../../app/config/database.php";
require "../../app/models/KategoriKelompok.php";
require "../../app/core/permission.php";
require '../../app/core/activity_log.php';

Permission::authorize($pdo, 'Kategori Kelompok', 'delete');

$kategoriKelompokModel = new KategoriKelompok($pdo);

$oldData = $kategoriKelompokModel->findById($_POST["id"]);
$result = $kategoriKelompokModel->softDelete($_POST["id"], $_SESSION["user_id"]);

if ($result) {
    logActivity(
        $pdo,
        'DELETE',
        'Kategori Kelompok',
        $_POST["id"],
        'm_kategori_kelompok',
        $oldData,
        null,
        'Menghapus data Kategori Kelompok',
        'SUCCESS'
    );
}

// header("Location: index.php");
header("Location: index.php?success=deleted");