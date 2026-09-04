<?php
require "../../app/core/session.php";
secureSessionStart();
require "../../app/core/csrf.php";
require "../../app/config/database.php";
require "../../app/models/KategoriKelompok.php";
require "../../app/core/permission.php";

Permission::authorize($pdo, 'Kategori Kelompok', 'update');
verifyCsrfToken();

$kategoriKelompokModel = new KategoriKelompok($pdo);

$data = [
    "kode" => $_POST["kode"],
    "nama" => $_POST["nama"],
    // "is_masyarakat_hukum_adat" => $_POST["is_masyarakat_hukum_adat"],
    "is_masyarakat_hukum_adat" => isset($_POST["is_masyarakat_hukum_adat"]) ? 1 : 0,
    "deskripsi" => $_POST["deskripsi"],
    "urutan" => $_POST["urutan"],
    "is_active" => $_POST["is_active"],
    "updated_by" => $_SESSION["user_id"]
];

$kategoriKelompokModel->update($_POST["id"], $data);

header("Location: index.php?success=updated");