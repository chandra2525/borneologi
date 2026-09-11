<?php
require "../../app/core/session.php";
secureSessionStart();
require "../../app/core/csrf.php";
require "../../app/config/database.php";
require "../../app/models/LegalitasLahan.php";
require "../../app/core/permission.php";
require '../../app/core/activity_log.php';

Permission::authorize($pdo, 'Legalitas Lahan', 'update');
verifyCsrfToken();

$legalitasLahanModel = new LegalitasLahan($pdo);

$data = [
    "kode" => $_POST["kode"],
    "nama" => $_POST["nama"],
    "deskripsi" => $_POST["deskripsi"],
    "urutan" => $_POST["urutan"],
    "is_active" => $_POST["is_active"],
    "updated_by" => $_SESSION["user_id"]
];

$oldData = $legalitasLahanModel->findById($_POST["id"]);
$result = $legalitasLahanModel->update($_POST["id"], $data);

if ($result) {
    $newData = $legalitasLahanModel->findById($_POST["id"]);
    logActivity(
        $pdo,
        'UPDATE',
        'Legalitas Lahan',
        $_POST["id"],
        'm_legalitas_lahan',
        $oldData,
        $newData,
        'Mengubah data Legalitas Lahan',
        'SUCCESS'
    );
}

header("Location: index.php?success=updated");