<?php
require "../../app/core/session.php";
require "../../app/core/csrf.php";

require "../../app/config/database.php";
require "../../app/models/PetaniKelompok.php";

secureSessionStart();
verifyCsrfToken();

$petaniKelompok = new PetaniKelompok($pdo);

$data = [
    "id_petani" => $_POST["id_petani"],
    "id_kelompok_tani" => $_POST["id_kelompok_tani"],
    "id_jabatan_kelompok" => $_POST["id_jabatan_kelompok"],
    "tanggal_gabung" => $_POST["tanggal_gabung"],
    "tanggal_keluar" => $_POST["tanggal_keluar"],
    // "is_pengurus" => $_POST["is_pengurus"],
    "is_pengurus" => isset($_POST["is_pengurus"]) ? 1 : 0,
    "keterangan" => $_POST["keterangan"],
    "is_active" => $_POST["is_active"],
    "created_by" => $_SESSION["user_id"]
];

$petaniKelompok->create($data);

header("Location: index.php?success=created");