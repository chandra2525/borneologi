<?php
require "../../app/core/session.php";
secureSessionStart();
require "../../app/core/csrf.php";
require "../../app/config/database.php";
require "../../app/models/Desa.php";
require "../../app/core/permission.php";
require '../../app/core/activity_log.php';

Permission::authorize($pdo, 'Desa', 'create');
verifyCsrfToken();

$desa = new Desa($pdo);

$data = [
    "id_kecamatan" => $_POST["id_kecamatan"],
    "kode_desa" => $_POST["kode_desa"],
    "nama_desa" => $_POST["nama_desa"],
    "is_active" => $_POST["is_active"],
    "created_by" => $_SESSION["user_id"]
];

$newId = $desa->create($data);

if ($newId) {
    logActivity(
        $pdo,
        'CREATE',
        'Desa',
        $newId,
        'm_desa',
        null,
        $data,
        'Menambahkan data Desa',
        'SUCCESS'
    );
}

header("Location: index.php?success=created");