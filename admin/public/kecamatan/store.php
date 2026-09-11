<?php
require "../../app/core/session.php";
secureSessionStart();
require "../../app/core/csrf.php";
require "../../app/config/database.php";
require "../../app/models/Kecamatan.php";
require "../../app/core/permission.php";
require '../../app/core/activity_log.php';

Permission::authorize($pdo, 'Kecamatan', 'create');
verifyCsrfToken();

$kecamatan = new Kecamatan($pdo);

$data = [
    "id_kabupaten" => $_POST["id_kabupaten"],
    "kode_kecamatan" => $_POST["kode_kecamatan"],
    "nama_kecamatan" => $_POST["nama_kecamatan"],
    "is_active" => $_POST["is_active"],
    "created_by" => $_SESSION["user_id"]
];

$newId = $kecamatan->create($data);

if ($newId) {
    logActivity(
        $pdo,
        'CREATE',
        'Kecamatan',
        $newId,
        'm_kecamatan',
        null,
        $data,
        'Menambahkan data Kecamatan',
        'SUCCESS'
    );
}

header("Location: index.php?success=created");