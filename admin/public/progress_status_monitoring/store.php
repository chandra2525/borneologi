<?php
require "../../app/core/session.php";
secureSessionStart();
require "../../app/core/csrf.php";
require "../../app/config/database.php";
require "../../app/models/ProgressStatusMonitoring.php";
require "../../app/core/permission.php";
require '../../app/core/activity_log.php';

Permission::authorize($pdo, 'Progress Monitoring', 'create');
verifyCsrfToken();

$progressStatusMonitoring = new ProgressStatusMonitoring($pdo);

$data = [
    "kode" => $_POST["kode"],
    "nama" => $_POST["nama"],
    "deskripsi" => $_POST["deskripsi"],
    "urutan" => $_POST["urutan"],
    "is_active" => $_POST["is_active"],
    "created_by" => $_SESSION["user_id"]
];

$newId = $progressStatusMonitoring->create($data);

if ($newId) {
    logActivity(
        $pdo,
        'CREATE',
        'Progress Monitoring',
        $newId,
        'm_progress_status_monitoring',
        null,
        $data,
        'Menambahkan data Progress Monitoring',
        'SUCCESS'
    );
}

header("Location: index.php?success=created");