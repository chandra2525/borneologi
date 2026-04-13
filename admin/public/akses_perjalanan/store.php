<?php
require "../../app/core/session.php";
require "../../app/core/csrf.php";

require "../../app/config/database.php";
require "../../app/models/AksesPerjalanan.php";

secureSessionStart();
verifyCsrfToken();

$aksesPerjalanan = new AksesPerjalanan($pdo);

$data = [
    "kode" => $_POST["kode"],
    "nama" => $_POST["nama"],
    "deskripsi" => $_POST["deskripsi"],
    "urutan" => $_POST["urutan"],
    "is_active" => $_POST["is_active"],
    "created_by" => $_SESSION["user_id"]
];

$aksesPerjalanan->create($data);

header("Location: index.php?success=created");