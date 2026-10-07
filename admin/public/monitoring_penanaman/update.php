<?php
require "../../app/core/session.php";
secureSessionStart();
require "../../app/core/csrf.php";
require "../../app/config/database.php";
require "../../app/models/MonitoringPenanaman.php";
require "../../app/core/permission.php";
require '../../app/core/activity_log.php';

error_reporting(E_ALL);
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');

Permission::authorize($pdo, 'Monitoring Penanaman', 'update');
verifyCsrfToken();

$monitoringPenanamanModel = new MonitoringPenanaman($pdo);

$data = [
    "kode_monitoring" => $_POST["kode_monitoring"],
    "id_tanah" => $_POST["id_tanah"],
    "id_progress_status_monitoring" => $_POST["id_progress_status_monitoring"],
    "periode_pengecekan" => $_POST["periode_pengecekan"],
    "tanggal_tanam" => $_POST["tanggal_tanam"],
    "tanggal_monitoring" => $_POST["tanggal_monitoring"],
    "catatan" => $_POST["catatan"],
    "id_turunan" => !empty($_POST["id_turunan"])
        ? (int) $_POST["id_turunan"]
        : null,
    "is_active" => $_POST["is_active"],
    "updated_by" => $_SESSION["user_id"]
];

$oldData = $monitoringPenanamanModel->findById($_POST["id"]);
$result = $monitoringPenanamanModel->update($_POST["id"], $data);

if ($result) {
    $newData = $monitoringPenanamanModel->findById($_POST["id"]);
    logActivity(
        $pdo,
        'UPDATE',
        'Monitoring Penanaman',
        $_POST["id"],
        't_monitoring_penanaman',
        $oldData,
        $newData,
        'Mengubah data Monitoring Penanaman',
        'SUCCESS'
    );
}

header("Location: index.php?success=updated");