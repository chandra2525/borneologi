<?php
require "../../app/core/session.php";
secureSessionStart();
require "../../app/core/csrf.php";
require "../../app/config/database.php";
require "../../app/models/KategoriArea.php";
require "../../app/core/permission.php";
require '../../app/core/activity_log.php';

Permission::authorize($pdo, 'Kategori Area', 'update');
verifyCsrfToken();

$kategoriAreaModel = new KategoriArea($pdo);

$data = [
    "kode" => $_POST["kode"],
    "nama" => $_POST["nama"],
    "deskripsi" => $_POST["deskripsi"],
    "urutan" => $_POST["urutan"],
    "is_active" => $_POST["is_active"],
    "updated_by" => $_SESSION["user_id"]
];

$oldData = $kategoriAreaModel->findById($_POST["id"]);
$result = $kategoriAreaModel->update($_POST["id"], $data);

if ($result) {
    $newData = $kategoriAreaModel->findById($_POST["id"]);
    logActivity(
        $pdo,
        'UPDATE',
        'Kategori Area',
        $_POST["id"],
        'm_kategori_area',
        $oldData,
        $newData,
        'Mengubah data Kategori Area',
        'SUCCESS'
    );
}

header("Location: index.php?success=updated");