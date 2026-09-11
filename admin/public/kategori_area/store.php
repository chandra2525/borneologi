<?php
require "../../app/core/session.php";
secureSessionStart();
require "../../app/core/csrf.php";
require "../../app/config/database.php";
require "../../app/models/KategoriArea.php";
require "../../app/core/permission.php";
require '../../app/core/activity_log.php';

Permission::authorize($pdo, 'Kategori Area', 'create');
verifyCsrfToken();

$kategoriArea = new KategoriArea($pdo);

$data = [
    "kode" => $_POST["kode"],
    "nama" => $_POST["nama"],
    "deskripsi" => $_POST["deskripsi"],
    "urutan" => $_POST["urutan"],
    "is_active" => $_POST["is_active"],
    "created_by" => $_SESSION["user_id"]
];

$newId = $kategoriArea->create($data);

if ($newId) {
    logActivity(
        $pdo,
        'CREATE',
        'Kategori Area',
        $newId,
        'm_kategori_area',
        null,
        $data,
        'Menambahkan data Kategori Area',
        'SUCCESS'
    );
}

header("Location: index.php?success=created");