<?php
require "../../app/core/session.php";
require "../../app/core/csrf.php";

require "../../app/config/database.php";
require "../../app/models/TopografiObservasi.php";

secureSessionStart();
verifyCsrfToken();

$topografiObservasiModel = new TopografiObservasi($pdo);

$data = [
    "id_tanah" => $_POST["id_tanah"],
    "periode_pengecekan" => $_POST["periode_pengecekan"],
    "id_lanskap" => $_POST["id_lanskap"],
    "id_fitur_tambahan" => $_POST["id_fitur_tambahan"],
    "elevasi_mdpl" => $_POST["elevasi_mdpl"],
    "kemiringan_derajat" => $_POST["kemiringan_derajat"],
    "rawan_erosi" => $_POST["rawan_erosi"],
    "arah_lereng" => $_POST["arah_lereng"],
    "catatan" => $_POST["catatan"],
    "updated_by" => $_SESSION["user_id"]
];

$topografiObservasiModel->update($_POST["id"], $data);

header("Location: index.php?success=updated");