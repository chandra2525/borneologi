<?php
require "../../app/core/session.php";
secureSessionStart();
require "../../app/core/csrf.php";
require "../../app/config/database.php";
require "../../app/models/KecepatanAliran.php";
require "../../app/core/permission.php";
require '../../app/core/activity_log.php';

Permission::authorize($pdo, 'Kecepatan Aliran', 'update');
verifyCsrfToken();

$kecepatanAliranModel = new KecepatanAliran($pdo);

$data = [
    "kode" => $_POST["kode"],
    "nama" => $_POST["nama"],
    "deskripsi" => $_POST["deskripsi"],
    "urutan" => $_POST["urutan"],
    "is_active" => $_POST["is_active"],
    "updated_by" => $_SESSION["user_id"]
];

$oldData = $kecepatanAliranModel->findById($_POST["id"]);
$result = $kecepatanAliranModel->update($_POST["id"], $data);

if ($result) {
    $newData = $kecepatanAliranModel->findById($_POST["id"]);
    logActivity(
        $pdo,
        'UPDATE',
        'Kecepatan Aliran',
        $_POST["id"],
        'm_kecepatan_aliran',
        $oldData,
        $newData,
        'Mengubah data Kecepatan Aliran',
        'SUCCESS'
    );
}

header("Location: index.php?success=updated");