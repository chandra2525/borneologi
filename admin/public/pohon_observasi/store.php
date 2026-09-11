<?php
require "../../app/core/session.php";
secureSessionStart();
require "../../app/core/csrf.php";
require "../../app/config/database.php";
require "../../app/models/PohonObservasi.php";
require "../../app/core/permission.php";
require '../../app/core/activity_log.php';

Permission::authorize($pdo, 'Pohon Observasi', 'create');
verifyCsrfToken();

$pohonObservasi = new PohonObservasi($pdo);

$data = [
    "id_tanah" => $_POST["id_tanah"],
    "periode_pengecekan" => $_POST["periode_pengecekan"],
    "id_jenis_pohon" => $_POST["id_jenis_pohon"],
    "id_fungsi_pohon" => $_POST["id_fungsi_pohon"],
    "jumlah_pohon" => $_POST["jumlah_pohon"],
    "diameter_rata2_cm" => $_POST["diameter_rata2_cm"],
    "tinggi_rata2_m" => $_POST["tinggi_rata2_m"],
    "kondisi" => $_POST["kondisi"],
    "catatan" => $_POST["catatan"],
    "created_by" => $_SESSION["user_id"]
];

$newId = $pohonObservasi->create($data);

if ($newId) {
    logActivity(
        $pdo,
        'CREATE',
        'Pohon Observasi',
        $newId,
        't_pohon_observasi',
        null,
        $data,
        'Menambahkan data Pohon Observasi',
        'SUCCESS'
    );
}

header("Location: index.php?success=created");