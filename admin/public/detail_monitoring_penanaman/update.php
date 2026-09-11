<?php
require "../../app/core/session.php";
secureSessionStart();
require "../../app/core/csrf.php";
require "../../app/config/database.php";
require "../../app/models/DetailMonitoringPenanaman.php";
require "../../app/core/permission.php";
require '../../app/core/activity_log.php';

Permission::authorize($pdo, 'Detail Monitoring', 'update');
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

$oldData = $detailMonitoringPenanamanModel->findById($_POST["id"]);
$result = $detailMonitoringPenanamanModel->update($_POST["id"], $data);

if ($result) {
    $newData = $detailMonitoringPenanamanModel->findById($_POST["id"]);
    logActivity(
        $pdo,
        'UPDATE',
        'Detail Monitoring Penanaman',
        $_POST["id"],
        't_detail_monitoring_penanaman',
        $oldData,
        $newData,
        'Mengubah data Detail Monitoring Penanaman',
        'SUCCESS'
    );
}

header("Location: index.php?success=updated");