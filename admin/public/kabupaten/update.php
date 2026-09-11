<?php
require "../../app/core/session.php";
secureSessionStart();
require "../../app/core/csrf.php";
require "../../app/config/database.php";
require "../../app/models/Kabupaten.php";
require "../../app/core/permission.php";
require '../../app/core/activity_log.php';

Permission::authorize($pdo, 'Kabupaten', 'update');
verifyCsrfToken();

$kabupatenModel = new Kabupaten($pdo);

$data = [
    "id_provinsi" => $_POST["id_provinsi"],
    "kode_kabupaten" => $_POST["kode_kabupaten"],
    "nama_kabupaten" => $_POST["nama_kabupaten"],
    "tipe" => $_POST["tipe"],
    "is_active" => $_POST["is_active"],
    "updated_by" => $_SESSION["user_id"]
];

$oldData = $kabupatenModel->findById($_POST["id"]);
$result = $kabupatenModel->update($_POST["id"], $data);

if ($result) {
    $newData = $kabupatenModel->findById($_POST["id"]);
    logActivity(
        $pdo,
        'UPDATE',
        'Kabupaten',
        $_POST["id"],
        'm_kabupaten',
        $oldData,
        $newData,
        'Mengubah data Kabupaten',
        'SUCCESS'
    );
}

header("Location: index.php?success=updated");