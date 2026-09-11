<?php
require "../../app/core/session.php";
secureSessionStart();
require "../../app/core/csrf.php";
require "../../app/config/database.php";
require "../../app/models/KondisiJalan.php";
require "../../app/core/permission.php";
require '../../app/core/activity_log.php';

Permission::authorize($pdo, 'Kondisi Jalan', 'update');
verifyCsrfToken();

$kondisiJalanModel = new KondisiJalan($pdo);

$data = [
    "kode" => $_POST["kode"],
    "nama" => $_POST["nama"],
    "deskripsi" => $_POST["deskripsi"],
    "urutan" => $_POST["urutan"],
    "is_active" => $_POST["is_active"],
    "updated_by" => $_SESSION["user_id"]
];

$oldData = $kondisiJalanModel->findById($_POST["id"]);
$result = $kondisiJalanModel->update($_POST["id"], $data);

if ($result) {
    $newData = $kondisiJalanModel->findById($_POST["id"]);
    logActivity(
        $pdo,
        'UPDATE',
        'Kondisi Jalan',
        $_POST["id"],
        'm_kondisi_jalan',
        $oldData,
        $newData,
        'Mengubah data Kondisi Jalan',
        'SUCCESS'
    );
}

header("Location: index.php?success=updated");