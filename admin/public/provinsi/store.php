<?php
require "../../app/core/session.php";
require "../../app/core/csrf.php";

require "../../app/config/database.php";
require "../../app/models/Provinsi.php";

secureSessionStart();
verifyCsrfToken();

$provinsi = new Provinsi($pdo);

$data = [
    "kode_provinsi" => $_POST["kode_provinsi"],
    "nama_provinsi" => $_POST["nama_provinsi"],
    "is_active" => $_POST["is_active"],
    "created_by" => $_SESSION["user_id"]
];

$provinsi->create($data);

// header("Location: index.php");
header("Location: index.php?success=created");