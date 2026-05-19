<?php

require_once '../../app/config/database.php';
require_once '../../app/controllers/MonitoringPenanamanController.php';
require_once '../../app/core/session.php';
require_once '../../app/core/csrf.php';
require_once "../../app/core/auth.php";
require_once '../../app/helpers/escape.php';

secureSessionStart();
checkAuth("non_dashboard");

$controller = new MonitoringPenanamanController($pdo);
$monitoringPenanamans = $controller->index();
$tanahs = $controller->getTanah();
$tipePenanamans = $controller->getTipePenanaman();
$progressStatusMonitorings = $controller->getProgressStatusMonitoring();

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
                                    <button class="col-3 btn btn-block btn-success" data-toggle="modal"
                                        data-target="#modalTambah">
                                        Tambah Monitoring Penanaman
                                    </button>
                                </div>
                                <!-- /.card-header -->
                                <div class="card-body">
                                    <table id="example1" class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th>Kode Monitoring</th>
                                                <th>Nama Lahan</th>
                                                <th>Tipe Penanaman</th>
                                                <th>Progress Status Monitoring</th>
                                                <th>Periode Pengecekan</th>
                                                <th>Tanggal Tanam</th>
                                                <th>Tanggal Monitoring</th>
                                                <th>Luas Tanam (ha)</th>
                                                <th>Survival Rate (%)</th>
                                                <th>Catatan</th>
                                                <th>Status Aktif</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($monitoringPenanamans as $monitoringPenanaman): ?>
                                                <tr>
                                                    <td><?= htmlspecialchars($monitoringPenanaman['kode_monitoring']) ?></td>
                                                    <td><?= htmlspecialchars($monitoringPenanaman['nama_lahan']) ?></td>
                                                    <td><?= htmlspecialchars($monitoringPenanaman['nama_tipe_penanaman']) ?></td>
                                                    <td><?= htmlspecialchars($monitoringPenanaman['nama_progress_status_monitoring']) ?></td>
                                                    <td><?= htmlspecialchars($monitoringPenanaman['periode_pengecekan']) ?></td>
                                                    <td><?= htmlspecialchars($monitoringPenanaman['tanggal_tanam']) ?></td>
                                                    <td><?= htmlspecialchars($monitoringPenanaman['tanggal_monitoring']) ?></td>
                                                    <td><?= htmlspecialchars($monitoringPenanaman['luas_tanam_ha']) ?></td>
                                                    <td><?= htmlspecialchars($monitoringPenanaman['survival_rate_persen']) ?></td>
                                                    <td><?= htmlspecialchars($monitoringPenanaman['catatan']) ?></td>
                                                    <td><?= $monitoringPenanaman['is_active'] ? 'Aktif' : 'Nonaktif' ?></td>
                                                    <td class="row">
                                                        <div class="col">
                                                            <button class="btn btn-block btn-info btn-edit"
                                                                data-id="<?= $monitoringPenanaman['id'] ?>"
                                                                data-kode_monitoring="<?= htmlspecialchars($monitoringPenanaman['kode_monitoring']) ?>"
                                                                data-id_tanah="<?= htmlspecialchars($monitoringPenanaman['id_tanah']) ?>"
                                                                data-id_tipe_penanaman="<?= htmlspecialchars($monitoringPenanaman['id_tipe_penanaman']) ?>"
                                                                data-id_progress_status_monitoring="<?= htmlspecialchars($monitoringPenanaman['id_progress_status_monitoring']) ?>"
                                                                data-periode_pengecekan="<?= htmlspecialchars($monitoringPenanaman['periode_pengecekan']) ?>"
                                                                data-tanggal_tanam="<?= htmlspecialchars($monitoringPenanaman['tanggal_tanam']) ?>"
                                                                data-tanggal_monitoring="<?= htmlspecialchars($monitoringPenanaman['tanggal_monitoring']) ?>"
                                                                data-luas_tanam_ha="<?= htmlspecialchars($monitoringPenanaman['luas_tanam_ha']) ?>"
                                                                data-survival_rate_persen="<?= htmlspecialchars($monitoringPenanaman['survival_rate_persen']) ?>"
                                                                data-catatan="<?= htmlspecialchars($monitoringPenanaman['catatan']) ?>"
                                                                data-status="<?= $monitoringPenanaman['is_active'] ?>"
                                                                data-toggle="modal" data-target="#modalEdit">
                                                                <i class="fas fa-edit"></i> Edit
                                                            </button>
                                                        </div>

                                                        <form method="POST" action="delete.php" class="form-delete col">
                                                            <?= csrfField() ?>
                                                            <input type="hidden" name="id" value="<?= $monitoringPenanaman['id'] ?>">
                                                            <button type="submit" class="btn btn-block btn-danger">
                                                                <i class="fas fa-trash"></i> Hapus
                                                            </button>
                                                        </form>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th>Kode Monitoring</th>
                                                <th>Nama Lahan</th>
                                                <th>Tipe Penanaman</th>
                                                <th>Progress Status Monitoring</th>
                                                <th>Periode Pengecekan</th>
                                                <th>Tanggal Tanam</th>
                                                <th>Tanggal Monitoring</th>
                                                <th>Luas Tanam (ha)</th>
                                                <th>Survival Rate (%)</th>
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
                                <div class="form-group">
                                    <label for="kode_monitoring">Kode Monitoring<code>*</code></label>
                                    <input type="text" name="kode_monitoring" class="form-control" id="kode_monitoring"
                                        value="<?= (new MonitoringPenanaman($pdo))->generateKode() ?>"
                                        placeholder="Masukkan kode monitoring">
                                </div>
                                <div class="form-group">
                                    <label for="id_tanah">Lahan<code>*</code></label>
                                    <select name="id_tanah" class="form-control" id="id_tanah">
                                        <option value="">-- Pilih Lahan --</option>
                                        <?php foreach ($tanahs as $la): ?>
                                            <option value="<?= $la['id'] ?>">
                                                <?= htmlspecialchars($la['nama_lahan']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="id_tipe_penanaman">Tipe Penanaman<code>*</code></label>
                                    <select name="id_tipe_penanaman" class="form-control" id="id_tipe_penanaman">
                                        <option value="">-- Pilih Tipe Penanaman --</option>
                                        <?php foreach ($tipePenanamans as $tp): ?>
                                            <option value="<?= $tp['id'] ?>">
                                                <?= htmlspecialchars($tp['nama']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="id_progress_status_monitoring">Progress Status Monitoring<code>*</code></label>
                                    <select name="id_progress_status_monitoring" class="form-control" id="id_progress_status_monitoring">
                                        <option value="">-- Pilih Progress Status Monitoring --</option>
                                        <?php foreach ($progressStatusMonitorings as $tipe): ?>
                                            <option value="<?= $tipe['id'] ?>">
                                                <?= htmlspecialchars($tipe['nama']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="periode_pengecekan">Periode Pengecekan<code>*</code></label>
                                    <input type="date" name="periode_pengecekan" class="form-control" id="periode_pengecekan">
                                </div>
                                <div class="form-group">
                                    <label for="tanggal_tanam">Tanggal Tanam<code>*</code></label>
                                    <input type="date" name="tanggal_tanam" class="form-control" id="tanggal_tanam">
                                </div>
                                <div class="form-group">
                                    <label for="tanggal_monitoring">Tanggal Monitoring<code>*</code></label>
                                    <input type="date" name="tanggal_monitoring" class="form-control" id="tanggal_monitoring">
                                </div>
                                <div class="form-group">
                                    <label for="luas_tanam_ha">Luas Tanam (ha)<code>*</code></label>
                                    <input type="number" name="luas_tanam_ha" class="form-control" id="luas_tanam_ha"
                                        placeholder="Masukkan Luas Tanam (ha)" min="0">
                                </div>
                                <div class="form-group">
                                    <label for="survival_rate_persen">Survival Rate (%)<code>*</code></label>
                                    <input type="number" name="survival_rate_persen" class="form-control" id="survival_rate_persen"
                                        placeholder="Masukkan Survival Rate (%)" min="0">
                                </div>
                                <div class="form-group">
                                    <label for="catatan">Catatan<code>*</code></label>
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
                                    <input type="text" name="kode_monitoring" class="form-control" id="edit_kode_monitoring"
                                        placeholder="Masukkan kode monitoring">
                                </div>
                                <div class="form-group">
                                    <label for="id_tanah">Lahan<code>*</code></label>
                                    <select name="id_tanah" class="form-control" id="edit_id_tanah">
                                        <option value="">-- Pilih Lahan --</option>
                                        <?php foreach ($tanahs as $la): ?>
                                            <option value="<?= $la['id'] ?>">
                                                <?= htmlspecialchars($la['nama_lahan']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="id_tipe_penanaman">Tipe Penanaman<code>*</code></label>
                                    <select name="id_tipe_penanaman" class="form-control" id="edit_id_tipe_penanaman">
                                        <option value="">-- Pilih Tipe Penanaman --</option>
                                        <?php foreach ($tipePenanamans as $tp): ?>
                                            <option value="<?= $tp['id'] ?>">
                                                <?= htmlspecialchars($tp['nama']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="id_progress_status_monitoring">Progress Status Monitoring<code>*</code></label>
                                    <select name="id_progress_status_monitoring" class="form-control" id="edit_id_progress_status_monitoring">
                                        <option value="">-- Pilih Progress Status Monitoring --</option>
                                        <?php foreach ($progressStatusMonitorings as $tipe): ?>
                                            <option value="<?= $tipe['id'] ?>">
                                                <?= htmlspecialchars($tipe['nama']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="periode_pengecekan">Periode Pengecekan<code>*</code></label>
                                    <input type="date" name="periode_pengecekan" class="form-control" id="edit_periode_pengecekan">
                                </div>
                                <div class="form-group">
                                    <label for="tanggal_tanam">Tanggal Tanam<code>*</code></label>
                                    <input type="date" name="tanggal_tanam" class="form-control" id="edit_tanggal_tanam">
                                </div>
                                <div class="form-group">
                                    <label for="tanggal_monitoring">Tanggal Monitoring<code>*</code></label>
                                    <input type="date" name="tanggal_monitoring" class="form-control" id="edit_tanggal_monitoring">
                                </div>
                                <div class="form-group">
                                    <label for="luas_tanam_ha">Luas Tanam (ha)<code>*</code></label>
                                    <input type="number" name="luas_tanam_ha" class="form-control" id="edit_luas_tanam_ha"
                                        placeholder="Masukkan Luas Tanam (ha)" min="0">
                                </div>
                                <div class="form-group">
                                    <label for="survival_rate_persen">Survival Rate (%)<code>*</code></label>
                                    <input type="number" name="survival_rate_persen" class="form-control" id="edit_survival_rate_persen"
                                        placeholder="Masukkan Survival Rate (%)" min="0">
                                </div>
                                <div class="form-group">
                                    <label for="catatan">Catatan<code>*</code></label>
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
                "responsive": true, "lengthChange": false, "autoWidth": false,
                "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
            }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
            $('#example2').DataTable({
                "paging": true,
                "lengthChange": false,
                "searching": false,
                "ordering": true,
                "info": true,
                "autoWidth": false,
                "responsive": true,
            });
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
                        id_tipe_penanaman: {
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
                        luas_tanam_ha: {
                            required: true
                        },
                        survival_rate_persen: {
                            required: true
                        },
                        catatan: {
                            required: true
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
                        id_tipe_penanaman: {
                            required: "Silahkan pilih Tipe Penanaman"
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
                        luas_tanam_ha: {
                            required: "Silahkan masukkan Luas Tanam (ha)"
                        },
                        survival_rate_persen: {
                            required: "Silahkan masukkan Survival Rate (%)"
                        },
                        catatan: {
                            required: "Silahkan masukkan Catatan"
                        },
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
            let id_tipe_penanaman = $(this).data("id_tipe_penanaman");
            let id_progress_status_monitoring = $(this).data("id_progress_status_monitoring");
            let periode_pengecekan = $(this).data("periode_pengecekan");
            let tanggal_tanam = $(this).data("tanggal_tanam");
            let tanggal_monitoring = $(this).data("tanggal_monitoring");
            let luas_tanam_ha = $(this).data("luas_tanam_ha");
            let survival_rate_persen = $(this).data("survival_rate_persen");
            let catatan = $(this).data("catatan");
            let status = $(this).data("status");

            $("#edit_id").val(id);
            $("#edit_kode_monitoring").val(kode_monitoring);
            $("#edit_id_tanah").val(id_tanah);
            $("#edit_id_tipe_penanaman").val(id_tipe_penanaman);
            $("#edit_id_progress_status_monitoring").val(id_progress_status_monitoring);
            $("#edit_periode_pengecekan").val(periode_pengecekan);
            $("#edit_tanggal_tanam").val(tanggal_tanam);
            $("#edit_tanggal_monitoring").val(tanggal_monitoring);
            $("#edit_luas_tanam_ha").val(luas_tanam_ha);
            $("#edit_survival_rate_persen").val(survival_rate_persen);
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
                title: 'Berhasil',
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
</body>

</html>