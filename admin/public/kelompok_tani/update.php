<?php
require "../../app/core/session.php";
require "../../app/core/csrf.php";

require "../../app/config/database.php";
require "../../app/models/KelompokTani.php";

secureSessionStart();
verifyCsrfToken();

$kelompokTaniModel = new KelompokTani($pdo);

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
    "updated_by" => $_SESSION["user_id"]
];

$kelompokTaniModel->update($_POST["id"], $data);

header("Location: index.php?success=updated");