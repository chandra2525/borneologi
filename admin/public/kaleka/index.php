<?php

require_once '../../app/config/database.php';
require_once '../../app/controllers/KalekaController.php';
require_once '../../app/core/session.php';
require_once '../../app/core/csrf.php';
require_once "../../app/core/auth.php";
require_once '../../app/helpers/escape.php';

secureSessionStart();
checkAuth("non_dashboard");

$controller = new KalekaController($pdo);
$kalekas = $controller->index();
$petanis = $controller->getPetani();
$desas = $controller->getDesa();

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
        $menu = "kaleka";
        include "../feature/sidebar.php";
        ?>

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1>Data Kaleka</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="#">Master Data</a></li>
                                <li class="breadcrumb-item active">Data Kaleka</li>
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
                                    <h3 class="card-title col-9">Berikut adalah list dari Data Kaleka</h3>
                                    <button class="col-3 btn btn-block btn-success" data-toggle="modal"
                                        data-target="#modalTambah">
                                        Tambah Kaleka
                                    </button>
                                </div>
                                <!-- /.card-header -->
                                <div class="card-body">
                                    <table id="example1" class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th>Kode Kaleka</th>
                                                <th>Nama Kaleka</th>
                                                <th>Petani</th>
                                                <th>Desa</th>
                                                <th>Luas (ha)</th>
                                                <th>Keterangan</th>
                                                <th>Status Aktif</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($kalekas as $kaleka): ?>
                                                <tr>
                                                    <td><?= htmlspecialchars($kaleka['kode_kaleka']) ?></td>
                                                    <td><?= htmlspecialchars($kaleka['nama_kaleka']) ?></td>
                                                    <td><?= htmlspecialchars($kaleka['nama_petani']) ?></td>
                                                    <td><?= htmlspecialchars($kaleka['nama_desa']) ?></td>
                                                    <td><?= htmlspecialchars($kaleka['luas_ha']) ?></td>
                                                    <td><?= htmlspecialchars($kaleka['keterangan']) ?></td>
                                                    <td><?= $kaleka['is_active'] ? 'Aktif' : 'Nonaktif' ?></td>
                                                    <td class="row">
                                                        <div class="col">
                                                            <button class="btn btn-block btn-info btn-edit"
                                                                data-id="<?= $kaleka['id'] ?>"
                                                                data-kode_kaleka="<?= htmlspecialchars($kaleka['kode_kaleka']) ?>"
                                                                data-nama_kaleka="<?= htmlspecialchars($kaleka['nama_kaleka']) ?>"
                                                                data-id_petani="<?= htmlspecialchars($kaleka['id_petani']) ?>"
                                                                data-id_desa="<?= htmlspecialchars($kaleka['id_desa']) ?>"
                                                                data-luas_ha="<?= htmlspecialchars($kaleka['luas_ha']) ?>"
                                                                data-keterangan="<?= htmlspecialchars($kaleka['keterangan']) ?>"
                                                                data-status="<?= $kaleka['is_active'] ?>"
                                                                data-toggle="modal" data-target="#modalEdit">
                                                                <i class="fas fa-edit"></i> Edit
                                                            </button>
                                                        </div>

                                                        <form method="POST" action="delete.php" class="form-delete col">
                                                            <?= csrfField() ?>
                                                            <input type="hidden" name="id" value="<?= $kaleka['id'] ?>">
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
                                                <th>Kode Kaleka</th>
                                                <th>Nama Kaleka</th>
                                                <th>Petani</th>
                                                <th>Desa</th>
                                                <th>Luas (ha)</th>
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
                            <h4 class="modal-title">Tambah Kaleka</h4>
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                        </div>
                        <form id="formTambah" method="POST" action="store.php">
                            <?= csrfField() ?>
                            <div class="modal-body">
                                <div class="form-group">
                                    <label for="kode_kaleka">Kode Kaleka<code>*</code></label>
                                    <input type="text" name="kode_kaleka" class="form-control" id="kode_kaleka"
                                        value="<?= (new Kaleka($pdo))->generateKode() ?>"
                                        placeholder="Masukkan Kode Kaleka">
                                </div>
                                <div class="form-group">
                                    <label for="nama_kaleka">Nama Kaleka<code>*</code></label>
                                    <input type="text" name="nama_kaleka" class="form-control" id="nama_kaleka"
                                        placeholder="Masukkan Nama Kaleka">
                                </div>
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
                                    <label for="luas_ha">Luas (ha)<code>*</code></label>
                                    <input type="number" name="luas_ha" class="form-control" id="luas_ha"
                                        placeholder="Masukkan Luas dalam hektar">
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
                            <h4 class="modal-title">Edit Kaleka</h4>
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                        </div>

                        <form id="formEdit" method="POST" action="update.php">
                            <?= csrfField() ?>
                            <input type="hidden" name="id" id="edit_id">
                            <div class="modal-body">
                                <div class="form-group">
                                    <label for="kode_kaleka">Kode Kaleka<code>*</code></label>
                                    <input type="text" name="kode_kaleka" class="form-control" id="edit_kode_kaleka"
                                        value="<?= (new Kaleka($pdo))->generateKode() ?>"
                                        placeholder="Masukkan Kode Kaleka">
                                </div>
                                <div class="form-group">
                                    <label for="nama_kaleka">Nama Kaleka<code>*</code></label>
                                    <input type="text" name="nama_kaleka" class="form-control" id="edit_nama_kaleka"
                                        placeholder="Masukkan Nama Kaleka">
                                </div>
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
                                    <label for="edit_luas_ha">Luas (ha)<code>*</code></label>
                                    <input type="number" name="luas_ha" class="form-control" id="edit_luas_ha"
                                        placeholder="Masukkan Luas dalam hektar">
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
                        kode_kaleka: {
                            required: true
                        },
                        nama_kaleka: {
                            required: true
                        },
                        id_petani: {
                            required: true
                        },
                        id_desa: {
                            required: true
                        },
                        luas_ha: {
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
                        kode_kaleka: {
                            required: "Silahkan masukkan Kode Kaleka"
                        },
                        nama_kaleka: {
                            required: "Silahkan masukkan Nama Kaleka"
                        },
                        id_petani: {
                            required: "Silahkan pilih Petani"
                        },
                        id_desa: {
                            required: "Silahkan pilih Desa"
                        },
                        luas_ha: {
                            required: "Silahkan masukkan Luas dalam hektar"
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
            let kode_kaleka = $(this).data("kode_kaleka");
            let nama_kaleka = $(this).data("nama_kaleka");
            let id_petani = $(this).data("id_petani");
            let id_desa = $(this).data("id_desa");
            let luas_ha = $(this).data("luas_ha");
            let keterangan = $(this).data("keterangan");
            let status = $(this).data("status");

            $("#edit_id").val(id);
            $("#edit_kode_kaleka").val(kode_kaleka);
            $("#edit_nama_kaleka").val(nama_kaleka);
            $("#edit_id_petani").val(id_petani);
            $("#edit_id_desa").val(id_desa);
            $("#edit_luas_ha").val(luas_ha);
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
            showToast("Data Kaleka berhasil ditambahkan", "created");
        }
        if (success === "updated") {
            showToast("Data Kaleka berhasil diperbarui", "updated");
        }
        if (success === "deleted") {
            showToast("Data Kaleka berhasil dihapus", "deleted");
        }
    </script>

</body>

</html>