<?php
require "../../app/core/session.php";
secureSessionStart();
require "../../app/core/csrf.php";
require "../../app/config/database.php";
require "../../app/models/ProgressStatusMonitoring.php";
require "../../app/core/permission.php";
require '../../app/core/activity_log.php';

Permission::authorize($pdo, 'Progress Monitoring', 'update');
verifyCsrfToken();

$progressStatusMonitoringModel = new ProgressStatusMonitoring($pdo);

$data = [
    "kode" => $_POST["kode"],
    "nama" => $_POST["nama"],
    "deskripsi" => $_POST["deskripsi"],
    "urutan" => $_POST["urutan"],
    "is_active" => $_POST["is_active"],
    "updated_by" => $_SESSION["user_id"]
];

$oldData = $progressStatusMonitoringModel->findById($_POST["id"]);
$result = $progressStatusMonitoringModel->update($_POST["id"], $data);

if ($result) {
    $newData = $progressStatusMonitoringModel->findById($_POST["id"]);
    logActivity(
        $pdo,
        'UPDATE',
        'Progress Monitoring',
        $_POST["id"],
        'm_progress_status_monitoring',
        $oldData,
        $newData,
        'Mengubah data Progress Monitoring',
        'SUCCESS'
    );
}

header("Location: index.php?success=updated");