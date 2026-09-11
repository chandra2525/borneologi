<?php
require "../../app/core/session.php";
secureSessionStart();
require "../../app/core/csrf.php";
require "../../app/config/database.php";
require "../../app/models/Provinsi.php";
require "../../app/core/permission.php";
require '../../app/core/activity_log.php';

Permission::authorize($pdo, 'Provinsi', 'update');
verifyCsrfToken();

$provinsiModel = new Provinsi($pdo);

$data = [
    "kode_provinsi" => $_POST["kode_provinsi"],
    "nama_provinsi" => $_POST["nama_provinsi"],
    "is_active" => $_POST["is_active"],
    "updated_by" => $_SESSION["user_id"]
];

$oldData = $provinsiModel->findById($_POST["id"]);
$result = $provinsiModel->update($_POST["id"], $data);

if ($result) {
    $newData = $provinsiModel->findById($_POST["id"]);
    logActivity(
        $pdo,
        'UPDATE',
        'Provinsi',
        $_POST["id"],
        'm_provinsi',
        $oldData,
        $newData,
        'Mengubah data Provinsi',
        'SUCCESS'
    );
}

// header("Location: index.php");
header("Location: index.php?success=updated");