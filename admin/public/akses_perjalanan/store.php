<?php
require "../../app/core/session.php";
secureSessionStart();
require "../../app/core/csrf.php";
require "../../app/config/database.php";
require "../../app/models/AksesPerjalanan.php";
require "../../app/core/permission.php";
require '../../app/core/activity_log.php';

Permission::authorize($pdo, 'Akses Perjalanan', 'create');
verifyCsrfToken();

$aksesPerjalanan = new AksesPerjalanan($pdo);

$data = [
    "kode" => $_POST["kode"],
    "nama" => $_POST["nama"],
    "deskripsi" => $_POST["deskripsi"],
    "urutan" => $_POST["urutan"],
    "is_active" => $_POST["is_active"],
    "created_by" => $_SESSION["user_id"]
];

$newId = $aksesPerjalanan->create($data);

if ($newId) {
    logActivity(
        $pdo,
        'CREATE',
        'Akses Perjalanan',
        $newId,
        'm_akses_perjalanan',
        null,
        $data,
        'Menambahkan data Akses Perjalanan',
        'SUCCESS'
    );
}

header("Location: index.php?success=created");