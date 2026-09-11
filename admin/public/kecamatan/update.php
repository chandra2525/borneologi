<?php
require "../../app/core/session.php";
secureSessionStart();
require "../../app/core/csrf.php";
require "../../app/config/database.php";
require "../../app/models/Kecamatan.php";
require "../../app/core/permission.php";
require '../../app/core/activity_log.php';

Permission::authorize($pdo, 'Kecamatan', 'update');
verifyCsrfToken();

$kecamatanModel = new Kecamatan($pdo);

$data = [
    "id_kabupaten" => $_POST["id_kabupaten"],
    "kode_kecamatan" => $_POST["kode_kecamatan"],
    "nama_kecamatan" => $_POST["nama_kecamatan"],
    "is_active" => $_POST["is_active"],
    "updated_by" => $_SESSION["user_id"]
];

$oldData = $kecamatanModel->findById($_POST["id"]);
$result = $kecamatanModel->update($_POST["id"], $data);

if ($result) {
    $newData = $kecamatanModel->findById($_POST["id"]);
    logActivity(
        $pdo,
        'UPDATE',
        'Kecamatan',
        $_POST["id"],
        'm_kecamatan',
        $oldData,
        $newData,
        'Mengubah data Kecamatan',
        'SUCCESS'
    );
}

header("Location: index.php?success=updated");