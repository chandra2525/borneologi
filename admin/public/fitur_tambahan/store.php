<?php
require "../../app/core/session.php";
secureSessionStart();
require "../../app/core/csrf.php";
require "../../app/config/database.php";
require "../../app/models/FiturTambahan.php";
require "../../app/core/permission.php";
require '../../app/core/activity_log.php';

Permission::authorize($pdo, 'Fitur Tambahan', 'create');

verifyCsrfToken();

$fiturTambahan = new FiturTambahan($pdo);

$data = [
    "kode" => $_POST["kode"],
    "nama" => $_POST["nama"],
    "deskripsi" => $_POST["deskripsi"],
    "urutan" => $_POST["urutan"],
    "is_active" => $_POST["is_active"],
    "created_by" => $_SESSION["user_id"]
];

$newId = $fiturTambahan->create($data);

if ($newId) {
    logActivity(
        $pdo,
        'CREATE',
        'Fitur Tambahan',
        $newId,
        'm_fitur_tambahan',
        null,
        $data,
        'Menambahkan data Fitur Tambahan',
        'SUCCESS'
    );
}

header("Location: index.php?success=created");