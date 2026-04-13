<?php

require_once '../../app/config/database.php';
require_once '../../app/controllers/KecamatanController.php';
require_once '../../app/core/session.php';
require_once '../../app/core/csrf.php';
require_once "../../app/core/auth.php";
require_once '../../app/helpers/escape.php';

secureSessionStart();
checkAuth("non_dashboard");

$controller = new KecamatanController($pdo);
$kecamatan = $controller->index();
$kabupaten = $controller->getKabupaten();

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
        $menu = "kecamatan";
        include "../feature/sidebar.php";
        ?>

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1>Data Kecamatan</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="#">Master Data</a></li>
                                <li class="breadcrumb-item active">Data Kecamatan</li>
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
                                    <h3 class="card-title col-9">Berikut adalah list dari Data Kecamatan</h3>
                                    <button class="col-3 btn btn-block btn-success" data-toggle="modal"
                                        data-target="#modalTambah">
                                        Tambah Kecamatan
                                    </button>
                                </div>
                                <!-- /.card-header -->
                                <div class="card-body">
                                    <table id="example1" class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th>Nama Kabupaten</th>
                                                <th>Kode Kecamatan</th>
                                                <th>Nama Kecamatan</th>
                                                <th>Status Aktif</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($kecamatan as $kecamatan): ?>
                                                <tr>
                                                    <td><?= htmlspecialchars($kecamatan['nama_kabupaten']) ?></td>
                                                    <td><?= htmlspecialchars($kecamatan['kode_kecamatan']) ?></td>
                                                    <td><?= htmlspecialchars($kecamatan['nama_kecamatan']) ?></td>
                                                    <td><?= $kecamatan['is_active'] ? 'Aktif' : 'Nonaktif' ?></td>
                                                    <td class="row">
                                                        <div class="col">
                                                            <button class="btn btn-block btn-info btn-edit"
                                                                data-id="<?= $kecamatan['id'] ?>"
                                                                data-id_kabupaten="<?= $kecamatan['id_kabupaten'] ?>"
                                                                data-kode_kecamatan="<?= htmlspecialchars($kecamatan['kode_kecamatan']) ?>"
                                                                data-nama_kecamatan="<?= htmlspecialchars($kecamatan['nama_kecamatan']) ?>"
                                                                data-status="<?= $kecamatan['is_active'] ?>"
                                                                data-toggle="modal" data-target="#modalEdit">
                                                                <i class="fas fa-edit"></i> Edit
                                                            </button>
                                                        </div>

                                                        <form method="POST" action="delete.php" class="form-delete col">
                                                            <?= csrfField() ?>
                                                            <input type="hidden" name="id" value="<?= $kecamatan['id'] ?>">
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
                                                <th>Nama Kabupaten</th>
                                                <th>Kode Kecamatan</th>
                                                <th>Nama Kecamatan</th>
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
                            <h4 class="modal-title">Tambah Kecamatan</h4>
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                        </div>
                        <form id="formTambah" method="POST" action="store.php">
                            <?= csrfField() ?>
                            <div class="modal-body">
                                <div class="form-group">
                                    <label for="id_kabupaten">Kabupaten<code>*</code></label>
                                    <select name="id_kabupaten" class="form-control">
                                        <option value="">-- Pilih Kabupaten --</option>
                                        <?php foreach ($kabupaten as $p): ?>
                                            <option value="<?= $p['id'] ?>" data-kode="<?= $p['kode_kabupaten'] ?>">
                                                <?= htmlspecialchars($p['nama_kabupaten']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="nama_kecamatan">Nama Kecamatan<code>*</code></label>
                                    <select name="nama_kecamatan" id="nama_kecamatan" class="form-control">
                                        <option value="">-- Pilih Kecamatan --</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="kode_kecamatan">Kode Kecamatan<code>*</code></label>
                                    <input type="number" name="kode_kecamatan" class="form-control" id="kode_kecamatan"
                                        placeholder="Masukkan kode kecamatan" maxlength="6">
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
                            <h4 class="modal-title">Edit Kecamatan</h4>
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                        </div>

                        <form id="formEdit" method="POST" action="update.php">
                            <?= csrfField() ?>
                            <input type="hidden" name="id" id="edit_id">
                            <div class="modal-body">
                                <div class="form-group">
                                    <label for="edit_id_kabupaten">Kabupaten<code>*</code></label>
                                    <select name="id_kabupaten" class="form-control" id="edit_id_kabupaten">
                                        <option value="">-- Pilih Kabupaten --</option>
                                        <?php foreach ($kabupaten as $p): ?>
                                            <option value="<?= $p['id'] ?>" data-kode="<?= $p['kode_kabupaten'] ?>">
                                                <?= htmlspecialchars($p['nama_kabupaten']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="nama_kecamatan">Nama Kecamatan<code>*</code></label>
                                    <select name="nama_kecamatan" id="edit_nama" class="form-control">
                                        <option value="">-- Pilih Kecamatan --</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="kode_kecamatan">Kode Kecamatan<code>*</code></label>
                                    <input type="number" name="kode_kecamatan" id="edit_kode" class="form-control"
                                        placeholder="Masukkan kode kecamatan" maxlength="6">
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
                        id_kabupaten: {
                            required: true
                        },
                        kode_kecamatan: {
                            required: true
                        },
                        nama_kecamatan: {
                            required: true
                        },
                        is_active: {
                            required: true
                        }
                    },
                    messages: {
                        id_kabupaten: {
                            required: "Silahkan pilih Kabupaten"
                        },
                        kode_kecamatan: {
                            required: "Silahkan masukkan Kode Kecamatan"
                        },
                        nama_kecamatan: {
                            required: "Silahkan masukkan Nama Kecamatan"
                        },
                        is_active: {
                            required: "Silahkan pilih Status Kecamatan"
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
            let id_kabupaten = $(this).data("id_kabupaten");
            let kode_kecamatan = $(this).data("kode_kecamatan");
            let nama_kecamatan = $(this).data("nama_kecamatan");
            let status = $(this).data("status");

            let kode_kabupaten = $(this).closest("tr").find("button").data("kode_kabupaten");

            $("#edit_id").val(id);
            $("#edit_id_kabupaten").val(id_kabupaten);

            // 🔥 Ambil kode kabupaten dari option
            let selectedOption = $("#edit_id_kabupaten option[value='" + id_kabupaten + "']");
            let kodeKab = selectedOption.data("kode");

            if (!kodeKab) return;

            // // 🔥 Load kecamatan dulu
            // loadKecamatan(kodeKab, "#edit_nama");

            // // 🔥 Tunggu sampai selesai load
            // setTimeout(function () {
            //     $("#edit_nama").val(nama_kecamatan);
            //     $("#edit_kode").val(kode_kecamatan);
            // }, 300);

            loadKecamatan(kodeKab, "#edit_nama", function () {
                $("#edit_nama").val(nama_kecamatan);
                $("#edit_kode").val(kode_kecamatan);
            });

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
            showToast("Data Kecamatan berhasil ditambahkan", "created");
        }
        if (success === "updated") {
            showToast("Data Kecamatan berhasil diperbarui", "updated");
        }
        if (success === "deleted") {
            showToast("Data Kecamatan berhasil dihapus", "deleted");
        }
    </script>

    <script>
        let kecamatanData = [];

        function loadKecamatan(kode_kabupaten, targetSelect, callback = null) {
            let url = `https://ibnux.github.io/data-indonesia/kecamatan/${kode_kabupaten}.json`;

            $.getJSON(url, function (data) {
                $(targetSelect).html('<option>Loading...</option>');

                // kosongkan select
                $(targetSelect).empty().append('<option value="">-- Pilih Kecamatan --</option>');

                // isi select
                data.forEach(item => {
                    $(targetSelect).append(`
                <option value="${item.nama}" 
                        data-kode="${item.id}">
                    ${item.nama}
                </option>
            `);
                });

                // simpan ke global
                kecamatanData = data.map(item => ({
                    kode_kecamatan: item.id,
                    nama_kecamatan: item.nama
                }));

                if (callback) callback();
            }).fail(function () {
                alert("Gagal mengambil data kecamatan dari API");
            });
        }

        $("select[name='id_kabupaten']").on("change", function () {
            // let kode_kabupaten = $(this).val().padStart(2, '0'); // 1 → 
            // loadKecamatan(kode_kabupaten, "#nama_kecamatan");
            let kode_kabupaten = $(this).find(':selected').data('kode');
            if (!kode_kabupaten) return;
            loadKecamatan(kode_kabupaten, "#nama_kecamatan");
        });

        $("#edit_id_kabupaten").on("change", function () {
            // let kode_kabupaten = $(this).val().padStart(2, '0');
            // loadKecamatan(kode_kabupaten, "#edit_nama");
            let kode_kabupaten = $(this).find(':selected').data('kode');
            if (!kode_kabupaten) return;
            loadKecamatan(kode_kabupaten, "#edit_nama");
        });

        // const kecamatanData = [
        //     { "kode_kecamatan": "01", "nama_kecamatan": "Kecamatan Kotawaringin Barat" },
        //     { "kode_kecamatan": "02", "nama_kecamatan": "Kecamatan Kotawaringin Timur" },
        //     { "kode_kecamatan": "03", "nama_kecamatan": "Kecamatan Kapuas" },
        //     { "kode_kecamatan": "04", "nama_kecamatan": "Kecamatan Barito Selatan" },
        //     { "kode_kecamatan": "05", "nama_kecamatan": "Kecamatan Barito Utara" },
        //     { "kode_kecamatan": "06", "nama_kecamatan": "Kecamatan Sukamara" },
        //     { "kode_kecamatan": "07", "nama_kecamatan": "Kecamatan Lamandau" },
        //     { "kode_kecamatan": "08", "nama_kecamatan": "Kecamatan Seruyan" },
        //     { "kode_kecamatan": "09", "nama_kecamatan": "Kecamatan Katingan" },
        //     { "kode_kecamatan": "10", "nama_kecamatan": "Kecamatan Pulang Pisau" },
        //     { "kode_kecamatan": "11", "nama_kecamatan": "Kecamatan Gunung Mas" },
        //     { "kode_kecamatan": "12", "nama_kecamatan": "Kecamatan Barito Timur" },
        //     { "kode_kecamatan": "13", "nama_kecamatan": "Kecamatan Murung Raya" },
        //     { "kode_kecamatan": "71", "nama_kecamatan": "Kota Palangka Raya" }
        // ];

        // $(function () {
        //     kecamatanData.forEach(p => {
        //         $("#nama_kecamatan").append(
        //             `<option value="${p.nama_kecamatan}" 
        //                 data-kode="${p.kode_kecamatan}">
        //                 ${p.nama_kecamatan}
        //             </option>`
        //         );

        //         $("#edit_nama").append(
        //             `<option value="${p.nama_kecamatan}" 
        //                 data-kode="${p.kode_kecamatan}">
        //                 ${p.nama_kecamatan}
        //             </option>`
        //         );
        //     });
        // });

        $(function () {

            // ================= TAMBAH =================
            $("#nama_kecamatan").on("change", function () {
                let selected = $(this).find(":selected");

                $("#kode_kecamatan").val(selected.data("kode") || "");
            });

            $("#kode_kecamatan").on("keyup", function () {
                let kodeInput = $(this).val();

                let found = kecamatanData.find(p => p.kode_kecamatan === kodeInput);

                if (found) {
                    $("#nama_kecamatan").val(found.nama_kecamatan);
                }
            });


            // ================= EDIT =================
            $("#edit_nama").on("change", function () {
                let selected = $(this).find(":selected");

                $("#edit_kode").val(selected.data("kode") || "");
            });

            $("#edit_kode").on("keyup", function () {
                let kodeInput = $(this).val();

                let found = kecamatanData.find(p => p.kode_kecamatan === kodeInput);

                if (found) {
                    $("#edit_nama").val(found.nama_kecamatan);
                }
            });

        });
    </script>
</body>

</html>