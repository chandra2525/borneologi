<?php
require "../../app/core/session.php";
secureSessionStart();
require "../../app/core/csrf.php";
require "../../app/config/database.php";
require "../../app/models/StatusKawasan.php";
require "../../app/core/permission.php";
require '../../app/core/activity_log.php';

Permission::authorize($pdo, 'Status Kawasan', 'update');
verifyCsrfToken();

$statusKawasanModel = new StatusKawasan($pdo);

$data = [
    "kode" => $_POST["kode"],
    "nama" => $_POST["nama"],
    "deskripsi" => $_POST["deskripsi"],
    "urutan" => $_POST["urutan"],
    "is_active" => $_POST["is_active"],
    "updated_by" => $_SESSION["user_id"]
];

$oldData = $statusKawasanModel->findById($_POST["id"]);
$result = $statusKawasanModel->update($_POST["id"], $data);

if ($result) {
    $newData = $statusKawasanModel->findById($_POST["id"]);
    logActivity(
        $pdo,
        'UPDATE',
        'Status Kawasan',
        $_POST["id"],
        'm_status_kawasan',
        $oldData,
        $newData,
        'Mengubah data Status Kawasan',
        'SUCCESS'
    );
}

header("Location: index.php?success=updated");