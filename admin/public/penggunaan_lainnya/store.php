<?php
require "../../app/core/session.php";
secureSessionStart();
require "../../app/core/csrf.php";
require "../../app/config/database.php";
require "../../app/models/PenggunaanLainnya.php";
require "../../app/core/permission.php";
require '../../app/core/activity_log.php';

Permission::authorize($pdo, 'Penggunaan Lainnya', 'create');
verifyCsrfToken();

$penggunaanLainnya = new PenggunaanLainnya($pdo);

$data = [
    "kode" => $_POST["kode"],
    "nama" => $_POST["nama"],
    "deskripsi" => $_POST["deskripsi"],
    "urutan" => $_POST["urutan"],
    "is_active" => $_POST["is_active"],
    "created_by" => $_SESSION["user_id"]
];

$newId = $penggunaanLainnya->create($data);

if ($newId) {
    logActivity(
        $pdo,
        'CREATE',
        'Penggunaan Lainnya',
        $newId,
        'm_penggunaan_lainnya',
        null,
        $data,
        'Menambahkan data Penggunaan Lainnya',
        'SUCCESS'
    );
}

header("Location: index.php?success=created");