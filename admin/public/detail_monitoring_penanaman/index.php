<?php

require_once '../../app/config/database.php';
require_once '../../app/controllers/MonitoringPenanamanController.php';
require_once '../../app/controllers/DetailMonitoringPenanamanController.php';

require_once '../../app/core/session.php';
require_once '../../app/core/csrf.php';
require_once "../../app/core/auth.php";
require_once '../../app/helpers/escape.php';
require_once "../../app/core/permission.php";

error_reporting(E_ALL);
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');

secureSessionStart();
checkAuth("non_dashboard");

Permission::authorize(
    $pdo,
    'Monitoring Penanaman',
    'view'
);

$id_monitoring = (int) ($_GET['id_monitoring'] ?? 0);

if ($id_monitoring <= 0) {
    die('ID Monitoring Penanaman tidak valid.');
}

$monitoringController =
    new MonitoringPenanamanController($pdo);

$detailController =
    new DetailMonitoringPenanamanController($pdo);

/*
|--------------------------------------------------------------------------
| Ambil data monitoring
|--------------------------------------------------------------------------
*/

$monitoring =
    $monitoringController->find($id_monitoring);

if (!$monitoring) {
    die('Data Monitoring Penanaman tidak ditemukan.');
}

/*
|--------------------------------------------------------------------------
| Ambil detail berdasarkan monitoring
|--------------------------------------------------------------------------
*/

$details =
    $detailController->getByMonitoring(
        $id_monitoring
    );

/*
|--------------------------------------------------------------------------
| Data Bank Benih dan Tipe Penanaman
|--------------------------------------------------------------------------
*/

$bankBenihs =
    $detailController->getBankBenih();

$tipePenanamans =
    $detailController->getTipePenanaman();

/*
|--------------------------------------------------------------------------
| Hitung summary
|--------------------------------------------------------------------------
*/

$totalDetail = count($details);

$totalDitanam = 0;
$totalHidup = 0;
$totalMati = 0;
$totalLuas = 0;

foreach ($details as $detail) {

    $totalDitanam +=
        (float) ($detail['jumlah_ditanam'] ?? 0);

    $totalHidup +=
        (float) ($detail['jumlah_hidup'] ?? 0);

    $totalMati +=
        (float) ($detail['jumlah_mati'] ?? 0);

    $totalLuas +=
        (float) ($detail['luas_tanam_ha'] ?? 0);
}

$survivalRate = 0;

if ($totalDitanam > 0) {
    $survivalRate =
        ($totalHidup / $totalDitanam) * 100;
}

$survivalRate =
    min(100, max(0, $survivalRate));


$isMonitoringTurunan =
    !empty($monitoring['id_turunan'])
    && (int) $monitoring['id_turunan'] > 0;

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Borneologi</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="../assets/adminlte/plugins/fontawesome-free/css/all.min.css">
    <!-- DataTables -->
    <link rel="stylesheet" href="../assets/adminlte/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="../assets/adminlte/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
    <link rel="stylesheet" href="../assets/adminlte/plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="../assets/adminlte/dist/css/adminlte.min.css">
</head>

