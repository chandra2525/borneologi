<?php
require "../../app/core/session.php";
secureSessionStart();
require "../../app/core/csrf.php";
require "../../app/config/database.php";
require "../../app/models/JenisPalung.php";
require "../../app/core/permission.php";
require '../../app/core/activity_log.php';

Permission::authorize($pdo, 'Jenis Palung', 'update');
verifyCsrfToken();

$jenisPalungModel = new JenisPalung($pdo);

$data = [
    "kode" => $_POST["kode"],
    "nama" => $_POST["nama"],
    "deskripsi" => $_POST["deskripsi"],
    "urutan" => $_POST["urutan"],
    "is_active" => $_POST["is_active"],
    "updated_by" => $_SESSION["user_id"]
];

$oldData = $jenisPalungModel->findById($_POST["id"]);
$result = $jenisPalungModel->update($_POST["id"], $data);

if ($result) {
    $newData = $jenisPalungModel->findById($_POST["id"]);
    logActivity(
        $pdo,
        'UPDATE',
        'Jenis Palung',
        $_POST["id"],
        'm_jenis_palung',
        $oldData,
        $newData,
        'Mengubah data Jenis Palung',
        'SUCCESS'
    );
}

header("Location: index.php?success=updated");