<?php
require "../../app/core/session.php";
secureSessionStart();
require "../../app/core/csrf.php";
require "../../app/config/database.php";
require "../../app/models/AksesPerjalanan.php";
require "../../app/core/permission.php";
require '../../app/core/activity_log.php';

Permission::authorize($pdo, 'Akses Perjalanan', 'update');
verifyCsrfToken();

$aksesPerjalananModel = new AksesPerjalanan($pdo);

$data = [
    "kode" => $_POST["kode"],
    "nama" => $_POST["nama"],
    "deskripsi" => $_POST["deskripsi"],
    "urutan" => $_POST["urutan"],
    "is_active" => $_POST["is_active"],
    "updated_by" => $_SESSION["user_id"]
];

$oldData = $aksesPerjalananModel->findById($_POST["id"]);
$result = $aksesPerjalananModel->update($_POST["id"], $data);

if ($result) {
    $newData = $aksesPerjalananModel->findById($_POST["id"]);
    logActivity(
        $pdo,
        'UPDATE',
        'Akses Perjalanan',
        $_POST["id"],
        'm_akses_perjalanan',
        $oldData,
        $newData,
        'Mengubah data Akses Perjalanan',
        'SUCCESS'
    );
}

header("Location: index.php?success=updated");