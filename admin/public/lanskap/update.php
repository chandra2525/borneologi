<?php
require "../../app/core/session.php";
secureSessionStart();
require "../../app/core/csrf.php";
require "../../app/config/database.php";
require "../../app/models/Lanskap.php";
require "../../app/core/permission.php";
require '../../app/core/activity_log.php';

Permission::authorize($pdo, 'Lanskap', 'update');
verifyCsrfToken();

$lanskapModel = new Lanskap($pdo);

$data = [
    "kode" => $_POST["kode"],
    "nama" => $_POST["nama"],
    "deskripsi" => $_POST["deskripsi"],
    "urutan" => $_POST["urutan"],
    "is_active" => $_POST["is_active"],
    "updated_by" => $_SESSION["user_id"]
];

$oldData = $lanskapModel->findById($_POST["id"]);
$result = $lanskapModel->update($_POST["id"], $data);

if ($result) {
    $newData = $lanskapModel->findById($_POST["id"]);
    logActivity(
        $pdo,
        'UPDATE',
        'Lanskap',
        $_POST["id"],
        'm_lanskap',
        $oldData,
        $newData,
        'Mengubah data Lanskap',
        'SUCCESS'
    );
}

header("Location: index.php?success=updated");