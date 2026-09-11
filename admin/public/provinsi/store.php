<?php
require "../../app/core/session.php";
secureSessionStart();
require "../../app/core/csrf.php";
require "../../app/config/database.php";
require "../../app/models/Provinsi.php";
require "../../app/core/permission.php";
require '../../app/core/activity_log.php';

Permission::authorize($pdo, 'Provinsi', 'create');
verifyCsrfToken();

$provinsi = new Provinsi($pdo);

$data = [
    "kode_provinsi" => $_POST["kode_provinsi"],
    "nama_provinsi" => $_POST["nama_provinsi"],
    "is_active" => $_POST["is_active"],
    "created_by" => $_SESSION["user_id"]
];

$newId = $provinsi->create($data);

if ($newId) {
    logActivity(
        $pdo,
        'CREATE',
        'Provinsi',
        $newId,
        'm_provinsi',
        null,
        $data,
        'Menambahkan data Provinsi',
        'SUCCESS'
    );
}

// header("Location: index.php");
header("Location: index.php?success=created");