<?php

require_once '../../../app/config/database.php';
require_once '../../../app/models/MonitoringPenanaman.php';
require_once '../../../app/controllers/MonitoringPenanamanController.php';
require_once '../../../app/core/session.php';
require_once '../../../app/core/csrf.php';
require_once '../../../app/core/auth.php';
require_once '../../../app/core/Permission.php';
require_once '../../../app/core/ActivityLog.php';

secureSessionStart();

Permission::authorize($pdo, 'Monitoring Penanaman', 'update');

$id = (int) ($_POST['id'] ?? 0);
$id_bank_benih = (int) ($_POST['id_bank_benih'] ?? 0);

if ($id <= 0 || $id_bank_benih <= 0) {

    header('Location: ../index.php');
    exit;
}

$model = new MonitoringPenanaman($pdo);

$oldData = $model->findById($id);

if (!$oldData) {

    header(
        'Location: index.php?id_bank_benih=' .
        $id_bank_benih .
        '&error=' .
        urlencode('Data monitoring tidak ditemukan.')
    );

    exit;
}

try {

    $controller =
        new MonitoringPenanamanController($pdo);

    $user_id =
        $_SESSION['user_id'] ?? null;

    $result =
        $controller->update(
            $id,
            $_POST,
            $user_id
        );

    if (!$result) {
        throw new Exception(
            'Monitoring gagal diperbarui.'
        );
    }

    $newData = $model->findById($id);

    logActivity(
        $pdo,
        'UPDATE',
        'Monitoring Penanaman',
        $id,
        't_monitoring_penanaman',
        $oldData,
        $newData,
        'Memperbarui monitoring penanaman.',
        'SUCCESS'
    );

    header(
        'Location: index.php?id_bank_benih=' .
        $id_bank_benih .
        '&success=updated'
    );

    exit;

} catch (Throwable $e) {

    header(
        'Location: edit.php?id=' .
        $id .
        '&id_bank_benih=' .
        $id_bank_benih .
        '&error=' .
        urlencode($e->getMessage())
    );

    exit;
}