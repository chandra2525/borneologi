<?php
require "../../app/core/session.php";
secureSessionStart();
require "../../app/core/csrf.php";
require "../../app/config/database.php";
require "../../app/models/WarnaAir.php";
require "../../app/core/permission.php";
require '../../app/core/activity_log.php';

Permission::authorize($pdo, 'Warna Air', 'update');
verifyCsrfToken();

$warnaAirModel = new WarnaAir($pdo);

$data = [
    "kode" => $_POST["kode"],
    "nama" => $_POST["nama"],
    "deskripsi" => $_POST["deskripsi"],
    "urutan" => $_POST["urutan"],
    "is_active" => $_POST["is_active"],
    "updated_by" => $_SESSION["user_id"]
];

$oldData = $warnaAirModel->findById($_POST["id"]);
$result = $warnaAirModel->update($_POST["id"], $data);

if ($result) {
    $newData = $warnaAirModel->findById($_POST["id"]);
    logActivity(
        $pdo,
        'UPDATE',
        'Warna Air',
        $_POST["id"],
        'm_warna_air',
        $oldData,
        $newData,
        'Mengubah data Warna Air',
        'SUCCESS'
    );
}

header("Location: index.php?success=updated");