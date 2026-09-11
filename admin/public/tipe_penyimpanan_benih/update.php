<?php
require "../../app/core/session.php";
secureSessionStart();
require "../../app/core/csrf.php";
require "../../app/config/database.php";
require "../../app/models/TipePenyimpananBenih.php";
require "../../app/core/permission.php";
require '../../app/core/activity_log.php';

Permission::authorize($pdo, 'Tipe Penyimpanan Benih', 'update');
verifyCsrfToken();

$tipePenyimpananBenihModel = new TipePenyimpananBenih($pdo);

$data = [
    "kode" => $_POST["kode"],
    "nama" => $_POST["nama"],
    "deskripsi" => $_POST["deskripsi"],
    "urutan" => $_POST["urutan"],
    "is_active" => $_POST["is_active"],
    "updated_by" => $_SESSION["user_id"]
];

$oldData = $tipePenyimpananBenihModel->findById($_POST["id"]);
$result = $tipePenyimpananBenihModel->update($_POST["id"], $data);

if ($result) {
    $newData = $tipePenyimpananBenihModel->findById($_POST["id"]);
    logActivity(
        $pdo,
        'UPDATE',
        'Tipe Penyimpanan Benih',
        $_POST["id"],
        'm_tipe_penyimpanan_benih',
        $oldData,
        $newData,
        'Mengubah data Tipe Penyimpanan Benih',
        'SUCCESS'
    );
}

header("Location: index.php?success=updated");