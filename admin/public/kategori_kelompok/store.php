<?php
require "../../app/core/session.php";
require "../../app/core/csrf.php";

require "../../app/config/database.php";
require "../../app/models/KategoriKelompok.php";

secureSessionStart();
verifyCsrfToken();

$kategoriKelompok = new KategoriKelompok($pdo);

$data = [
    "kode" => $_POST["kode"],
    "nama" => $_POST["nama"],
    // "is_masyarakat_hukum_adat" => $_POST["is_masyarakat_hukum_adat"],
    "is_masyarakat_hukum_adat" => isset($_POST["is_masyarakat_hukum_adat"]) ? 1 : 0,
    "deskripsi" => $_POST["deskripsi"],
    "urutan" => $_POST["urutan"],
    "is_active" => $_POST["is_active"],
    "created_by" => $_SESSION["user_id"]
];

$kategoriKelompok->create($data);

header("Location: index.php?success=created");