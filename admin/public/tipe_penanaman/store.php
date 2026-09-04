<?php
require "../../app/core/session.php";
secureSessionStart();
require "../../app/core/csrf.php";
require "../../app/config/database.php";
require "../../app/models/TipePenanaman.php";
require "../../app/core/permission.php";

Permission::authorize($pdo, 'Tipe Penanaman', 'create');
verifyCsrfToken();

$tipePenanaman = new TipePenanaman($pdo);

$data = [
    "kode" => $_POST["kode"],
    "nama" => $_POST["nama"],
    "deskripsi" => $_POST["deskripsi"],
    "urutan" => $_POST["urutan"],
    "is_active" => $_POST["is_active"],
    "created_by" => $_SESSION["user_id"]
];

$tipePenanaman->create($data);

header("Location: index.php?success=created");