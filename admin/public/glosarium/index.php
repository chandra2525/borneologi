<?php

require_once '../../app/config/database.php';
require_once '../../app/controllers/GlosariumController.php';
require_once '../../app/core/session.php';
require_once '../../app/core/csrf.php';
require_once "../../app/core/auth.php";
require_once '../../app/helpers/escape.php';
require_once "../../app/core/permission.php";

secureSessionStart();
checkAuth("non_dashboard");

$controller = new GlosariumController($pdo);
$glosariums = $controller->index();
Permission::authorize($pdo, 'Glosarium', 'view');

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
        $menu = "glosarium";
        include "../feature/sidebar.php";
        ?>

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1>Data Glosarium</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="#">Master Data</a></li>
                                <li class="breadcrumb-item active">Data Glosarium</li>
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
                                    <h3 class="card-title col-9">Berikut adalah list dari Data Glosarium</h3>
                                    <?php if (Permission::can($pdo, 'Glosarium', 'create')): ?>
                                        <button class="col-3 btn btn-block btn-success" data-toggle="modal"
                                            data-target="#modalTambah">
                                            Tambah Glosarium
                                        </button>
                                    <?php endif; ?>
                                </div>
                                <!-- /.card-header -->
                                <div class="card-body">
                                    <table id="example1" class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th class="text-center">No</th>
                                                <th>Istilah</th>
                                                <th>Istilah lain / sinonim</th>
                                                <th>Kategori</th>
                                                <th>Definisi Singkat</th>
                                                <th>Penjelasan Lengkap</th>
                                                <th>Bahasa Asal</th>
                                                <th>Pengucapan</th>
                                                <th>Sumber</th>
                                                <th>Gambar</th>
                                                <th>Status Aktif</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $no = 1; ?>
                                            <?php foreach ($glosariums as $glosarium): ?>
                                                <tr>
                                                    <td class="text-center"><?= $no++ ?></td>
                                                    <td><?= htmlspecialchars($glosarium['istilah']) ?></td>
                                                    <td><?= htmlspecialchars($glosarium['istilah_lain']) ?></td>
                                                    <td><?= htmlspecialchars($glosarium['kategori']) ?></td>
                                                    <td><?= htmlspecialchars($glosarium['definisi_singkat']) ?></td>
                                                    <td><?= htmlspecialchars($glosarium['penjelasan_lengkap']) ?></td>
                                                    <td><?= htmlspecialchars($glosarium['bahasa_asal']) ?></td>
                                                    <td><?= htmlspecialchars($glosarium['pengucapan']) ?></td>
                                                    <td><?= htmlspecialchars($glosarium['sumber']) ?></td>
                                                    <td>
                                                        <img class="img-circle elevation-2" src="<?= !empty($glosarium['gambar'])
                                                            ? '../../uploads/glosarium/' . htmlspecialchars($glosarium['gambar'])
                                                            : '../../../assets/image/benih_placeholder.jpg' ?>"
                                                            width="80">
                                                    </td>
                                                    <td><?= $glosarium['is_active'] ? 'Aktif' : 'Nonaktif' ?></td>
                                                    <td class="row">
                                                        <?php if (Permission::can($pdo, 'Glosarium', 'update')): ?>
                                                            <div class="col">
                                                                <button class="btn btn-block btn-info btn-edit"
                                                                    data-id="<?= $glosarium['id'] ?>"
                                                                    data-istilah="<?= htmlspecialchars($glosarium['istilah']) ?>"
                                                                    data-istilah_lain="<?= htmlspecialchars($glosarium['istilah_lain']) ?>"
                                                                    data-kategori="<?= htmlspecialchars($glosarium['kategori']) ?>"
                                                                    data-definisi_singkat="<?= htmlspecialchars($glosarium['definisi_singkat']) ?>"
                                                                    data-penjelasan_lengkap="<?= htmlspecialchars($glosarium['penjelasan_lengkap']) ?>"
                                                                    data-bahasa_asal="<?= htmlspecialchars($glosarium['bahasa_asal']) ?>"
                                                                    data-pengucapan="<?= htmlspecialchars($glosarium['pengucapan']) ?>"
                                                                    data-sumber="<?= htmlspecialchars($glosarium['sumber']) ?>"
                                                                    data-gambar="<?= $glosarium['gambar'] ?>"
                                                                    data-status="<?= $glosarium['is_active'] ?>"
                                                                    data-toggle="modal" data-target="#modalEdit">
                                                                    <i class="fas fa-edit"></i> Edit
                                                                </button>
                                                            </div>
                                                        <?php endif; ?>

                                                        <?php if (Permission::can($pdo, 'Glosarium', 'delete')): ?>
                                                            <form method="POST" action="delete.php" class="form-delete col">
                                                                <?= csrfField() ?>
                                                                <input type="hidden" name="id" value="<?= $glosarium['id'] ?>">
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
                                                <th>Istilah</th>
                                                <th>Istilah lain / sinonim</th>
                                                <th>Kategori</th>
                                                <th>Definisi Singkat</th>
                                                <th>Penjelasan Lengkap</th>
                                                <th>Bahasa Asal</th>
                                                <th>Pengucapan</th>
                                                <th>Sumber</th>
                                                <th>Gambar</th>
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
                            <h4 class="modal-title">Tambah Glosarium</h4>
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                        </div>
                        <form id="formTambah" method="POST" action="store.php" enctype="multipart/form-data">
                            <?= csrfField() ?>
                            <div class="modal-body">
                                <div class="form-group">
                                    <label for="istilah">Istilah<code>*</code></label>
                                    <input type="text" name="istilah" class="form-control" id="istilah"
                                        placeholder="Masukkan Istilah" maxlength="100">
                                </div>
                                <div class="form-group">
                                    <label for="istilah_lain">Istilah lain / sinonim</label>
                                    <input type="text" name="istilah_lain" class="form-control" id="istilah_lain"
                                        placeholder="Masukkan Istilah lain / sinonim" maxlength="100">
                                </div>
                                <div class="form-group">
                                    <label for="kategori">Kategori<code>*</code></label>
                                    <select name="kategori" class="form-control" id="kategori">
                                        <option value="">-- Pilih Kategori --</option>
                                        <option value="Budaya">Budaya</option>
                                        <option value="Bahasa">Bahasa</option>
                                        <option value="Adat Istiadat">Adat Istiadat</option>
                                        <option value="Hukum Adat">Hukum Adat</option>
                                        <option value="Kesenian">Kesenian</option>
                                        <option value="Kepercayaan">Kepercayaan</option>
                                        <option value="Rumah Adat">Rumah Adat</option>
                                        <option value="Makanan">Makanan</option>
                                        <option value="Flora">Flora</option>
                                        <option value="Fauna">Fauna</option>
                                        <option value="Pertanian">Pertanian</option>
                                        <option value="Hutan">Hutan</option>
                                        <option value="Wilayah">Wilayah</option>
                                        <option value="Kelembagaan Adat">Kelembagaan Adat</option>
                                        <option value="Musik Tradisional">Musik Tradisional</option>
                                        <option value="Arsitektur Tradisional">Arsitektur Tradisional</option>
                                        <option value="Permainan Tradisional">Permainan Tradisional</option>
                                        <option value="Peralatan Tradisional">Peralatan Tradisional</option>
                                        <option value="Upacara Adat">Upacara Adat</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="definisi_singkat">Definisi Singkat<code>*</code></label>
                                    <textarea name="definisi_singkat" class="form-control" id="definisi_singkat"
                                        placeholder="Masukkan Definisi Singkat"></textarea>
                                </div>
                                <div class="form-group">
                                    <label for="penjelasan_lengkap">Penjelasan Lengkap</label>
                                    <textarea name="penjelasan_lengkap" class="form-control" id="penjelasan_lengkap"
                                        placeholder="Masukkan Penjelasan Lengkap"></textarea>
                                </div>
                                <div class="form-group">
                                    <label for="bahasa_asal">Bahasa Asal</label>
                                    <select name="bahasa_asal" class="form-control" id="bahasa_asal">
                                        <option value="">-- Pilih Bahasa Asal --</option>
                                        <option value="Dayak Ngaju">Dayak Ngaju</option>
                                        <option value="Dayak Ot Danum">Dayak Ot Danum</option>
                                        <option value="Dayak Ma'anyan">Dayak Ma'anyan</option>
                                        <option value="Dayak Katingan">Dayak Katingan</option>
                                        <option value="Murung">Murung</option>
                                        <option value="Suang">Suang</option>
                                        <option value="Bakumpai">Bakumpai</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="pengucapan">Pengucapan</label>
                                    <input type="text" name="pengucapan" class="form-control" id="pengucapan"
                                        placeholder="Contoh : ka-le-ka" maxlength="100">
                                </div>
                                <div class="form-group">
                                    <label for="sumber">Sumber</label>
                                    <input type="text" name="sumber" class="form-control" id="sumber"
                                        placeholder="Masukkan Sumber" maxlength="100">
                                </div>
                                <div class="form-group">
                                    <label for="gambar">Gambar</label>
                                    <input type="file" name="gambar" class="form-control" id="gambar" accept="image/*">
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
                            <h4 class="modal-title">Edit Glosarium</h4>
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                        </div>

                        <form id="formEdit" method="POST" action="update.php" enctype="multipart/form-data">
                            <?= csrfField() ?>
                            <input type="hidden" name="id" id="edit_id">
                            <div class="modal-body">
                                <div class="form-group">
                                    <label for="istilah">Istilah<code>*</code></label>
                                    <input type="text" name="istilah" class="form-control" id="edit_istilah"
                                        placeholder="Masukkan Istilah" maxlength="100">
                                </div>
                                <div class="form-group">
                                    <label for="istilah_lain">Istilah lain / sinonim</label>
                                    <input type="text" name="istilah_lain" class="form-control" id="edit_istilah_lain"
                                        placeholder="Masukkan Istilah lain / sinonim" maxlength="100">
                                </div>
                                <div class="form-group">
                                    <label for="kategori">Kategori<code>*</code></label>
                                    <select name="kategori" class="form-control" id="edit_kategori">
                                        <option value="">-- Pilih Kategori --</option>
                                        <option value="Budaya">Budaya</option>
                                        <option value="Bahasa">Bahasa</option>
                                        <option value="Adat Istiadat">Adat Istiadat</option>
                                        <option value="Hukum Adat">Hukum Adat</option>
                                        <option value="Kesenian">Kesenian</option>
                                        <option value="Kepercayaan">Kepercayaan</option>
                                        <option value="Rumah Adat">Rumah Adat</option>
                                        <option value="Makanan">Makanan</option>
                                        <option value="Flora">Flora</option>
                                        <option value="Fauna">Fauna</option>
                                        <option value="Pertanian">Pertanian</option>
                                        <option value="Hutan">Hutan</option>
                                        <option value="Wilayah">Wilayah</option>
                                        <option value="Kelembagaan Adat">Kelembagaan Adat</option>
                                        <option value="Musik Tradisional">Musik Tradisional</option>
                                        <option value="Arsitektur Tradisional">Arsitektur Tradisional</option>
                                        <option value="Permainan Tradisional">Permainan Tradisional</option>
                                        <option value="Peralatan Tradisional">Peralatan Tradisional</option>
                                        <option value="Upacara Adat">Upacara Adat</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="definisi_singkat">Definisi Singkat<code>*</code></label>
                                    <textarea name="definisi_singkat" class="form-control" id="edit_definisi_singkat"
                                        placeholder="Masukkan Definisi Singkat"></textarea>
                                </div>
                                <div class="form-group">
                                    <label for="penjelasan_lengkap">Penjelasan Lengkap</label>
                                    <textarea name="penjelasan_lengkap" class="form-control"
                                        id="edit_penjelasan_lengkap"
                                        placeholder="Masukkan Penjelasan Lengkap"></textarea>
                                </div>
                                <div class="form-group">
                                    <label for="bahasa_asal">Bahasa Asal</label>
                                    <select name="bahasa_asal" class="form-control" id="edit_bahasa_asal">
                                        <option value="">-- Pilih Bahasa Asal --</option>
                                        <option value="Dayak Ngaju">Dayak Ngaju</option>
                                        <option value="Dayak Ot Danum">Dayak Ot Danum</option>
                                        <option value="Dayak Ma'anyan">Dayak Ma'anyan</option>
                                        <option value="Dayak Katingan">Dayak Katingan</option>
                                        <option value="Murung">Murung</option>
                                        <option value="Suang">Suang</option>
                                        <option value="Bakumpai">Bakumpai</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="pengucapan">Pengucapan</label>
                                    <input type="text" name="pengucapan" class="form-control" id="edit_pengucapan"
                                        placeholder="Masukkan Pengucapan" maxlength="100">
                                </div>
                                <div class="form-group">
                                    <label for="sumber">Sumber</label>
                                    <input type="text" name="sumber" class="form-control" id="edit_sumber"
                                        placeholder="Masukkan Sumber" maxlength="100">
                                </div>
                                <input type="hidden" name="gambar_lama" id="edit_gambar_lama">
                                <div class="form-group">
                                    <label for="edit_gambar">Gambar</label>
                                    <input type="file" name="gambar" class="form-control" id="edit_gambar"
                                        accept="image/*">

                                    <small class="text-muted">
                                        Kosongkan jika tidak ingin mengganti gambar
                                    </small>
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
                        istilah: {
                            required: true
                        },
                        istilah_lain: {
                            required: false
                        },
                        kategori: {
                            required: true
                        },
                        definisi_singkat: {
                            required: true
                        },
                        penjelasan_lengkap: {
                            required: false
                        },
                        bahasa_asal: {
                            required: false
                        },
                        pengucapan: {
                            required: false
                        },
                        sumber: {
                            required: false
                        },
                        is_active: {
                            required: true
                        }
                    },
                    messages: {
                        istilah: {
                            required: "Silahkan masukkan Istilah"
                        },
                        // istilah_lain: {
                        //     required: "Silahkan masukkan Istilah lain / sinonim"
                        // },
                        kategori: {
                            required: "Silahkan masukkan Kategori"
                        },
                        definisi_singkat: {
                            required: "Silahkan masukkan Definisi Singkat"
                        },
                        // penjelasan_lengkap: {
                        //     required: "Silahkan masukkan Penjelasan Lengkap"
                        // },
                        // bahasa_asal: {
                        //     required: "Silahkan masukkan Bahasa Asal"
                        // },
                        // pengucapan: {
                        //     required: "Silahkan masukkan Pengucapan"
                        // },
                        // sumber: {
                        //     required: "Silahkan masukkan Sumber"
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
            let istilah = $(this).data("istilah");
            let istilah_lain = $(this).data("istilah_lain");
            let kategori = $(this).data("kategori");
            let definisi_singkat = $(this).data("definisi_singkat");
            let penjelasan_lengkap = $(this).data("penjelasan_lengkap");
            let bahasa_asal = $(this).data("bahasa_asal");
            let pengucapan = $(this).data("pengucapan");
            let sumber = $(this).data("sumber");
            let gambar = $(this).data("gambar");
            let status = $(this).data("status");

            $("#edit_id").val(id);
            $("#edit_istilah").val(istilah);
            $("#edit_istilah_lain").val(istilah_lain);
            $("#edit_kategori").val(kategori);
            $("#edit_definisi_singkat").val(definisi_singkat);
            $("#edit_penjelasan_lengkap").val(penjelasan_lengkap);
            $("#edit_bahasa_asal").val(bahasa_asal);
            $("#edit_pengucapan").val(pengucapan);
            $("#edit_sumber").val(sumber);
            $("#edit_gambar_lama").val(gambar);
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
            showToast("Data Glosarium berhasil ditambahkan", "created");
        }
        if (success === "updated") {
            showToast("Data Glosarium berhasil diperbarui", "updated");
        }
        if (success === "deleted") {
            showToast("Data Glosarium berhasil dihapus", "deleted");
        }
    </script>
</body>

</html>