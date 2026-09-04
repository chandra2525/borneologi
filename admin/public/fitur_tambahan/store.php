<?php
require "../../app/core/session.php";
secureSessionStart();
require "../../app/core/csrf.php";
require "../../app/config/database.php";
require "../../app/models/FiturTambahan.php";
require "../../app/core/permission.php";

Permission::authorize($pdo, 'Roles', 'create');

verifyCsrfToken();

$fiturTambahan = new FiturTambahan($pdo);

$data = [
    "kode" => $_POST["kode"],
    "nama" => $_POST["nama"],
    "deskripsi" => $_POST["deskripsi"],
    "urutan" => $_POST["urutan"],
    "is_active" => $_POST["is_active"],
    "created_by" => $_SESSION["user_id"]
];

$fiturTambahan->create($data);

header("Location: index.php?success=created");