<?php
require "../../app/core/session.php";
require "../../app/core/csrf.php";

require "../../app/config/database.php";
require "../../app/models/InfrastrukturObservasi.php";

secureSessionStart();
verifyCsrfToken();

$infrastrukturObservasiModel = new InfrastrukturObservasi($pdo);

$data = [
    "id_tanah" => $_POST["id_tanah"],
    "periode_pengecekan" => $_POST["periode_pengecekan"],
    "id_akses_perjalanan" => $_POST["id_akses_perjalanan"],
    "id_kondisi_jalan" => $_POST["id_kondisi_jalan"],
    "jarak_ke_jalan_km" => $_POST["jarak_ke_jalan_km"],
    "ada_jembatan" => $_POST["ada_jembatan"],
    "ada_listrik" => $_POST["ada_listrik"],
    "ada_internet" => $_POST["ada_internet"],
    "sinyal_seluler" => $_POST["sinyal_seluler"],
    "catatan" => $_POST["catatan"],
    "updated_by" => $_SESSION["user_id"]
];

$infrastrukturObservasiModel->update($_POST["id"], $data);

header("Location: index.php?success=updated");