<?php

require_once '../../app/config/database.php';
require_once '../../app/controllers/LandCoverObservasiController.php';
require_once '../../app/core/session.php';
require_once '../../app/core/csrf.php';
require_once "../../app/core/auth.php";
require_once '../../app/helpers/escape.php';

secureSessionStart();
checkAuth("non_dashboard");

$controller = new LandCoverObservasiController($pdo);
$landCoverObservasis = $controller->index();
$tanahs = $controller->getTanah();
$kategoriAreas = $controller->getKategoriArea();
$penggunaanPertanians = $controller->getPenggunaanPertanian();
$penggunaanLainnyas = $controller->getPenggunaanLainnya();

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
        $menu = "land_cover_observasi";
        include "../feature/sidebar.php";
        ?>

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1>Data Land Cover Observasi</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="#">Master Data</a></li>
                                <li class="breadcrumb-item active">Data Land Cover Observasi</li>
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
                                    <h3 class="card-title col-9">Berikut adalah list dari Data Land Cover Observasi</h3>
                                    <button class="col-3 btn btn-block btn-success" data-toggle="modal"
                                        data-target="#modalTambah">
                                        Tambah Land Cover Observasi
                                    </button>
                                </div>
                                <!-- /.card-header -->
                                <div class="card-body">
                                    <table id="example1" class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th>Nama Lahan</th>
                                                <th>Periode Pengecekan</th>
                                                <th>Kategori Area</th>
                                                <th>Penggunaan Pertanian</th>
                                                <th>Penggunaan Lainnya</th>
                                                <th>Persentase Tutupan</th>
                                                <th>Catatan</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($landCoverObservasis as $landCoverObservasi): ?>
                                                <tr>
                                                    <td><?= htmlspecialchars($landCoverObservasi['nama_lahan']) ?></td>
                                                    <td><?= htmlspecialchars($landCoverObservasi['periode_pengecekan']) ?></td>
                                                    <td><?= htmlspecialchars($landCoverObservasi['nama_kategori_area']) ?></td>
                                                    <td><?= htmlspecialchars($landCoverObservasi['nama_penggunaan_pertanian']) ?></td>
                                                    <td><?= htmlspecialchars($landCoverObservasi['nama_penggunaan_lainnya']) ?></td>
                                                    <td><?= htmlspecialchars($landCoverObservasi['persentase_tutupan']) ?></td>
                                                    <td><?= htmlspecialchars($landCoverObservasi['catatan']) ?></td>
                                                    <td class="row">
                                                        <div class="col">
                                                            <button class="btn btn-block btn-info btn-edit"
                                                                data-id="<?= $landCoverObservasi['id'] ?>"
                                                                data-id_tanah="<?= htmlspecialchars($landCoverObservasi['id_tanah']) ?>"
                                                                data-periode_pengecekan="<?= htmlspecialchars($landCoverObservasi['periode_pengecekan']) ?>"
                                                                data-id_kategori_area="<?= htmlspecialchars($landCoverObservasi['id_kategori_area']) ?>"
                                                                data-id_penggunaan_pertanian="<?= htmlspecialchars($landCoverObservasi['id_penggunaan_pertanian']) ?>"
                                                                data-id_penggunaan_lainnya="<?= htmlspecialchars($landCoverObservasi['id_penggunaan_lainnya']) ?>"
                                                                data-persentase_tutupan="<?= htmlspecialchars($landCoverObservasi['persentase_tutupan']) ?>"
                                                                data-catatan="<?= htmlspecialchars($landCoverObservasi['catatan']) ?>"
                                                                data-toggle="modal" data-target="#modalEdit">
                                                                <i class="fas fa-edit"></i> Edit
                                                            </button>
                                                        </div>

                                                        <form method="POST" action="delete.php" class="form-delete col">
                                                            <?= csrfField() ?>
                                                            <input type="hidden" name="id" value="<?= $landCoverObservasi['id'] ?>">
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
                                                <th>Kategori Area</th>
                                                <th>Penggunaan Pertanian</th>
                                                <th>Penggunaan Lainnya</th>
                                                <th>Persentase Tutupan</th>
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
                            <h4 class="modal-title">Tambah Land Cover Observasi</h4>
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
                                    <label for="id_kategori_area">Kategori Area<code>*</code></label>
                                    <select name="id_kategori_area" class="form-control" id="id_kategori_area">
                                        <option value="">-- Pilih Kategori Area --</option>
                                        <?php foreach ($kategoriAreas as $ka): ?>
                                            <option value="<?= $ka['id'] ?>">
                                                <?= htmlspecialchars($ka['nama']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="id_penggunaan_pertanian">Penggunaan Pertanian<code>*</code></label>
                                    <select name="id_penggunaan_pertanian" class="form-control" id="id_penggunaan_pertanian">
                                        <option value="">-- Pilih Penggunaan Pertanian --</option>
                                        <?php foreach ($penggunaanPertanians as $pp): ?>
                                            <option value="<?= $pp['id'] ?>">
                                                <?= htmlspecialchars($pp['nama']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="id_penggunaan_lainnya">Penggunaan Lainnya<code>*</code></label>
                                    <select name="id_penggunaan_lainnya" class="form-control" id="id_penggunaan_lainnya">
                                        <option value="">-- Pilih Penggunaan Lainnya --</option>
                                        <?php foreach ($penggunaanLainnyas as $pl): ?>
                                            <option value="<?= $pl['id'] ?>">
                                                <?= htmlspecialchars($pl['nama']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="persentase_tutupan">Persentase Tutupan<code>*</code></label>
                                    <input type="number" name="persentase_tutupan" class="form-control" id="persentase_tutupan"
                                        placeholder="Masukkan Persentase Tutupan" min="0" max="100" step="0.01">
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
                            <h4 class="modal-title">Edit Land Cover Observasi</h4>
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
                                    <label for="id_kategori_area">Kategori Area<code>*</code></label>
                                    <select name="id_kategori_area" class="form-control" id="edit_id_kategori_area">
                                        <option value="">-- Pilih Kategori Area --</option>
                                        <?php foreach ($kategoriAreas as $ka): ?>
                                            <option value="<?= $ka['id'] ?>">
                                                <?= htmlspecialchars($ka['nama']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="id_penggunaan_pertanian">Penggunaan Pertanian<code>*</code></label>
                                    <select name="id_penggunaan_pertanian" class="form-control" id="edit_id_penggunaan_pertanian">
                                        <option value="">-- Pilih Penggunaan Pertanian --</option>
                                        <?php foreach ($penggunaanPertanians as $pp): ?>
                                            <option value="<?= $pp['id'] ?>">
                                                <?= htmlspecialchars($pp['nama']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="id_penggunaan_lainnya">Penggunaan Lainnya<code>*</code></label>
                                    <select name="id_penggunaan_lainnya" class="form-control" id="edit_id_penggunaan_lainnya">
                                        <option value="">-- Pilih Penggunaan Lainnya --</option>
                                        <?php foreach ($penggunaanLainnyas as $pl): ?>
                                            <option value="<?= $pl['id'] ?>">
                                                <?= htmlspecialchars($pl['nama']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="persentase_tutupan">Persentase Tutupan<code>*</code></label>
                                    <input type="number" name="persentase_tutupan" class="form-control" id="edit_persentase_tutupan"
                                        placeholder="Masukkan Persentase Tutupan" min="0" max="100" step="0.01">
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
                        id_kategori_area: {
                            required: true
                        },
                        id_penggunaan_pertanian: {
                            required: true
                        },
                        id_penggunaan_lainnya: {
                            required: true
                        },
                        persentase_tutupan: {
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
                            required: "Silahkan masukkan Periode Pengecekan"
                        },
                        id_kategori_area: {
                            required: "Silahkan pilih Kategori Area"
                        },
                        id_penggunaan_pertanian : {
                            required: "Silahkan pilih Penggunaan Pertanian"
                        },
                        id_penggunaan_lainnya: {
                            required: "Silahkan pilih Penggunaan Lainnya"
                        },
                        persentase_tutupan: {
                            required: "Silahkan masukkan Persentase Tutupan"
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
            let id_kategori_area = $(this).data("id_kategori_area");
            let id_penggunaan_pertanian = $(this).data("id_penggunaan_pertanian");
            let id_penggunaan_lainnya = $(this).data("id_penggunaan_lainnya");
            let persentase_tutupan = $(this).data("persentase_tutupan");
            let catatan = $(this).data("catatan");

            $("#edit_id").val(id);
            $("#edit_id_tanah").val(id_tanah);
            $("#edit_periode_pengecekan").val(periode_pengecekan);
            $("#edit_id_kategori_area").val(id_kategori_area);
            $("#edit_id_penggunaan_pertanian").val(id_penggunaan_pertanian);
            $("#edit_id_penggunaan_lainnya").val(id_penggunaan_lainnya);
            $("#edit_persentase_tutupan").val(persentase_tutupan);
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
            showToast("Data Land Cover Observasi berhasil ditambahkan", "created");
        }
        if (success === "updated") {
            showToast("Data Land Cover Observasi berhasil diperbarui", "updated");
        }
        if (success === "deleted") {
            showToast("Data Land Cover Observasi berhasil dihapus", "deleted");
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