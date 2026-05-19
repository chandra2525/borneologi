<?php
require "../../app/core/session.php";
require "../../app/core/csrf.php";

require "../../app/config/database.php";
require "../../app/models/DetailMonitoringPenanaman.php";

secureSessionStart();
verifyCsrfToken();

$detailMonitoringPenanamanModel = new DetailMonitoringPenanaman($pdo);

$data = [
    "id_monitoring" => $_POST["id_monitoring"],
    "id_bank_benih" => $_POST["id_bank_benih"],
    "jumlah_ditanam" => $_POST["jumlah_ditanam"],
    "satuan" => $_POST["satuan"],
    "jumlah_hidup" => $_POST["jumlah_hidup"],
    "jumlah_mati" => $_POST["jumlah_mati"],
    "tinggi_rata2_cm" => $_POST["tinggi_rata2_cm"],
    "diameter_rata2_cm" => $_POST["diameter_rata2_cm"],
    "catatan" => $_POST["catatan"],
    "updated_by" => $_SESSION["user_id"]
];

$detailMonitoringPenanamanModel->update($_POST["id"], $data);

header("Location: index.php?success=updated");