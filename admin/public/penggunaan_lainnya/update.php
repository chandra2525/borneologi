<?php
require "../../app/core/session.php";
secureSessionStart();
require "../../app/core/csrf.php";
require "../../app/config/database.php";
require "../../app/models/PenggunaanLainnya.php";
require "../../app/core/permission.php";
require '../../app/core/activity_log.php';

Permission::authorize($pdo, 'Penggunaan Lainnya', 'update');
verifyCsrfToken();

$penggunaanLainnyaModel = new PenggunaanLainnya($pdo);

$data = [
    "kode" => $_POST["kode"],
    "nama" => $_POST["nama"],
    "deskripsi" => $_POST["deskripsi"],
    "urutan" => $_POST["urutan"],
    "is_active" => $_POST["is_active"],
    "updated_by" => $_SESSION["user_id"]
];

$oldData = $penggunaanLainnyaModel->findById($_POST["id"]);
$result = $penggunaanLainnyaModel->update($_POST["id"], $data);

if ($result) {
    $newData = $penggunaanLainnyaModel->findById($_POST["id"]);
    logActivity(
        $pdo,
        'UPDATE',
        'Penggunaan Lainnya',
        $_POST["id"],
        'm_penggunaan_lainnya',
        $oldData,
        $newData,
        'Mengubah data Penggunaan Lainnya',
        'SUCCESS'
    );
}

header("Location: index.php?success=updated");