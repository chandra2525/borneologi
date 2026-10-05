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
    'create'
);
verifyCsrfToken();
$model = new DetailMonitoringPenanaman($pdo);
$userId = $_SESSION["user_id"];

/*
 * =========================================================
 * VALIDASI INPUT DASAR
 * =========================================================
 */
$idMonitoring = trim($_POST["id_monitoring"] ?? '');
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
        . '&success=validation1'
    );
    exit;
}

if (!is_numeric($jumlahDitanam) || (float) $jumlahDitanam <= 0) {
    header(
        'Location: index.php?id_monitoring='
        . $idMonitoring
        . '&success=validation2'
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

/*
 * =========================================================
 * DATA
 * =========================================================
 */
$data = [
    "id_monitoring" => $idMonitoring,
    "id_tipe_penanaman" => $idTipePenanaman,
    "id_bank_benih" => $idBankBenih,
    "jumlah_ditanam" => number_format(
        (float) $jumlahDitanam,
        2,
        ".",
        ""
    ),
    "satuan" => $satuan,
    "jumlah_hidup" => $jumlahHidup,
    "jumlah_mati" => $jumlahMati,
    "tinggi_rata2_cm" => $tinggiRata,
    "diameter_rata2_cm" => $diameterRata,
    "luas_tanam_ha" => $luasTanamHa,
    "catatan" => $catatan
];

/*
 * =========================================================
 * CREATE + STOCK
 * =========================================================
 */
if (
    $model->existsPair(
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

$result = $model->createWithStock(
    $data,
    $userId
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