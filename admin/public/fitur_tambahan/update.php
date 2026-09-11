<?php
require "../../app/core/session.php";
secureSessionStart();
require "../../app/core/csrf.php";
require "../../app/config/database.php";
require "../../app/models/FiturTambahan.php";
require "../../app/core/permission.php";
require '../../app/core/activity_log.php';

Permission::authorize($pdo, 'Fitur Tambahan', 'update');
verifyCsrfToken();

$fiturTambahanModel = new FiturTambahan($pdo);

$data = [
    "kode" => $_POST["kode"],
    "nama" => $_POST["nama"],
    "deskripsi" => $_POST["deskripsi"],
    "urutan" => $_POST["urutan"],
    "is_active" => $_POST["is_active"],
    "updated_by" => $_SESSION["user_id"]
];

$oldData = $fiturTambahanModel->findById($_POST["id"]);
$result = $fiturTambahanModel->update($_POST["id"], $data);

if ($result) {
    $newData = $fiturTambahanModel->findById($_POST["id"]);
    logActivity(
        $pdo,
        'UPDATE',
        'Fitur Tambahan',
        $_POST["id"],
        'm_fitur_tambahan',
        $oldData,
        $newData,
        'Mengubah data Fitur Tambahan',
        'SUCCESS'
    );
}

header("Location: index.php?success=updated");