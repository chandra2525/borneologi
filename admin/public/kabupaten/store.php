<?php
require "../../app/core/session.php";
require "../../app/core/csrf.php";

require "../../app/config/database.php";
require "../../app/models/Kabupaten.php";

secureSessionStart();
verifyCsrfToken();

$kabupaten = new Kabupaten($pdo);

$data = [
    "id_provinsi" => $_POST["id_provinsi"],
    "kode_kabupaten" => $_POST["kode_kabupaten"],
    "nama_kabupaten" => $_POST["nama_kabupaten"],
    "tipe" => $_POST["tipe"],
    "is_active" => $_POST["is_active"],
    "created_by" => $_SESSION["user_id"]
];

$kabupaten->create($data);

header("Location: index.php?success=created");