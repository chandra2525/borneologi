<?php
require "../../app/core/session.php";
secureSessionStart();
require "../../app/core/csrf.php";
require "../../app/config/database.php";
require "../../app/models/TipePenanaman.php";
require "../../app/core/permission.php";
require '../../app/core/activity_log.php';

Permission::authorize($pdo, 'Tipe Penanaman', 'update');
verifyCsrfToken();

$tipePenanamanModel = new TipePenanaman($pdo);

$data = [
    "kode" => $_POST["kode"],
    "nama" => $_POST["nama"],
    "deskripsi" => $_POST["deskripsi"],
    "urutan" => $_POST["urutan"],
    "is_active" => $_POST["is_active"],
    "updated_by" => $_SESSION["user_id"]
];

$oldData = $tipePenanamanModel->findById($_POST["id"]);
$result = $tipePenanamanModel->update($_POST["id"], $data);

if ($result) {
    $newData = $tipePenanamanModel->findById($_POST["id"]);
    logActivity(
        $pdo,
        'UPDATE',
        'Tipe Penanaman',
        $_POST["id"],
        'm_tipe_penanaman',
        $oldData,
        $newData,
        'Mengubah data Tipe Penanaman',
        'SUCCESS'
    );
}

header("Location: index.php?success=updated");