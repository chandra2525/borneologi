<?php
require "../../app/core/session.php";
secureSessionStart();
require "../../app/core/csrf.php";
require "../../app/config/database.php";
require "../../app/models/Lanskap.php";
require "../../app/core/permission.php";
require '../../app/core/activity_log.php';

Permission::authorize($pdo, 'Lanskap', 'create');
verifyCsrfToken();

$lanskap = new Lanskap($pdo);

$data = [
    "kode" => $_POST["kode"],
    "nama" => $_POST["nama"],
    "deskripsi" => $_POST["deskripsi"],
    "urutan" => $_POST["urutan"],
    "is_active" => $_POST["is_active"],
    "created_by" => $_SESSION["user_id"]
];

$newId = $lanskap->create($data);

if ($newId) {
    logActivity(
        $pdo,
        'CREATE',
        'Lanskap',
        $newId,
        'm_lanskap',
        null,
        $data,
        'Menambahkan data Lanskap',
        'SUCCESS'
    );
}

header("Location: index.php?success=created");