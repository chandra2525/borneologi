<?php

require_once '../../app/config/database.php';
require_once '../../app/models/MonitoringPenanaman.php';
require_once '../../app/controllers/MonitoringPenanamanController.php';
require_once '../../app/core/session.php';
require_once '../../app/core/csrf.php';
require_once '../../app/core/auth.php';
require_once '../../app/helpers/escape.php';
require_once '../../app/core/permission.php';

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

$controller = new MonitoringPenanamanController($pdo);

/**
 * Data Monitoring.
 */
$monitoringPenanamans = $controller->index();

/**
 * Data Tanah.
 */
$tanahs = $controller->getTanah();

/**
 * Status Monitoring.
 */
$progressStatusMonitorings =
    $controller->getStatusMonitoring();

/**
 * Generate kode berikutnya.
 */
$nextKode =
    (new MonitoringPenanaman($pdo))
        ->generateKodeMonitoring();

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

        <?php include "../feature/navbar.php"; ?>

        <?php

        $menu = "monitoring_penanaman";

        include "../feature/sidebar.php";

        ?>

        <div class="content-wrapper">

            <section class="content-header">

                <div class="container-fluid">

                    <div class="row mb-2">

                        <div class="col-sm-6">

                            <h1>
                                Monitoring Penanaman
                            </h1>

                        </div>

                        <div class="col-sm-6">

                            <ol class="breadcrumb float-sm-right">

                                <li class="breadcrumb-item">
                                    <a href="../dashboard/index.php">
                                        Beranda
                                    </a>
                                </li>

                                <li class="breadcrumb-item active">
                                    Monitoring Penanaman
                                </li>

                            </ol>

                        </div>

                    </div>

                </div>

            </section>


            <section class="content">

                <div class="container-fluid">

                    <div class="row">

                        <div class="col-12">

                            <div class="card">

                                <div class="card-header">

                                    <div class="d-flex justify-content-between align-items-center">

                                        <h3 class="card-title mb-0">

                                            <i class="fas fa-seedling mr-2"></i>

                                            List Monitoring Penanaman

                                        </h3>


                                        <?php if (
                                            Permission::can(
                                                $pdo,
                                                'Monitoring Penanaman',
                                                'create'
                                            )
                                        ): ?>

                                            <button type="button" class="btn btn-success" data-toggle="modal"
                                                data-target="#modalTambah">

                                                <i class="fas fa-plus mr-1"></i>

                                                Tambah Monitoring

                                            </button>

                                        <?php endif; ?>

                                    </div>

                                </div>


                                <div class="card-body">

                                    <div class="table-responsive">

                                        <table id="example1" class="table table-bordered table-striped table-hover">

                                            <thead>

                                                <tr>

                                                    <th class="text-center">
                                                        No
                                                    </th>

                                                    <th>
                                                        Kode Monitoring
                                                    </th>

                                                    <th>
                                                        Nama Lahan
                                                    </th>

                                                    <th>
                                                        Progress Status
                                                    </th>

                                                    <th>
                                                        Periode Pengecekan
                                                    </th>

                                                    <th>
                                                        Tanggal Tanam
                                                    </th>

                                                    <th>
                                                        Tanggal Monitoring
                                                    </th>

                                                    <th>
                                                        Jumlah Detail
                                                    </th>

                                                    <th>
                                                        Total Luas (Ha)
                                                    </th>

                                                    <th>
                                                        Catatan
                                                    </th>

                                                    <th class="text-center" style="min-width: 180px;">

                                                        Aksi

                                                    </th>

                                                </tr>

                                            </thead>


                                            <tbody>

                                                <?php $no = 1; ?>

                                                <?php foreach (
                                                    $monitoringPenanamans
                                                    as $monitoringPenanaman
                                                ): ?>

                                                    <tr>

                                                        <td class="text-center">

                                                            <?= $no++ ?>

                                                        </td>


                                                        <td>

                                                            <span class="badge badge-primary">

                                                                <?= htmlspecialchars(
                                                                    $monitoringPenanaman[
                                                                        'kode_monitoring'
                                                                    ] ?? '-',
                                                                    ENT_QUOTES,
                                                                    'UTF-8'
                                                                ) ?>

                                                            </span>

                                                        </td>


                                                        <td>

                                                            <?= htmlspecialchars(
                                                                $monitoringPenanaman[
                                                                    'nama_lahan'
                                                                ] ?? '-',
                                                                ENT_QUOTES,
                                                                'UTF-8'
                                                            ) ?>

                                                        </td>


                                                        <td>

                                                            <?php

                                                            $status =
                                                                $monitoringPenanaman[
                                                                    'nama_status_monitoring'
                                                                ] ?? '-';

                                                            ?>

                                                            <?php if (
                                                                $status !== '-'
                                                            ): ?>

                                                                <span class="badge badge-info">

                                                                    <?= htmlspecialchars(
                                                                        $status,
                                                                        ENT_QUOTES,
                                                                        'UTF-8'
                                                                    ) ?>

                                                                </span>

                                                            <?php else: ?>

                                                                -

                                                            <?php endif; ?>

                                                        </td>


                                                        <td>

                                                            <?= !empty(
                                                                $monitoringPenanaman[
                                                                    'periode_pengecekan'
                                                                ]
                                                            )
                                                                ? date(
                                                                    'd/m/Y',
                                                                    strtotime(
                                                                        $monitoringPenanaman[
                                                                            'periode_pengecekan'
                                                                        ]
                                                                    )
                                                                )
                                                                : '-'
                                                                ?>

                                                        </td>


                                                        <td>

                                                            <?= !empty(
                                                                $monitoringPenanaman[
                                                                    'tanggal_tanam'
                                                                ]
                                                            )
                                                                ? date(
                                                                    'd/m/Y',
                                                                    strtotime(
                                                                        $monitoringPenanaman[
                                                                            'tanggal_tanam'
                                                                        ]
                                                                    )
                                                                )
                                                                : '-'
                                                                ?>

                                                        </td>


                                                        <td>

                                                            <?= !empty(
                                                                $monitoringPenanaman[
                                                                    'tanggal_monitoring'
                                                                ]
                                                            )
                                                                ? date(
                                                                    'd/m/Y',
                                                                    strtotime(
                                                                        $monitoringPenanaman[
                                                                            'tanggal_monitoring'
                                                                        ]
                                                                    )
                                                                )
                                                                : '-'
                                                                ?>

                                                        </td>


                                                        <td class="text-center">

                                                            <span class="badge badge-secondary">

                                                                <?= (int) (
                                                                    $monitoringPenanaman[
                                                                        'jumlah_detail'
                                                                    ] ?? 0
                                                                ) ?>

                                                            </span>

                                                        </td>


                                                        <td class="text-right">

                                                            <?= number_format(
                                                                (float) (
                                                                    $monitoringPenanaman[
                                                                        'total_luas_tanam_ha'
                                                                    ] ?? 0
                                                                ),
                                                                2,
                                                                ',',
                                                                '.'
                                                            ) ?>

                                                        </td>


                                                        <td>

                                                            <?php

                                                            $catatan =
                                                                trim(
                                                                    $monitoringPenanaman[
                                                                        'catatan'
                                                                    ] ?? ''
                                                                );

                                                            ?>

                                                            <?= $catatan !== ''
                                                                ? htmlspecialchars(
                                                                    $catatan,
                                                                    ENT_QUOTES,
                                                                    'UTF-8'
                                                                )
                                                                : '-'
                                                                ?>

                                                        </td>


                                                        <td class="text-center">

                                                            <div class="btn-group">

                                                                <!-- DETAIL -->

                                                                <a href="../detail_monitoring/index.php?id_monitoring=<?= (int) $monitoringPenanaman['id'] ?>"
                                                                    class="btn btn-sm btn-info" title="Detail Monitoring">

                                                                    <i class="fas fa-list"></i>

                                                                </a>


                                                                <!-- EDIT -->

                                                                <?php if (
                                                                    Permission::can(
                                                                        $pdo,
                                                                        'Monitoring Penanaman',
                                                                        'update'
                                                                    )
                                                                ): ?>

                                                                    <button type="button"
                                                                        class="btn btn-sm btn-warning btn-edit" title="Edit"
                                                                        data-id="<?= (int) $monitoringPenanaman['id'] ?>"
                                                                        data-kode-monitoring="<?= htmlspecialchars(
                                                                            $monitoringPenanaman[
                                                                                'kode_monitoring'
                                                                            ] ?? '',
                                                                            ENT_QUOTES,
                                                                            'UTF-8'
                                                                        ) ?>"
                                                                        data-id-tanah="<?= (int) $monitoringPenanaman['id_tanah'] ?>"
                                                                        data-id-status="<?= (int) $monitoringPenanaman['id_progress_status_monitoring'] ?>"
                                                                        data-periode="<?= htmlspecialchars(
                                                                            $monitoringPenanaman[
                                                                                'periode_pengecekan'
                                                                            ] ?? '',
                                                                            ENT_QUOTES,
                                                                            'UTF-8'
                                                                        ) ?>" data-tanggal-tanam="<?= htmlspecialchars(
                                                                             $monitoringPenanaman[
                                                                                 'tanggal_tanam'
                                                                             ] ?? '',
                                                                             ENT_QUOTES,
                                                                             'UTF-8'
                                                                         ) ?>" data-tanggal-monitoring="<?= htmlspecialchars(
                                                                              $monitoringPenanaman[
                                                                                  'tanggal_monitoring'
                                                                              ] ?? '',
                                                                              ENT_QUOTES,
                                                                              'UTF-8'
                                                                          ) ?>" data-catatan="<?= htmlspecialchars(
                                                                               $monitoringPenanaman[
                                                                                   'catatan'
                                                                               ] ?? '',
                                                                               ENT_QUOTES,
                                                                               'UTF-8'
                                                                           ) ?>">

                                                                        <i class="fas fa-edit"></i>

                                                                    </button>

                                                                <?php endif; ?>


                                                                <!-- DELETE -->

                                                                <?php if (
                                                                    Permission::can(
                                                                        $pdo,
                                                                        'Monitoring Penanaman',
                                                                        'delete'
                                                                    )
                                                                ): ?>

                                                                    <form method="POST" action="delete.php" class="form-delete"
                                                                        style="display:inline;">

                                                                        <?= csrfField() ?>

                                                                        <input type="hidden" name="id"
                                                                            value="<?= (int) $monitoringPenanaman['id'] ?>">

                                                                        <button type="submit" class="btn btn-sm btn-danger"
                                                                            title="Hapus">

                                                                            <i class="fas fa-trash"></i>

                                                                        </button>

                                                                    </form>

                                                                <?php endif; ?>

                                                            </div>

                                                        </td>

                                                    </tr>

                                                <?php endforeach; ?>

                                            </tbody>


                                            <tfoot>

                                                <tr>

                                                    <th class="text-center">
                                                        No
                                                    </th>

                                                    <th>
                                                        Kode Monitoring
                                                    </th>

                                                    <th>
                                                        Nama Lahan
                                                    </th>

                                                    <th>
                                                        Progress Status
                                                    </th>

                                                    <th>
                                                        Periode Pengecekan
                                                    </th>

                                                    <th>
                                                        Tanggal Tanam
                                                    </th>

                                                    <th>
                                                        Tanggal Monitoring
                                                    </th>

                                                    <th>
                                                        Jumlah Detail
                                                    </th>

                                                    <th>
                                                        Total Luas (Ha)
                                                    </th>

                                                    <th>
                                                        Catatan
                                                    </th>

                                                    <th>
                                                        Aksi
                                                    </th>

                                                </tr>

                                            </tfoot>

                                        </table>

                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            </section>


            <!-- =====================================================
             MODAL TAMBAH
        ====================================================== -->

            <div class="modal fade" id="modalTambah" tabindex="-1" role="dialog" aria-labelledby="modalTambahLabel"
                aria-hidden="true">

                <div class="modal-dialog modal-lg" role="document">

                    <div class="modal-content">

                        <form id="formTambah" method="POST" action="store.php">

                            <?= csrfField() ?>


                            <div class="modal-header">

                                <h4 class="modal-title" id="modalTambahLabel">

                                    <i class="fas fa-plus-circle mr-2"></i>

                                    Tambah Monitoring Penanaman

                                </h4>

                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">

                                    <span aria-hidden="true">
                                        &times;
                                    </span>

                                </button>

                            </div>


                            <div class="modal-body">

                                <div class="alert alert-info">

                                    <i class="fas fa-info-circle mr-1"></i>

                                    Kode Monitoring akan dibuat otomatis oleh sistem.

                                </div>


                                <div class="row">

                                    <div class="col-md-6">

                                        <div class="form-group">

                                            <label>
                                                Kode Monitoring
                                            </label>

                                            <input type="text" name="kode_monitoring" class="form-control" value="<?= htmlspecialchars(
                                                $nextKode,
                                                ENT_QUOTES,
                                                'UTF-8'
                                            ) ?>" readonly>

                                        </div>

                                    </div>


                                    <div class="col-md-6">

                                        <div class="form-group">

                                            <label for="add_id_tanah">

                                                Nama Lahan
                                                <code>*</code>

                                            </label>

                                            <select name="id_tanah" id="add_id_tanah" class="form-control" required>

                                                <option value="">
                                                    -- Pilih Lahan --
                                                </option>

                                                <?php foreach (
                                                    $tanahs
                                                    as $tanah
                                                ): ?>

                                                    <option value="<?= (int) $tanah['id'] ?>">

                                                        <?= htmlspecialchars(
                                                            $tanah['nama_lahan'],
                                                            ENT_QUOTES,
                                                            'UTF-8'
                                                        ) ?>

                                                    </option>

                                                <?php endforeach; ?>

                                            </select>

                                        </div>

                                    </div>

                                </div>


                                <div class="row">

                                    <div class="col-md-6">

                                        <div class="form-group">

                                            <label for="add_periode_pengecekan">

                                                Periode Pengecekan
                                                <code>*</code>

                                            </label>

                                            <input type="date" name="periode_pengecekan" id="add_periode_pengecekan"
                                                class="form-control" required>

                                        </div>

                                    </div>


                                    <div class="col-md-6">

                                        <div class="form-group">

                                            <label for="add_id_status">

                                                Progress Status Monitoring
                                                <code>*</code>

                                            </label>

                                            <select name="id_progress_status_monitoring" id="add_id_status"
                                                class="form-control" required>

                                                <option value="">
                                                    -- Pilih Status --
                                                </option>

                                                <?php foreach (
                                                    $progressStatusMonitorings
                                                    as $status
                                                ): ?>

                                                    <option value="<?= (int) $status['id'] ?>">

                                                        <?= htmlspecialchars(
                                                            $status['nama'],
                                                            ENT_QUOTES,
                                                            'UTF-8'
                                                        ) ?>

                                                    </option>

                                                <?php endforeach; ?>

                                            </select>

                                        </div>

                                    </div>

                                </div>


                                <div class="row">

                                    <div class="col-md-6">

                                        <div class="form-group">

                                            <label for="add_tanggal_tanam">

                                                Tanggal Tanam
                                                <code>*</code>

                                            </label>

                                            <input type="date" name="tanggal_tanam" id="add_tanggal_tanam"
                                                class="form-control" required>

                                        </div>

                                    </div>


                                    <div class="col-md-6">

                                        <div class="form-group">

                                            <label for="add_tanggal_monitoring">

                                                Tanggal Monitoring

                                            </label>

                                            <input type="date" name="tanggal_monitoring" id="add_tanggal_monitoring"
                                                class="form-control">

                                            <small class="text-muted">

                                                Boleh dikosongkan jika monitoring
                                                belum dilakukan.

                                            </small>

                                        </div>

                                    </div>

                                </div>


                                <div class="form-group">

                                    <label for="add_catatan">
                                        Catatan
                                    </label>

                                    <textarea name="catatan" id="add_catatan" class="form-control" rows="3"
                                        placeholder="Masukkan catatan..."></textarea>

                                </div>
                                <div class="form-group">
                                    <label>Status Aktif<code>*</code></label>
                                    <div class="form-group row">
                                        <div class="col-sm-2">
                                            <div class="custom-control custom-radio">
                                                <input class="custom-control-input" type="radio" id="add_status_aktif"
                                                    name="is_active" value="1" checked>
                                                <label for="add_status_aktif" class="custom-control-label">Aktif</label>
                                            </div>
                                        </div>
                                        <div class="col-sm-2">
                                            <div class="custom-control custom-radio">
                                                <input class="custom-control-input" type="radio"
                                                    id="add_status_nonaktif" name="is_active" value="0">
                                                <label for="add_status_nonaktif"
                                                    class="custom-control-label">Nonaktif</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <div class="modal-footer">

                                <button type="button" class="btn btn-secondary" data-dismiss="modal">

                                    <i class="fas fa-times mr-1"></i>

                                    Batal

                                </button>

                                <button type="submit" class="btn btn-primary">

                                    <i class="fas fa-save mr-1"></i>

                                    Simpan

                                </button>

                            </div>

                        </form>

                    </div>

                </div>

            </div>


            <!-- =====================================================
             MODAL EDIT
        ====================================================== -->

            <div class="modal fade" id="modalEdit" tabindex="-1" role="dialog" aria-labelledby="modalEditLabel"
                aria-hidden="true">

                <div class="modal-dialog modal-lg" role="document">

                    <div class="modal-content">

                        <form id="formEdit" method="POST" action="update.php">

                            <?= csrfField() ?>

                            <input type="hidden" name="id" id="edit_id">


                            <div class="modal-header">

                                <h4 class="modal-title" id="modalEditLabel">

                                    <i class="fas fa-edit mr-2"></i>

                                    Edit Monitoring Penanaman

                                </h4>

                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">

                                    <span aria-hidden="true">
                                        &times;
                                    </span>

                                </button>

                            </div>


                            <div class="modal-body">

                                <div class="row">

                                    <div class="col-md-6">

                                        <div class="form-group">

                                            <label>
                                                Kode Monitoring
                                            </label>

                                            <input type="text" class="form-control" name="kode_monitoring"
                                                id="edit_kode_monitoring" readonly>

                                        </div>

                                    </div>


                                    <div class="col-md-6">

                                        <div class="form-group">

                                            <label for="edit_id_tanah">

                                                Nama Lahan
                                                <code>*</code>

                                            </label>

                                            <select name="id_tanah" id="edit_id_tanah" class="form-control" required>

                                                <option value="">
                                                    -- Pilih Lahan --
                                                </option>

                                                <?php foreach (
                                                    $tanahs
                                                    as $tanah
                                                ): ?>

                                                    <option value="<?= (int) $tanah['id'] ?>">

                                                        <?= htmlspecialchars(
                                                            $tanah['nama_lahan'],
                                                            ENT_QUOTES,
                                                            'UTF-8'
                                                        ) ?>

                                                    </option>

                                                <?php endforeach; ?>

                                            </select>

                                        </div>

                                    </div>

                                </div>


                                <div class="row">

                                    <div class="col-md-6">

                                        <div class="form-group">

                                            <label for="edit_periode_pengecekan">

                                                Periode Pengecekan
                                                <code>*</code>

                                            </label>

                                            <input type="date" name="periode_pengecekan" id="edit_periode_pengecekan"
                                                class="form-control" required>

                                        </div>

                                    </div>


                                    <div class="col-md-6">

                                        <div class="form-group">

                                            <label for="edit_id_status">

                                                Progress Status Monitoring
                                                <code>*</code>

                                            </label>

                                            <select name="id_progress_status_monitoring" id="edit_id_status"
                                                class="form-control" required>

                                                <option value="">
                                                    -- Pilih Status --
                                                </option>

                                                <?php foreach (
                                                    $progressStatusMonitorings
                                                    as $status
                                                ): ?>

                                                    <option value="<?= (int) $status['id'] ?>">

                                                        <?= htmlspecialchars(
                                                            $status['nama'],
                                                            ENT_QUOTES,
                                                            'UTF-8'
                                                        ) ?>

                                                    </option>

                                                <?php endforeach; ?>

                                            </select>

                                        </div>

                                    </div>

                                </div>


                                <div class="row">

                                    <div class="col-md-6">

                                        <div class="form-group">

                                            <label for="edit_tanggal_tanam">

                                                Tanggal Tanam
                                                <code>*</code>

                                            </label>

                                            <input type="date" name="tanggal_tanam" id="edit_tanggal_tanam"
                                                class="form-control" required>

                                        </div>

                                    </div>


                                    <div class="col-md-6">

                                        <div class="form-group">

                                            <label for="edit_tanggal_monitoring">

                                                Tanggal Monitoring

                                            </label>

                                            <input type="date" name="tanggal_monitoring" id="edit_tanggal_monitoring"
                                                class="form-control">

                                        </div>

                                    </div>

                                </div>


                                <div class="form-group">

                                    <label for="edit_catatan">
                                        Catatan
                                    </label>

                                    <textarea name="catatan" id="edit_catatan" class="form-control" rows="3"
                                        placeholder="Masukkan catatan..."></textarea>

                                </div>

                                <div class="form-group">
                                    <label>Status Aktif<code>*</code></label>
                                    <div class="form-group row">
                                        <div class="col-sm-2">
                                            <div class="custom-control custom-radio">
                                                <input class="custom-control-input" type="radio" id="edit_status_aktif"
                                                    name="is_active" value="1" checked>
                                                <label for="edit_status_aktif" class="custom-control-label">Aktif</label>
                                            </div>
                                        </div>
                                        <div class="col-sm-2">
                                            <div class="custom-control custom-radio">
                                                <input class="custom-control-input" type="radio"
                                                    id="edit_status_nonaktif" name="is_active" value="0">
                                                <label for="edit_status_nonaktif"
                                                    class="custom-control-label">Nonaktif</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <div class="modal-footer">

                                <button type="button" class="btn btn-secondary" data-dismiss="modal">

                                    <i class="fas fa-times mr-1"></i>

                                    Batal

                                </button>

                                <button type="submit" class="btn btn-primary">

                                    <i class="fas fa-save mr-1"></i>

                                    Update

                                </button>

                            </div>

                        </form>

                    </div>

                </div>

            </div>

        </div>


        <?php include "../feature/footer.php"; ?>

        <div aria-live="polite" aria-atomic="true" style="position: fixed; top: 20px; right: 20px; z-index: 9999;">
            <div id="toast-container"></div>
        </div>

    </div>


    <!-- =========================================================
     JAVASCRIPT
