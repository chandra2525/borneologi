<?php
require "../../app/core/session.php";
secureSessionStart();
require "../../app/core/csrf.php";
require "../../app/config/database.php";
require "../../app/models/FungsiPohon.php";
require "../../app/core/permission.php";
require '../../app/core/activity_log.php';

Permission::authorize($pdo, 'Fungsi Pohon', 'create');
verifyCsrfToken();

$fungsiPohon = new FungsiPohon($pdo);

$data = [
    "kode" => $_POST["kode"],
    "nama" => $_POST["nama"],
    "deskripsi" => $_POST["deskripsi"],
    "urutan" => $_POST["urutan"],
    "is_active" => $_POST["is_active"],
    "created_by" => $_SESSION["user_id"]
];

$newId = $fungsiPohon->create($data);

if ($newId) {
    logActivity(
        $pdo,
        'CREATE',
        'Fungsi Pohon',
        $newId,
        'm_fungsi_pohon',
        null,
        $data,
        'Menambahkan data Fungsi Pohon',
        'SUCCESS'
    );
}

header("Location: index.php?success=created");