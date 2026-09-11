<?php
require "../../app/core/session.php";
secureSessionStart();
require "../../app/core/csrf.php";
require "../../app/config/database.php";
require "../../app/models/LegalitasLahan.php";
require "../../app/core/permission.php";
require '../../app/core/activity_log.php';

Permission::authorize($pdo, 'Legalitas Lahan', 'create');
verifyCsrfToken();

$legalitasLahan = new LegalitasLahan($pdo);

$data = [
    "kode" => $_POST["kode"],
    "nama" => $_POST["nama"],
    "deskripsi" => $_POST["deskripsi"],
    "urutan" => $_POST["urutan"],
    "is_active" => $_POST["is_active"],
    "created_by" => $_SESSION["user_id"]
];

$newId = $legalitasLahan->create($data);

if ($newId) {
    logActivity(
        $pdo,
        'CREATE',
        'Legalitas Lahan',
        $newId,
        'm_legalitas_lahan',
        null,
        $data,
        'Menambahkan data Legalitas Lahan',
        'SUCCESS'
    );
}

header("Location: index.php?success=created");