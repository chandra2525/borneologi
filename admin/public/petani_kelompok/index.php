<?php

require_once '../../app/config/database.php';
require_once '../../app/controllers/PetaniKelompokController.php';
require_once '../../app/core/session.php';
require_once '../../app/core/csrf.php';
require_once "../../app/core/auth.php";
require_once '../../app/helpers/escape.php';

secureSessionStart();
checkAuth("non_dashboard");

$controller = new PetaniKelompokController($pdo);
$petaniKelompoks = $controller->index();
$petanis = $controller->getPetani();
$kelompokTanis = $controller->getKelompokTani();
$jabatanKelompoks = $controller->getJabatanKelompok();

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
        $menu = "petani_kelompok";
        include "../feature/sidebar.php";
        ?>

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1>Data Petani Kelompok</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="#">Master Data</a></li>
                                <li class="breadcrumb-item active">Data Petani Kelompok</li>
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
                                    <h3 class="card-title col-9">Berikut adalah list dari Data Petani Kelompok</h3>
                                    <button class="col-3 btn btn-block btn-success" data-toggle="modal"
                                        data-target="#modalTambah">
                                        Tambah Petani Kelompok
                                    </button>
                                </div>
                                <!-- /.card-header -->
                                <div class="card-body">
                                    <table id="example1" class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th>Petani</th>
                                                <th>Kelompok Tani</th>
                                                <th>Jabatan Kelompok</th>
                                                <th>Tanggal Gabung</th>
                                                <th>Tanggal Keluar</th>
                                                <th>Pengurus</th>
                                                <th>Keterangan</th>
                                                <th>Status Aktif</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($petaniKelompoks as $petaniKelompok): ?>
                                                <tr>
                                                    <td><?= htmlspecialchars($petaniKelompok['nama_petani']) ?></td>
                                                    <td><?= htmlspecialchars($petaniKelompok['nama_kelompok_tani']) ?></td>
                                                    <td><?= htmlspecialchars($petaniKelompok['nama_jabatan_kelompok']) ?></td>
                                                    <td><?= htmlspecialchars($petaniKelompok['tanggal_gabung']) ?></td>
                                                    <td><?= htmlspecialchars($petaniKelompok['tanggal_keluar']) ?></td>
                                                    <td><?= $petaniKelompok['is_pengurus'] ? 'Ya' : 'Tidak' ?></td>
                                                    <td><?= htmlspecialchars($petaniKelompok['keterangan']) ?></td>
                                                    <td><?= $petaniKelompok['is_active'] ? 'Aktif' : 'Nonaktif' ?></td>
                                                    <td class="row">
                                                        <div class="col">
                                                            <button class="btn btn-block btn-info btn-edit"
                                                                data-id="<?= $petaniKelompok['id'] ?>"
                                                                data-id_petani="<?= htmlspecialchars($petaniKelompok['id_petani']) ?>"
                                                                data-id_kelompok_tani="<?= htmlspecialchars($petaniKelompok['id_kelompok_tani']) ?>"
                                                                data-id_jabatan_kelompok="<?= htmlspecialchars($petaniKelompok['id_jabatan_kelompok']) ?>"
                                                                data-tanggal_gabung="<?= htmlspecialchars($petaniKelompok['tanggal_gabung']) ?>"
                                                                data-tanggal_keluar="<?= htmlspecialchars($petaniKelompok['tanggal_keluar']) ?>"
                                                                data-is_pengurus="<?= htmlspecialchars($petaniKelompok['is_pengurus']) ?>"
                                                                data-keterangan="<?= htmlspecialchars($petaniKelompok['keterangan']) ?>"
                                                                data-status="<?= $petaniKelompok['is_active'] ?>"
                                                                data-toggle="modal" data-target="#modalEdit">
                                                                <i class="fas fa-edit"></i> Edit
                                                            </button>
                                                        </div>

                                                        <form method="POST" action="delete.php" class="form-delete col">
                                                            <?= csrfField() ?>
                                                            <input type="hidden" name="id" value="<?= $petaniKelompok['id'] ?>">
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
                                                <th>Petani</th>
                                                <th>Kelompok Tani</th>
                                                <th>Jabatan Kelompok</th>
                                                <th>Tanggal Gabung</th>
                                                <th>Tanggal Keluar</th>
                                                <th>Pengurus</th>
                                                <th>Keterangan</th>
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
                                    <label for="id_petani">Petani<code>*</code></label>
                                    <select name="id_petani" class="form-control" id="id_petani">
                                        <option value="">-- Pilih Petani --</option>
                                        <?php foreach ($petanis as $p): ?>
                                            <option value="<?= $p['id'] ?>">
                                                <?= htmlspecialchars($p['nama_lengkap']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="id_kelompok_tani">Kelompok Tani<code>*</code></label>
                                    <select name="id_kelompok_tani" class="form-control" id="id_kelompok_tani">
                                        <option value="">-- Pilih Kelompok Tani --</option>
                                        <?php foreach ($kelompokTanis as $kt): ?>
                                            <option value="<?= $kt['id'] ?>">
                                                <?= htmlspecialchars($kt['nama_kelompok']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="id_jabatan_kelompok">Jabatan Kelompok<code>*</code></label>
                                    <select name="id_jabatan_kelompok" class="form-control" id="id_jabatan_kelompok">
                                        <option value="">-- Pilih Jabatan Kelompok --</option>
                                        <?php foreach ($jabatanKelompoks as $jk): ?>
                                            <option value="<?= $jk['id'] ?>">
                                                <?= htmlspecialchars($jk['nama']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="tanggal_gabung">Tanggal Gabung<code>*</code></label>
                                    <input type="date" name="tanggal_gabung" class="form-control" id="tanggal_gabung"
                                        placeholder="Pilih Tanggal Gabung">
                                </div>
                                <div class="form-group">
                                    <label for="tanggal_keluar">Tanggal Keluar<code>*</code></label>
                                    <input type="date" name="tanggal_keluar" class="form-control" id="tanggal_keluar"
                                        placeholder="Pilih Tanggal Keluar">
                                </div>
                                <div class="custom-control custom-checkbox">
                                    <input class="custom-control-input" type="checkbox" id="add_is_pengurus" value="1"
                                        name="is_pengurus">
                                    <label for="add_is_pengurus" class="custom-control-label">Apakah Pengurus?</label>
                                </div>
                                <div class="form-group">
                                    <label for="keterangan">Keterangan<code>*</code></label>
                                    <textarea name="keterangan" class="form-control" id="keterangan"
                                        placeholder="Masukkan Keterangan"></textarea>
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
                                    <label for="edit_id_petani">Petani<code>*</code></label>
                                    <select name="id_petani" class="form-control" id="edit_id_petani">
                                        <option value="">-- Pilih Petani --</option>
                                        <?php foreach ($petanis as $p): ?>
                                            <option value="<?= $p['id'] ?>">
                                                <?= htmlspecialchars($p['nama_lengkap']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="edit_id_kelompok_tani">Kelompok Tani<code>*</code></label>
                                    <select name="id_kelompok_tani" class="form-control" id="edit_id_kelompok_tani">
                                        <option value="">-- Pilih Kelompok Tani --</option>
                                        <?php foreach ($kelompokTanis as $kt): ?>
                                            <option value="<?= $kt['id'] ?>">
                                                <?= htmlspecialchars($kt['nama_kelompok']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="edit_id_jabatan_kelompok">Jabatan Kelompok<code>*</code></label>
                                    <select name="id_jabatan_kelompok" class="form-control" id="edit_id_jabatan_kelompok">
                                        <option value="">-- Pilih Jabatan Kelompok --</option>
                                        <?php foreach ($jabatanKelompoks as $jk): ?>
                                            <option value="<?= $jk['id'] ?>">
                                                <?= htmlspecialchars($jk['nama']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="tanggal_gabung">Tanggal Gabung<code>*</code></label>
                                    <input type="date" name="tanggal_gabung" class="form-control" id="edit_tanggal_gabung"
                                        placeholder="Pilih Tanggal Gabung">
                                </div>
                                <div class="form-group">
                                    <label for="tanggal_keluar">Tanggal Keluar<code>*</code></label>
                                    <input type="date" name="tanggal_keluar" class="form-control" id="edit_tanggal_keluar"
                                        placeholder="Pilih Tanggal Keluar">
                                </div>
                                <div class="custom-control custom-checkbox">
                                    <input class="custom-control-input" type="checkbox" id="edit_is_pengurus" value="1"
                                        name="is_pengurus">
                                    <label for="edit_is_pengurus" class="custom-control-label">Apakah Pengurus?</label>
                                </div>
                                <div class="form-group">
                                    <label for="keterangan">Keterangan<code>*</code></label>
                                    <textarea name="keterangan" class="form-control" id="edit_keterangan"
                                        placeholder="Masukkan Keterangan"></textarea>
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
                        id_petani: {
                            required: true
                        },
                        id_kelompok_tani: {
                            required: true
                        },
                        id_jabatan_kelompok: {
                            required: true
                        },
                        tanggal_gabung: {
                            required: true
                        },
                        tanggal_keluar: {
                            required: true
                        },
                        keterangan: {
                            required: true
                        },
                        is_active: {
                            required: true
                        }
                    },
                    messages: {
                        id_petani: {
                            required: "Silahkan pilih Petani"
                        },
                        id_kelompok_tani: {
                            required: "Silahkan pilih Kelompok Tani"
                        },
                        id_jabatan_kelompok: {
                            required: "Silahkan pilih Jabatan Kelompok"
                        },
                        tanggal_gabung: {
                            required: "Silahkan pilih Tanggal Gabung"
                        },
                        tanggal_keluar: {
                            required: "Silahkan pilih Tanggal Keluar"
                        },
                        keterangan: {
                            required: "Silahkan masukkan Keterangan"
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
            let id_petani = $(this).data("id_petani");
            let id_kelompok_tani = $(this).data("id_kelompok_tani");
            let id_jabatan_kelompok = $(this).data("id_jabatan_kelompok");
            let tanggal_gabung = $(this).data("tanggal_gabung");
            let tanggal_keluar = $(this).data("tanggal_keluar");
            let is_pengurus = $(this).data("is_pengurus");
            let keterangan = $(this).data("keterangan");
            let status = $(this).data("status");

            $("#edit_id").val(id);
            $("#edit_id_petani").val(id_petani);
            $("#edit_id_kelompok_tani").val(id_kelompok_tani);
            $("#edit_id_jabatan_kelompok").val(id_jabatan_kelompok);
            $("#edit_tanggal_gabung").val(tanggal_gabung);
            $("#edit_tanggal_keluar").val(tanggal_keluar);
            $("#edit_is_pengurus").prop("checked", is_pengurus == 1);
            $("#edit_keterangan").val(keterangan);
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
        document.getElementById('tanggal_gabung').addEventListener('focus', function () {
            this.showPicker();
        });
        document.getElementById('edit_tanggal_gabung').addEventListener('focus', function () {
            this.showPicker();
        });
        document.getElementById('tanggal_keluar').addEventListener('focus', function () {
            this.showPicker();
        });
        document.getElementById('edit_tanggal_keluar').addEventListener('focus', function () {
            this.showPicker();
        });
    </script>
</body>

</html>