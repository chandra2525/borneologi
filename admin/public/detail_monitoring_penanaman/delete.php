<?php

require "../../app/core/session.php";
secureSessionStart();

require "../../app/core/csrf.php";
require "../../app/config/database.php";
require "../../app/models/DetailMonitoringPenanaman.php";
require "../../app/core/permission.php";
require '../../app/core/activity_log.php';

error_reporting(E_ALL);
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');

Permission::authorize(
    $pdo,
    'Detail Monitoring',
    'delete'
);

verifyCsrfToken();
$model = new DetailMonitoringPenanaman($pdo);

$id = (int) ($_POST["id"] ?? 0);

/*
 * =========================================================
 * DATA LAMA
 * =========================================================
 */
$oldData = $model->findById($id);
if (!$oldData) {
    $_SESSION["error"] =
        "Data Detail Monitoring tidak ditemukan.";
    header("Location: index.php?error=notfound");
    exit;
}

$id_monitoring =
    (int) $oldData['id_monitoring'];

$userId = $_SESSION["user_id"];
if ($id <= 0) {
    $_SESSION["error"] =
        "ID Detail Monitoring tidak valid.";
    header(
        'Location: index.php?id_monitoring='
        . $id_monitoring
        . '&error=validation'
    );
    exit;
}


/*
 * =========================================================
 * DELETE + KEMBALIKAN STOK
 * =========================================================
 */

$result = $model->deleteWithStock(
    $id,
    $userId
);


if (!$result["success"]) {
    logActivity(
        $pdo,
        'DELETE',
        'Detail Monitoring Penanaman',
        $id,
        't_detail_monitoring_penanaman',
        $oldData,
        null,
        'Menghapus Detail Monitoring Penanaman dan mengembalikan stok Bank Benih',
        'SUCCESS'
    );

    $_SESSION["error"] = $result["message"];

    header(
        'Location: index.php?id_monitoring='
        . $id_monitoring
        . '&success=deleted'
    );
    exit;
}

exit;