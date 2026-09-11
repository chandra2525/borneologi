<?php
require "../../app/core/session.php";
secureSessionStart();
require "../../app/core/csrf.php";
require "../../app/config/database.php";
require "../../app/models/StatusKawasan.php";
require "../../app/core/permission.php";
require '../../app/core/activity_log.php';

Permission::authorize($pdo, 'Status Kawasan', 'create');
verifyCsrfToken();

$statusKawasan = new StatusKawasan($pdo);

$data = [
    "kode" => $_POST["kode"],
    "nama" => $_POST["nama"],
    "deskripsi" => $_POST["deskripsi"],
    "urutan" => $_POST["urutan"],
    "is_active" => $_POST["is_active"],
    "created_by" => $_SESSION["user_id"]
];

$newId = $statusKawasan->create($data);

if ($newId) {
    logActivity(
        $pdo,
        'CREATE',
        'Status Kawasan',
        $newId,
        'm_status_kawasan',
        null,
        $data,
        'Menambahkan data Status Kawasan',
        'SUCCESS'
    );
}

header("Location: index.php?success=created");