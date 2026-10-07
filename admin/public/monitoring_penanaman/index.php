<?php

require_once '../../app/config/database.php';
require_once '../../app/controllers/MonitoringPenanamanController.php';
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

$controller = new MonitoringPenanamanController($pdo);
$monitoringPenanamans = $controller->index();
$tanahs = $controller->getTanah();
$progressStatusMonitorings = $controller->getStatusMonitoring();
$monitoringAwals = $controller->getMonitoringAwal();
Permission::authorize($pdo, 'Monitoring Penanaman', 'view');

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
        <!-- Navbar -->
        <?php include "../feature/navbar.php" ?>
        <!-- /.navbar -->

        <!-- Main Sidebar Container -->
        <?php
        $menu = "monitoring_penanaman";
        include "../feature/sidebar.php";
        ?>

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1>Data Monitoring Penanaman</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="#">Master Data</a></li>
                                <li class="breadcrumb-item active">Data Monitoring Penanaman</li>
                            </ol>
                        </div>
                    </div>
                </div><!-- /.container-fluid -->
            </section>

            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header row">
                                    <h3 class="card-title col-9">Berikut adalah list dari Data Monitoring Penanaman</h3>
                                    <?php if (Permission::can($pdo, 'Monitoring Penanaman', 'create')): ?>
                                        <button class="col-3 btn btn-block btn-success" data-toggle="modal"
                                            data-target="#modalTambah">
                                            Tambah Monitoring Penanaman
                                        </button>
                                    <?php endif; ?>
                                </div>
                                <!-- /.card-header -->
                                <div class="card-body">
                                    <table id="example1" class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th class="text-center">No</th>
                                                <th>Monitoring Awal</th>
                                                <th>Kode Monitoring</th>
                                                <th>Nama Lahan</th>
                                                <th>Progress Status Monitoring</th>
                                                <th>Periode Pengecekan</th>
                                                <th>Tanggal Tanam</th>
                                                <th>Tanggal Monitoring</th>
                                                <th>Jumlah Detail</th>
                                                <th>Total Luas (Ha)</th>
                                                <th>Catatan</th>
                                                <th>Status Aktif</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $no = 1; ?>
                                            <?php foreach ($monitoringPenanamans as $monitoringPenanaman): ?>
                                                <tr>
                                                    <td class="text-center"><?= $no++ ?></td>
                                                    <td>
                                                        <?php if (!empty($monitoringPenanaman['kode_monitoring_awal'])): ?>
                                                            <span class="badge badge-secondary">
                                                                <?= htmlspecialchars(
                                                                    $monitoringPenanaman['kode_monitoring_awal'],
                                                                    ENT_QUOTES,
                                                                    'UTF-8'
                                                                ) ?>
                                                            </span>
                                                        <?php else: ?>
                                                            <span class="text-muted">-</span>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td><span
                                                            class="badge badge-primary"><?= htmlspecialchars($monitoringPenanaman['kode_monitoring'] ?? '-', ENT_QUOTES, 'UTF-8') ?></span>
                                                    </td>
                                                    <td><?= htmlspecialchars($monitoringPenanaman['nama_lahan'] ?? '-', ENT_QUOTES, 'UTF-8') ?>
                                                    </td>
                                                    <td>
                                                        <span class="badge <?= match ($monitoringPenanaman['nama_status_monitoring'] ?? '') {
                                                            'Baru Ditanam' => 'badge-primary',
                                                            'Proses Panen' => 'badge-info',
                                                            default => 'badge-success',
                                                        } ?>">
                                                            <?= htmlspecialchars(
                                                                $monitoringPenanaman['nama_status_monitoring'] ?? '-',
                                                                ENT_QUOTES,
                                                                'UTF-8'
                                                            ) ?>
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <?= htmlspecialchars($monitoringPenanaman['periode_pengecekan'] ? date('d/m/Y', strtotime($monitoringPenanaman['periode_pengecekan'])) : '-') ?>
                                                    </td>
                                                    <td>
                                                        <?= htmlspecialchars($monitoringPenanaman['tanggal_tanam'] ? date('d/m/Y', strtotime($monitoringPenanaman['tanggal_tanam'])) : '-') ?>
                                                    </td>
                                                    <td>
                                                        <?= htmlspecialchars($monitoringPenanaman['tanggal_monitoring'] ? date('d/m/Y', strtotime($monitoringPenanaman['tanggal_monitoring'])) : '-') ?>
                                                    </td>
                                                    <td class="text-center"><span
                                                            class="badge badge-secondary"><?= (int) ($monitoringPenanaman['jumlah_detail'] ?? 0) ?></span>
                                                    </td>
                                                    <td class="text-right">
                                                        <?= number_format((float) ($monitoringPenanaman['total_luas_tanam_ha'] ?? 0), 2, ',', '.') ?>
                                                    </td>
                                                    <td><?= htmlspecialchars($monitoringPenanaman['catatan'] ?? '-') ?></td>
                                                    <td><?= $monitoringPenanaman['is_active'] ? 'Aktif' : 'Nonaktif' ?></td>
                                                    <td class="row">
                                                        <div class="col">
                                                            <a href="../detail_monitoring_penanaman/index.php?id_monitoring=<?= (int) $monitoringPenanaman['id'] ?>"
                                                                class="btn btn-block btn-primary btn-detail"
                                                                title="Detail Monitoring">
                                                                <i class="fas fa-list"></i> Detail
                                                            </a>
                                                        </div>
                                                        <?php if (Permission::can($pdo, 'Monitoring Penanaman', 'update')): ?>
                                                            <div class="col">
                                                                <button class="btn btn-block btn-info btn-edit"
                                                                    data-id="<?= $monitoringPenanaman['id'] ?>"
                                                                    data-kode_monitoring="<?= htmlspecialchars($monitoringPenanaman['kode_monitoring']) ?>"
                                                                    data-id_turunan="<?= htmlspecialchars(
                                                                        $monitoringPenanaman['id_turunan'] ?? ''
                                                                    ) ?>"
                                                                    data-id_tanah="<?= htmlspecialchars($monitoringPenanaman['id_tanah']) ?>"
                                                                    data-id_progress_status_monitoring="<?= htmlspecialchars($monitoringPenanaman['id_progress_status_monitoring']) ?>"
                                                                    data-periode_pengecekan="<?= htmlspecialchars($monitoringPenanaman['periode_pengecekan']) ?>"
                                                                    data-tanggal_tanam="<?= htmlspecialchars($monitoringPenanaman['tanggal_tanam']) ?>"
                                                                    data-tanggal_monitoring="<?= htmlspecialchars($monitoringPenanaman['tanggal_monitoring']) ?>"
                                                                    data-catatan="<?= htmlspecialchars($monitoringPenanaman['catatan']) ?>"
                                                                    data-status="<?= $monitoringPenanaman['is_active'] ?>"
                                                                    data-toggle="modal" data-target="#modalEdit">
                                                                    <i class="fas fa-edit"></i> Edit
                                                                </button>
                                                            </div>
                                                        <?php endif; ?>

                                                        <?php if (Permission::can($pdo, 'Monitoring Penanaman', 'delete')): ?>
                                                            <form method="POST" action="delete.php" class="form-delete col">
                                                                <?= csrfField() ?>
                                                                <input type="hidden" name="id"
                                                                    value="<?= $monitoringPenanaman['id'] ?>">
                                                                <button type="submit" class="btn btn-block btn-danger">
                                                                    <i class="fas fa-trash"></i> Hapus
                                                                </button>
                                                            </form>
                                                        <?php endif; ?>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th class="text-center">No</th>
                                                <th>Monitoring Awal</th>
                                                <th>Kode Monitoring</th>
                                                <th>Nama Lahan</th>
                                                <th>Progress Status Monitoring</th>
                                                <th>Periode Pengecekan</th>
                                                <th>Tanggal Tanam</th>
                                                <th>Tanggal Monitoring</th>
                                                <th>Jumlah Detail</th>
                                                <th>Total Luas (Ha)</th>
                                                <th>Catatan</th>
                                                <th>Status Aktif</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                                <!-- /.card-body -->
                            </div>
                            <!-- /.card -->
                        </div>
                        <!-- /.col -->
                    </div>
                    <!-- /.row -->
                </div>
                <!-- /.container-fluid -->
            </section>

            <div class="modal fade" id="modalTambah">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">Tambah Monitoring Penanaman</h4>
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                        </div>
                        <form id="formTambah" method="POST" action="store.php">
                            <?= csrfField() ?>
                            <div class="modal-body">
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle mr-1"></i>
                                    Kode Monitoring akan dibuat otomatis oleh sistem.
                                </div>
                                <div class="form-group">
                                    <label for="kode_monitoring">Kode Monitoring<code>*</code></label>
                                    <input type="text" name="kode_monitoring" class="form-control" id="kode_monitoring"
                                        value="<?= (new MonitoringPenanaman($pdo))->generateKodeMonitoring() ?>"
                                        placeholder="Masukkan kode monitoring" readonly>
                                </div>
                                <div class="form-group">
                                    <label for="id_progress_status_monitoring">Progress Status
                                        Monitoring<code>*</code></label>
                                    <select name="id_progress_status_monitoring" class="form-control"
                                        id="id_progress_status_monitoring">
                                        <option value="">-- Pilih Progress Status Monitoring --</option>
                                        <?php foreach ($progressStatusMonitorings as $tipe): ?>
                                            <option value="<?= $tipe['id'] ?>">
                                                <?= htmlspecialchars($tipe['nama']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="form-group" id="group_monitoring_awal" style="display: none;">
                                    <label for="id_turunan">
                                        Turunan Monitoring Awal<code>*</code>
                                    </label>

                                    <select name="id_turunan" class="form-control" id="id_turunan">

                                        <option value="">
                                            -- Pilih Monitoring Awal --
                                        </option>

                                        <?php foreach ($monitoringAwals as $awal): ?>
                                            <option value="<?= (int) $awal['id'] ?>"
                                                data-id-tanah="<?= (int) $awal['id_tanah'] ?>" data-tanggal-tanam="<?= htmlspecialchars(
                                                       $awal['tanggal_tanam'],
                                                       ENT_QUOTES,
                                                       'UTF-8'
                                                   ) ?>">

                                                <?= htmlspecialchars(
                                                    $awal['kode_monitoring'],
                                                    ENT_QUOTES,
                                                    'UTF-8'
                                                ) ?>

                                                -
                                                <?= htmlspecialchars(
                                                    $awal['nama_lahan'] ?? '-',
                                                    ENT_QUOTES,
                                                    'UTF-8'
                                                ) ?>

                                                -
                                                <?= date(
                                                    'd/m/Y',
                                                    strtotime($awal['tanggal_tanam'])
                                                ) ?>

                                            </option>
                                        <?php endforeach; ?>

                                    </select>

                                    <small class="form-text text-muted">
                                        Pilih monitoring awal untuk melanjutkan monitoring pada
                                        lahan dan tanggal tanam yang sama.
                                    </small>
                                </div>
                                <div class="form-group">
                                    <label for="id_tanah">Lahan<code>*</code></label>
                                    <select class="form-control" id="id_tanah">
                                        <option value="">-- Pilih Lahan --</option>
                                        <?php foreach ($tanahs as $la): ?>
                                            <option value="<?= $la['id'] ?>">
                                                <?= htmlspecialchars($la['nama_lahan']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                    <input type="hidden" name="id_tanah" id="id_tanah_hidden">
                                </div>
                                <div class="form-group">
                                    <label for="periode_pengecekan">Periode Pengecekan<code>*</code></label>
                                    <input type="date" name="periode_pengecekan" class="form-control"
                                        id="periode_pengecekan">
                                </div>
                                <div class="form-group">
                                    <label for="tanggal_tanam">Tanggal Tanam<code>*</code></label>
                                    <input type="date" class="form-control" id="tanggal_tanam">
                                    <input type="hidden" name="tanggal_tanam" id="tanggal_tanam_hidden">
                                </div>
                                <div class="form-group">
                                    <label for="tanggal_monitoring">Tanggal Monitoring<code>*</code></label>
                                    <input type="date" name="tanggal_monitoring" class="form-control"
                                        id="tanggal_monitoring">
                                </div>
                                <div class="form-group">
                                    <label for="catatan">Catatan</label>
                                    <textarea name="catatan" class="form-control" id="catatan"
                                        placeholder="Masukkan Catatan"></textarea>
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
                                <button type="submit" class="btn btn-primary">Simpan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="modalEdit">
                <div class="modal-dialog">
                    <div class="modal-content">

                        <div class="modal-header">
                            <h4 class="modal-title">Edit Monitoring Penanaman</h4>
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                        </div>

                        <form id="formEdit" method="POST" action="update.php">
                            <?= csrfField() ?>
                            <input type="hidden" name="id" id="edit_id">
                            <div class="modal-body">
                                <div class="form-group">
                                    <label for="kode_monitoring">Kode Monitoring<code>*</code></label>
                                    <input type="text" name="kode_monitoring" class="form-control"
                                        id="edit_kode_monitoring" placeholder="Masukkan kode monitoring" readonly>
                                </div>
                                <div class="form-group">
                                    <label for="id_progress_status_monitoring">Progress Status
                                        Monitoring<code>*</code></label>
                                    <select name="id_progress_status_monitoring" class="form-control"
                                        id="edit_id_progress_status_monitoring">
                                        <option value="">-- Pilih Progress Status Monitoring --</option>
                                        <?php foreach ($progressStatusMonitorings as $tipe): ?>
                                            <option value="<?= $tipe['id'] ?>">
                                                <?= htmlspecialchars($tipe['nama']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="form-group" id="edit_group_monitoring_awal" style="display: none;">
                                    <label for="edit_id_turunan">
                                        Turunan Monitoring Awal<code>*</code>
                                    </label>

                                    <select class="form-control" id="edit_id_turunan" name="id_turunan">

                                        <option value="">
                                            -- Pilih Monitoring Awal --
                                        </option>

                                        <?php foreach ($monitoringAwals as $awal): ?>
                                            <option value="<?= (int) $awal['id'] ?>"
                                                data-id-tanah="<?= (int) $awal['id_tanah'] ?>" data-tanggal-tanam="<?= htmlspecialchars(
                                                       $awal['tanggal_tanam'],
                                                       ENT_QUOTES,
                                                       'UTF-8'
                                                   ) ?>">

                                                <?= htmlspecialchars(
                                                    $awal['kode_monitoring'],
                                                    ENT_QUOTES,
                                                    'UTF-8'
                                                ) ?>

                                                -
                                                <?= htmlspecialchars(
                                                    $awal['nama_lahan'] ?? '-',
                                                    ENT_QUOTES,
                                                    'UTF-8'
                                                ) ?>

                                                -
                                                <?= date(
                                                    'd/m/Y',
                                                    strtotime($awal['tanggal_tanam'])
                                                ) ?>

                                            </option>
                                        <?php endforeach; ?>

                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="id_tanah">Lahan<code>*</code></label>
                                    <select class="form-control" id="edit_id_tanah">
                                        <option value="">-- Pilih Lahan --</option>
                                        <?php foreach ($tanahs as $la): ?>
                                            <option value="<?= $la['id'] ?>">
                                                <?= htmlspecialchars($la['nama_lahan']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                    <input type="hidden" name="id_tanah" id="edit_id_tanah_hidden">
                                </div>
                                <div class="form-group">
                                    <label for="periode_pengecekan">Periode Pengecekan<code>*</code></label>
                                    <input type="date" name="periode_pengecekan" class="form-control"
                                        id="edit_periode_pengecekan">
                                </div>
                                <div class="form-group">
                                    <label for="tanggal_tanam">Tanggal Tanam<code>*</code></label>
                                    <input type="date" class="form-control" id="edit_tanggal_tanam">
                                    <input type="hidden" name="tanggal_tanam" id="edit_tanggal_tanam_hidden">
                                </div>
                                <div class="form-group">
                                    <label for="tanggal_monitoring">Tanggal Monitoring<code>*</code></label>
                                    <input type="date" name="tanggal_monitoring" class="form-control"
                                        id="edit_tanggal_monitoring">
                                </div>
                                <div class="form-group">
                                    <label for="catatan">Catatan</label>
                                    <textarea name="catatan" class="form-control" id="edit_catatan"
                                        placeholder="Masukkan Catatan"></textarea>
                                </div>
                                <div class="form-group">
                                    <label>Status Aktif<code>*</code></label>
                                    <div class="form-group row">
                                        <div class="col-sm-2">
                                            <div class="custom-control custom-radio">
                                                <input class="custom-control-input" type="radio" id="edit_status_aktif"
                                                    name="is_active" value="1" checked>
                                                <label for="edit_status_aktif"
                                                    class="custom-control-label">Aktif</label>
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
                                <button type="submit" class="btn btn-primary">Update</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <!-- /.content -->
        </div>
        <!-- /.content-wrapper -->
        <?php include "../feature/footer.php" ?>

        <div aria-live="polite" aria-atomic="true" style="position: fixed; top: 20px; right: 20px; z-index: 9999;">
            <div id="toast-container"></div>
        </div>
    </div>
    <!-- ./wrapper -->

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
    <!-- Page specific script -->
    <script>
        $(function () {
            $("#example1").DataTable({
                "responsive": true,
                "lengthChange": false,
                "autoWidth": false,
                "ordering": true,
                "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
            }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
        });
    </script>

    <script>
        $(function () {
            function initValidation(formId) {
                $(formId).validate({
                    rules: {
                        kode_monitoring: {
                            required: true
                        },
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
                        },
                        tanggal_monitoring: {
                            required: true
                        },
                        catatan: {
                            required: false
                        },
                        is_active: {
                            required: true
                        }
                    },
                    messages: {
                        kode_monitoring: {
                            required: "Silahkan masukkan Kode Monitoring"
                        },
                        id_tanah: {
                            required: "Silahkan pilih Tanah"
                        },
                        id_progress_status_monitoring: {
                            required: "Silahkan pilih Progress Status Monitoring"
                        },
                        periode_pengecekan: {
                            required: "Silahkan masukkan Periode Pengecekan"
                        },
                        tanggal_tanam: {
                            required: "Silahkan masukkan Tanggal Tanam"
                        },
                        tanggal_monitoring: {
                            required: "Silahkan masukkan Tanggal Monitoring"
                        },
                        // catatan: {
                        //     required: "Silahkan masukkan Catatan"
                        // },
                        is_active: {
                            required: "Silahkan pilih Status Aktif"
                        }
                    },
                    errorElement: 'span',
                    errorPlacement: function (error, element) {
                        error.addClass('invalid-feedback');
                        element.closest('.form-group').append(error);
                    },
                    highlight: function (element) {
                        $(element).addClass('is-invalid');
                    },
                    unhighlight: function (element) {
                        $(element).removeClass('is-invalid');
                    }
                });
            }
            initValidation("#formTambah");
            initValidation("#formEdit");
        });
    </script>

    <script>
        $(document).on("click", ".btn-edit", function () {
            let id = $(this).data("id");
            let kode_monitoring = $(this).data("kode_monitoring");
            let id_tanah = $(this).data("id_tanah");
            let id_progress_status_monitoring = $(this).data("id_progress_status_monitoring");
            let periode_pengecekan = $(this).data("periode_pengecekan");
            let tanggal_tanam = $(this).data("tanggal_tanam");
            let tanggal_monitoring = $(this).data("tanggal_monitoring");
            let catatan = $(this).data("catatan");
            let status = $(this).data("status");

            $("#edit_id").val(id);
            $("#edit_kode_monitoring").val(kode_monitoring);
            $("#edit_id_tanah").val(id_tanah);
            $("#edit_id_progress_status_monitoring").val(id_progress_status_monitoring);
            $("#edit_periode_pengecekan").val(periode_pengecekan);
            $("#edit_tanggal_tanam").val(tanggal_tanam);
            $("#edit_tanggal_monitoring").val(tanggal_monitoring);
            $("#edit_catatan").val(catatan);
            $("input[name='is_active'][value='" + status + "']").prop("checked", true);
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(document).on("submit", ".form-delete", function (e) {
            e.preventDefault();
            let form = this;
            Swal.fire({
                title: 'Apakah anda yakin?',
                text: "Data akan dihapus!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
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
                title: 'Message',
                body: message,
                delay: 1000
            });
        }
        const urlParams = new URLSearchParams(window.location.search);
        const success = urlParams.get('success');
        if (success === "created") {
            showToast("Data Monitoring Penanaman berhasil ditambahkan", "created");
        }
        if (success === "updated") {
            showToast("Data Monitoring Penanaman berhasil diperbarui", "updated");
        }
        if (success === "deleted") {
            showToast("Data Monitoring Penanaman berhasil dihapus", "deleted");
        }
        if (success === "validation1") {
            showToast("Monitoring Penanaman tidak dapat dihapus karena masih memiliki Detail Monitoring Penanaman aktif.", "deleted");
        }
    </script>

    <script>
        document.getElementById('periode_pengecekan').addEventListener('focus', function () {
            this.showPicker();
        });
        document.getElementById('tanggal_tanam').addEventListener('focus', function () {
            this.showPicker();
        });
        document.getElementById('tanggal_monitoring').addEventListener('focus', function () {
            this.showPicker();
        });
        document.getElementById('edit_periode_pengecekan').addEventListener('focus', function () {
            this.showPicker();
        });
        document.getElementById('edit_tanggal_tanam').addEventListener('focus', function () {
            this.showPicker();
        });
        document.getElementById('edit_tanggal_monitoring').addEventListener('focus', function () {
            this.showPicker();
        });
    </script>

    <script>
        $(function () {

            function handleMonitoringAwalAdd() {

                const statusText = $(
                    "#id_progress_status_monitoring option:selected"
                ).text().trim();

                const statusValue = $(
                    "#id_progress_status_monitoring"
                ).val();

                const isBaruDitanam =
                    statusValue !== "" &&
                    statusText === "Baru Ditanam";

                if (!isBaruDitanam) {

                    $("#group_monitoring_awal").slideDown(200);

                    $("#id_turunan").prop("required", true);

                    $("#id_tanah")
                        .prop("disabled", true);

                    $("#tanggal_tanam")
                        .prop("readonly", true);

                } else {

                    $("#group_monitoring_awal").slideUp(200);

                    $("#id_turunan")
                        .val("")
                        .prop("required", false);

                    $("#id_tanah")
                        .prop("disabled", false);

                    $("#tanggal_tanam")
                        .prop("readonly", false);

                    $("#id_tanah_hidden")
                        .val("");

                    $("#tanggal_tanam_hidden")
                        .val("");
                }
            }


            $("#id_progress_status_monitoring").on(
                "change",
                function () {

                    handleMonitoringAwalAdd();

                }
            );


            $("#id_turunan").on(
                "change",
                function () {

                    const option = $(
                        "#id_turunan option:selected"
                    );

                    const idTanah =
                        option.data("id-tanah");

                    const tanggalTanam =
                        option.data("tanggal-tanam");

                    if (!idTanah) {

                        $("#id_tanah")
                            .val("")
                            .prop("disabled", true);

                        $("#id_tanah_hidden")
                            .val("");

                        $("#tanggal_tanam")
                            .val("");

                        $("#tanggal_tanam_hidden")
                            .val("");

                        return;
                    }

                    $("#id_tanah")
                        .val(idTanah)
                        .prop("disabled", true);

                    $("#id_tanah_hidden")
                        .val(idTanah);

                    $("#tanggal_tanam")
                        .val(tanggalTanam)
                        .prop("readonly", true);

                    $("#tanggal_tanam_hidden")
                        .val(tanggalTanam);
                }
            );


            /*
             * Saat modal tambah dibuka,
             * pastikan kondisi awal benar.
             */
            $("#modalTambah").on(
                "shown.bs.modal",
                function () {

                    handleMonitoringAwalAdd();

                }
            );


            /*
             * Sinkronisasi hidden field
             * untuk status Baru Ditanam.
             */
            $("#id_tanah").on(
                "change",
                function () {

                    $("#id_tanah_hidden")
                        .val($(this).val());

                }
            );


            $("#tanggal_tanam").on(
                "change",
                function () {

                    $("#tanggal_tanam_hidden")
                        .val($(this).val());

                }
            );

        });
    </script>

    <script>
        $(document).on("click", ".btn-edit", function () {

            let id =
                $(this).data("id");

            let kode_monitoring =
                $(this).data("kode_monitoring");

            let id_turunan =
                $(this).data("id_turunan");

            let id_tanah =
                $(this).data("id_tanah");

            let id_progress_status_monitoring =
                $(this).data(
                    "id_progress_status_monitoring"
                );

            let periode_pengecekan =
                $(this).data(
                    "periode_pengecekan"
                );

            let tanggal_tanam =
                $(this).data(
                    "tanggal_tanam"
                );

            let tanggal_monitoring =
                $(this).data(
                    "tanggal_monitoring"
                );

            let catatan =
                $(this).data("catatan");

            let status =
                $(this).data("status");


            $("#edit_id")
                .val(id);

            $("#edit_kode_monitoring")
                .val(kode_monitoring);

            $("#edit_id_progress_status_monitoring")
                .val(id_progress_status_monitoring);

            $("#edit_periode_pengecekan")
                .val(periode_pengecekan);

            $("#edit_tanggal_monitoring")
                .val(tanggal_monitoring);

            $("#edit_catatan")
                .val(catatan);

            $("input[name='is_active'][value='" + status + "']")
                .prop("checked", true);


            /*
             * Isi monitoring awal
             */
            $("#edit_id_turunan")
                .val(id_turunan || "");


            /*
             * Isi tanah
             */
            $("#edit_id_tanah")
                .val(id_tanah);

            $("#edit_id_tanah_hidden")
                .val(id_tanah);


            /*
             * Isi tanggal tanam
             */
            $("#edit_tanggal_tanam")
                .val(tanggal_tanam);

            $("#edit_tanggal_tanam_hidden")
                .val(tanggal_tanam);


            handleMonitoringAwalEdit();

        });
    </script>

    <script>
        function handleMonitoringAwalEdit() {

            const statusValue =
                $("#edit_id_progress_status_monitoring").val();

            const statusText =
                $("#edit_id_progress_status_monitoring option:selected")
                    .text()
                    .trim();

            const isBaruDitanam =
                statusValue !== "" &&
                statusText === "Baru Ditanam";


            if (!isBaruDitanam) {

                $("#edit_group_monitoring_awal")
                    .show();

                $("#edit_id_turunan")
                    .prop("required", true);

                $("#edit_id_tanah")
                    .prop("disabled", true);

                $("#edit_tanggal_tanam")
                    .prop("readonly", true);

            } else {

                $("#edit_group_monitoring_awal")
                    .hide();

                $("#edit_id_turunan")
                    .val("")
                    .prop("required", false);

                $("#edit_id_tanah")
                    .prop("disabled", false);

                $("#edit_tanggal_tanam")
                    .prop("readonly", false);

                $("#edit_id_tanah_hidden")
                    .val(
                        $("#edit_id_tanah").val()
                    );

                $("#edit_tanggal_tanam_hidden")
                    .val(
                        $("#edit_tanggal_tanam").val()
                    );
            }
        }


        $("#edit_id_progress_status_monitoring").on(
            "change",
            function () {

                handleMonitoringAwalEdit();

            }
        );


        $("#edit_id_turunan").on(
            "change",
            function () {

                const option =
                    $("#edit_id_turunan option:selected");

                const idTanah =
                    option.data("id-tanah");

                const tanggalTanam =
                    option.data("tanggal-tanam");


                if (!idTanah) {

                    $("#edit_id_tanah")
                        .val("")
                        .prop("disabled", true);

                    $("#edit_id_tanah_hidden")
                        .val("");

                    $("#edit_tanggal_tanam")
                        .val("");

                    $("#edit_tanggal_tanam_hidden")
                        .val("");

                    return;
                }


                $("#edit_id_tanah")
                    .val(idTanah)
                    .prop("disabled", true);

                $("#edit_id_tanah_hidden")
                    .val(idTanah);


                $("#edit_tanggal_tanam")
                    .val(tanggalTanam)
                    .prop("readonly", true);

                $("#edit_tanggal_tanam_hidden")
                    .val(tanggalTanam);
            }
        );


        $("#edit_id_tanah").on(
            "change",
            function () {

                $("#edit_id_tanah_hidden")
                    .val($(this).val());

            }
        );


        $("#edit_tanggal_tanam").on(
            "change",
            function () {

                $("#edit_tanggal_tanam_hidden")
                    .val($(this).val());

            }
        );
    </script>
</body>

</html>