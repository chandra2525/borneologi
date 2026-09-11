<?php
require "../../app/core/session.php";
secureSessionStart();
require "../../app/core/csrf.php";
require "../../app/config/database.php";
require "../../app/models/JenisPohon.php";
require "../../app/core/permission.php";
require '../../app/core/activity_log.php';

Permission::authorize($pdo, 'Jenis Pohon', 'create');
verifyCsrfToken();

$jenisPohon = new JenisPohon($pdo);

$data = [
    "kode" => $_POST["kode"],
    "nama" => $_POST["nama"],
    "nama_latin" => $_POST["nama_latin"],
    "deskripsi" => $_POST["deskripsi"],
    "urutan" => $_POST["urutan"],
    "is_active" => $_POST["is_active"],
    "created_by" => $_SESSION["user_id"]
];

$newId = $jenisPohon->create($data);

if ($newId) {
    logActivity(
        $pdo,
        'CREATE',
        'Jenis Pohon',
        $newId,
        'm_jenis_pohon',
        null,
        $data,
        'Menambahkan data Jenis Pohon',
        'SUCCESS'
    );
}

header("Location: index.php?success=created");