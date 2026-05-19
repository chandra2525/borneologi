<?php
require "../../app/core/session.php";
require "../../app/core/csrf.php";

require "../../app/config/database.php";
require "../../app/models/LandCoverObservasi.php";

secureSessionStart();
verifyCsrfToken();

$landCoverObservasi = new LandCoverObservasi($pdo);

$data = [
    "id_tanah" => $_POST["id_tanah"],
    "periode_pengecekan" => $_POST["periode_pengecekan"],
    "id_kategori_area" => $_POST["id_kategori_area"],
    "id_penggunaan_pertanian" => $_POST["id_penggunaan_pertanian"],
    "id_penggunaan_lainnya" => $_POST["id_penggunaan_lainnya"],
    "persentase_tutupan" => $_POST["persentase_tutupan"],
    "catatan" => $_POST["catatan"],
    "created_by" => $_SESSION["user_id"]
];

$landCoverObservasi->create($data);

header("Location: index.php?success=created");