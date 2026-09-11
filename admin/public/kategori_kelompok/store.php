<?php
require "../../app/core/session.php";
secureSessionStart();
require "../../app/core/csrf.php";
require "../../app/config/database.php";
require "../../app/models/KategoriKelompok.php";
require "../../app/core/permission.php";
require '../../app/core/activity_log.php';

Permission::authorize($pdo, 'Kategori Kelompok', 'create');
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

$newId = $kategoriKelompok->create($data);

if ($newId) {
    logActivity(
        $pdo,
        'CREATE',
        'Kategori Kelompok',
        $newId,
        'm_kategori_kelompok',
        null,
        $data,
        'Menambahkan data Kategori Kelompok',
        'SUCCESS'
    );
}

header("Location: index.php?success=created");