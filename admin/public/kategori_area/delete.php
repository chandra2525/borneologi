<?php
require "../../app/core/session.php";
secureSessionStart();
require "../../app/config/database.php";
require "../../app/models/KategoriArea.php";
require "../../app/core/permission.php";
require '../../app/core/activity_log.php';

Permission::authorize($pdo, 'Kategori Area', 'delete');

$kategoriAreaModel = new KategoriArea($pdo);

$oldData = $kategoriAreaModel->findById($_POST["id"]);
$result = $kategoriAreaModel->softDelete($_POST["id"], $_SESSION["user_id"]);

if ($result) {
    logActivity(
        $pdo,
        'DELETE',
        'Kategori Area',
        $_POST["id"],
        'm_kategori_area',
        $oldData,
        null,
        'Menghapus data Kategori Area',
        'SUCCESS'
    );
}

header("Location: index.php?success=deleted");