<?php
require "../../app/core/session.php";
secureSessionStart();
require "../../app/config/database.php";
require "../../app/models/ProgressStatusMonitoring.php";
require "../../app/core/permission.php";
require '../../app/core/activity_log.php';

Permission::authorize($pdo, 'Progress Monitoring', 'delete');

$progressStatusMonitoringModel = new ProgressStatusMonitoring($pdo);

$oldData = $progressStatusMonitoringModel->findById($_POST["id"]);
$result = $progressStatusMonitoringModel->softDelete($_POST["id"], $_SESSION["user_id"]);

if ($result) {
    logActivity(
        $pdo,
        'DELETE',
        'Progress Monitoring',
        $_POST["id"],
        'm_progress_status_monitoring',
        $oldData,
        null,
        'Menghapus data Progress Monitoring',
        'SUCCESS'
    );
}

header("Location: index.php?success=deleted");