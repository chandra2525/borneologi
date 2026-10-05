<?php

require_once '../../../app/config/database.php';
require_once '../../../app/models/MonitoringPenanaman.php';
require_once '../../../app/controllers/MonitoringPenanamanController.php';
require_once '../../../app/core/session.php';
require_once '../../../app/core/csrf.php';
require_once '../../../app/core/auth.php';
require_once '../../../app/core/Permission.php';
require_once '../../../app/core/ActivityLog.php';

error_reporting(E_ALL);
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');

secureSessionStart();

Permission::authorize($pdo, 'Monitoring Penanaman', 'create');

$id_bank_benih = (int) ($_POST['id_bank_benih'] ?? 0);

try {

    $controller =
        new MonitoringPenanamanController($pdo);

    $user_id =
        $_SESSION['user_id'] ?? null;

    $newId =
        $controller->store($_POST, $user_id);

    if (!$newId) {
        throw new Exception(
            'Monitoring gagal disimpan.'
        );
    }

    logActivity(
        $pdo,
        'CREATE',
        'Monitoring Penanaman',
        $newId,
        't_monitoring_penanaman',
        null,
        $_POST,
        'Menambahkan monitoring penanaman.',
        'SUCCESS'
    );

    header(
        'Location: index.php?id_bank_benih=' .
        $id_bank_benih .
        '&success=created'
    );

    exit;

} catch (Throwable $e) {

    header(
        'Location: create.php?id_bank_benih=' .
        $id_bank_benih .
        '&error=' .
        urlencode($e->getMessage())
    );

    exit;
}