========================================================= -->


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

            $("#example1").DataTable({

                responsive: true,

                lengthChange: true,

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

            }).buttons().container()
                .appendTo(
                    '#example1_wrapper .col-md-6:eq(0)'
                );

        });

    </script>


    <!-- =========================================================
     EDIT MODAL
========================================================= -->

    <script>

        $(document).on(
            "click",
            ".btn-edit",
            function () {

                const button = $(this);

                $("#edit_id").val(
                    button.data("id")
                );

                $("#edit_kode_monitoring").val(
                    button.data("kode-monitoring")
                );

                $("#edit_id_tanah").val(
                    button.data("id-tanah")
                );

                $("#edit_id_status").val(
                    button.data("id-status")
                );

                $("#edit_periode_pengecekan").val(
                    button.data("periode")
                );

                $("#edit_tanggal_tanam").val(
                    button.data("tanggal-tanam")
                );

                $("#edit_tanggal_monitoring").val(
                    button.data("tanggal-monitoring")
                );

                $("#edit_catatan").val(
                    button.data("catatan")
                );

                $("#edit_is_active").val(
                    button.data("is-active")
                );

                $("#modalEdit").modal("show");

            }
        );

    </script>


    <!-- =========================================================
     VALIDATION
========================================================= -->

    <script>

        $(function () {

            $("#formTambah").validate({

                rules: {

                    id_tanah: {
                        required: true
                    },

                    id_progress_status_monitoring: {
                        required: true
                    },

                    periode_pengecekan: {
                        required: true
                    },

                    tanggal_tanam: {
                        required: true
                    }

                },

                messages: {

                    id_tanah: {
                        required: "Silahkan pilih Tanah"
                    },

                    id_progress_status_monitoring: {
                        required:
                            "Silahkan pilih Progress Status Monitoring"
                    },

                    periode_pengecekan: {
                        required:
                            "Silahkan masukkan Periode Pengecekan"
                    },

                    tanggal_tanam: {
                        required:
                            "Silahkan masukkan Tanggal Tanam"
                    }

                },

                errorElement: "span",

                errorPlacement:
                    function (error, element) {

                        error.addClass(
                            "invalid-feedback"
                        );

                        element.closest(
                            ".form-group"
                        ).append(error);

                    },

                highlight:
                    function (element) {

                        $(element).addClass(
                            "is-invalid"
                        );

                    },

                unhighlight:
                    function (element) {

                        $(element).removeClass(
                            "is-invalid"
                        );

                    }

            });


            $("#formEdit").validate({

                rules: {

                    id_tanah: {
                        required: true
                    },

                    id_progress_status_monitoring: {
                        required: true
                    },

                    periode_pengecekan: {
                        required: true
                    },

                    tanggal_tanam: {
                        required: true
                    }

                },

                messages: {

                    id_tanah: {
                        required: "Silahkan pilih Tanah"
                    },

                    id_progress_status_monitoring: {
                        required:
                            "Silahkan pilih Progress Status Monitoring"
                    },

                    periode_pengecekan: {
                        required:
                            "Silahkan masukkan Periode Pengecekan"
                    },

                    tanggal_tanam: {
                        required:
                            "Silahkan masukkan Tanggal Tanam"
                    }

                },

                errorElement: "span",

                errorPlacement:
                    function (error, element) {

                        error.addClass(
                            "invalid-feedback"
                        );

                        element.closest(
                            ".form-group"
                        ).append(error);

                    },

                highlight:
                    function (element) {

                        $(element).addClass(
                            "is-invalid"
                        );

                    },

                unhighlight:
                    function (element) {

                        $(element).removeClass(
                            "is-invalid"
                        );

                    }

            });

        });

    </script>


    <!-- =========================================================
     DELETE CONFIRMATION
