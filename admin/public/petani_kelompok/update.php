<?php
require "../../app/core/session.php";
secureSessionStart();
require "../../app/core/csrf.php";
require "../../app/config/database.php";
require "../../app/models/PetaniKelompok.php";
require "../../app/core/permission.php";
require '../../app/core/activity_log.php';

Permission::authorize($pdo, 'Kelompok Penerima', 'update');
verifyCsrfToken();

$petaniKelompokModel = new PetaniKelompok($pdo);

$data = [
    "id_petani" => $_POST["id_petani"],
    "id_kelompok_tani" => $_POST["id_kelompok_tani"],
    "id_jabatan_kelompok" => $_POST["id_jabatan_kelompok"],
    "tanggal_gabung" => $_POST["tanggal_gabung"],
    "tanggal_keluar" => $_POST["tanggal_keluar"],
    // "is_pengurus" => $_POST["is_pengurus"],
    "keterangan" => $_POST["keterangan"],
    "is_active" => $_POST["is_active"],
    "updated_by" => $_SESSION["user_id"]
];

$oldData = $petaniKelompokModel->findById($_POST["id"]);
$result = $petaniKelompokModel->update($_POST["id"], $data);

if ($result) {
    $newData = $petaniKelompokModel->findById($_POST["id"]);
    logActivity(
        $pdo,
        'UPDATE',
        'Kelompok Penerima',
        $_POST["id"],
        't_petani_kelompok',
        $oldData,
        $newData,
        'Mengubah data Kelompok Penerima',
        'SUCCESS'
    );
}

header("Location: index.php?success=updated");