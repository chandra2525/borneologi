<?php

require_once '../../app/config/database.php';
require_once '../../app/controllers/InfrastrukturObservasiController.php';
require_once '../../app/core/session.php';
require_once '../../app/core/csrf.php';
require_once "../../app/core/auth.php";
require_once '../../app/helpers/escape.php';

secureSessionStart();
checkAuth("non_dashboard");

$controller = new InfrastrukturObservasiController($pdo);
$infrasturkturObservasis = $controller->index();
$tanahs = $controller->getTanah();
$aksesPerjalanans = $controller->getAksesPerjalanan();
$kondisiJalans = $controller->getKondisiJalan();

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
        $menu = "infrastruktur_observasi";
        include "../feature/sidebar.php";
        ?>

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1>Data Infrastruktur Observasi</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="#">Master Data</a></li>
                                <li class="breadcrumb-item active">Data Infrastruktur Observasi</li>
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
                                    <h3 class="card-title col-9">Berikut adalah list dari Data Infrastruktur Observasi</h3>
                                    <button class="col-3 btn btn-block btn-success" data-toggle="modal"
                                        data-target="#modalTambah">
                                        Tambah Infrastruktur Observasi
                                    </button>
                                </div>
                                <!-- /.card-header -->
                                <div class="card-body">
                                    <table id="example1" class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th>Nama Lahan</th>
                                                <th>Periode Pengecekan</th>
                                                <th>Akses Perjalanan</th>
                                                <th>Kondisi Jalan</th>
                                                <th>Jarak ke Jalan (km)</th>
                                                <th>Ada Jembatan</th>
                                                <th>Ada Listrik</th>
                                                <th>Ada Internet</th>
                                                <th>Sinyal Seluler</th>
                                                <th>Catatan</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($infrasturkturObservasis as $infrastrukturObservasi): ?>
                                                <tr>
                                                    <td><?= htmlspecialchars($infrastrukturObservasi['nama_lahan']) ?></td>
                                                    <td><?= htmlspecialchars($infrastrukturObservasi['periode_pengecekan']) ?></td>
                                                    <td><?= htmlspecialchars($infrastrukturObservasi['nama_akses_perjalanan']) ?></td>
                                                    <td><?= htmlspecialchars($infrastrukturObservasi['nama_kondisi_jalan']) ?></td>
                                                    <td><?= htmlspecialchars($infrastrukturObservasi['jarak_ke_jalan_km']) ?></td>
                                                    <td><?= $infrastrukturObservasi['ada_jembatan'] ? 'Iya' : 'Tidak' ?></td>
                                                    <td><?= $infrastrukturObservasi['ada_listrik'] ? 'Iya' : 'Tidak' ?></td>
                                                    <td><?= $infrastrukturObservasi['ada_internet'] ? 'Iya' : 'Tidak' ?></td>
                                                    <td><?= match ($infrastrukturObservasi['sinyal_seluler']) {
                                                        'tidak_ada' => 'Tidak Ada',
                                                        'lemah' => 'Lemah',
                                                        'sedang' => 'Sedang',
                                                        'kuat' => 'Kuat',
                                                        default => 'Tidak Ada',
                                                    } ?></td>
                                                    <td><?= htmlspecialchars($infrastrukturObservasi['catatan']) ?></td>
                                                    <td class="row">
                                                        <div class="col">
                                                            <button class="btn btn-block btn-info btn-edit"
                                                                data-id="<?= $infrastrukturObservasi['id'] ?>"
                                                                data-id_tanah="<?= htmlspecialchars($infrastrukturObservasi['id_tanah']) ?>"
                                                                data-periode_pengecekan="<?= htmlspecialchars($infrastrukturObservasi['periode_pengecekan']) ?>"
                                                                data-id_akses_perjalanan="<?= htmlspecialchars($infrastrukturObservasi['id_akses_perjalanan']) ?>"
                                                                data-id_kondisi_jalan="<?= htmlspecialchars($infrastrukturObservasi['id_kondisi_jalan']) ?>"
                                                                data-jarak_ke_jalan_km="<?= htmlspecialchars($infrastrukturObservasi['jarak_ke_jalan_km']) ?>"
                                                                data-ada_jembatan="<?= htmlspecialchars($infrastrukturObservasi['ada_jembatan']) ?>"
                                                                data-ada_listrik="<?= htmlspecialchars($infrastrukturObservasi['ada_listrik']) ?>"
                                                                data-ada_internet="<?= htmlspecialchars($infrastrukturObservasi['ada_internet']) ?>"
                                                                data-sinyal_seluler="<?= htmlspecialchars($infrastrukturObservasi['sinyal_seluler']) ?>"
                                                                data-catatan="<?= htmlspecialchars($infrastrukturObservasi['catatan']) ?>"
                                                                data-toggle="modal" data-target="#modalEdit">
                                                                <i class="fas fa-edit"></i> Edit
                                                            </button>
                                                        </div>

                                                        <form method="POST" action="delete.php" class="form-delete col">
                                                            <?= csrfField() ?>
                                                            <input type="hidden" name="id" value="<?= $infrastrukturObservasi['id'] ?>">
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
                                                <th>Nama Lahan</th>
                                                <th>Periode Pengecekan</th>
                                                <th>Akses Perjalanan</th>
                                                <th>Kondisi Jalan</th>
                                                <th>Jarak ke Jalan (km)</th>
                                                <th>Ada Jembatan</th>
                                                <th>Ada Listrik</th>
                                                <th>Ada Internet</th>
                                                <th>Sinyal Seluler</th>
                                                <th>Catatan</th>
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
                            <h4 class="modal-title">Tambah Infrastruktur Observasi</h4>
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                        </div>
                        <form id="formTambah" method="POST" action="store.php">
                            <?= csrfField() ?>
                            <div class="modal-body">
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
                                    <label for="periode_pengecekan">Periode Pengecekan<code>*</code></label>
                                    <input type="date" name="periode_pengecekan" class="form-control" id="periode_pengecekan">
                                </div>
                                <div class="form-group">
                                    <label for="id_akses_perjalanan">Akses Perjalanan<code>*</code></label>
                                    <select name="id_akses_perjalanan" class="form-control" id="id_akses_perjalanan">
                                        <option value="">-- Pilih Akses Perjalanan --</option>
                                        <?php foreach ($aksesPerjalanans as $ne): ?>
                                            <option value="<?= $ne['id'] ?>">
                                                <?= htmlspecialchars($ne['nama']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="id_kondisi_jalan">Kondisi Jalan<code>*</code></label>
                                    <select name="id_kondisi_jalan" class="form-control" id="id_kondisi_jalan">
                                        <option value="">-- Pilih Kondisi Jalan --</option>
                                        <?php foreach ($kondisiJalans as $tipe): ?>
                                            <option value="<?= $tipe['id'] ?>">
                                                <?= htmlspecialchars($tipe['nama']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="jarak_ke_jalan_km">Jarak ke Jalan (km)<code>*</code></label>
                                    <input type="number" name="jarak_ke_jalan_km" class="form-control" id="jarak_ke_jalan_km"
                                        placeholder="Masukkan Jarak ke Jalan (km)" min="0" step="0.01">
                                </div>
                                <div class="form-group">
                                    <label>Ada Jembatan<code>*</code></label>
                                    <div class="form-group row">
                                        <div class="col-sm-2">
                                            <div class="custom-control custom-radio">
                                                <input class="custom-control-input" type="radio" id="add_ada_jembatan"
                                                    name="ada_jembatan" value="1" checked>
                                                <label for="add_ada_jembatan" class="custom-control-label">Ada</label>
                                            </div>
                                        </div>
                                        <div class="col-sm-2">
                                            <div class="custom-control custom-radio">
                                                <input class="custom-control-input" type="radio"
                                                    id="add_tidak_ada_jembatan" name="ada_jembatan" value="0">
                                                <label for="add_tidak_ada_jembatan"
                                                    class="custom-control-label">Tidak Ada</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>Ada Listrik<code>*</code></label>
                                    <div class="form-group row">
                                        <div class="col-sm-2">
                                            <div class="custom-control custom-radio">
                                                <input class="custom-control-input" type="radio" id="add_ada_listrik"
                                                    name="ada_listrik" value="1" checked>
                                                <label for="add_ada_listrik" class="custom-control-label">Ada</label>
                                            </div>
                                        </div>
                                        <div class="col-sm-2">
                                            <div class="custom-control custom-radio">
                                                <input class="custom-control-input" type="radio"
                                                    id="add_tidak_ada_listrik" name="ada_listrik" value="0">
                                                <label for="add_tidak_ada_listrik"
                                                    class="custom-control-label">Tidak Ada</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>Ada Internet<code>*</code></label>
                                    <div class="form-group row">
                                        <div class="col-sm-2">
                                            <div class="custom-control custom-radio">
                                                <input class="custom-control-input" type="radio" id="add_ada_internet"
                                                    name="ada_internet" value="1" checked>
                                                <label for="add_ada_internet" class="custom-control-label">Ada</label>
                                            </div>
                                        </div>
                                        <div class="col-sm-2">
                                            <div class="custom-control custom-radio">
                                                <input class="custom-control-input" type="radio"
                                                    id="add_tidak_ada_internet" name="ada_internet" value="0">
                                                <label for="add_tidak_ada_internet"
                                                    class="custom-control-label">Tidak Ada</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="sinyal_seluler">Sinyal Seluler<code>*</code></label>
                                    <select name="sinyal_seluler" class="form-control" id="sinyal_seluler">
                                        <option value="">-- Pilih Sinyal Seluler --</option>
                                        <option value="tidak_ada">Tidak Ada</option>
                                        <option value="lemah">Lemah</option>
                                        <option value="sedang">Sedang</option>
                                        <option value="kuat">Kuat</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="catatan">Catatan<code>*</code></label>
                                    <textarea name="catatan" class="form-control" id="catatan"
                                        placeholder="Masukkan Catatan"></textarea>
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
                            <h4 class="modal-title">Edit Infrastruktur Observasi</h4>
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                        </div>

                        <form id="formEdit" method="POST" action="update.php">
                            <?= csrfField() ?>
                            <input type="hidden" name="id" id="edit_id">
                            <div class="modal-body">
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
                                    <label for="periode_pengecekan">Periode Pengecekan<code>*</code></label>
                                    <input type="date" name="periode_pengecekan" class="form-control" id="edit_periode_pengecekan">
                                </div>
                                <div class="form-group">
                                    <label for="id_akses_perjalanan">Akses Perjalanan<code>*</code></label>
                                    <select name="id_akses_perjalanan" class="form-control" id="edit_id_akses_perjalanan">
                                        <option value="">-- Pilih Akses Perjalanan --</option>
                                        <?php foreach ($aksesPerjalanans as $ne): ?>
                                            <option value="<?= $ne['id'] ?>">
                                                <?= htmlspecialchars($ne['nama']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="id_kondisi_jalan">Kondisi Jalan<code>*</code></label>
                                    <select name="id_kondisi_jalan" class="form-control" id="edit_id_kondisi_jalan">
                                        <option value="">-- Pilih Kondisi Jalan --</option>
                                        <?php foreach ($kondisiJalans as $tipe): ?>
                                            <option value="<?= $tipe['id'] ?>">
                                                <?= htmlspecialchars($tipe['nama']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="jarak_ke_jalan_km">Jarak ke Jalan (km)<code>*</code></label>
                                    <input type="number" name="jarak_ke_jalan_km" class="form-control" id="edit_jarak_ke_jalan_km"
                                        placeholder="Masukkan Jarak ke Jalan (km)" min="0" step="0.01">
                                </div>
                                <div class="form-group">
                                    <label>Ada Jembatan<code>*</code></label>
                                    <div class="form-group row">
                                        <div class="col-sm-2">
                                            <div class="custom-control custom-radio">
                                                <input class="custom-control-input" type="radio" id="edit_ada_jembatan"
                                                    name="ada_jembatan" value="1" checked>
                                                <label for="edit_ada_jembatan" class="custom-control-label">Ada</label>
                                            </div>
                                        </div>
                                        <div class="col-sm-2">
                                            <div class="custom-control custom-radio">
                                                <input class="custom-control-input" type="radio"
                                                    id="edit_tidak_ada_jembatan" name="ada_jembatan" value="0">
                                                <label for="edit_tidak_ada_jembatan"
                                                    class="custom-control-label">Tidak Ada</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>Ada Listrik<code>*</code></label>
                                    <div class="form-group row">
                                        <div class="col-sm-2">
                                            <div class="custom-control custom-radio">
                                                <input class="custom-control-input" type="radio" id="edit_ada_listrik"
                                                    name="ada_listrik" value="1" checked>
                                                <label for="edit_ada_listrik" class="custom-control-label">Ada</label>
                                            </div>
                                        </div>
                                        <div class="col-sm-2">
                                            <div class="custom-control custom-radio">
                                                <input class="custom-control-input" type="radio"
                                                    id="edit_tidak_ada_listrik" name="ada_listrik" value="0">
                                                <label for="edit_tidak_ada_listrik"
                                                    class="custom-control-label">Tidak Ada</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>Ada Internet<code>*</code></label>
                                    <div class="form-group row">
                                        <div class="col-sm-2">
                                            <div class="custom-control custom-radio">
                                                <input class="custom-control-input" type="radio" id="edit_ada_internet"
                                                    name="ada_internet" value="1" checked>
                                                <label for="edit_ada_internet" class="custom-control-label">Ada</label>
                                            </div>
                                        </div>
                                        <div class="col-sm-2">
                                            <div class="custom-control custom-radio">
                                                <input class="custom-control-input" type="radio"
                                                    id="edit_tidak_ada_internet" name="ada_internet" value="0">
                                                <label for="edit_tidak_ada_internet"
                                                    class="custom-control-label">Tidak Ada</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="sinyal_seluler">Sinyal Seluler<code>*</code></label>
                                    <select name="sinyal_seluler" class="form-control" id="edit_sinyal_seluler">
                                        <option value="">-- Pilih Sinyal Seluler --</option>
                                        <option value="tidak_ada">Tidak Ada</option>
                                        <option value="lemah">Lemah</option>
                                        <option value="sedang">Sedang</option>
                                        <option value="kuat">Kuat</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="catatan">Catatan<code>*</code></label>
                                    <textarea name="catatan" class="form-control" id="edit_catatan"
                                        placeholder="Masukkan Catatan"></textarea>
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
                        id_tanah: {
                            required: true
                        },
                        periode_pengecekan: {
                            required: true
                        },
                        id_akses_perjalanan: {
                            required: true
                        },
                        id_kondisi_jalan: {
                            required: true
                        },
                        jarak_ke_jalan_km: {
                            required: true
                        },
                        ada_jembatan: {
                            required: true
                        },
                        ada_listrik: {
                            required: true
                        },
                        ada_internet: {
                            required: true
                        },
                        sinyal_seluler: {
                            required: true
                        },
                        catatan: {
                            required: true
                        }
                    },
                    messages: {
                        id_tanah: {
                            required: "Silahkan pilih Tanah"
                        },
                        periode_pengecekan: {
                            required: "Silahkan pilih Periode Pengecekan"
                        },
                        id_akses_perjalanan: {
                            required: "Silahkan pilih Akses Perjalanan"
                        },
                        id_kondisi_jalan : {
                            required: "Silahkan pilih Kondisi Jalan"
                        },
                        jarak_ke_jalan_km: {
                            required: "Silahkan masukkan Jarak ke Jalan (km)"
                        },
                        ada_jembatan: {
                            required: "Silahkan pilih Ada Jembatan"
                        },
                        ada_listrik: {
                            required: "Silahkan pilih Ada Listrik"
                        },
                        ada_internet: {
                            required: "Silahkan pilih Ada Internet"
                        },
                        sinyal_seluler: {
                            required: "Silahkan pilih Sinyal Seluler"
                        },
                        catatan: {
                            required: "Silahkan masukkan Catatan"
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
            let id_tanah = $(this).data("id_tanah");
            let periode_pengecekan = $(this).data("periode_pengecekan");
            let id_akses_perjalanan = $(this).data("id_akses_perjalanan");
            let id_kondisi_jalan = $(this).data("id_kondisi_jalan");
            let jarak_ke_jalan_km = $(this).data("jarak_ke_jalan_km");
            let ada_jembatan = $(this).data("ada_jembatan");
            let ada_listrik = $(this).data("ada_listrik");
            let ada_internet = $(this).data("ada_internet");
            let sinyal_seluler = $(this).data("sinyal_seluler");
            let catatan = $(this).data("catatan");

            $("#edit_id").val(id);
            $("#edit_id_tanah").val(id_tanah);
            $("#edit_periode_pengecekan").val(periode_pengecekan);
            $("#edit_id_akses_perjalanan").val(id_akses_perjalanan);
            $("#edit_id_kondisi_jalan").val(id_kondisi_jalan);
            $("#edit_jarak_ke_jalan_km").val(jarak_ke_jalan_km);
            $("input[name='ada_jembatan'][value='" + ada_jembatan + "']").prop("checked", true);
            $("input[name='ada_listrik'][value='" + ada_listrik + "']").prop("checked", true);
            $("input[name='ada_internet'][value='" + ada_internet + "']").prop("checked", true);
            $("#edit_sinyal_seluler").val(sinyal_seluler);
            $("#edit_catatan").val(catatan);
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
            showToast("Data Infrastruktur Observasi berhasil ditambahkan", "created");
        }
        if (success === "updated") {
            showToast("Data Infrastruktur Observasi berhasil diperbarui", "updated");
        }
        if (success === "deleted") {
            showToast("Data Infrastruktur Observasi berhasil dihapus", "deleted");
        }
    </script>

    <script>
        document.getElementById('periode_pengecekan').addEventListener('focus', function () {
            this.showPicker();
        });
        document.getElementById('edit_periode_pengecekan').addEventListener('focus', function () {
            this.showPicker();
        });
    </script>
</body>

</html>