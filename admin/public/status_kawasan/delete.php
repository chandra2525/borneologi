<?php
require "../../app/core/session.php";
secureSessionStart();
require "../../app/config/database.php";
require "../../app/models/StatusKawasan.php";
require "../../app/core/permission.php";
require '../../app/core/activity_log.php';

Permission::authorize($pdo, 'Status Kawasan', 'delete');

$statusKawasanModel = new StatusKawasan($pdo);

$oldData = $statusKawasanModel->findById($_POST["id"]);
$result = $statusKawasanModel->softDelete($_POST["id"], $_SESSION["user_id"]);

if ($result) {
    logActivity(
        $pdo,
        'DELETE',
        'Status Kawasan',
        $_POST["id"],
        'm_status_kawasan',
        $oldData,
        null,
        'Menghapus data Status Kawasan',
        'SUCCESS'
    );
}

header("Location: index.php?success=deleted");