<body class="hold-transition sidebar-mini">
    <div class="wrapper">
        <?php include "../feature/navbar.php" ?>
        <?php
        $menu = "monitoring_penanaman";
        include "../feature/sidebar.php";
        ?>

        <div class="content-wrapper">
            <!-- =========================================================
             HEADER
            ========================================================== -->
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1>Detail Monitoring Penanaman</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item">
                                    <a href="../monitoring_penanaman/index.php">
                                        Monitoring Penanaman
                                    </a>
                                </li>
                                <li class="breadcrumb-item active">Detail</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </section>

            <section class="content">
                <div class="container-fluid">
                    <!-- =====================================================
                     INFORMASI MONITORING
                    ====================================================== -->
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-info-circle mr-1"></i>
                                Informasi Monitoring Penanaman
                            </h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <!-- KODE -->
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>
                                            Kode Monitoring
                                        </label>
                                        <div>
                                            <span class="badge badge-primary" style="font-size: 15px;">
                                                <?= htmlspecialchars(
                                                    $monitoring['kode_monitoring'] ?? '-',
                                                    ENT_QUOTES,
                                                    'UTF-8'
                                                ) ?>
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <!-- LAHAN -->
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>
                                            Nama Lahan
                                        </label>
                                        <div>
                                            <?= htmlspecialchars(
                                                $monitoring['nama_lahan'] ?? '-',
                                                ENT_QUOTES,
                                                'UTF-8'
                                            ) ?>
                                        </div>
                                    </div>
                                </div>

                                <!-- STATUS -->
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>
                                            Progress Status
                                        </label>
                                        <div>
                                            <span class="badge <?= match ($monitoring['nama_status_monitoring'] ?? '') {
                                                'Baru Ditanam' => 'badge-primary',
                                                'Proses Panen' => 'badge-info',
                                                default => 'badge-success',
                                            } ?>">
                                                <?= htmlspecialchars(
                                                    $monitoring['nama_status_monitoring'] ?? '-',
                                                    ENT_QUOTES,
                                                    'UTF-8'
                                                ) ?>
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <!-- PERIODE -->
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>
                                            Periode Pengecekan
                                        </label>
                                        <div>
                                            <?= !empty(
                                                $monitoring['periode_pengecekan']
                                            )
                                                ? date(
                                                    'd/m/Y',
                                                    strtotime(
                                                        $monitoring['periode_pengecekan']
                                                    )
                                                )
                                                : '-'
                                                ?>
                                        </div>
                                    </div>
                                </div>

                                <!-- TANGGAL TANAM -->
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>
                                            Tanggal Tanam
                                        </label>
                                        <div>
                                            <?= !empty(
                                                $monitoring['tanggal_tanam']
                                            )
                                                ? date(
                                                    'd/m/Y',
                                                    strtotime(
                                                        $monitoring['tanggal_tanam']
                                                    )
                                                )
                                                : '-'
                                                ?>
                                        </div>
                                    </div>
                                </div>

                                <!-- TANGGAL MONITORING -->
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>
                                            Tanggal Monitoring
                                        </label>
                                        <div>
                                            <?= !empty(
                                                $monitoring['tanggal_monitoring']
                                            )
                                                ? date(
                                                    'd/m/Y',
                                                    strtotime(
                                                        $monitoring['tanggal_monitoring']
                                                    )
                                                )
                                                : '-'
                                                ?>
                                        </div>
                                    </div>
                                </div>

                                <!-- CATATAN -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>
                                            Catatan
                                        </label>
                                        <div>
                                            <?= !empty(
                                                $monitoring['catatan']
                                            )
                                                ? nl2br(
                                                    htmlspecialchars(
                                                        $monitoring['catatan'],
                                                        ENT_QUOTES,
                                                        'UTF-8'
                                                    )
                                                )
                                                : '-'
                                                ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- =====================================================
                     SUMMARY
                    ====================================================== -->
                    <div class="row">
                        <!-- JUMLAH DETAIL -->
                        <div class="col-lg-2 col-md-4 col-sm-6">
                            <div class="small-box bg-secondary">
                                <div class="inner">
                                    <h3>
                                        <?= $totalDetail ?>
                                    </h3>
                                    <p>
                                        Jumlah Detail
                                    </p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-list"></i>
                                </div>
                            </div>
                        </div>

                        <!-- DITANAM -->
                        <div class="col-lg-2 col-md-4 col-sm-6">
                            <div class="small-box bg-primary">
                                <div class="inner">
                                    <h3>
                                        <?= number_format(
                                            $totalDitanam,
                                            2,
                                            ',',
                                            '.'
                                        ) ?>
                                    </h3>
                                    <p>
                                        Total Ditanam
                                    </p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-seedling"></i>
                                </div>
                            </div>
                        </div>

                        <!-- HIDUP -->
                        <div class="col-lg-2 col-md-4 col-sm-6">
                            <div class="small-box bg-success">
                                <div class="inner">
                                    <h3>
                                        <?= number_format(
                                            $totalHidup,
                                            2,
                                            ',',
                                            '.'
                                        ) ?>
                                    </h3>
                                    <p>
                                        Total Hidup
                                    </p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-leaf"></i>
                                </div>
                            </div>
                        </div>

                        <!-- MATI -->
                        <div class="col-lg-2 col-md-4 col-sm-6">
                            <div class="small-box bg-danger">
                                <div class="inner">
                                    <h3>
                                        <?= number_format(
                                            $totalMati,
                                            2,
                                            ',',
                                            '.'
                                        ) ?>
                                    </h3>
                                    <p>
                                        Total Mati
                                    </p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-times-circle"></i>
                                </div>
                            </div>
                        </div>

                        <!-- LUAS -->
                        <div class="col-lg-2 col-md-4 col-sm-6">
                            <div class="small-box bg-warning">
                                <div class="inner">
                                    <h3>
                                        <?= number_format(
                                            $totalLuas,
                                            2,
                                            ',',
                                            '.'
                                        ) ?>
                                    </h3>
                                    <p>
                                        Total Luas (Ha)
                                    </p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-map"></i>
                                </div>
                            </div>
                        </div>

                        <!-- SURVIVAL RATE -->
                        <div class="col-lg-2 col-md-4 col-sm-6">
                            <div class="small-box bg-info">
                                <div class="inner">
                                    <h3>
                                        <?= number_format(
                                            $survivalRate,
                                            2,
                                            ',',
                                            '.'
                                        ) ?>%
                                    </h3>
                                    <p>
                                        Survival Rate
                                    </p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-chart-line"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- =====================================================
                     TABLE DETAIL
                    ====================================================== -->
                    <div class="card">
                        <div class="card-header">
                            <div class="row align-items-center">
                                <div class="col-md-8">
                                    <h3 class="card-title">
                                        <i class="fas fa-seedling mr-1"></i>
                                        Detail Tanaman
                                    </h3>
                                </div>

                                <div class="col-md-4 text-right">
                                    <?php
                                    if (
                                        !$isMonitoringTurunan &&
                                        Permission::can(
                                            $pdo,
                                            'Monitoring Penanaman',
                                            'create'
                                        )
                                    ):
                                        ?>
                                        <button type="button" class="btn btn-success" data-toggle="modal"
                                            data-target="#modalTambah">
                                            <i class="fas fa-plus"></i>
                                            Tambah Detail
                                        </button>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>

                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="tableDetail" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th class="text-center">No</th>
                                            <th>Bank Benih</th>
                                            <th>Tipe Penanaman</th>
                                            <th class="text-right">Jumlah Ditanam</th>
                                            <th class="text-right">Hidup</th>
                                            <th class="text-right">Mati</th>
                                            <th class="text-right">Survival Rate</th>
                                            <th class="text-right">Tinggi Rata-rata</th>
                                            <th class="text-right">Diameter Rata-rata</th>
                                            <th class="text-right">Luas Tanam</th>
                                            <th>Catatan</th>
                                            <th class="text-center">Aksi</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        <?php $no = 1; ?>
                                        <?php foreach (
                                            $details
                                            as $detail
                                        ): ?>
                                            <tr>
                                                <td class="text-center">
                                                    <?= $no++ ?>
                                                </td>
                                                <td>
                                                    <strong>
                                                        <?= htmlspecialchars(
                                                            $detail['nama_lokal'] ?? '-',
                                                            ENT_QUOTES,
                                                            'UTF-8'
                                                        ) ?>
                                                    </strong>
                                                    <br>
                                                    <small class="text-muted">
                                                        Aksesi:
                                                        <?= htmlspecialchars(
                                                            $detail['nomor_aksesi'] ?? '-',
                                                            ENT_QUOTES,
                                                            'UTF-8'
                                                        ) ?>
                                                        <br>
                                                        <i>
                                                            <?= htmlspecialchars(
                                                                $detail['nama_ilmiah'] ?? '-',
                                                                ENT_QUOTES,
                                                                'UTF-8'
                                                            ) ?>
                                                        </i>
                                                    </small>
                                                </td>
                                                <td>
                                                    <span class="badge badge-info">
                                                        <?= htmlspecialchars(
                                                            $detail['nama_tipe_penanaman'] ?? '-',
                                                            ENT_QUOTES,
                                                            'UTF-8'
                                                        ) ?>
                                                    </span>
                                                </td>
                                                <td class="text-right">
                                                    <?= number_format(
                                                        (float) $detail['jumlah_ditanam'],
                                                        2,
                                                        ',',
                                                        '.'
                                                    ) ?>
                                                    <?= htmlspecialchars(
                                                        $detail['satuan'] ?? '',
                                                        ENT_QUOTES,
                                                        'UTF-8'
                                                    ) ?>
                                                </td>
                                                <td class="text-right">
                                                    <?= $detail['jumlah_hidup'] !== null
                                                        ? number_format(
                                                            (float) $detail['jumlah_hidup'],
                                                            2,
                                                            ',',
                                                            '.'
                                                        )
                                                        : '-'
                                                        ?>
                                                </td>
                                                <td class="text-right">
                                                    <?= $detail['jumlah_mati'] !== null
                                                        ? number_format(
                                                            (float) $detail['jumlah_mati'],
                                                            2,
                                                            ',',
                                                            '.'
                                                        )
                                                        : '-'
                                                        ?>
                                                </td>
                                                <td class="text-right">
                                                    <?php
                                                    $sr =
                                                        $detail['survival_rate_persen'];
                                                    if ($sr !== null):
                                                        ?>
                                                        <span class="badge badge-success">
                                                            <?= number_format(
                                                                (float) $sr,
                                                                2,
                                                                ',',
                                                                '.'
                                                            ) ?>%
                                                        </span>
                                                    <?php else: ?>
                                                        -
                                                    <?php endif; ?>
                                                </td>
                                                <td class="text-right">
                                                    <?= $detail['tinggi_rata2_cm'] !== null
                                                        ? number_format(
                                                            (float) $detail['tinggi_rata2_cm'],
                                                            2,
                                                            ',',
                                                            '.'
                                                        ) . ' cm'
                                                        : '-'
                                                        ?>
                                                </td>
                                                <td class="text-right">
                                                    <?= $detail['diameter_rata2_cm'] !== null
                                                        ? number_format(
                                                            (float) $detail['diameter_rata2_cm'],
                                                            2,
                                                            ',',
                                                            '.'
                                                        ) . ' cm'
                                                        : '-'
                                                        ?>
                                                </td>
                                                <td class="text-right">
                                                    <?= number_format(
                                                        (float) $detail['luas_tanam_ha'],
                                                        2,
                                                        ',',
                                                        '.'
                                                    ) ?>
                                                    Ha
                                                </td>
                                                <td>
                                                    <?= !empty(
                                                        $detail['catatan']
                                                    )
                                                        ? nl2br(
                                                            htmlspecialchars(
                                                                $detail['catatan'],
                                                                ENT_QUOTES,
                                                                'UTF-8'
                                                            )
                                                        )
                                                        : '-'
                                                        ?>
                                                </td>
                                                <td class="text-center">
                                                    <div class="btn-group">
                                                        <?php
                                                        if (
                                                            Permission::can(
                                                                $pdo,
                                                                'Monitoring Penanaman',
                                                                'update'
                                                            )
                                                        ):
                                                            ?>
                                                            <button type="button" class="btn btn-info btn-edit"
                                                                data-id="<?= (int) $detail['id'] ?>"
                                                                data-id-bank-benih="<?= (int) $detail['id_bank_benih'] ?>"
                                                                data-id-tipe="<?= (int) $detail['id_tipe_penanaman'] ?>"
                                                                data-jumlah-ditanam="<?= htmlspecialchars($detail['jumlah_ditanam']) ?>"
                                                                data-stok="<?= htmlspecialchars($detail['jumlah_stok'] ?? 0, ENT_QUOTES, 'UTF-8') ?>"
                                                                data-satuan="<?= htmlspecialchars($detail['satuan']) ?>"
                                                                data-jumlah-hidup="<?= htmlspecialchars($detail['jumlah_hidup'] ?? '') ?>"
                                                                data-jumlah-mati="<?= htmlspecialchars($detail['jumlah_mati'] ?? '') ?>"
                                                                data-tinggi="<?= htmlspecialchars($detail['tinggi_rata2_cm'] ?? '') ?>"
                                                                data-diameter="<?= htmlspecialchars($detail['diameter_rata2_cm'] ?? '') ?>"
                                                                data-luas="<?= htmlspecialchars($detail['luas_tanam_ha']) ?>"
                                                                data-catatan="<?= htmlspecialchars($detail['catatan'] ?? '') ?>"
                                                                data-nama-lokal="<?= htmlspecialchars($detail['nama_lokal'] ?? '') ?>"
                                                                data-nomor-aksesi="<?= htmlspecialchars($detail['nomor_aksesi'] ?? '') ?>"
                                                                data-jumlah-stok="<?= htmlspecialchars($detail['jumlah_stok'] ?? '') ?>"
                                                                data-satuan-stok="<?= htmlspecialchars($detail['satuan_stok'] ?? '') ?>"
                                                                data-toggle="modal" data-target="#modalEdit" title="Edit">
                                                                <i class="fas fa-edit"></i>
                                                            </button>
                                                        <?php endif; ?>
                                                        <?php
                                                        if (
                                                            !$isMonitoringTurunan &&
                                                            Permission::can(
                                                                $pdo,
                                                                'Monitoring Penanaman',
                                                                'delete'
                                                            )
                                                        ):
                                                            ?>
                                                            <form method="POST" action="delete.php" class="form-delete"
                                                                style="display:inline;">
                                                                <?= csrfField() ?>
                                                                <input type="hidden" name="id"
                                                                    value="<?= (int) $detail['id'] ?>">
                                                                <button type="submit" class="btn btn-danger" title="Hapus">
                                                                    <i class="fas fa-trash"></i>
                                                                </button>
                                                            </form>
                                                        <?php endif; ?>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="card-footer">
                            <a href="../monitoring_penanaman/index.php" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i>
                                Kembali
                            </a>
                        </div>
                    </div>
                </div>
            </section>

            <div class="modal fade" id="modalTambah">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">
                                Tambah Detail Monitoring
                            </h4>
                            <button type="button" class="close" data-dismiss="modal">
                                &times;
                            </button>
                        </div>
                        <form method="POST" action="store.php" id="formTambah">
                            <?= csrfField() ?>
                            <input type="hidden" name="id_monitoring" value="<?= $id_monitoring ?>">
                            <div class="modal-body">
                                <div class="form-group">
                                    <label>
                                        Monitoring Penanaman
                                    </label>
                                    <input type="text" class="form-control" value="<?= htmlspecialchars(
                                        $monitoring['kode_monitoring']
                                        . ' - '
                                        . $monitoring['nama_lahan']
                                    ) ?>" readonly>
                                </div>
                                <div class="form-group">
                                    <label>
                                        Bank Benih <code>*</code>
                                    </label>
                                    <select name="id_bank_benih" id="add_id_bank_benih" class="form-control">
                                        <option value="">
                                            -- Pilih Bank Benih --
                                        </option>
                                        <?php foreach (
                                            $bankBenihs
                                            as $bank
                                        ): ?>
                                            <option value="<?= (int) $bank['id'] ?>" data-satuan="<?= htmlspecialchars(
                                                   $bank['satuan_stok'],
                                                   ENT_QUOTES,
                                                   'UTF-8'
                                               ) ?>" data-stok="<?= htmlspecialchars(
                                                    $bank['jumlah_stok'],
                                                    ENT_QUOTES,
                                                    'UTF-8'
                                                ) ?>">
                                                <?= htmlspecialchars($bank['nama_lokal']) ?>

                                                -
                                                <?= htmlspecialchars($bank['nomor_aksesi']) ?>

                                                | Stok:
                                                <?= number_format(
                                                    (float) $bank['jumlah_stok'],
                                                    2,
                                                    ',',
                                                    '.'
                                                ) ?>

                                                <?= htmlspecialchars($bank['satuan_stok']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>
                                        Tipe Penanaman <code>*</code>
                                    </label>
                                    <select name="id_tipe_penanaman" class="form-control" id="add_id_tipe_penanaman"
                                        <?= $isMonitoringTurunan ? 'readonly' : '' ?>>
                                        <option value="">
                                            -- Pilih Tipe Penanaman --
                                        </option>
                                        <?php foreach (
                                            $tipePenanamans
                                            as $tipe
                                        ): ?>
                                            <option value="<?= (int) $tipe['id'] ?>">
                                                <?= htmlspecialchars(
                                                    $tipe['nama']
                                                ) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>
                                                Jumlah Ditanam <code>*</code>
                                            </label>
                                            <input type="number" step="0.01" min="0" name="jumlah_ditanam"
                                                id="add_jumlah_ditanam" class="form-control" <?= $isMonitoringTurunan ? 'readonly' : '' ?>>

                                        </div>

                                    </div>


                                    <!-- SATUAN -->

                                    <div class="col-md-6">

                                        <div class="form-group">

                                            <label>
                                                Satuan <code>*</code>
                                            </label>
                                            <input type="text" name="satuan" id="add_satuan" class="form-control"
                                                readonly>
                                            <!-- <select name="satuan" id="add_satuan" class="form-control" readonly>
                                                <option value="">
                                                    -- Pilih Satuan --
                                                </option>
                                                <option value="butir">
                                                    Butir
                                                </option>
                                                <option value="gram">
                                                    Gram
                                                </option>
                                                <option value="kg">
                                                    Kg
                                                </option>
                                                <option value="paket">
                                                    Paket
                                                </option>
                                                <option value="bibit">
                                                    Bibit
                                                </option>
                                            </select> -->
                                        </div>
                                    </div>
                                </div>

                                <div class="row">


                                    <!-- HIDUP -->

                                    <div class="col-md-6">

                                        <div class="form-group">

                                            <label>
                                                Jumlah Hidup
                                            </label>

                                            <input type="number" step="0.01" min="0" name="jumlah_hidup"
                                                id="add_jumlah_hidup" class="form-control">

                                        </div>

                                    </div>


                                    <!-- MATI -->

                                    <div class="col-md-6">

                                        <div class="form-group">

                                            <label>
                                                Jumlah Mati
                                            </label>

                                            <input type="number" step="0.01" min="0" name="jumlah_mati"
                                                id="add_jumlah_mati" class="form-control">

                                        </div>

                                    </div>

                                </div>


                                <div class="row">


                                    <!-- TINGGI -->

                                    <div class="col-md-6">

                                        <div class="form-group">

                                            <label>
                                                Tinggi Rata-rata (cm)
                                            </label>

                                            <input type="number" step="0.01" min="0" name="tinggi_rata2_cm"
                                                class="form-control">

                                        </div>

                                    </div>


                                    <!-- DIAMETER -->

                                    <div class="col-md-6">

                                        <div class="form-group">

                                            <label>
                                                Diameter Rata-rata (cm)
                                            </label>

                                            <input type="number" step="0.01" min="0" name="diameter_rata2_cm"
                                                class="form-control">

                                        </div>

                                    </div>

                                </div>


                                <!-- LUAS -->

                                <div class="form-group">

                                    <label>
                                        Luas Tanam (Ha) <code>*</code>
                                    </label>

                                    <input type="number" step="0.01" min="0" name="luas_tanam_ha" class="form-control"
                                        <?= $isMonitoringTurunan ? 'readonly' : '' ?>>

                                </div>


                                <!-- CATATAN -->

                                <div class="form-group">

                                    <label>
                                        Catatan
                                    </label>

                                    <textarea name="catatan" class="form-control" rows="3"
                                        placeholder="Masukkan catatan"></textarea>

                                </div>

                            </div>


                            <div class="modal-footer">

                                <button type="button" class="btn btn-secondary" data-dismiss="modal">

                                    Batal

                                </button>

                                <button type="submit" class="btn btn-primary">

                                    <i class="fas fa-save"></i>

                                    Simpan

                                </button>

                            </div>

                        </form>

                    </div>

                </div>

            </div>


            <!-- =============================================================
             MODAL EDIT
        ============================================================= -->

            <div class="modal fade" id="modalEdit">

                <div class="modal-dialog modal-lg">

                    <div class="modal-content">


                        <div class="modal-header">

                            <h4 class="modal-title">

                                Edit Detail Monitoring

                            </h4>

                            <button type="button" class="close" data-dismiss="modal">

                                &times;

                            </button>

                        </div>


                        <form method="POST" action="update.php" id="formEdit">

                            <?= csrfField() ?>


                            <input type="hidden" name="id" id="edit_id">


                            <input type="hidden" name="id_monitoring" value="<?= $id_monitoring ?>">


                            <input type="hidden" name="id_bank_benih" id="edit_id_bank_benih">


                            <div class="modal-body">


                                <!-- MONITORING -->

                                <div class="form-group">

                                    <label>
                                        Monitoring Penanaman
                                    </label>

                                    <input type="text" class="form-control" value="<?= htmlspecialchars(
                                        $monitoring['kode_monitoring']
                                        . ' - '
                                        . $monitoring['nama_lahan']
                                    ) ?>" readonly>

                                </div>


                                <!-- BANK BENIH -->

                                <div class="form-group">

                                    <label>
                                        Bank Benih
                                    </label>

                                    <input type="text" class="form-control" id="edit_bank_benih" readonly>

                                    <small class="text-muted">

                                        Bank Benih tidak dapat diganti pada Edit Detail Monitoring.

                                    </small>

                                </div>


                                <!-- TIPE -->

                                <div class="form-group">

                                    <label>
                                        Tipe Penanaman <code>*</code>
                                    </label>

                                    <select name="id_tipe_penanaman" id="edit_id_tipe_penanaman" class="form-control"
                                        <?= $isMonitoringTurunan ? 'readonly' : '' ?>>

                                        <option value="">
                                            -- Pilih Tipe Penanaman --
                                        </option>

                                        <?php foreach (
                                            $tipePenanamans
                                            as $tipe
                                        ): ?>

                                            <option value="<?= (int) $tipe['id'] ?>">

                                                <?= htmlspecialchars(
                                                    $tipe['nama']
                                                ) ?>

                                            </option>

                                        <?php endforeach; ?>

                                    </select>

                                </div>


                                <div class="row">


                                    <!-- JUMLAH DITANAM -->

                                    <div class="col-md-6">

                                        <div class="form-group">

                                            <label>
                                                Jumlah Ditanam <code>*</code>
                                            </label>

                                            <input type="number" step="0.01" min="0" name="jumlah_ditanam"
                                                id="edit_jumlah_ditanam" class="form-control" <?= $isMonitoringTurunan ? 'readonly' : '' ?>>

                                        </div>

                                    </div>


                                    <!-- SATUAN -->

                                    <div class="col-md-6">

                                        <div class="form-group">

                                            <label>
                                                Satuan <code>*</code>
                                            </label>
                                            <input type="text" name="satuan" id="edit_satuan" class="form-control"
                                                readonly>
                                            <!-- <select name="satuan" id="edit_satuan" class="form-control" readonly>
                                                <option value="butir">
                                                    Butir
                                                </option>
                                                <option value="gram">
                                                    Gram
                                                </option>
                                                <option value="kg">
                                                    Kg
                                                </option>
                                                <option value="paket">
                                                    Paket
                                                </option>
                                                <option value="bibit">
                                                    Bibit
                                                </option>
                                            </select> -->
                                        </div>
                                    </div>
                                </div>

                                <div class="row">


                                    <div class="col-md-6">

                                        <div class="form-group">

                                            <label>
                                                Jumlah Hidup
                                            </label>

                                            <input type="number" step="0.01" min="0" name="jumlah_hidup"
                                                id="edit_jumlah_hidup" class="form-control">

                                        </div>

                                    </div>


                                    <div class="col-md-6">

                                        <div class="form-group">

                                            <label>
                                                Jumlah Mati
                                            </label>

                                            <input type="number" step="0.01" min="0" name="jumlah_mati"
                                                id="edit_jumlah_mati" class="form-control">

                                        </div>

                                    </div>

                                </div>


                                <div class="row">


                                    <div class="col-md-6">

                                        <div class="form-group">

                                            <label>
                                                Tinggi Rata-rata (cm)
                                            </label>

                                            <input type="number" step="0.01" min="0" name="tinggi_rata2_cm"
                                                id="edit_tinggi" class="form-control">

                                        </div>

                                    </div>


                                    <div class="col-md-6">

                                        <div class="form-group">

                                            <label>
                                                Diameter Rata-rata (cm)
                                            </label>

                                            <input type="number" step="0.01" min="0" name="diameter_rata2_cm"
                                                id="edit_diameter" class="form-control">

                                        </div>

                                    </div>

                                </div>


                                <div class="form-group">

                                    <label>
                                        Luas Tanam (Ha) <code>*</code>
                                    </label>

                                    <input type="number" step="0.01" min="0" name="luas_tanam_ha" id="edit_luas"
                                        class="form-control" <?= $isMonitoringTurunan ? 'readonly' : '' ?>>

                                </div>


                                <div class="form-group">

                                    <label>
                                        Catatan
                                    </label>

                                    <textarea name="catatan" id="edit_catatan" class="form-control" rows="3"></textarea>

                                </div>

                            </div>


                            <div class="modal-footer">

                                <button type="button" class="btn btn-secondary" data-dismiss="modal">

                                    Batal

                                </button>

                                <button type="submit" class="btn btn-primary">

                                    <i class="fas fa-save"></i>

                                    Update

                                </button>

                            </div>

                        </form>

                    </div>

                </div>

            </div>


        </div>


        <?php include "../feature/footer.php" ?>

        <!-- jQuery -->
        <script src="../assets/adminlte/plugins/jquery/jquery.min.js"></script>
        <!-- Bootstrap 4 -->
        <script src="../assets/adminlte/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
        <!-- DataTables  & Plugins -->
        <script src="../assets/adminlte/plugins/datatables/jquery.dataTables.min.js"></script>
        <script src="../assets/adminlte/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
        <script src="../assets/adminlte/plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
        <script src="../assets/adminlte/plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
        <script src="../assets/adminlte/plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
        <script src="../assets/adminlte/plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
        <script src="../assets/adminlte/plugins/jszip/jszip.min.js"></script>
        <script src="../assets/adminlte/plugins/pdfmake/pdfmake.min.js"></script>
        <script src="../assets/adminlte/plugins/pdfmake/vfs_fonts.js"></script>
        <script src="../assets/adminlte/plugins/datatables-buttons/js/buttons.html5.min.js"></script>
        <script src="../assets/adminlte/plugins/datatables-buttons/js/buttons.print.min.js"></script>
        <script src="../assets/adminlte/plugins/datatables-buttons/js/buttons.colVis.min.js"></script>
        <!-- AdminLTE App -->
        <script src="../assets/adminlte/dist/js/adminlte.min.js"></script>
        <!-- AdminLTE for demo purposes -->
        <script src="../assets/adminlte/dist/js/demo.js"></script>
        <!-- jquery-validation -->
        <script src="../assets/adminlte/plugins/jquery-validation/jquery.validate.min.js"></script>
        <script src="../assets/adminlte/plugins/jquery-validation/additional-methods.min.js"></script>

        <script>

            $(function () {

                $("#tableDetail").DataTable({

                    responsive: true,

                    lengthChange: false,

                    autoWidth: false,

                    ordering: true,

                    buttons: [
                        "copy",
                        "csv",
                        "excel",
                        "pdf",
                        "print",
                        "colvis"
                    ]

                }).buttons()
                    .container()
                    .appendTo(
                        '#tableDetail_wrapper .col-md-6:eq(0)'
                    );

            });

        </script>


        <!-- =============================================================
         EDIT
    ============================================================= -->

        <script>

            $(document).on(
                "click",
                ".btn-edit",
                function () {

                    let button = $(this);

                    let id =
                        button.data("id");

                    let idBank =
                        button.data("id-bank-benih");

                    let idTipe =
                        button.data("id-tipe");

                    let jumlahDitanam =
                        parseFloat(
                            button.attr("data-jumlah-ditanam")
                        ) || 0;

                    let stok =
                        parseFloat(
                            button.attr("data-stok")
                        ) || 0;

                    let satuan =
                        button.data("satuan");

                    let jumlahHidup =
                        button.data("jumlah-hidup");

                    let jumlahMati =
                        button.data("jumlah-mati");

                    let tinggi =
                        button.data("tinggi");

                    let diameter =
                        button.data("diameter");

                    let luas =
                        button.data("luas");

                    let catatan =
                        button.data("catatan");
                    let nama_lokal =
                        button.data("nama-lokal");
                    let nomor_aksesi =
                        button.data("nomor-aksesi");
                    let jumlah_stok =
                        button.data("jumlah-stok");
                    let satuan_stok =
                        button.data("satuan-stok");

                    $("#edit_id")
                        .val(id);

                    $("#edit_id_bank_benih")
                        .val(idBank);

                    $("#edit_id_tipe_penanaman")
                        .val(idTipe);

                    $("#edit_jumlah_ditanam")
                        .val(jumlahDitanam);

                    /*
                    |--------------------------------------------------------------------------
                    | SIMPAN NILAI LAMA
                    |
                    | Digunakan untuk menghitung perubahan stok.
                    |--------------------------------------------------------------------------
                    */
                    $("#edit_jumlah_ditanam")
                        .attr(
                            "data-jumlah-lama",
                            jumlahDitanam
                        );

                    /*
                    |--------------------------------------------------------------------------
                    | STOK TERSEDIA SAAT INI
                    |
                    | Stok maksimum yang boleh digunakan:
                    |
                    | stok saat ini + jumlah ditanam lama
                    |--------------------------------------------------------------------------
                    */
                    let stokMaksimal =
                        stok + jumlahDitanam;

                    $("#edit_jumlah_ditanam")
                        .attr(
                            "data-stok-tersedia",
                            stokMaksimal
                        )
                        .attr(
                            "max",
                            stokMaksimal
                        );

                    $("#edit_satuan")
                        .val(satuan);

                    $("#edit_jumlah_hidup")
                        .val(jumlahHidup);

                    $("#edit_jumlah_mati")
                        .val(jumlahMati);

                    $("#edit_tinggi")
                        .val(tinggi);

                    $("#edit_diameter")
                        .val(diameter);

                    $("#edit_luas")
                        .val(luas);

                    $("#edit_catatan")
                        .val(catatan);

                    let bankText =
                        button
                            .closest("tr")
                            .find("td:eq(1)")
                            .text()
                            .trim();
                    $("#edit_bank_benih")
                        .val(nama_lokal + " - " + nomor_aksesi + " | " + "Stok: " + jumlah_stok + " " + satuan_stok);
                }
            );

            /*
            |--------------------------------------------------------------------------
            | JUMLAH DITANAM - EDIT
            |--------------------------------------------------------------------------
            */
            $("#edit_jumlah_ditanam").on(
                "input",
                function () {
                    let input =
                        $(this);
                    let jumlahBaru =
                        parseFloat(
                            input.val()
                        ) || 0;
                    let stokMaksimal =
                        parseFloat(
                            input.attr("data-stok-tersedia")
                        ) || 0;

                    /*
                    |--------------------------------------------------------------------------
                    | Tidak boleh melebihi stok tersedia
                    |--------------------------------------------------------------------------
                    */
                    if (
                        stokMaksimal > 0 &&
                        jumlahBaru > stokMaksimal
                    ) {
                        jumlahBaru =
                            stokMaksimal;
                        input.val(
                            jumlahBaru
                        );
                    }

                    /*
                    |--------------------------------------------------------------------------
                    | Tidak boleh negatif
                    |--------------------------------------------------------------------------
                    */
                    if (jumlahBaru < 0) {
                        jumlahBaru = 0;
                        input.val(0);
                    }

                    /*
                    |--------------------------------------------------------------------------
                    | HIDUP = DITANAM
                    | MATI = 0
                    |
                    | Ketika jumlah ditanam diubah,
                    | kita reset nilai default.
                    |--------------------------------------------------------------------------
                    */
                    $("#edit_jumlah_hidup")
                        .val(jumlahBaru);
                    $("#edit_jumlah_mati")
                        .val(0);
                }
            );

            /*
            |--------------------------------------------------------------------------
            | JUMLAH MATI - EDIT
            |--------------------------------------------------------------------------
            */
            $("#edit_jumlah_mati").on(
                "input",
                function () {
                    let jumlahDitanam =
                        parseFloat(
                            $("#edit_jumlah_ditanam").val()
                        ) || 0;
                    let jumlahMati =
                        parseFloat(
                            $(this).val()
                        ) || 0;

                    /*
                    |--------------------------------------------------------------------------
                    | Mati tidak boleh lebih dari Ditanam
                    |--------------------------------------------------------------------------
                    */
                    if (
                        jumlahMati > jumlahDitanam
                    ) {
                        jumlahMati =
                            jumlahDitanam;
                        $(this).val(
                            jumlahMati
                        );
                    }

                    /*
                    |--------------------------------------------------------------------------
                    | Tidak boleh negatif
                    |--------------------------------------------------------------------------
                    */
                    if (jumlahMati < 0) {
                        jumlahMati = 0;
                        $(this).val(0);
                    }

                    /*
                    |--------------------------------------------------------------------------
                    | HIDUP = DITANAM - MATI
                    |--------------------------------------------------------------------------
                    */
                    let jumlahHidup =
                        jumlahDitanam -
                        jumlahMati;

                    $("#edit_jumlah_hidup")
                        .val(jumlahHidup);
                }
            );

            /*
            |--------------------------------------------------------------------------
            | JUMLAH HIDUP - EDIT
            |--------------------------------------------------------------------------
            */
            $("#edit_jumlah_hidup").on(
                "input",
                function () {
                    let jumlahDitanam =
                        parseFloat(
                            $("#edit_jumlah_ditanam").val()
                        ) || 0;
                    let jumlahHidup =
                        parseFloat(
                            $(this).val()
                        ) || 0;

                    /*
                    |--------------------------------------------------------------------------
                    | Hidup tidak boleh lebih dari Ditanam
                    |--------------------------------------------------------------------------
                    */
                    if (
                        jumlahHidup > jumlahDitanam
                    ) {
                        jumlahHidup =
                            jumlahDitanam;
                        $(this).val(
                            jumlahHidup
                        );
                    }

                    /*
                    |--------------------------------------------------------------------------
                    | Tidak boleh negatif
                    |--------------------------------------------------------------------------
                    */
                    if (jumlahHidup < 0) {
                        jumlahHidup = 0;
                        $(this).val(0);
                    }

                    /*
                    |--------------------------------------------------------------------------
                    | MATI = DITANAM - HIDUP
                    |--------------------------------------------------------------------------
                    */
                    let jumlahMati =
                        jumlahDitanam -
                        jumlahHidup;

                    $("#edit_jumlah_mati")
                        .val(jumlahMati);
                }
            );
        </script>


        <!-- =============================================================
         DELETE
    ============================================================= -->

        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

        <script>

            $(document).on(
                "submit",
                ".form-delete",
                function (e) {

                    e.preventDefault();

                    let form = this;

                    Swal.fire({

                        title: 'Apakah anda yakin?',

                        text:
                            "Detail Monitoring akan dihapus dan stok Bank Benih akan dikembalikan.",

                        icon: 'warning',

                        showCancelButton: true,

                        confirmButtonColor: '#d33',

                        cancelButtonColor: '#6c757d',

                        confirmButtonText:
                            'Ya, hapus!',

                        cancelButtonText:
                            'Batal'

                    }).then(
                        function (result) {

                            if (
                                result.isConfirmed
                            ) {

                                form.submit();

                            }

                        }
                    );

                }
            );

        </script>


        <!-- =============================================================
         VALIDATION
    ============================================================= -->

        <script>

            $(function () {

                $("#formTambah").validate({

                    rules: {

                        id_bank_benih: {
                            required: true
                        },

                        id_tipe_penanaman: {
                            required: true
                        },

                        jumlah_ditanam: {
                            required: true,
                            number: true,
                            min: 0.01,
                            max: function () {
                                return parseFloat(
                                    $("#add_id_bank_benih")
                                        .find(":selected")
                                        .data("stok")
                                ) || 0;
                            }
                        },

                        satuan: {
                            required: true
                        },

                        luas_tanam_ha: {
                            required: true,
                            number: true,
                            min: 0.01
                        }

                    },

                    messages: {

                        id_bank_benih: {
                            required:
                                "Silahkan pilih Bank Benih"
                        },

                        id_tipe_penanaman: {
                            required:
                                "Silahkan pilih Tipe Penanaman"
                        },

                        jumlah_ditanam: {
                            required:
                                "Jumlah ditanam wajib diisi",
                            number:
                                "Jumlah harus berupa angka",
                            min:
                                "Jumlah harus lebih dari 0",
                            max:
                                "Jumlah ditanam tidak boleh melebihi stok Bank Benih"
                        },

                        satuan: {
                            required:
                                "Silahkan pilih satuan"
                        },

                        luas_tanam_ha: {
                            required:
                                "Luas tanam wajib diisi",
                            number:
                                "Luas harus berupa angka",
                            min:
                                "Luas harus lebih dari 0"
                        }

                    },

                    errorElement: 'span',

                    errorPlacement:
                        function (
                            error,
                            element
                        ) {

                            error.addClass(
                                'invalid-feedback'
                            );

                            element
                                .closest(
                                    '.form-group'
                                )
                                .append(error);

                        },

                    highlight:
                        function (element) {

                            $(element)
                                .addClass(
                                    'is-invalid'
                                );

                        },

                    unhighlight:
                        function (element) {

                            $(element)
                                .removeClass(
                                    'is-invalid'
                                );

                        }

                });


                $("#formEdit").validate({

                    rules: {

                        id_tipe_penanaman: {
                            required: true
                        },

                        jumlah_ditanam: {
                            required: true,
                            number: true,
                            min: 0.01
                        },

                        satuan: {
                            required: true
                        },

                        luas_tanam_ha: {
                            required: true,
                            number: true,
                            min: 0.01
                        }

                    },

                    messages: {

                        id_tipe_penanaman: {
                            required:
                                "Silahkan pilih Tipe Penanaman"
                        },

                        jumlah_ditanam: {
                            required:
                                "Jumlah ditanam wajib diisi",
                            number:
                                "Jumlah harus berupa angka",
                            min:
                                "Jumlah harus lebih dari 0"
                        },

                        satuan: {
                            required:
                                "Silahkan pilih satuan"
                        },

                        luas_tanam_ha: {
                            required:
                                "Luas tanam wajib diisi",
                            number:
                                "Luas harus berupa angka",
                            min:
                                "Luas harus lebih dari 0"
                        }

                    },

                    errorElement: 'span',

                    errorPlacement:
                        function (
                            error,
                            element
                        ) {

                            error.addClass(
                                'invalid-feedback'
                            );

                            element
                                .closest(
                                    '.form-group'
                                )
                                .append(error);

                        },

                    highlight:
                        function (element) {

                            $(element)
                                .addClass(
                                    'is-invalid'
                                );

                        },

                    unhighlight:
                        function (element) {

                            $(element)
                                .removeClass(
                                    'is-invalid'
                                );

                        }

                });

            });

        </script>


        <!-- =============================================================
         AUTO SATUAN DARI BANK BENIH
    ============================================================= -->

        <script>
            $("#add_id_bank_benih").on(
                "change",
                function () {
                    let selected =
                        $(this).find(":selected");
                    let satuan =
                        selected.data("satuan");
                    let stok =
                        parseFloat(selected.data("stok")) || 0;

                    // Set satuan otomatis
                    if (satuan) {
                        $("#add_satuan")
                            .val(satuan);
                    }

                    // Simpan stok ke input jumlah ditanam
                    $("#add_jumlah_ditanam")
                        .attr("max", stok);

                    // Jika jumlah ditanam sebelumnya lebih besar dari stok
                    let jumlahDitanam =
                        parseFloat(
                            $("#add_jumlah_ditanam").val()
                        ) || 0;

                    if (jumlahDitanam > stok) {
                        $("#add_jumlah_ditanam")
                            .val(stok)
                            .trigger("input");
                    }
                }
            );
        </script>

        <script>
            function showToast(message, type) {
                let bgColor = 'bg-success';
                if (type === 'updated') {
                    bgColor = 'bg-info';
                } else if (type === 'deleted') {
                    bgColor = 'bg-danger';
                }
                $(document).Toasts('create', {
                    class: bgColor,
                    title: 'Pesan',
                    body: message,
                    delay: 1000
                });
            }
            const urlParams = new URLSearchParams(window.location.search);
            const success = urlParams.get('success');
            if (success === "created") {
                showToast("Data Detail Monitoring Penanaman berhasil ditambahkan", "created");
            }
            if (success === "updated") {
                showToast("Data Detail Monitoring Penanaman berhasil diperbarui", "updated");
            }
            if (success === "deleted") {
                showToast("Data Detail Monitoring Penanaman berhasil dihapus", "deleted");
            }
            if (success === "validation1") {
                showToast("Monitoring, Tipe Penanaman, Bank Benih, jumlah ditanam, satuan dan Luas Tanam (Ha) wajib diisi.", "deleted");
            }
            if (success === "validation2") {
                showToast("Jumlah ditanam harus berupa angka lebih besar dari 0.", "deleted");
            }
            if (success === "validation3") {
                showToast("Bank Benih tersebut sudah memiliki Detail Monitoring pada monitoring ini.", "deleted");
            }
            if (success === "validation4") {
                showToast("Terjadi Error", "deleted");
            }
        </script>

        <!-- =============================================================
        AUTO JUMLAH HIDUP & MATI
        ============================================================= -->
        <script>
            $(function () {
                /**
                 * Format angka agar tidak menghasilkan NaN
                 */
                function getNumber(value) {
                    let number = parseFloat(value);
                    return isNaN(number) ? 0 : number;
                }

                /**
                 * =========================================================
                 * TAMBAH DATA
                 * =========================================================
                 */
                // Saat Jumlah Ditanam berubah
                $("#add_jumlah_ditanam").on("input", function () {
                    let jumlahDitanam =
                        getNumber($(this).val());
                    let jumlahStok =
                        getNumber(
                            $("#add_id_bank_benih")
                                .find(":selected")
                                .data("stok")
                        );

                    // Jumlah ditanam tidak boleh lebih dari stok
                    if (
                        jumlahStok > 0 &&
                        jumlahDitanam > jumlahStok
                    ) {
                        jumlahDitanam = jumlahStok;
                        $(this).val(jumlahDitanam);
                    }

                    // Tidak boleh negatif
                    if (jumlahDitanam < 0) {
                        jumlahDitanam = 0;
                        $(this).val(0);
                    }

                    // Hidup = Jumlah Ditanam
                    $("#add_jumlah_hidup")
                        .val(jumlahDitanam);

                    // Mati = 0
                    $("#add_jumlah_mati")
                        .val(0);
                });

                // Saat Jumlah Mati diubah
                $("#add_jumlah_mati").on("input", function () {
                    let jumlahDitanam =
                        getNumber(
                            $("#add_jumlah_ditanam").val()
                        );
                    let jumlahMati =
                        getNumber($(this).val());

                    // Mati tidak boleh lebih dari Ditanam
                    if (jumlahMati > jumlahDitanam) {
                        jumlahMati = jumlahDitanam;
                        $(this).val(jumlahMati);
                    }

                    // Mati tidak boleh negatif
                    if (jumlahMati < 0) {
                        jumlahMati = 0;
                        $(this).val(0);
                    }

                    // Hidup = Ditanam - Mati
                    let jumlahHidup =
                        jumlahDitanam - jumlahMati;

                    $("#add_jumlah_hidup")
                        .val(jumlahHidup);
                });

                // Saat Jumlah Hidup diubah
                $("#add_jumlah_hidup").on("input", function () {
                    let jumlahDitanam =
                        getNumber(
                            $("#add_jumlah_ditanam").val()
                        );
                    let jumlahHidup =
                        getNumber($(this).val());

                    // Hidup tidak boleh lebih dari Ditanam
                    if (jumlahHidup > jumlahDitanam) {
                        jumlahHidup = jumlahDitanam;
                        $(this).val(jumlahHidup);
                    }

                    // Hidup tidak boleh negatif
                    if (jumlahHidup < 0) {
                        jumlahHidup = 0;
                        $(this).val(0);
                    }

                    // Mati = Ditanam - Hidup
                    let jumlahMati =
                        jumlahDitanam - jumlahHidup;

                    $("#add_jumlah_mati")
                        .val(jumlahMati);
                });

                /**
                 * =========================================================
                 * EDIT DATA
                 * =========================================================
                 */
                // Saat Jumlah Ditanam berubah
                $("#edit_jumlah_ditanam").on("input", function () {
                    let jumlahDitanam =
                        getNumber($(this).val());

                    // Ketika jumlah ditanam diubah,
                    // default kembali:
                    // Hidup = Ditanam
                    // Mati = 0
                    $("#edit_jumlah_hidup")
                        .val(jumlahDitanam);
                    $("#edit_jumlah_mati")
                        .val(0);
                });

                // Saat Jumlah Mati diubah
                $("#edit_jumlah_mati").on("input", function () {
                    let jumlahDitanam =
                        getNumber(
                            $("#edit_jumlah_ditanam").val()
                        );
                    let jumlahMati =
                        getNumber($(this).val());

                    // Mati tidak boleh lebih dari Ditanam
                    if (jumlahMati > jumlahDitanam) {
                        jumlahMati = jumlahDitanam;
                        $(this).val(jumlahMati);
                    }

                    // Mati tidak boleh negatif
                    if (jumlahMati < 0) {
                        jumlahMati = 0;
                        $(this).val(0);
                    }

                    // Hidup = Ditanam - Mati
                    let jumlahHidup =
                        jumlahDitanam - jumlahMati;

                    $("#edit_jumlah_hidup")
                        .val(jumlahHidup);
                });

                // Saat Jumlah Hidup diubah
                $("#edit_jumlah_hidup").on("input", function () {
                    let jumlahDitanam =
                        getNumber(
                            $("#edit_jumlah_ditanam").val()
                        );
                    let jumlahHidup =
                        getNumber($(this).val());

                    // Hidup tidak boleh lebih dari Ditanam
                    if (jumlahHidup > jumlahDitanam) {
                        jumlahHidup = jumlahDitanam;
                        $(this).val(jumlahHidup);
                    }

                    // Hidup tidak boleh negatif
                    if (jumlahHidup < 0) {
                        jumlahHidup = 0;
                        $(this).val(0);
                    }

                    // Mati = Ditanam - Hidup
                    let jumlahMati =
                        jumlahDitanam - jumlahHidup;

                    $("#edit_jumlah_mati")
                        .val(jumlahMati);
                });
            });
        </script>
    </div>
</body>

</html>