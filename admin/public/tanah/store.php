<?php
require "../../app/core/session.php";
require "../../app/core/csrf.php";

require "../../app/config/database.php";
require "../../app/models/Tanah.php";

secureSessionStart();
verifyCsrfToken();

$tanah = new Tanah($pdo);

$data = [
    "kode_tanah" => $_POST["kode_tanah"],
    "id_petani" => $_POST["id_petani"],
    // "id_kaleka" => $_POST["id_kaleka"],
    "id_relasi" => $_POST["id_relasi"],
    "tipe_relasi" => $_POST["tipe_relasi"],
    "nama_lahan" => $_POST["nama_lahan"],
    "id_legalitas_lahan" => $_POST["id_legalitas_lahan"],
    "id_status_kawasan" => $_POST["id_status_kawasan"],
    "luas_ha" => $_POST["luas_ha"],
    "centroid_lat" => $_POST["centroid_lat"],
    "centroid_lng" => $_POST["centroid_lng"],
    "geom_area" => $_POST["geom_area"],
    "sejarah" => $_POST["sejarah"],
    "alamat_lokasi" => $_POST["alamat_lokasi"],
    "keterangan" => $_POST["keterangan"],
    "sudah_validasi" => $_POST["sudah_validasi"],
    "tanggal_validasi" => $_POST["tanggal_validasi"],
    "is_active" => $_POST["is_active"],
    "created_by" => $_SESSION["user_id"]
];

$tanah->create($data);

header("Location: index.php?success=created");