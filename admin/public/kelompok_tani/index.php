<?php

require_once '../../app/config/database.php';
require_once '../../app/controllers/KelompokTaniController.php';
require_once '../../app/core/session.php';
require_once '../../app/core/csrf.php';
require_once "../../app/core/auth.php";
require_once '../../app/helpers/escape.php';

secureSessionStart();
checkAuth("non_dashboard");

$controller = new KelompokTaniController($pdo);
$kelompokTanis = $controller->index();
$kategoriKelompoks = $controller->getKategoriKelompok();
$desas = $controller->getDesa();
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
        $menu = "kelompok_tani";
        include "../feature/sidebar.php";
        ?>

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1>Data Kelompok Tani</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="#">Master Data</a></li>
                                <li class="breadcrumb-item active">Data Kelompok Tani</li>
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
                                    <h3 class="card-title col-9">Berikut adalah list dari Data Kelompok Tani</h3>
                                    <button class="col-3 btn btn-block btn-success" data-toggle="modal"
                                        data-target="#modalTambah">
                                        Tambah Kelompok Tani
                                    </button>
                                </div>
                                <!-- /.card-header -->
                                <div class="card-body">
                                    <table id="example1" class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th>Kode Kelompok</th>
                                                <th>Nama Kelompok</th>
                                                <th>Kategori Kelompok</th>
                                                <th>Desa</th>
                                                <th>Alamat</th>
                                                <th>Akses Perjalanan</th>
                                                <th>Kondisi Jalan</th>
                                                <th>Tahun Bentuk</th>
                                                <th>Nomor SK</th>
                                                <th>Tanggal SK</th>
                                                <th>Status Kelompok Tani</th>
                                                <th>Status Aktif</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($kelompokTanis as $kelompokTani): ?>
                                                <tr>
                                                    <td><?= htmlspecialchars($kelompokTani['kode_kelompok']) ?></td>
                                                    <td><?= htmlspecialchars($kelompokTani['nama_kelompok']) ?></td>
                                                    <td><?= htmlspecialchars($kelompokTani['nama_kategori_kelompok']) ?></td>
                                                    <td><?= htmlspecialchars($kelompokTani['nama_desa']) ?></td>
                                                    <td><?= htmlspecialchars($kelompokTani['alamat']) ?></td>
                                                    <td><?= htmlspecialchars($kelompokTani['nama_akses_perjalanan']) ?></td>
                                                    <td><?= htmlspecialchars($kelompokTani['nama_kondisi_jalan']) ?></td>
                                                    <td><?= htmlspecialchars($kelompokTani['tahun_bentuk']) ?></td>
                                                    <td><?= htmlspecialchars($kelompokTani['nomor_sk']) ?></td>
                                                    <td><?= htmlspecialchars($kelompokTani['tanggal_sk']) ?></td>
                                                    <td><?= $kelompokTani['status_kelompok'] == 'aktif' ? 'Aktif' : 'Nonaktif' ?>
                                                    </td>
                                                    <td><?= $kelompokTani['is_active'] ? 'Aktif' : 'Nonaktif' ?></td>
                                                    <td class="row">
                                                        <div class="col">
                                                            <button class="btn btn-block btn-info btn-edit"
                                                                data-id="<?= $kelompokTani['id'] ?>"
                                                                data-kode_kelompok="<?= htmlspecialchars($kelompokTani['kode_kelompok']) ?>"
                                                                data-nama_kelompok="<?= htmlspecialchars($kelompokTani['nama_kelompok']) ?>"
                                                                data-id_kategori_kelompok="<?= htmlspecialchars($kelompokTani['id_kategori_kelompok']) ?>"
                                                                data-id_desa="<?= htmlspecialchars($kelompokTani['id_desa']) ?>"
                                                                data-alamat="<?= htmlspecialchars($kelompokTani['alamat']) ?>"
                                                                data-id_akses_perjalanan="<?= htmlspecialchars($kelompokTani['id_akses_perjalanan']) ?>"
                                                                data-id_kondisi_jalan="<?= htmlspecialchars($kelompokTani['id_kondisi_jalan']) ?>"
                                                                data-tahun_bentuk="<?= htmlspecialchars($kelompokTani['tahun_bentuk']) ?>"
                                                                data-nomor_sk="<?= htmlspecialchars($kelompokTani['nomor_sk']) ?>"
                                                                data-tanggal_sk="<?= htmlspecialchars($kelompokTani['tanggal_sk']) ?>"
                                                                data-status_kelompok="<?= htmlspecialchars($kelompokTani['status_kelompok']) ?>"
                                                                data-status="<?= $kelompokTani['is_active'] ?>"
                                                                data-toggle="modal" data-target="#modalEdit">
                                                                <i class="fas fa-edit"></i> Edit
                                                            </button>
                                                        </div>

                                                        <form method="POST" action="delete.php" class="form-delete col">
                                                            <?= csrfField() ?>
                                                            <input type="hidden" name="id" value="<?= $kelompokTani['id'] ?>">
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
                                                <th>Kode Kelompok</th>
                                                <th>Nama Kelompok</th>
                                                <th>Kategori Kelompok</th>
                                                <th>Desa</th>
                                                <th>Alamat</th>
                                                <th>Akses Perjalanan</th>
                                                <th>Kondisi Jalan</th>
                                                <th>Tahun Bentuk</th>
                                                <th>Nomor SK</th>
                                                <th>Tanggal SK</th>
                                                <th>Status Kelompok Tani</th>
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
                            <h4 class="modal-title">Tambah Kelompok Tani</h4>
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                        </div>
                        <form id="formTambah" method="POST" action="store.php">
                            <?= csrfField() ?>
                            <div class="modal-body">
                                <div class="form-group">
                                    <label for="kode_kelompok">Kode Kelompok<code>*</code></label>
                                    <input type="text" name="kode_kelompok" class="form-control" id="kode_kelompok"
                                        value="<?= (new KelompokTani($pdo))->generateKode() ?>"
                                        placeholder="Masukkan Kode Kelompok">
                                </div>
                                <div class="form-group">
                                    <label for="nama_kelompok">Nama Kelompok<code>*</code></label>
                                    <input type="text" name="nama_kelompok" class="form-control" id="nama_kelompok"
                                        placeholder="Masukkan Nama Kelompok" minlength="2" maxlength="100">
                                </div>
                                <div class="form-group">
                                    <label for="id_kategori_kelompok">Kategori Kelompok<code>*</code></label>
                                    <select name="id_kategori_kelompok" class="form-control" id="id_kategori_kelompok">
                                        <option value="">-- Pilih Kategori Kelompok --</option>
                                        <?php foreach ($kategoriKelompoks as $kk): ?>
                                            <option value="<?= $kk['id'] ?>">
                                                <?= htmlspecialchars($kk['nama']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="id_desa">Desa<code>*</code></label>
                                    <select name="id_desa" class="form-control" id="id_desa">
                                        <option value="">-- Pilih Desa --</option>
                                        <?php foreach ($desas as $d): ?>
                                            <option value="<?= $d['id'] ?>">
                                                <?= htmlspecialchars($d['nama_desa']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="alamat">Alamat<code>*</code></label>
                                    <textarea name="alamat" class="form-control" id="alamat"
                                        placeholder="Masukkan Alamat"></textarea>
                                </div>
                                <div class="form-group">
                                    <label for="id_akses_perjalanan">Akses Perjalanan<code>*</code></label>
                                    <select name="id_akses_perjalanan" class="form-control" id="id_akses_perjalanan">
                                        <option value="">-- Pilih Akses Perjalanan --</option>
                                        <?php foreach ($aksesPerjalanans as $ap): ?>
                                            <option value="<?= $ap['id'] ?>">
                                                <?= htmlspecialchars($ap['nama']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="id_kondisi_jalan">Kondisi Jalan<code>*</code></label>
                                    <select name="id_kondisi_jalan" class="form-control" id="id_kondisi_jalan">
                                        <option value="">-- Pilih Kondisi Jalan --</option>
                                        <?php foreach ($kondisiJalans as $kj): ?>
                                            <option value="<?= $kj['id'] ?>">
                                                <?= htmlspecialchars($kj['nama']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="tahun_bentuk">Tahun Bentuk<code>*</code></label>
                                    <input type="number" name="tahun_bentuk" class="form-control" id="tahun_bentuk"
                                        placeholder="Masukkan Tahun Bentuk" min="1900" max="<?= date('Y') ?>" minlength="4" maxlength="4">
                                </div>
                                <div class="form-group">
                                    <label for="nomor_sk">Nomor SK<code>*</code></label>
                                    <input type="number" name="nomor_sk" class="form-control" id="nomor_sk"
                                        placeholder="Masukkan Nomor SK" minlength="1" maxlength="100">
                                </div>
                                <div class="form-group">
                                    <label for="tanggal_sk">Tanggal SK<code>*</code></label>
                                    <input type="date" name="tanggal_sk" class="form-control" id="tanggal_sk"
                                        placeholder="Masukkan Tanggal SK">
                                </div>
                                <div class="form-group">
                                    <label>Status Kelompok Tani<code>*</code></label>
                                    <div class="form-group row">
                                        <div class="col-sm-2">
                                            <div class="custom-control custom-radio">
                                                <input class="custom-control-input" type="radio"
                                                    id="add_status_kelompok_aktif" name="status_kelompok" value="aktif"
                                                    checked>
                                                <label for="add_status_kelompok_aktif"
                                                    class="custom-control-label">Aktif</label>
                                            </div>
                                        </div>
                                        <div class="col-sm-2">
                                            <div class="custom-control custom-radio">
                                                <input class="custom-control-input" type="radio"
                                                    id="add_status_kelompok_nonaktif" name="status_kelompok"
                                                    value="nonaktif">
                                                <label for="add_status_kelompok_nonaktif"
                                                    class="custom-control-label">Nonaktif</label>
                                            </div>
                                        </div>
                                    </div>
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
                            <h4 class="modal-title">Edit Kelompok Tani</h4>
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                        </div>

                        <form id="formEdit" method="POST" action="update.php">
                            <?= csrfField() ?>
                            <input type="hidden" name="id" id="edit_id">
                            <div class="modal-body">
                                <div class="form-group">
                                    <label for="kode_kelompok">Kode Kelompok<code>*</code></label>
                                    <input type="text" name="kode_kelompok" id="edit_kode_kelompok" class="form-control" minlength="2"
                                        maxlength="50">
                                </div>
                                <div class="form-group">
                                    <label for="nama_kelompok">Nama Kelompok<code>*</code></label>
                                    <input type="text" name="nama_kelompok" id="edit_nama_kelompok" class="form-control" minlength="2"
                                        maxlength="100">
                                </div>
                                <div class="form-group">
                                    <label for="edit_id_kategori_kelompok">Kategori Kelompok<code>*</code></label>
                                    <select name="id_kategori_kelompok" class="form-control" id="edit_id_kategori_kelompok">
                                        <option value="">-- Pilih Kategori Kelompok --</option>
                                        <?php foreach ($kategoriKelompoks as $kk): ?>
                                            <option value="<?= $kk['id'] ?>">
                                                <?= htmlspecialchars($kk['nama']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="edit_id_desa">Desa<code>*</code></label>
                                    <select name="id_desa" class="form-control" id="edit_id_desa">
                                        <option value="">-- Pilih Desa --</option>
                                        <?php foreach ($desas as $d): ?>
                                            <option value="<?= $d['id'] ?>">
                                                <?= htmlspecialchars($d['nama_desa']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="alamat">Alamat<code>*</code></label>
                                    <textarea name="alamat" class="form-control" id="edit_alamat"
                                        placeholder="Masukkan Alamat"></textarea>
                                </div>
                                <div class="form-group">
                                    <label for="edit_id_akses_perjalanan">Akses Perjalanan<code>*</code></label>
                                    <select name="id_akses_perjalanan" class="form-control" id="edit_id_akses_perjalanan">
                                        <option value="">-- Pilih Akses Perjalanan --</option>
                                        <?php foreach ($aksesPerjalanans as $ap): ?>
                                            <option value="<?= $ap['id'] ?>">
                                                <?= htmlspecialchars($ap['nama']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="edit_id_kondisi_jalan">Kondisi Jalan<code>*</code></label>
                                    <select name="id_kondisi_jalan" class="form-control" id="edit_id_kondisi_jalan">
                                        <option value="">-- Pilih Kondisi Jalan --</option>
                                        <?php foreach ($kondisiJalans as $kj): ?>
                                            <option value="<?= $kj['id'] ?>">
                                                <?= htmlspecialchars($kj['nama']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="tahun_bentuk">Tahun Bentuk<code>*</code></label>
                                    <input type="number" name="tahun_bentuk" class="form-control" id="edit_tahun_bentuk"
                                        placeholder="Masukkan Tahun Bentuk" min="1900" max="<?= date('Y') ?>" minlength="4" maxlength="4">
                                </div>
                                <div class="form-group">
                                    <label for="nomor_sk">Nomor SK<code>*</code></label>
                                    <input type="number" name="nomor_sk" class="form-control" id="edit_nomor_sk"
                                        placeholder="Masukkan Nomor SK" minlength="1" maxlength="100">
                                </div>
                                <div class="form-group">
                                    <label for="tanggal_sk">Tanggal SK<code>*</code></label>
                                    <input type="date" name="tanggal_sk" class="form-control" id="edit_tanggal_sk"
                                        placeholder="Masukkan Tanggal SK">
                                </div>
                                <div class="form-group">
                                    <label>Status Kelompok Tani<code>*</code></label>
                                    <div class="form-group row">
                                        <div class="col-sm-2">
                                            <div class="custom-control custom-radio">
                                                <input class="custom-control-input" type="radio"
                                                    id="edit_status_kelompok_aktif" name="status_kelompok" value="aktif"
                                                    checked>
                                                <label for="edit_status_kelompok_aktif"
                                                    class="custom-control-label">Aktif</label>
                                            </div>
                                        </div>
                                        <div class="col-sm-2">
                                            <div class="custom-control custom-radio">
                                                <input class="custom-control-input" type="radio"
                                                    id="edit_status_kelompok_nonaktif" name="status_kelompok"
                                                    value="nonaktif">
                                                <label for="edit_status_kelompok_nonaktif"
                                                    class="custom-control-label">Nonaktif</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>Status Aktif <code>*</code></label>
                                    <div class="custom-control custom-radio">
                                        <input class="custom-control-input edit_status" type="radio"
                                            id="edit_status_aktif" name="is_active" value="1">
                                        <label for="edit_status_aktif" class="custom-control-label">
                                            Aktif
                                        </label>
                                    </div>
                                    <div class="custom-control custom-radio">
                                        <input class="custom-control-input edit_status" type="radio"
                                            id="edit_status_nonaktif" name="is_active" value="0">
                                        <label for="edit_status_nonaktif" class="custom-control-label">
                                            Nonaktif
                                        </label>
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
                        kode_kelompok: {
                            required: true
                        },
                        nama_kelompok: {
                            required: true
                        },
                        id_kategori_kelompok: {
                            required: true
                        },
                        id_desa: {
                            required: true
                        },
                        alamat: {
                            required: true
                        },
                        id_akses_perjalanan: {
                            required: true
                        },
                        id_kondisi_jalan: {
                            required: true
                        },
                        tahun_bentuk: {
                            required: true
                        },
                        nomor_sk: {
                            required: true
                        },
                        tanggal_sk: {
                            required: true
                        },
                        status_kelompok: {
                            required: true
                        },
                        is_active: {
                            required: true
                        }
                    },
                    messages: {
                        kode_kelompok: {
                            required: "Silahkan masukkan Kode Kelompok"
                        },
                        nama_kelompok: {
                            required: "Silahkan masukkan Nama Kelompok"
                        },
                        id_kategori_kelompok: {
                            required: "Silahkan pilih Kategori Kelompok"
                        },
                        id_desa: {
                            required: "Silahkan pilih Desa"
                        },
                        alamat: {
                            required: "Silahkan masukkan Alamat"
                        },
                        id_akses_perjalanan: {
                            required: "Silahkan pilih Akses Perjalanan"
                        },
                        id_kondisi_jalan: {
                            required: "Silahkan pilih Kondisi Jalan"
                        },
                        tahun_bentuk: {
                            required: "Silahkan masukkan Tahun Bentuk"
                        },
                        nomor_sk: {
                            required: "Silahkan masukkan Nomor SK"
                        },
                        tanggal_sk: {
                            required: "Silahkan pilih Tanggal SK"
                        },
                        status_kelompok: {
                            required: "Silahkan pilih Status Kelompok Tani"
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
            let kode_kelompok = $(this).data("kode_kelompok");
            let nama_kelompok = $(this).data("nama_kelompok");
            let id_kategori_kelompok = $(this).data("id_kategori_kelompok");
            let id_desa = $(this).data("id_desa");
            let alamat = $(this).data("alamat");
            let id_akses_perjalanan = $(this).data("id_akses_perjalanan");
            let id_kondisi_jalan = $(this).data("id_kondisi_jalan");
            let tahun_bentuk = $(this).data("tahun_bentuk");
            let nomor_sk = $(this).data("nomor_sk");
            let tanggal_sk = $(this).data("tanggal_sk");
            let status_kelompok = $(this).data("status_kelompok");
            let status = $(this).data("status");

            $("#edit_id").val(id);
            $("#edit_kode_kelompok").val(kode_kelompok);
            $("#edit_nama_kelompok").val(nama_kelompok);
            $("#edit_id_kategori_kelompok").val(id_kategori_kelompok);
            $("#edit_id_desa").val(id_desa);
            $("#edit_alamat").val(alamat);
            $("#edit_id_akses_perjalanan").val(id_akses_perjalanan);
            $("#edit_id_kondisi_jalan").val(id_kondisi_jalan);
            $("#edit_tahun_bentuk").val(tahun_bentuk);
            $("#edit_nomor_sk").val(nomor_sk);
            $("#edit_tanggal_sk").val(tanggal_sk);
            $("input[name='status_kelompok'][value='" + status_kelompok + "']").prop("checked", true);
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
            showToast("Data Kelompok Tani berhasil ditambahkan", "created");
        }
        if (success === "updated") {
            showToast("Data Kelompok Tani berhasil diperbarui", "updated");
        }
        if (success === "deleted") {
            showToast("Data Kelompok Tani berhasil dihapus", "deleted");
        }
    </script>

    <script>
        document.getElementById('tanggal_sk').addEventListener('focus', function () {
            this.showPicker();
        });
        document.getElementById('edit_tanggal_sk').addEventListener('focus', function () {
            this.showPicker();
        });
    </script>
</body>

</html>