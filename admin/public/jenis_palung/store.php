<?php
require "../../app/core/session.php";
secureSessionStart();
require "../../app/core/csrf.php";
require "../../app/config/database.php";
require "../../app/models/JenisPalung.php";
require "../../app/core/permission.php";
require '../../app/core/activity_log.php';

Permission::authorize($pdo, 'Jenis Palung', 'create');
verifyCsrfToken();

$jenisPalung = new JenisPalung($pdo);

$data = [
    "kode" => $_POST["kode"],
    "nama" => $_POST["nama"],
    "deskripsi" => $_POST["deskripsi"],
    "urutan" => $_POST["urutan"],
    "is_active" => $_POST["is_active"],
    "created_by" => $_SESSION["user_id"]
];

$newId = $jenisPalung->create($data);

if ($newId) {
    logActivity(
        $pdo,
        'CREATE',
        'Jenis Palung',
        $newId,
        'm_jenis_palung',
        null,
        $data,
        'Menambahkan data Jenis Palung',
        'SUCCESS'
    );
}

header("Location: index.php?success=created");