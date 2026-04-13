<?php
require "../../app/core/session.php";
require "../../app/core/csrf.php";

require "../../app/config/database.php";
require "../../app/models/Petani.php";

secureSessionStart();
verifyCsrfToken();

$petaniModel = new Petani($pdo);

$data = [
    "nik" => $_POST["nik"],
    "no_kk" => $_POST["no_kk"],
    "nama_lengkap" => $_POST["nama_lengkap"],
    "nama_panggilan" => $_POST["nama_panggilan"],
    "jenis_kelamin" => $_POST["jenis_kelamin"],
    "tanggal_lahir" => $_POST["tanggal_lahir"],
    "nomor_hp" => $_POST["nomor_hp"],
    "id_desa" => $_POST["id_desa"],
    "alamat" => $_POST["alamat"],
    "status_petani" => $_POST["status_petani"],
    "is_active" => $_POST["is_active"],
    "updated_by" => $_SESSION["user_id"]
];

$petaniModel->update($_POST["id"], $data);

header("Location: index.php?success=updated");