<?php
require "../../app/core/session.php";
secureSessionStart();
require "../../app/core/csrf.php";
require "../../app/config/database.php";
require "../../app/models/JabatanKelompok.php";
require "../../app/core/permission.php";
require '../../app/core/activity_log.php';

Permission::authorize($pdo, 'Jabatan Kelompok', 'update');
verifyCsrfToken();

$jabatanKelompokModel = new JabatanKelompok($pdo);

$data = [
    "kode" => $_POST["kode"],
    "nama" => $_POST["nama"],
    // "is_pengurus" => $_POST["is_pengurus"],
    "is_pengurus" => isset($_POST["is_pengurus"]) ? 1 : 0,
    "deskripsi" => $_POST["deskripsi"],
    "urutan" => $_POST["urutan"],
    "is_active" => $_POST["is_active"],
    "updated_by" => $_SESSION["user_id"]
];

$oldData = $jabatanKelompokModel->findById($_POST["id"]);
$result = $jabatanKelompokModel->update($_POST["id"], $data);

if ($result) {
    $newData = $jabatanKelompokModel->findById($_POST["id"]);
    logActivity(
        $pdo,
        'UPDATE',
        'Jabatan Kelompok',
        $_POST["id"],
        'm_jabatan_kelompok',
        $oldData,
        $newData,
        'Mengubah data Jabatan Kelompok',
        'SUCCESS'
    );
}

header("Location: index.php?success=updated");