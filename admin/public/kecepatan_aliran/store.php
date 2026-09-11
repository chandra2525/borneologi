<?php
require "../../app/core/session.php";
secureSessionStart();
require "../../app/core/csrf.php";
require "../../app/config/database.php";
require "../../app/models/KecepatanAliran.php";
require "../../app/core/permission.php";
require '../../app/core/activity_log.php';

Permission::authorize($pdo, 'Kecepatan Aliran', 'create');
verifyCsrfToken();

$kecepatanAliran = new KecepatanAliran($pdo);

$data = [
    "kode" => $_POST["kode"],
    "nama" => $_POST["nama"],
    "deskripsi" => $_POST["deskripsi"],
    "urutan" => $_POST["urutan"],
    "is_active" => $_POST["is_active"],
    "created_by" => $_SESSION["user_id"]
];

$newId = $kecepatanAliran->create($data);

if ($newId) {
    logActivity(
        $pdo,
        'CREATE',
        'Kecepatan Aliran',
        $newId,
        'm_kecepatan_aliran',
        null,
        $data,
        'Menambahkan data Kecepatan Aliran',
        'SUCCESS'
    );
}

header("Location: index.php?success=created");