<?php

require_once '../../app/config/database.php';
require_once '../../app/controllers/DetailMonitoringPenanamanController.php';
require_once '../../app/core/session.php';
require_once "../../app/core/auth.php";
require_once "../../app/core/permission.php";
require_once '../../app/core/activity_log.php';

error_reporting(E_ALL);
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');

secureSessionStart();

checkAuth("non_dashboard");

Permission::authorize(
    $pdo,
    'Monitoring Penanaman',
    'create'
);

try {
    $idMonitoring =
        (int) ($_POST['id_monitoring'] ?? 0);
    $idTipePenanaman = trim($_POST["id_tipe_penanaman"] ?? '');
    $idBankBenih = trim($_POST["id_bank_benih"] ?? '');
    $jumlahDitanam = trim($_POST["jumlah_ditanam"] ?? '');
    $satuan = trim($_POST["satuan"] ?? '');
    $jumlahHidup = trim($_POST["jumlah_hidup"] ?? '');
    $jumlahMati = trim($_POST["jumlah_mati"] ?? '');
    $tinggiRata = trim($_POST["tinggi_rata2_cm"] ?? '');
    $diameterRata = trim($_POST["diameter_rata2_cm"] ?? '');
    $luasTanamHa = trim($_POST["luas_tanam_ha"] ?? '');
    $catatan = trim($_POST["catatan"] ?? '');

    /*
     * =========================================================
     * VALIDASI JUMLAH
     * =========================================================
     */
    if (
        $idMonitoring === '' ||
        $idTipePenanaman === '' ||
        $idBankBenih === '' ||
        $jumlahDitanam === '' ||
        $satuan === '' ||
        $luasTanamHa === ''
    ) {
        header(
            'Location: index.php?id_monitoring='
            . $idMonitoring
            . '&error=validation1'
        );
        exit;
    }

    if (!is_numeric($jumlahDitanam) || (float) $jumlahDitanam <= 0) {
        header(
            'Location: index.php?id_monitoring='
            . $idMonitoring
            . '&error=validation2'
        );
        exit;
    }

    /*
     * =========================================================
     * NORMALISASI NILAI OPTIONAL
     * =========================================================
     */
    $jumlahHidup = ($jumlahHidup === '')
        ? null
        : $jumlahHidup;
    $jumlahMati = ($jumlahMati === '')
        ? null
        : $jumlahMati;
    $tinggiRata = ($tinggiRata === '')
        ? null
        : $tinggiRata;
    $diameterRata = ($diameterRata === '')
        ? null
        : $diameterRata;

    $controller =
        new DetailMonitoringPenanamanController($pdo);

    $user_id =
        $_SESSION['user_id'] ?? null;

    if (!$user_id) {
        throw new Exception(
            'User tidak ditemukan.'
        );
    }

    if (
        $controller->existsPair(
            $idMonitoring,
            $idBankBenih
        )
    ) {
        header(
            'Location: index.php?id_monitoring='
            . $idMonitoring
            . '&success=validation3'
        );
    }

    $result = $controller->store(
        $_POST,
        $user_id
    );

    /*
     * =========================================================
     * ACTIVITY LOG
     * =========================================================
     */
    $newId = $result["id"];
    $newData = array_merge(
        $data,
        [
            "stok_sebelum" => $result["stok_sebelum"],
            "stok_sesudah" => $result["stok_sesudah"]
        ]
    );

    logActivity(
        $pdo,
        'CREATE',
        'Detail Monitoring Penanaman',
        $newId,
        't_detail_monitoring_penanaman',
        null,
        $newData,
        'Menambahkan detail monitoring penanaman dan mengurangi stok Bank Benih',
        'SUCCESS'
    );

    header(
        'Location: index.php?id_monitoring='
        . $idMonitoring
        . '&success=created'
    );

    exit;

} catch (Throwable $e) {

    die(
        'Gagal menyimpan Detail Monitoring: '
        . htmlspecialchars(
            $e->getMessage(),
            ENT_QUOTES,
            'UTF-8'
        )
    );

}