<?php

require_once '../../app/config/database.php';
require_once '../../app/controllers/KabupatenController.php';
require_once '../../app/core/session.php';
require_once '../../app/core/csrf.php';
require_once "../../app/core/auth.php";
require_once '../../app/helpers/escape.php';

secureSessionStart();
checkAuth("non_dashboard");

$controller = new KabupatenController($pdo);
$kabupaten = $controller->index();
$provinsi = $controller->getProvinsi();

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
        $menu = "kabupaten";
        include "../feature/sidebar.php";
        ?>

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1>Data Kabupaten</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="#">Master Data</a></li>
                                <li class="breadcrumb-item active">Data Kabupaten</li>
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
                                    <h3 class="card-title col-9">Berikut adalah list dari Data Kabupaten</h3>
                                    <button class="col-3 btn btn-block btn-success" data-toggle="modal"
                                        data-target="#modalTambah">
                                        Tambah Kabupaten
                                    </button>
                                </div>
                                <!-- /.card-header -->
                                <div class="card-body">
                                    <table id="example1" class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th>Nama Provinsi</th>
                                                <th>Kode Kabupaten</th>
                                                <th>Nama Kabupaten</th>
                                                <th>Tipe</th>
                                                <th>Status Aktif</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($kabupaten as $kabupaten): ?>
                                                <tr>
                                                    <td><?= htmlspecialchars($kabupaten['nama_provinsi']) ?></td>
                                                    <td><?= htmlspecialchars($kabupaten['kode_kabupaten']) ?></td>
                                                    <td><?= htmlspecialchars($kabupaten['nama_kabupaten']) ?></td>
                                                    <td><?= htmlspecialchars($kabupaten['tipe']) ?></td>
                                                    <td><?= $kabupaten['is_active'] ? 'Aktif' : 'Nonaktif' ?></td>
                                                    <td class="row">
                                                        <div class="col">
                                                            <button class="btn btn-block btn-info btn-edit"
                                                                data-id="<?= $kabupaten['id'] ?>"
                                                                data-id_provinsi="<?= $kabupaten['id_provinsi'] ?>"
                                                                data-kode_kabupaten="<?= htmlspecialchars($kabupaten['kode_kabupaten']) ?>"
                                                                data-nama_kabupaten="<?= htmlspecialchars($kabupaten['nama_kabupaten']) ?>"
                                                                data-tipe="<?= htmlspecialchars($kabupaten['tipe']) ?>"
                                                                data-status="<?= $kabupaten['is_active'] ?>"
                                                                data-toggle="modal" data-target="#modalEdit">
                                                                <i class="fas fa-edit"></i> Edit
                                                            </button>
                                                        </div>

                                                        <form method="POST" action="delete.php" class="form-delete col">
                                                            <?= csrfField() ?>
                                                            <input type="hidden" name="id" value="<?= $kabupaten['id'] ?>">
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
                                                <th>Nama Provinsi</th>
                                                <th>Kode Kabupaten</th>
                                                <th>Nama Kabupaten</th>
                                                <th>Tipe</th>
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
                            <h4 class="modal-title">Tambah Kabupaten</h4>
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                        </div>
                        <form id="formTambah" method="POST" action="store.php">
                            <?= csrfField() ?>
                            <div class="modal-body">
                                <div class="form-group">
                                    <label for="id_provinsi">Provinsi<code>*</code></label>
                                    <select name="id_provinsi" class="form-control">
                                        <option value="">-- Pilih Provinsi --</option>
                                        <?php foreach ($provinsi as $p): ?>
                                            <option value="<?= $p['id'] ?>">
                                                <?= htmlspecialchars($p['nama_provinsi']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="nama_kabupaten">Nama Kabupaten<code>*</code></label>
                                    <select name="nama_kabupaten" id="nama_kabupaten" class="form-control">
                                        <option value="">-- Pilih Kabupaten --</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="kode_kabupaten">Kode Kabupaten<code>*</code></label>
                                    <input type="number" name="kode_kabupaten" class="form-control" id="kode_kabupaten"
                                        placeholder="Masukkan kode kabupaten" maxlength="4">
                                </div>
                                <div class="form-group">
                                    <div class="form-group">
                                        <label>Tipe<code>*</code></label>
                                        <input type="text" name="tipe" id="tipe" class="form-control" readonly>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>Status Aktif <code>*</code></label>
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
                            <h4 class="modal-title">Edit Kabupaten</h4>
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                        </div>

                        <form id="formEdit" method="POST" action="update.php">
                            <?= csrfField() ?>
                            <input type="hidden" name="id" id="edit_id">
                            <div class="modal-body">
                                <div class="form-group">
                                    <label for="edit_id_provinsi">Provinsi<code>*</code></label>
                                    <select name="id_provinsi" class="form-control" id="edit_id_provinsi">
                                        <option value="">-- Pilih Provinsi --</option>
                                        <?php foreach ($provinsi as $p): ?>
                                            <option value="<?= $p['id'] ?>">
                                                <?= htmlspecialchars($p['nama_provinsi']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="nama_kabupaten">Nama Kabupaten<code>*</code></label>
                                    <select name="nama_kabupaten" id="edit_nama" class="form-control">
                                        <option value="">-- Pilih Kabupaten --</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="kode_kabupaten">Kode Kabupaten<code>*</code></label>
                                    <input type="number" name="kode_kabupaten" id="edit_kode" class="form-control"
                                        maxlength="4" placeholder="Masukkan kode kabupaten">
                                </div>
                                <div class="form-group">
                                    <label>Tipe<code>*</code></label>
                                    <input type="text" name="tipe" id="edit_tipe" class="form-control" readonly>
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
                        id_provinsi: {
                            required: true
                        },
                        kode_kabupaten: {
                            required: true
                        },
                        nama_kabupaten: {
                            required: true
                        },
                        is_active: {
                            required: true
                        }
                    },
                    messages: {
                        id_provinsi: {
                            required: "Silahkan pilih Role Pengguna"
                        },
                        kode_kabupaten: {
                            required: "Silahkan masukkan Kode Kabupaten"
                        },
                        nama_kabupaten: {
                            required: "Silahkan masukkan Nama Kabupaten"
                        },
                        is_active: {
                            required: "Silahkan pilih Status Kabupaten"
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
            let id_provinsi = $(this).data("id_provinsi");
            let kode_kabupaten = $(this).data("kode_kabupaten");
            let nama_kabupaten = $(this).data("nama_kabupaten");
            let tipe = $(this).data("tipe");
            let status = $(this).data("status");

            $("#edit_id").val(id);
            $("#edit_id_provinsi").val(id_provinsi);
            $("#edit_kode").val(kode_kabupaten);
            $("#edit_nama").val(nama_kabupaten).trigger("change");
            $("#edit_tipe").val(tipe);
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
            showToast("Data Kabupaten berhasil ditambahkan", "created");
        }
        if (success === "updated") {
            showToast("Data Kabupaten berhasil diperbarui", "updated");
        }
        if (success === "deleted") {
            showToast("Data Kabupaten berhasil dihapus", "deleted");
        }
    </script>

    <script>
        const kabupatenData = [
            { "kode_kabupaten": "6201", "nama_kabupaten": "Kabupaten Kotawaringin Barat", "tipe": "kabupaten" },
            { "kode_kabupaten": "6202", "nama_kabupaten": "Kabupaten Kotawaringin Timur", "tipe": "kabupaten" },
            { "kode_kabupaten": "6203", "nama_kabupaten": "Kabupaten Kapuas", "tipe": "kabupaten" },
            { "kode_kabupaten": "6204", "nama_kabupaten": "Kabupaten Barito Selatan", "tipe": "kabupaten" },
            { "kode_kabupaten": "6205", "nama_kabupaten": "Kabupaten Barito Utara", "tipe": "kabupaten" },
            { "kode_kabupaten": "6206", "nama_kabupaten": "Kabupaten Sukamara", "tipe": "kabupaten" },
            { "kode_kabupaten": "6207", "nama_kabupaten": "Kabupaten Lamandau", "tipe": "kabupaten" },
            { "kode_kabupaten": "6208", "nama_kabupaten": "Kabupaten Seruyan", "tipe": "kabupaten" },
            { "kode_kabupaten": "6209", "nama_kabupaten": "Kabupaten Katingan", "tipe": "kabupaten" },
            { "kode_kabupaten": "6210", "nama_kabupaten": "Kabupaten Pulang Pisau", "tipe": "kabupaten" },
            { "kode_kabupaten": "6211", "nama_kabupaten": "Kabupaten Gunung Mas", "tipe": "kabupaten" },
            { "kode_kabupaten": "6212", "nama_kabupaten": "Kabupaten Barito Timur", "tipe": "kabupaten" },
            { "kode_kabupaten": "6213", "nama_kabupaten": "Kabupaten Murung Raya", "tipe": "kabupaten" },
            { "kode_kabupaten": "6271", "nama_kabupaten": "Kota Palangka Raya", "tipe": "kota" }
        ];

        $(function () {
            kabupatenData.forEach(p => {
                $("#nama_kabupaten").append(
                    `<option value="${p.nama_kabupaten}" 
                        data-kode="${p.kode_kabupaten}" 
                        data-tipe="${p.tipe}">
                        ${p.nama_kabupaten}
                    </option>`
                );

                $("#edit_nama").append(
                    `<option value="${p.nama_kabupaten}" 
                        data-kode="${p.kode_kabupaten}" 
                        data-tipe="${p.tipe}">
                        ${p.nama_kabupaten}
                    </option>`
                );
            });
        });

        $(function () {

            // ================= TAMBAH =================
            $("#nama_kabupaten").on("change", function () {
                let selected = $(this).find(":selected");

                $("#kode_kabupaten").val(selected.data("kode") || "");
                $("#tipe").val(selected.data("tipe") || "");
            });

            $("#kode_kabupaten").on("keyup", function () {
                let kodeInput = $(this).val();

                let found = kabupatenData.find(p => p.kode_kabupaten === kodeInput);

                if (found) {
                    $("#nama_kabupaten").val(found.nama_kabupaten);
                    $("#tipe").val(found.tipe);
                }
            });


            // ================= EDIT =================
            $("#edit_nama").on("change", function () {
                let selected = $(this).find(":selected");

                $("#edit_kode").val(selected.data("kode") || "");
                $("#edit_tipe").val(selected.data("tipe") || "");
            });

            $("#edit_kode").on("keyup", function () {
                let kodeInput = $(this).val();

                let found = kabupatenData.find(p => p.kode_kabupaten === kodeInput);

                if (found) {
                    $("#edit_nama").val(found.nama_kabupaten);
                    $("#edit_tipe").val(found.tipe);
                }
            });

        });
    </script>
</body>

</html>