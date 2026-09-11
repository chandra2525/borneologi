<?php
require "../../app/core/session.php";
secureSessionStart();
require "../../app/core/csrf.php";
require "../../app/config/database.php";
require "../../app/models/KelompokTani.php";
require "../../app/core/permission.php";
require '../../app/core/activity_log.php';

Permission::authorize($pdo, 'Kelompok', 'create');
verifyCsrfToken();

$kelompokTani = new KelompokTani($pdo);

$data = [
    "kode_kelompok" => $_POST["kode_kelompok"],
    "nama_kelompok" => $_POST["nama_kelompok"],
    "id_kategori_kelompok" => $_POST["id_kategori_kelompok"],
    "id_desa" => $_POST["id_desa"],
    "alamat" => $_POST["alamat"],
    "id_akses_perjalanan" => $_POST["id_akses_perjalanan"],
    "id_kondisi_jalan" => $_POST["id_kondisi_jalan"],
    "tahun_bentuk" => $_POST["tahun_bentuk"],
    "nomor_sk" => $_POST["nomor_sk"],
    "tanggal_sk" => $_POST["tanggal_sk"],
    "status_kelompok" => $_POST["status_kelompok"],
    "is_active" => $_POST["is_active"],
    "created_by" => $_SESSION["user_id"]
];

$newId = $kelompokTani->create($data);

if ($newId) {
    logActivity(
        $pdo,
        'CREATE',
        'Kelompok',
        $newId,
        't_kelompok_tani',
        null,
        $data,
        'Menambahkan data Kelompok',
        'SUCCESS'
    );
}

header("Location: index.php?success=created");