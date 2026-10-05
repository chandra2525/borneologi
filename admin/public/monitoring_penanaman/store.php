<?php
require "../../app/core/session.php";
secureSessionStart();
require "../../app/core/csrf.php";
require "../../app/config/database.php";
require "../../app/models/MonitoringPenanaman.php";
require "../../app/core/permission.php";
require '../../app/core/activity_log.php';

Permission::authorize($pdo, 'Monitoring Penanaman', 'create');
verifyCsrfToken();

$monitoringPenanaman = new MonitoringPenanaman($pdo);

$data = [
    "kode_monitoring" => $_POST["kode_monitoring"],
    "id_tanah" => $_POST["id_tanah"],
    "id_progress_status_monitoring" => $_POST["id_progress_status_monitoring"],
    "periode_pengecekan" => $_POST["periode_pengecekan"],
    "tanggal_tanam" => $_POST["tanggal_tanam"],
    "tanggal_monitoring" => $_POST["tanggal_monitoring"],
    "catatan" => $_POST["catatan"],
    "is_active" => $_POST["is_active"],
    "created_by" => $_SESSION["user_id"]
];

$newId = $monitoringPenanaman->create($data);

if ($newId) {
    logActivity(
        $pdo,
        'CREATE',
        'Monitoring Penanaman',
        $newId,
        't_monitoring_penanaman',
        null,
        $data,
        'Menambahkan data Monitoring Penanaman',
        'SUCCESS'
    );
}

header("Location: index.php?success=created");