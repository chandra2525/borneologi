<?php

require_once '../../../app/config/database.php';
require_once '../../../app/models/DetailMonitoringPenanaman.php';
require_once '../../../app/controllers/DetailMonitoringPenanamanController.php';
require_once '../../../app/core/session.php';
require_once '../../../app/core/csrf.php';
require_once '../../../app/core/auth.php';
require_once '../../../app/core/Permission.php';
require_once '../../../app/core/ActivityLog.php';

secureSessionStart();

Permission::authorize(
    $pdo,
    'Detail Monitoring',
    'update'
);

$id =
    (int) ($_POST['id'] ?? 0);

$id_bank_benih =
    (int) ($_POST['id_bank_benih'] ?? 0);

if (
    $id <= 0
    || $id_bank_benih <= 0
) {

    header('Location: ../index.php');
    exit;
}

$model =
    new DetailMonitoringPenanaman($pdo);

$oldData =
    $model->findById($id);

if (
    !$oldData
    || (int) $oldData['id_bank_benih']
    !== $id_bank_benih
) {

    header(
        'Location: index.php?id_bank_benih='
        . $id_bank_benih
    );

    exit;
}

try {

    $controller =
        new DetailMonitoringPenanamanController(
            $pdo
        );

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
            'Detail Monitoring gagal diperbarui.'
        );
    }

    $newData =
        $model->findById($id);

    logActivity(
        $pdo,
        'UPDATE',
        'Detail Monitoring',
        $id,
        't_detail_monitoring_penanaman',
        $oldData,
        $newData,
        'Memperbarui Detail Monitoring Penanaman dan menyesuaikan stok Bank Benih.',
        'SUCCESS'
    );

    header(
        'Location: index.php?id_bank_benih='
        . $id_bank_benih
        . '&success=updated'
    );

    exit;

} catch (Throwable $e) {

    header(
        'Location: edit.php?id='
        . $id
        . '&id_bank_benih='
        . $id_bank_benih
        . '&error='
        . urlencode(
            $e->getMessage()
        )
    );

    exit;
}