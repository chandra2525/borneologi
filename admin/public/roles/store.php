<?php
require "../../app/core/session.php";
secureSessionStart();
require "../../app/core/csrf.php";
require "../../app/config/database.php";
require "../../app/models/Role.php";
require "../../app/core/permission.php";
require '../../app/core/activity_log.php';

Permission::authorize($pdo, 'Roles', 'create');
verifyCsrfToken();

$role = new Role($pdo);

$data = [
    "kode" => $_POST["kode"],
    "nama" => $_POST["nama"],
    "deskripsi" => $_POST["deskripsi"],
    "urutan" => $_POST["urutan"],
    "is_active" => $_POST["is_active"],
    "created_by" => $_SESSION["user_id"]
];

$newId = $role->create($data);

if ($newId) {
    logActivity(
        $pdo,
        'CREATE',
        'Roles',
        $newId,
        'm_roles',
        null,
        $data,
        'Menambahkan data Roles',
        'SUCCESS'
    );
}

// header("Location: index.php");
header("Location: index.php?success=created");