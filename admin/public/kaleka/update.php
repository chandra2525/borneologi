<?php
require "../../app/core/session.php";
require "../../app/core/csrf.php";

require "../../app/config/database.php";
require "../../app/models/Kaleka.php";

secureSessionStart();
verifyCsrfToken();

$kalekaModel = new Kaleka($pdo);

$data = [
    "kode_kaleka" => $_POST["kode_kaleka"],
    "nama_kaleka" => $_POST["nama_kaleka"],
    "id_petani" => $_POST["id_petani"],
    "id_desa" => $_POST["id_desa"],
    "luas_ha" => $_POST["luas_ha"],
    "centroid_lat" => $_POST["centroid_lat"],
    "centroid_lng" => $_POST["centroid_lng"],
    "geom_area" => $_POST["geom_area"],
    "keterangan" => $_POST["keterangan"],
    "is_active" => $_POST["is_active"],
    "updated_by" => $_SESSION["user_id"]
];

$kalekaModel->update($_POST["id"], $data);

header("Location: index.php?success=updated");