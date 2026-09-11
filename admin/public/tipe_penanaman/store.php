<?php
require "../../app/core/session.php";
secureSessionStart();
require "../../app/core/csrf.php";
require "../../app/config/database.php";
require "../../app/models/TipePenanaman.php";
require "../../app/core/permission.php";
require '../../app/core/activity_log.php';

Permission::authorize($pdo, 'Tipe Penanaman', 'create');
verifyCsrfToken();

$tipePenanaman = new TipePenanaman($pdo);

$data = [
    "kode" => $_POST["kode"],
    "nama" => $_POST["nama"],
    "deskripsi" => $_POST["deskripsi"],
    "urutan" => $_POST["urutan"],
    "is_active" => $_POST["is_active"],
    "created_by" => $_SESSION["user_id"]
];

$newId = $tipePenanaman->create($data);

if ($newId) {
    logActivity(
        $pdo,
        'CREATE',
        'Tipe Penanaman',
        $newId,
        'm_tipe_penanaman',
        null,
        $data,
        'Menambahkan data Tipe Penanaman',
        'SUCCESS'
    );
}

header("Location: index.php?success=created");