<?php
require "../../app/core/session.php";
secureSessionStart();
require "../../app/config/database.php";
require "../../app/models/DetailMonitoringPenanaman.php";
require "../../app/core/permission.php";
require '../../app/core/activity_log.php';

Permission::authorize($pdo, 'Detail Monitoring', 'delete');

$detailMonitoringPenanamanModel = new DetailMonitoringPenanaman($pdo);

$oldData = $detailMonitoringPenanamanModel->findById($_POST["id"]);
$result = $detailMonitoringPenanamanModel->softDelete($_POST["id"], $_SESSION["user_id"]);

if ($result) {
    logActivity(
        $pdo,
        'DELETE',
        'Detail Monitoring Penanaman',
        $_POST["id"],
        't_detail_monitoring_penanaman',
        $oldData,
        null,
        'Menghapus data Detail Monitoring Penanaman',
        'SUCCESS'
    );
}

header("Location: index.php?success=deleted");