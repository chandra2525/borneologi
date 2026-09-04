<?php
require "../../app/core/session.php";
secureSessionStart();
require "../../app/core/csrf.php";
require "../../app/config/database.php";
require "../../app/models/JabatanKelompok.php";
require "../../app/core/permission.php";

Permission::authorize($pdo, 'Jabatan Kelompok', 'create');
verifyCsrfToken();

$jabatanKelompok = new JabatanKelompok($pdo);

$data = [
    "kode" => $_POST["kode"],
    "nama" => $_POST["nama"],
    "is_pengurus" => isset($_POST["is_pengurus"]) ? 1 : 0,
    "deskripsi" => $_POST["deskripsi"],
    "urutan" => $_POST["urutan"],
    "is_active" => $_POST["is_active"],
    "created_by" => $_SESSION["user_id"]
];

$jabatanKelompok->create($data);

header("Location: index.php?success=created");