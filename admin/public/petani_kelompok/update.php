<?php
require "../../app/core/session.php";
require "../../app/core/csrf.php";

require "../../app/config/database.php";
require "../../app/models/PetaniKelompok.php";

secureSessionStart();
verifyCsrfToken();

$petaniKelompokModel = new PetaniKelompok($pdo);

$data = [
    "id_petani" => $_POST["id_petani"],
    "id_kelompok_tani" => $_POST["id_kelompok_tani"],
    "id_jabatan_kelompok" => $_POST["id_jabatan_kelompok"],
    "tanggal_gabung" => $_POST["tanggal_gabung"],
    "tanggal_keluar" => $_POST["tanggal_keluar"],
    "is_pengurus" => $_POST["is_pengurus"],
    "keterangan" => $_POST["keterangan"],
    "is_active" => $_POST["is_active"],
    "updated_by" => $_SESSION["user_id"]
];

$petaniKelompokModel->update($_POST["id"], $data);

header("Location: index.php?success=updated");