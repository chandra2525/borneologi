<?php
require "../../app/core/session.php";
require "../../app/core/csrf.php";

require "../../app/config/database.php";
require "../../app/models/HutanAdat.php";

secureSessionStart();
verifyCsrfToken();

$hutanAdat = new HutanAdat($pdo);

$data = [
    "kode_hutan_adat" => $_POST["kode_hutan_adat"],
    "nama_hutan_adat" => $_POST["nama_hutan_adat"],
    "id_masyarakat_hukum_adat" => $_POST["id_masyarakat_hukum_adat"],
    "id_desa" => $_POST["id_desa"],
    "nomor_sk" => $_POST["nomor_sk"],
    "tanggal_sk" => $_POST["tanggal_sk"],
    "id_status_kawasan" => $_POST["id_status_kawasan"],
    "luas_ha" => $_POST["luas_ha"],
    // "id_polygon" => $_POST["id_polygon"],
    // "geom_area" => $geomWKT,
    "keterangan" => $_POST["keterangan"],
    "is_active" => $_POST["is_active"],
    "created_by" => $_SESSION["user_id"]
];

$hutanAdat->create($data);

header("Location: index.php?success=created");