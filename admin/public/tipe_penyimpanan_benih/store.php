<?php
require "../../app/core/session.php";
secureSessionStart();
require "../../app/core/csrf.php";
require "../../app/config/database.php";
require "../../app/models/TipePenyimpananBenih.php";
require "../../app/core/permission.php";
require '../../app/core/activity_log.php';

Permission::authorize($pdo, 'Tipe Penyimpanan Benih', 'create');
verifyCsrfToken();

$tipePenyimpananBenih = new TipePenyimpananBenih($pdo);

$data = [
    "kode" => $_POST["kode"],
    "nama" => $_POST["nama"],
    "deskripsi" => $_POST["deskripsi"],
    "urutan" => $_POST["urutan"],
    "is_active" => $_POST["is_active"],
    "created_by" => $_SESSION["user_id"]
];

$newId = $tipePenyimpananBenih->create($data);

if ($newId) {
    logActivity(
        $pdo,
        'CREATE',
        'Tipe Penyimpanan Benih',
        $newId,
        'm_tipe_penyimpanan_benih',
        null,
        $data,
        'Menambahkan data Tipe Penyimpanan Benih',
        'SUCCESS'
    );
}

header("Location: index.php?success=created");