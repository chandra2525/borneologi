<?php
require "../../app/core/session.php";
require "../../app/core/csrf.php";

require "../../app/config/database.php";
require "../../app/models/Kecamatan.php";

secureSessionStart();
verifyCsrfToken();

$kecamatanModel = new Kecamatan($pdo);

$data = [
    "id_kabupaten" => $_POST["id_kabupaten"],
    "kode_kecamatan" => $_POST["kode_kecamatan"],
    "nama_kecamatan" => $_POST["nama_kecamatan"],
    "is_active" => $_POST["is_active"],
    "updated_by" => $_SESSION["user_id"]
];

$kecamatanModel->update($_POST["id"], $data);

header("Location: index.php?success=updated");