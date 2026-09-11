<?php
require "../../app/core/session.php";
secureSessionStart();
require "../../app/core/csrf.php";
require "../../app/config/database.php";
require "../../app/models/Kabupaten.php";
require "../../app/core/permission.php";
require '../../app/core/activity_log.php';

Permission::authorize($pdo, 'Kabupaten', 'create');
verifyCsrfToken();

$kabupaten = new Kabupaten($pdo);

$data = [
    "id_provinsi" => $_POST["id_provinsi"],
    "kode_kabupaten" => $_POST["kode_kabupaten"],
    "nama_kabupaten" => $_POST["nama_kabupaten"],
    "tipe" => $_POST["tipe"],
    "is_active" => $_POST["is_active"],
    "created_by" => $_SESSION["user_id"]
];

$newId = $kabupaten->create($data);

if ($newId) {
    logActivity(
        $pdo,
        'CREATE',
        'Kabupaten',
        $newId,
        'm_kabupaten',
        null,
        $data,
        'Menambahkan data Kabupaten',
        'SUCCESS'
    );
}

header("Location: index.php?success=created");