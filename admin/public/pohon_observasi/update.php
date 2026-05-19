<?php
require "../../app/core/session.php";
require "../../app/core/csrf.php";

require "../../app/config/database.php";
require "../../app/models/PohonObservasi.php";

secureSessionStart();
verifyCsrfToken();

$pohonObservasiModel = new PohonObservasi($pdo);

$data = [
    "id_tanah" => $_POST["id_tanah"],
    "periode_pengecekan" => $_POST["periode_pengecekan"],
    "id_jenis_pohon" => $_POST["id_jenis_pohon"],
    "id_fungsi_pohon" => $_POST["id_fungsi_pohon"],
    "jumlah_pohon" => $_POST["jumlah_pohon"],
    "diameter_rata2_cm" => $_POST["diameter_rata2_cm"],
    "tinggi_rata2_m" => $_POST["tinggi_rata2_m"],
    "kondisi" => $_POST["kondisi"],
    "catatan" => $_POST["catatan"],
    "updated_by" => $_SESSION["user_id"]
];

$pohonObservasiModel->update($_POST["id"], $data);

header("Location: index.php?success=updated");