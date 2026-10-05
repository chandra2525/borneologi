<?php

require_once '../../../app/config/database.php';
require_once '../../../app/models/MonitoringPenanaman.php';
require_once '../../../app/core/session.php';
require_once '../../../app/core/csrf.php';
require_once '../../../app/core/auth.php';
require_once '../../../app/core/Permission.php';
require_once '../../../app/core/ActivityLog.php';

secureSessionStart();

Permission::authorize($pdo, 'Monitoring Penanaman', 'delete');

$id = (int) ($_POST['id'] ?? 0);
$id_bank_benih = (int) ($_POST['id_bank_benih'] ?? 0);

if ($id <= 0 || $id_bank_benih <= 0) {

    header('Location: ../index.php');
    exit;
}

try {

    if (!verifyCsrfToken()) {
        throw new Exception(
            'Invalid CSRF Token'
        );
    }

    /*
     * Pastikan monitoring tidak masih memiliki
     * detail aktif.
     */
    $stmt = $pdo->prepare("
        SELECT COUNT(*)
        FROM t_detail_monitoring_penanaman
        WHERE id_monitoring = :id
        AND deleted_at IS NULL
    ");

    $stmt->execute([
        'id' => $id
    ]);

    $jumlahDetail =
        (int) $stmt->fetchColumn();

    if ($jumlahDetail > 0) {

        throw new Exception(
            'Monitoring tidak dapat dihapus karena masih memiliki '
            . $jumlahDetail
            . ' detail monitoring aktif. Hapus detail monitoring terlebih dahulu.'
        );
    }

    $model =
        new MonitoringPenanaman($pdo);

    $oldData =
        $model->findById($id);

    if (!$oldData) {

        throw new Exception(
            'Data monitoring tidak ditemukan.'
        );
    }

    $user_id =
        $_SESSION['user_id'] ?? null;

    $result =
        $model->softDelete(
            $id,
            $user_id
        );

    if (!$result) {

        throw new Exception(
            'Monitoring gagal dihapus.'
        );
    }

    logActivity(
        $pdo,
        'DELETE',
        'Monitoring Penanaman',
        $id,
        't_monitoring_penanaman',
        $oldData,
        null,
        'Menghapus monitoring penanaman.',
        'SUCCESS'
    );

    header(
        'Location: index.php?id_bank_benih=' .
        $id_bank_benih .
        '&success=deleted'
    );

    exit;

} catch (Throwable $e) {

    header(
        'Location: index.php?id_bank_benih=' .
        $id_bank_benih .
        '&error=' .
        urlencode($e->getMessage())
    );

    exit;
}