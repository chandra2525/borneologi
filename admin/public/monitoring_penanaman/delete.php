<?php
require "../../app/core/session.php";
secureSessionStart();
require "../../app/config/database.php";
require "../../app/models/MonitoringPenanaman.php";
require "../../app/core/permission.php";
require '../../app/core/activity_log.php';

Permission::authorize($pdo, 'Monitoring Penanaman', 'delete');

$monitoringPenanamanModel = new MonitoringPenanaman($pdo);

$oldData = $monitoringPenanamanModel->findById($_POST["id"]);
$result = $monitoringPenanamanModel->softDelete($_POST["id"], $_SESSION["user_id"]);

if ($result) {
    logActivity(
        $pdo,
        'DELETE',
        'Monitoring Penanaman',
        $_POST["id"],
        't_monitoring_penanaman',
        $oldData,
        null,
        'Menghapus data Monitoring Penanaman',
        'SUCCESS'
    );
}

header("Location: index.php?success=deleted");