<?php
require "../../app/core/session.php";
secureSessionStart();
require "../../app/core/csrf.php";
require "../../app/config/database.php";
require "../../app/models/KondisiJalan.php";
require "../../app/core/permission.php";
require '../../app/core/activity_log.php';

Permission::authorize($pdo, 'Kondisi Jalan', 'create');
verifyCsrfToken();

$kondisiJalan = new KondisiJalan($pdo);

$data = [
    "kode" => $_POST["kode"],
    "nama" => $_POST["nama"],
    "deskripsi" => $_POST["deskripsi"],
    "urutan" => $_POST["urutan"],
    "is_active" => $_POST["is_active"],
    "created_by" => $_SESSION["user_id"]
];

$newId = $kondisiJalan->create($data);

if ($newId) {
    logActivity(
        $pdo,
        'CREATE',
        'Kondisi Jalan',
        $newId,
        'm_kondisi_jalan',
        null,
        $data,
        'Menambahkan data Kondisi Jalan',
        'SUCCESS'
    );
}

header("Location: index.php?success=created");