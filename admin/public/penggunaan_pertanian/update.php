<?php
require "../../app/core/session.php";
secureSessionStart();
require "../../app/core/csrf.php";
require "../../app/config/database.php";
require "../../app/models/PenggunaanPertanian.php";
require "../../app/core/permission.php";
require '../../app/core/activity_log.php';

Permission::authorize($pdo, 'Penggunaan Pertanian', 'update');
verifyCsrfToken();

$penggunaanPertanianModel = new PenggunaanPertanian($pdo);

$data = [
    "kode" => $_POST["kode"],
    "nama" => $_POST["nama"],
    "deskripsi" => $_POST["deskripsi"],
    "urutan" => $_POST["urutan"],
    "is_active" => $_POST["is_active"],
    "updated_by" => $_SESSION["user_id"]
];

$oldData = $penggunaanPertanianModel->findById($_POST["id"]);
$result = $penggunaanPertanianModel->update($_POST["id"], $data);

if ($result) {
    $newData = $penggunaanPertanianModel->findById($_POST["id"]);
    logActivity(
        $pdo,
        'UPDATE',
        'Penggunaan Pertanian',
        $_POST["id"],
        'm_penggunaan_pertanian',
        $oldData,
        $newData,
        'Mengubah data Penggunaan Pertanian',
        'SUCCESS'
    );
}

header("Location: index.php?success=updated");