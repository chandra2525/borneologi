<?php
require "../../app/core/session.php";
require "../../app/core/csrf.php";

require "../../app/config/database.php";
require "../../app/models/PerairanObservasi.php";

secureSessionStart();
verifyCsrfToken();

$perairanObservasi = new PerairanObservasi($pdo);

$data = [
    "id_tanah" => $_POST["id_tanah"],
    "periode_pengecekan" => $_POST["periode_pengecekan"],
    "id_warna_air" => $_POST["id_warna_air"],
    "id_jenis_palung" => $_POST["id_jenis_palung"],
    "id_kecepatan_aliran" => $_POST["id_kecepatan_aliran"],
    "kedalaman_cm" => $_POST["kedalaman_cm"],
    "lebar_m" => $_POST["lebar_m"],
    "debit_lps" => $_POST["debit_lps"],
    "ph" => $_POST["ph"],
    "kekeruhan_ntu" => $_POST["kekeruhan_ntu"],
    "catatan" => $_POST["catatan"],
    "created_by" => $_SESSION["user_id"]
];

$perairanObservasi->create($data);

header("Location: index.php?success=created");