<?php
require "../../app/core/session.php";
require "../../app/core/csrf.php";

require "../../app/config/database.php";
require "../../app/models/Kecamatan.php";

secureSessionStart();
verifyCsrfToken();

$kecamatan = new Kecamatan($pdo);

$data = [
    "id_kabupaten" => $_POST["id_kabupaten"],
    "kode_kecamatan" => $_POST["kode_kecamatan"],
    "nama_kecamatan" => $_POST["nama_kecamatan"],
    "is_active" => $_POST["is_active"],
    "created_by" => $_SESSION["user_id"]
];

$kecamatan->create($data);

header("Location: index.php?success=created");