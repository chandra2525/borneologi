<?php

require_once '../../app/config/database.php';
require_once '../../app/controllers/ProvinsiController.php';
require_once '../../app/core/session.php';
require_once '../../app/core/csrf.php';
require_once "../../app/core/auth.php";
require_once '../../app/helpers/escape.php';

secureSessionStart();
checkAuth("non_dashboard");

$controller = new ProvinsiController($pdo);
$provinsi = $controller->index();

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
        $menu = "provinsi";
        include "../feature/sidebar.php";
        ?>

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1>Data Provinsi</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="#">Master Data</a></li>
                                <li class="breadcrumb-item active">Data Provinsi</li>
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
                                    <h3 class="card-title col-9">Berikut adalah list dari Data Provinsi</h3>
                                    <button class="col-3 btn btn-block btn-success" data-toggle="modal"
                                        data-target="#modalTambah">
                                        Tambah Provinsi
                                    </button>
                                </div>
                                <!-- /.card-header -->
                                <div class="card-body">
                                    <table id="example1" class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th>Kode Provinsi</th>
                                                <th>Nama Provinsi</th>
                                                <th>Status Aktif</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($provinsi as $provinsi): ?>
                                                <tr>
                                                    <td><?= htmlspecialchars($provinsi['kode_provinsi']) ?></td>
                                                    <td><?= htmlspecialchars($provinsi['nama_provinsi']) ?></td>
                                                    <td><?= $provinsi['is_active'] ? 'Aktif' : 'Nonaktif' ?></td>
                                                    <td class="row">
                                                        <div class="col">
                                                            <button class="btn btn-block btn-info btn-edit"
                                                                data-id="<?= $provinsi['id'] ?>"
                                                                data-kode_provinsi="<?= htmlspecialchars($provinsi['kode_provinsi']) ?>"
                                                                data-nama_provinsi="<?= htmlspecialchars($provinsi['nama_provinsi']) ?>"
                                                                data-status="<?= $provinsi['is_active'] ?>"
                                                                data-toggle="modal" data-target="#modalEdit">
                                                                <i class="fas fa-edit"></i> Edit
                                                            </button>
                                                        </div>

                                                        <form method="POST" action="delete.php" class="form-delete col">
                                                            <?= csrfField() ?>
                                                            <input type="hidden" name="id" value="<?= $provinsi['id'] ?>">
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
                                                <th>Kode Provinsi</th>
                                                <th>Nama Provinsi</th>
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
                            <h4 class="modal-title">Tambah Provinsi</h4>
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                        </div>
                        <form id="formTambah" method="POST" action="store.php">
                            <?= csrfField() ?>
                            <div class="modal-body">
                                <div class="form-group">
                                    <label for="nama_provinsi">Nama Provinsi<code>*</code></label>
                                    <!-- <input type="text" name="nama_provinsi" class="form-control" id="nama_provinsi"
                                        placeholder="Masukkan nama provinsi"> -->
                                    <select name="nama_provinsi" id="nama_provinsi" class="form-control">
                                        <option value="">-- Pilih Provinsi --</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="kode_provinsi">Kode Provinsi<code>*</code></label>
                                    <input type="number" name="kode_provinsi" class="form-control" id="kode_provinsi"
                                        placeholder="Masukkan kode provinsi" maxlength="2">
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
                            <h4 class="modal-title">Edit Provinsi</h4>
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                        </div>

                        <form id="formEdit" method="POST" action="update.php">
                            <?= csrfField() ?>
                            <input type="hidden" name="id" id="edit_id">
                            <div class="modal-body">
                                <div class="form-group">
                                    <label for="nama_provinsi">Nama Provinsi<code>*</code></label>
                                    <!-- <input type="text" name="nama_provinsi" id="edit_nama" class="form-control"> -->
                                    <select name="nama_provinsi" id="edit_nama" class="form-control">
                                        <option value="">-- Pilih Provinsi --</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="kode_provinsi">Kode Provinsi<code>*</code></label>
                                    <input type="number" name="kode_provinsi" id="edit_kode" class="form-control"
                                        maxlength="2">
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
                        kode_provinsi: {
                            required: true
                        },
                        nama_provinsi: {
                            required: true
                        },
                        is_active: {
                            required: true
                        }
                    },
                    messages: {
                        kode_provinsi: {
                            required: "Silahkan masukkan Kode Provinsi"
                        },
                        nama_provinsi: {
                            required: "Silahkan masukkan Nama Provinsi"
                        },
                        is_active: {
                            required: "Silahkan pilih Status Provinsi"
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
            let kode_provinsi = $(this).data("kode_provinsi");
            let nama_provinsi = $(this).data("nama_provinsi");
            let status = $(this).data("status");

            $("#edit_id").val(id);
            $("#edit_kode").val(kode_provinsi);
            $("#edit_nama").val(nama_provinsi);
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
            showToast("Data Provinsi berhasil ditambahkan", "created");
        }
        if (success === "updated") {
            showToast("Data Provinsi berhasil diperbarui", "updated");
        }
        if (success === "deleted") {
            showToast("Data Provinsi berhasil dihapus", "deleted");
        }
    </script>

    <script>
        const provinsiData = [
            { "kode_provinsi": "11", "nama_provinsi": "Aceh" },
            { "kode_provinsi": "12", "nama_provinsi": "Sumatera Utara" },
            { "kode_provinsi": "13", "nama_provinsi": "Sumatera Barat" },
            { "kode_provinsi": "14", "nama_provinsi": "Riau" },
            { "kode_provinsi": "15", "nama_provinsi": "Jambi" },
            { "kode_provinsi": "16", "nama_provinsi": "Sumatera Selatan" },
            { "kode_provinsi": "17", "nama_provinsi": "Bengkulu" },
            { "kode_provinsi": "18", "nama_provinsi": "Lampung" },
            { "kode_provinsi": "19", "nama_provinsi": "Kepulauan Bangka Belitung" },
            { "kode_provinsi": "21", "nama_provinsi": "Kepulauan Riau" },
            { "kode_provinsi": "31", "nama_provinsi": "DKI Jakarta" },
            { "kode_provinsi": "32", "nama_provinsi": "Jawa Barat" },
            { "kode_provinsi": "33", "nama_provinsi": "Jawa Tengah" },
            { "kode_provinsi": "34", "nama_provinsi": "DI Yogyakarta" },
            { "kode_provinsi": "35", "nama_provinsi": "Jawa Timur" },
            { "kode_provinsi": "36", "nama_provinsi": "Banten" },
            { "kode_provinsi": "51", "nama_provinsi": "Bali" },
            { "kode_provinsi": "52", "nama_provinsi": "Nusa Tenggara Barat" },
            { "kode_provinsi": "53", "nama_provinsi": "Nusa Tenggara Timur" },
            { "kode_provinsi": "61", "nama_provinsi": "Kalimantan Barat" },
            { "kode_provinsi": "62", "nama_provinsi": "Kalimantan Tengah" },
            { "kode_provinsi": "63", "nama_provinsi": "Kalimantan Selatan" },
            { "kode_provinsi": "64", "nama_provinsi": "Kalimantan Timur" },
            { "kode_provinsi": "65", "nama_provinsi": "Kalimantan Utara" },
            { "kode_provinsi": "71", "nama_provinsi": "Sulawesi Utara" },
            { "kode_provinsi": "72", "nama_provinsi": "Sulawesi Tengah" },
            { "kode_provinsi": "73", "nama_provinsi": "Sulawesi Selatan" },
            { "kode_provinsi": "74", "nama_provinsi": "Sulawesi Tenggara" },
            { "kode_provinsi": "75", "nama_provinsi": "Gorontalo" },
            { "kode_provinsi": "76", "nama_provinsi": "Sulawesi Barat" },
            { "kode_provinsi": "81", "nama_provinsi": "Maluku" },
            { "kode_provinsi": "82", "nama_provinsi": "Maluku Utara" },
            { "kode_provinsi": "91", "nama_provinsi": "Papua Barat" },
            { "kode_provinsi": "92", "nama_provinsi": "Papua Barat Daya" },
            { "kode_provinsi": "94", "nama_provinsi": "Papua" },
            { "kode_provinsi": "95", "nama_provinsi": "Papua Selatan" },
            { "kode_provinsi": "96", "nama_provinsi": "Papua Tengah" },
            { "kode_provinsi": "97", "nama_provinsi": "Papua Pegunungan" }
        ];

        $(function () {
            provinsiData.forEach(p => {
                $("#nama_provinsi").append(
                    `<option value="${p.nama_provinsi}" data-kode="${p.kode_provinsi}">
                        ${p.nama_provinsi}
                    </option>`
                );

                $("#edit_nama").append(
                    `<option value="${p.nama_provinsi}" data-kode="${p.kode_provinsi}">
                        ${p.nama_provinsi}
                    </option>`
                );

                $("#edit_nama").on("change", function () {
                    let kode = $(this).find(":selected").data("kode");
                    $("#edit_kode").val(kode);
                });

                $("#edit_kode").on("keyup", function () {
                    let kodeInput = $(this).val();
                    let found = provinsiData.find(p => p.kode_provinsi === kodeInput);
                    if (found) {
                        $("#edit_nama").val(found.nama_provinsi);
                    }
                });
            });
        });

        $("#nama_provinsi").on("change", function () {
            let kode = $(this).find(":selected").data("kode");
            $("#kode_provinsi").val(kode);
        });

        $("#kode_provinsi").on("keyup", function () {
            let kodeInput = $(this).val();
            let found = provinsiData.find(p => p.kode_provinsi === kodeInput);
            if (found) {
                $("#nama_provinsi").val(found.nama_provinsi);
            }
        });
    </script>
</body>

</html>