========================================================= -->

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>

        $(document).on(
            "submit",
            ".form-delete",
            function (e) {

                e.preventDefault();

                const form = this;

                Swal.fire({

                    title: "Apakah anda yakin?",

                    text:
                        "Data Monitoring Penanaman akan dihapus!",

                    icon: "warning",

                    showCancelButton: true,

                    confirmButtonColor: "#d33",

                    cancelButtonColor: "#6c757d",

                    confirmButtonText:
                        "Ya, hapus!",

                    cancelButtonText:
                        "Batal"

                }).then(
                    function (result) {

                        if (result.isConfirmed) {

                            form.submit();

                        }

                    }
                );

            }
        );

    </script>


    <!-- =========================================================
     DATE PICKER
========================================================= -->

    <script>

        document.addEventListener(
            "DOMContentLoaded",
            function () {

                const dateInputs = [

                    "add_periode_pengecekan",

                    "add_tanggal_tanam",

                    "add_tanggal_monitoring",

                    "edit_periode_pengecekan",

                    "edit_tanggal_tanam",

                    "edit_tanggal_monitoring"

                ];

                dateInputs.forEach(
                    function (id) {

                        const element =
                            document.getElementById(id);

                        if (element) {

                            element.addEventListener(
                                "focus",
                                function () {

                                    if (
                                        typeof this.showPicker ===
                                        "function"
                                    ) {

                                        this.showPicker();

                                    }

                                }
                            );

                        }

                    }
                );

            }
        );

    </script>


    <!-- =========================================================
     SUCCESS TOAST
========================================================= -->

    <script>

        function showToast(
            message,
            type
        ) {

            let bgColor =
                "bg-success";

            if (type === "updated") {

                bgColor =
                    "bg-info";

            } else if (type === "deleted") {

                bgColor =
                    "bg-danger";

            }

            $(document).Toasts(
                "create",
                {

                    class: bgColor,

                    title: "Berhasil",

                    body: message,

                    delay: 1500

                }
            );

        }


        const urlParams =
            new URLSearchParams(
                window.location.search
            );

        const success =
            urlParams.get("success");


        if (success === "created") {

            showToast(
                "Data Monitoring Penanaman berhasil ditambahkan",
                "created"
            );

        }


        if (success === "updated") {

            showToast(
                "Data Monitoring Penanaman berhasil diperbarui",
                "updated"
            );

        }


        if (success === "deleted") {

            showToast(
                "Data Monitoring Penanaman berhasil dihapus",
                "deleted"
            );

        }

    </script>

</body>

</html>