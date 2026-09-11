<?php
require "../../app/core/session.php";
secureSessionStart();
require "../../app/core/csrf.php";
require "../../app/config/database.php";
require "../../app/models/Role.php";
require "../../app/core/permission.php";
require '../../app/core/activity_log.php';

Permission::authorize($pdo, 'Roles', 'update');
verifyCsrfToken();

$roleModel = new Role($pdo);

$data = [
    "kode" => $_POST["kode"],
    "nama" => $_POST["nama"],
    "deskripsi" => $_POST["deskripsi"],
    "urutan" => $_POST["urutan"],
    "is_active" => $_POST["is_active"],
    "updated_by" => $_SESSION["user_id"]
];

$oldData = $roleModel->findById($_POST["id"]);
$result = $roleModel->update($_POST["id"], $data);

if ($result) {
    $newData = $roleModel->findById($_POST["id"]);
    logActivity(
        $pdo,
        'UPDATE',
        'Roles',
        $_POST["id"],
        'm_roles',
        $oldData,
        $newData,
        'Mengubah data Roles',
        'SUCCESS'
    );
}

// header("Location: index.php");
header("Location: index.php?success=updated");