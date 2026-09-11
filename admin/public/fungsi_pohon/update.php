<?php
require "../../app/core/session.php";
secureSessionStart();
require "../../app/core/csrf.php";
require "../../app/config/database.php";
require "../../app/models/FungsiPohon.php";
require "../../app/core/permission.php";
require '../../app/core/activity_log.php';

Permission::authorize($pdo, 'Fungsi Pohon', 'update');
verifyCsrfToken();

$fungsiPohonModel = new FungsiPohon($pdo);

$data = [
    "kode" => $_POST["kode"],
    "nama" => $_POST["nama"],
    "deskripsi" => $_POST["deskripsi"],
    "urutan" => $_POST["urutan"],
    "is_active" => $_POST["is_active"],
    "updated_by" => $_SESSION["user_id"]
];

$oldData = $fungsiPohonModel->findById($_POST["id"]);
$result = $fungsiPohonModel->update($_POST["id"], $data);

if ($result) {
    $newData = $fungsiPohonModel->findById($_POST["id"]);
    logActivity(
        $pdo,
        'UPDATE',
        'Fungsi Pohon',
        $_POST["id"],
        'm_fungsi_pohon',
        $oldData,
        $newData,
        'Mengubah data Fungsi Pohon',
        'SUCCESS'
    );
}

header("Location: index.php?success=updated");