<?php

require_once '../../app/config/database.php';
require_once '../../app/controllers/TopografiObservasiController.php';
require_once '../../app/core/session.php';
require_once '../../app/core/csrf.php';
require_once "../../app/core/auth.php";
require_once '../../app/helpers/escape.php';

secureSessionStart();
checkAuth("non_dashboard");

$controller = new TopografiObservasiController($pdo);
$topografiObservasis = $controller->index();
$tanahs = $controller->getTanah();
$lanskaps = $controller->getLanskap();
$fiturTambahans = $controller->getFiturTambahan();

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
        $menu = "topografi_observasi";
        include "../feature/sidebar.php";
        ?>

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1>Data Topografi Observasi</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="#">Master Data</a></li>
                                <li class="breadcrumb-item active">Data Topografi Observasi</li>
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
                                    <h3 class="card-title col-9">Berikut adalah list dari Data Topografi Observasi</h3>
                                    <button class="col-3 btn btn-block btn-success" data-toggle="modal"
                                        data-target="#modalTambah">
                                        Tambah Topografi Observasi
                                    </button>
                                </div>
                                <!-- /.card-header -->
                                <div class="card-body">
                                    <table id="example1" class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th>Nama Lahan</th>
                                                <th>Periode Pengecekan</th>
                                                <th>Lanskap</th>
                                                <th>Fitur Tambahan</th>
                                                <th>Elevasi (mdpl)</th>
                                                <th>Kemiringan Derajat</th>
                                                <th>Rawan Erosi</th>
                                                <th>Arah Lereng</th>
                                                <th>Catatan</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($topografiObservasis as $topografiObservasi): ?>
                                                <tr>
                                                    <td><?= htmlspecialchars($topografiObservasi['nama_lahan']) ?></td>
                                                    <td><?= htmlspecialchars($topografiObservasi['periode_pengecekan']) ?></td>
                                                    <td><?= htmlspecialchars($topografiObservasi['lanskap']) ?></td>
                                                    <td><?= htmlspecialchars($topografiObservasi['fitur_tambahan']) ?></td>
                                                    <td><?= htmlspecialchars($topografiObservasi['elevasi_mdpl']) ?></td>
                                                    <td><?= htmlspecialchars($topografiObservasi['kemiringan_derajat']) ?></td>
                                                    <td><?= $topografiObservasi['rawan_erosi'] ? 'Iya' : 'Tidak' ?></td>
                                                    <td><?= htmlspecialchars($topografiObservasi['arah_lereng']) ?></td>
                                                    <td><?= htmlspecialchars($topografiObservasi['catatan']) ?></td>
                                                    <td class="row">
                                                        <div class="col">
                                                            <button class="btn btn-block btn-info btn-edit"
                                                                data-id="<?= $topografiObservasi['id'] ?>"
                                                                data-id_tanah="<?= htmlspecialchars($topografiObservasi['id_tanah']) ?>"
                                                                data-periode_pengecekan="<?= htmlspecialchars($topografiObservasi['periode_pengecekan']) ?>"
                                                                data-id_lanskap="<?= htmlspecialchars($topografiObservasi['id_lanskap']) ?>"
                                                                data-id_fitur_tambahan="<?= htmlspecialchars($topografiObservasi['id_fitur_tambahan']) ?>"
                                                                data-elevasi_mdpl="<?= htmlspecialchars($topografiObservasi['elevasi_mdpl']) ?>"
                                                                data-kemiringan_derajat="<?= htmlspecialchars($topografiObservasi['kemiringan_derajat']) ?>"
                                                                data-rawan_erosi="<?= htmlspecialchars($topografiObservasi['rawan_erosi']) ?>"
                                                                data-arah_lereng="<?= htmlspecialchars($topografiObservasi['arah_lereng']) ?>"
                                                                data-catatan="<?= htmlspecialchars($topografiObservasi['catatan']) ?>"
                                                                data-toggle="modal" data-target="#modalEdit">
                                                                <i class="fas fa-edit"></i> Edit
                                                            </button>
                                                        </div>

                                                        <form method="POST" action="delete.php" class="form-delete col">
                                                            <?= csrfField() ?>
                                                            <input type="hidden" name="id" value="<?= $topografiObservasi['id'] ?>">
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
                                                <th>Lanskap</th>
                                                <th>Fitur Tambahan</th>
                                                <th>Elevasi (mdpl)</th>
                                                <th>Kemiringan Derajat</th>
                                                <th>Rawan Erosi</th>
                                                <th>Arah Lereng</th>
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
                            <h4 class="modal-title">Tambah Topografi Observasi</h4>
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
                                    <label for="id_lanskap">Lanskap<code>*</code></label>
                                    <select name="id_lanskap" class="form-control" id="id_lanskap">
                                        <option value="">-- Pilih Lanskap --</option>
                                        <?php foreach ($lanskaps as $ls): ?>
                                            <option value="<?= $ls['id'] ?>">
                                                <?= htmlspecialchars($ls['nama']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="id_fitur_tambahan">Fitur Tambahan<code>*</code></label>
                                    <select name="id_fitur_tambahan" class="form-control" id="id_fitur_tambahan">
                                        <option value="">-- Pilih Fitur Tambahan --</option>
                                        <?php foreach ($fiturTambahans as $ft): ?>
                                            <option value="<?= $ft['id'] ?>">
                                                <?= htmlspecialchars($ft['nama']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="elevasi_mdpl">Elevasi (mdpl)<code>*</code></label>
                                    <input type="number" name="elevasi_mdpl" class="form-control" id="elevasi_mdpl"
                                        placeholder="Masukkan Elevasi (mdpl)" min="0" max="99999999" step="1">
                                </div>
                                <div class="form-group">
                                    <label for="kemiringan_derajat">Kemiringan Derajat<code>*</code></label>
                                    <input type="number" name="kemiringan_derajat" class="form-control" id="kemiringan_derajat"
                                        placeholder="Masukkan Kemiringan Derajat" min="0" max="99999" step="0.01">
                                </div>
                                <div class="form-group">
                                    <label>Rawan Erosi<code>*</code></label>
                                    <div class="form-group row">
                                        <div class="col-sm-2">
                                            <div class="custom-control custom-radio">
                                                <input class="custom-control-input" type="radio" id="iya_rawan_erosi"
                                                    name="rawan_erosi" value="1" checked>
                                                <label for="iya_rawan_erosi" class="custom-control-label">Iya</label>
                                            </div>
                                        </div>
                                        <div class="col-sm-2">
                                            <div class="custom-control custom-radio">
                                                <input class="custom-control-input" type="radio"
                                                    id="tidak_rawan_erosi" name="rawan_erosi" value="0">
                                                <label for="tidak_rawan_erosi"
                                                    class="custom-control-label">Tidak</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="arah_lereng">Arah Lereng<code>*</code></label>
                                    <input type="text" name="arah_lereng" class="form-control" id="arah_lereng"
                                        placeholder="Masukkan Arah Lereng" maxlength="50">
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
                            <h4 class="modal-title">Edit Topografi Observasi</h4>
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
                                    <label for="id_lanskap">Lanskap<code>*</code></label>
                                    <select name="id_lanskap" class="form-control" id="edit_id_lanskap">
                                        <option value="">-- Pilih Lanskap --</option>
                                        <?php foreach ($lanskaps as $ls): ?>
                                            <option value="<?= $ls['id'] ?>">
                                                <?= htmlspecialchars($ls['nama']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="id_fitur_tambahan">Fitur Tambahan<code>*</code></label>
                                    <select name="id_fitur_tambahan" class="form-control" id="edit_id_fitur_tambahan">
                                        <option value="">-- Pilih Fitur Tambahan --</option>
                                        <?php foreach ($fiturTambahans as $ft): ?>
                                            <option value="<?= $ft['id'] ?>">
                                                <?= htmlspecialchars($ft['nama']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="elevasi_mdpl">Elevasi (mdpl)<code>*</code></label>
                                    <input type="number" name="elevasi_mdpl" class="form-control" id="edit_elevasi_mdpl"
                                        placeholder="Masukkan Elevasi (mdpl)" min="0" max="99999999" step="1">
                                </div>
                                <div class="form-group">
                                    <label for="kemiringan_derajat">Kemiringan Derajat<code>*</code></label>
                                    <input type="number" name="kemiringan_derajat" class="form-control" id="edit_kemiringan_derajat"
                                        placeholder="Masukkan Kemiringan Derajat" min="0" max="100" step="0.01">
                                </div>
                                <div class="form-group">
                                    <label>Rawan Erosi<code>*</code></label>
                                    <div class="form-group row">
                                        <div class="col-sm-2">
                                            <div class="custom-control custom-radio">
                                                <input class="custom-control-input" type="radio" id="edit_iya_rawan_erosi"
                                                    name="rawan_erosi" value="1" checked>
                                                <label for="edit_iya_rawan_erosi" class="custom-control-label">Iya</label>
                                            </div>
                                        </div>
                                        <div class="col-sm-2">
                                            <div class="custom-control custom-radio">
                                                <input class="custom-control-input" type="radio"
                                                    id="edit_tidak_rawan_erosi" name="rawan_erosi" value="0">
                                                <label for="edit_tidak_rawan_erosi"
                                                    class="custom-control-label">Tidak</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="arah_lereng">Arah Lereng<code>*</code></label>
                                    <input type="text" name="arah_lereng" class="form-control" id="edit_arah_lereng"
                                        placeholder="Masukkan Arah Lereng" maxlength="50">
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
                        id_lanskap: {
                            required: true
                        },
                        id_fitur_tambahan: {
                            required: true
                        },
                        elevasi_mdpl: {
                            required: true
                        },
                        kemiringan_derajat: {
                            required: true
                        },
                        rawan_erosi: {
                            required: true
                        },
                        arah_lereng: {
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
                        id_lanskap: {
                            required: "Silahkan pilih Lanskap"
                        },
                        id_fitur_tambahan : {
                            required: "Silahkan pilih Fitur Tambahan"
                        },
                        elevasi_mdpl: {
                            required: "Silahkan masukkan Elevasi (mdpl)"
                        },
                        kemiringan_derajat: {
                            required: "Silahkan masukkan Kemiringan Derajat"
                        },
                        rawan_erosi: {
                            required: "Silahkan pilih Rawan Erosi"
                        },
                        arah_lereng: {
                            required: "Silahkan masukkan Arah Lereng"
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
            let id_lanskap = $(this).data("id_lanskap");
            let id_fitur_tambahan = $(this).data("id_fitur_tambahan");
            let elevasi_mdpl = $(this).data("elevasi_mdpl");
            let kemiringan_derajat = $(this).data("kemiringan_derajat");
            let rawan_erosi = $(this).data("rawan_erosi");
            let arah_lereng = $(this).data("arah_lereng");
            let catatan = $(this).data("catatan");

            $("#edit_id").val(id);
            $("#edit_id_tanah").val(id_tanah);
            $("#edit_periode_pengecekan").val(periode_pengecekan);
            $("#edit_id_lanskap").val(id_lanskap);
            $("#edit_id_fitur_tambahan").val(id_fitur_tambahan);
            $("#edit_elevasi_mdpl").val(elevasi_mdpl);
            $("#edit_kemiringan_derajat").val(kemiringan_derajat);
            $("input[name='rawan_erosi'][value='" + rawan_erosi + "']").prop("checked", true);
            $("#edit_arah_lereng").val(arah_lereng);
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
            showToast("Data Topografi Observasi berhasil ditambahkan", "created");
        }
        if (success === "updated") {
            showToast("Data Topografi Observasi berhasil diperbarui", "updated");
        }
        if (success === "deleted") {
            showToast("Data Topografi Observasi berhasil dihapus", "deleted");
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