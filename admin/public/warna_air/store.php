<?php
require "../../app/core/session.php";
secureSessionStart();
require "../../app/core/csrf.php";
require "../../app/config/database.php";
require "../../app/models/WarnaAir.php";
require "../../app/core/permission.php";
require '../../app/core/activity_log.php';

Permission::authorize($pdo, 'Warna Air', 'create');
verifyCsrfToken();

$warnaAir = new WarnaAir($pdo);

$data = [
    "kode" => $_POST["kode"],
    "nama" => $_POST["nama"],
    "deskripsi" => $_POST["deskripsi"],
    "urutan" => $_POST["urutan"],
    "is_active" => $_POST["is_active"],
    "created_by" => $_SESSION["user_id"]
];

$newId = $warnaAir->create($data);

if ($newId) {
    logActivity(
        $pdo,
        'CREATE',
        'Warna Air',
        $newId,
        'm_warna_air',
        null,
        $data,
        'Menambahkan data Warna Air',
        'SUCCESS'
    );
}

header("Location: index.php?success=created");