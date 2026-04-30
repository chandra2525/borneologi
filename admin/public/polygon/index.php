<?php

require_once '../../app/config/database.php';
require_once '../../app/controllers/PolygonController.php';
require_once '../../app/core/session.php';
require_once '../../app/core/csrf.php';
require_once "../../app/core/auth.php";
require_once '../../app/helpers/escape.php';

secureSessionStart();
checkAuth("non_dashboard");

$controller = new PolygonController($pdo);
$polygons = $controller->index();
$hutanAdats = $controller->getHutanAdat();
$provinsis = $controller->getProvinsi();
$kabupatens = $controller->getKabupaten();
$kecamatans = $controller->getKecamatan();

function getRelasiTipeLabel($tipe)
{
    switch ($tipe) {
        case 'provinsi':
            return 'Provinsi';
        case 'kabupaten':
            return 'Kabupaten';
        case 'hutan_adat':
            return 'Hutan Adat';
        case 'kecamatan':
            return 'Kecamatan';
        case 'tanpa_relasi':
            return 'Tanpa Relasi';
        default:
            return 'Lainnya';
    }
}

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
        $menu = "polygon";
        include "../feature/sidebar.php";
        ?>

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1>Data Polygon</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="#">Master Data</a></li>
                                <li class="breadcrumb-item active">Data Polygon</li>
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
                                    <h3 class="card-title col-9">Berikut adalah list dari Data Polygon</h3>
                                    <button class="col-3 btn btn-block btn-success" data-toggle="modal"
                                        data-target="#modalTambah">
                                        Tambah Polygon
                                    </button>
                                </div>
                                <!-- /.card-header -->
                                <div class="card-body">
                                    <table id="example1" class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th>Kode Polygon</th>
                                                <th>Nama Polygon</th>
                                                <th>Geometri Area</th>
                                                <th>Relasi Nama</th>
                                                <th>Relasi Tipe</th>
                                                <th>Status Aktif</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($polygons as $polygon): ?>
                                                <tr>
                                                    <td><?= htmlspecialchars($polygon['kode_polygon']) ?></td>
                                                    <td><?= htmlspecialchars($polygon['nama_polygon']) ?></td>
                                                    <td>
                                                        <?php if (!empty($polygon['geom_area'])): ?>
                                                            <button class="btn btn-block btn-primary btn-geom btn-sm"
                                                                data-geom="<?= htmlspecialchars($polygon['geom_area']) ?>"
                                                                data-toggle="modal" data-target="#modalGeom">
                                                                <i class="fas fa-list"></i>
                                                                Lihat
                                                            </button>
                                                        <?php else: ?>
                                                            <span class="text-muted">Belum ada area</span>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td><?= htmlspecialchars($polygon['relasi_nama']) ?>
                                                    <td><?= htmlspecialchars(getRelasiTipeLabel($polygon['relasi_tipe'])) ?>
                                                    </td>
                                                    <td><?= $polygon['is_active'] ? 'Aktif' : 'Nonaktif' ?></td>
                                                    <td class="row">
                                                        <div class="col">
                                                            <button class="btn btn-block btn-info btn-edit"
                                                                data-id="<?= $polygon['id'] ?>"
                                                                data-kode_polygon="<?= htmlspecialchars($polygon['kode_polygon']) ?>"
                                                                data-nama_polygon="<?= htmlspecialchars($polygon['nama_polygon']) ?>"
                                                                data-relasi_id="<?= $polygon['relasi_id'] ?>"
                                                                data-relasi_tipe="<?= $polygon['relasi_tipe'] ?>"
                                                                data-status="<?= $polygon['is_active'] ?>"
                                                                data-toggle="modal" data-target="#modalEdit">
                                                                <i class="fas fa-edit"></i> Edit
                                                                <!-- data-geom_area="<?= htmlspecialchars($polygon['geom_area']) ?>" -->
                                                            </button>
                                                        </div>

                                                        <form method="POST" action="delete.php" class="form-delete col">
                                                            <?= csrfField() ?>
                                                            <input type="hidden" name="id" value="<?= $polygon['id'] ?>">
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
                                                <th>Kode Polygon</th>
                                                <th>Nama Polygon</th>
                                                <th>Geometri Area</th>
                                                <th>Relasi Nama</th>
                                                <th>Relasi Tipe</th>
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
                            <h4 class="modal-title">Tambah Polygon</h4>
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                        </div>
                        <form id="formTambah" method="POST" action="store.php" enctype="multipart/form-data">
                            <?= csrfField() ?>
                            <div class="modal-body">
                                <div class="form-group">
                                    <label for="kode_polygon">Kode Polygon<code>*</code></label>
                                    <input type="text" name="kode_polygon" class="form-control" id="kode_polygon"
                                        value="<?= (new Polygon($pdo))->generateKode() ?>"
                                        placeholder="Masukkan Kode Polygon">
                                </div>
                                <div class="form-group">
                                    <label for="nama_polygon">Nama Polygon<code>*</code></label>
                                    <input type="text" name="nama_polygon" class="form-control" id="nama_polygon"
                                        placeholder="Masukkan Nama Polygon">
                                </div>
                                <div class="form-group">
                                    <label>Upload Shapefile (.zip) <code>*</code></label>
                                    <input type="file" name="shp_file" class="form-control"
                                        accept=".zip,application/zip,application/x-zip-compressed">
                                    <small class="text-muted">Harus berisi: .shp, .shx, .dbf, .prj</small>
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
                            <h4 class="modal-title">Edit Polygon</h4>
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                        </div>

                        <form id="formEdit" method="POST" action="update.php">
                            <?= csrfField() ?>
                            <input type="hidden" name="id" id="edit_id">
                            <div class="modal-body">
                                <div class="form-group">
                                    <label for="kode_polygon">Kode Polygon<code>*</code></label>
                                    <input type="text" name="kode_polygon" class="form-control" id="edit_kode_polygon"
                                        value="<?= (new Polygon($pdo))->generateKode() ?>"
                                        placeholder="Masukkan Kode Polygon">
                                </div>
                                <div class="form-group">
                                    <label for="nama_polygon">Nama Polygon<code>*</code></label>
                                    <input type="text" name="nama_polygon" class="form-control" id="edit_nama_polygon"
                                        placeholder="Masukkan Nama Polygon">
                                </div>
                                <!-- <div class="form-group">
                                    <label>Upload Shapefile (.zip)</label>
                                    <input type="file" name="shp_file" class="form-control" accept=".zip">
                                </div> -->
                                <div class="form-group">
                                    <label>Relasi Tipe<code>*</code></label>
                                    <select name="relasi_tipe" class="form-control" id="edit_relasi_tipe">
                                        <option value="">-- Pilih Relasi --</option>
                                        <option value="hutan_adat">Hutan Adat</option>
                                        <option value="provinsi">Provinsi</option>
                                        <option value="kabupaten">Kabupaten</option>
                                        <option value="kecamatan">Kecamatan</option>
                                    </select>
                                </div>

                                <div class="form-group" id="group_hutan_adat" style="display:none;">
                                    <label>Hutan Adat<code>*</code></label>
                                    <select class="form-control relasi-dropdown" id="edit_hutan_adat" name="hutan_adat">
                                        <option value="">-- Pilih Hutan Adat --</option>
                                        <?php foreach ($hutanAdats as $hA): ?>
                                            <option value="<?= $hA['id'] ?>">
                                                <?= htmlspecialchars($hA['nama_hutan_adat']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <div class="form-group" id="group_provinsi" style="display:none;">
                                    <label>Provinsi<code>*</code></label>
                                    <select class="form-control relasi-dropdown" id="edit_provinsi" name="provinsi">
                                        <option value="">-- Pilih Provinsi --</option>
                                        <?php foreach ($provinsis as $p): ?>
                                            <option value="<?= $p['id'] ?>">
                                                <?= htmlspecialchars($p['nama_provinsi']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <div class="form-group" id="group_kabupaten" style="display:none;">
                                    <label>Kabupaten<code>*</code></label>
                                    <select class="form-control relasi-dropdown" id="edit_kabupaten" name="kabupaten">
                                        <option value="">-- Pilih Kabupaten --</option>
                                        <?php foreach ($kabupatens as $k): ?>
                                            <option value="<?= $k['id'] ?>">
                                                <?= htmlspecialchars($k['nama_kabupaten']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <div class="form-group" id="group_kecamatan" style="display:none;">
                                    <label>Kecamatan<code>*</code></label>
                                    <select class="form-control relasi-dropdown" id="edit_kecamatan" name="kecamatan">
                                        <option value="">-- Pilih Kecamatan --</option>
                                        <?php foreach ($kecamatans as $k): ?>
                                            <option value="<?= $k['id'] ?>">
                                                <?= htmlspecialchars($k['nama_kecamatan']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <!-- hidden untuk dikirim ke backend -->
                                <input type="hidden" name="relasi_id" id="edit_relasi_id">

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

            <div class="modal fade" id="modalGeom">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">Data Geometri Area</h4>
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                        </div>

                        <div class="modal-body" id="geom_content">
                            <!-- isi dari JS -->
                        </div>

                        <div class="modal-footer">
                            <button class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                        </div>
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
                        kode_polygon: {
                            required: true
                        },
                        nama_polygon: {
                            required: true
                        },
                        relasi_tipe: {
                            required: true
                        },
                        relasi_id: {
                            required: true
                        },
                        is_active: {
                            required: true
                        }
                    },
                    messages: {
                        kode_polygon: {
                            required: "Silahkan masukkan Kode Polygon"
                        },
                        nama_polygon: {
                            required: "Silahkan masukkan Nama Polygon"
                        },
                        relasi_tipe: {
                            required: "Silahkan pilih Relasi Tipe"
                        },
                        relasi_id: {
                            required: "Silahkan pilih data relasi"
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

            let validator = $("#formEdit").validate();

            // dynamic rules
            $("#edit_hutan_adat").rules("add", {
                required: function () {
                    return $("#edit_relasi_tipe").val() === "hutan_adat";
                },
                messages: {
                    required: "Silahkan pilih Hutan Adat"
                }
            });

            $("#edit_provinsi").rules("add", {
                required: function () {
                    return $("#edit_relasi_tipe").val() === "provinsi";
                },
                messages: {
                    required: "Silahkan pilih Provinsi"
                }
            });

            $("#edit_kabupaten").rules("add", {
                required: function () {
                    return $("#edit_relasi_tipe").val() === "kabupaten";
                },
                messages: {
                    required: "Silahkan pilih Kabupaten"
                }
            });

            $("#edit_kecamatan").rules("add", {
                required: function () {
                    return $("#edit_relasi_tipe").val() === "kecamatan";
                },
                messages: {
                    required: "Silahkan pilih Kecamatan"
                }
            });
        });
    </script>

    <script>
        $(document).on("click", ".btn-edit", function () {
            let id = $(this).data("id");
            let kode_polygon = $(this).data("kode_polygon");
            let nama_polygon = $(this).data("nama_polygon");
            // let geom_area = $(this).data("geom_area");
            let relasi_id = $(this).data("relasi_id");
            let relasi_tipe = $(this).data("relasi_tipe");
            let status = $(this).data("status");

            $("#edit_id").val(id);
            $("#edit_kode_polygon").val(kode_polygon);
            $("#edit_nama_polygon").val(nama_polygon);
            $("input[name='is_active'][value='" + status + "']").prop("checked", true);

            // set relasi tipe
            $("#edit_relasi_tipe").val(relasi_tipe).trigger("change");

            // delay biar dropdown muncul dulu
            setTimeout(() => {
                if (relasi_tipe === "hutan_adat") {
                    $("#edit_hutan_adat").val(relasi_id);
                } else if (relasi_tipe === "provinsi") {
                    $("#edit_provinsi").val(relasi_id);
                } else if (relasi_tipe === "kabupaten") {
                    $("#edit_kabupaten").val(relasi_id);
                } else if (relasi_tipe === "kecamatan") {
                    $("#edit_kecamatan").val(relasi_id);
                }

                $("#edit_relasi_id").val(relasi_id);
            }, 200);
        });

        // saat relasi tipe berubah
        $("#edit_relasi_tipe").on("change", function () {
            let tipe = $(this).val();

            $("#group_hutan_adat").hide();
            $("#group_provinsi").hide();
            $("#group_kabupaten").hide();
            $("#group_kecamatan").hide();
            // reset value
            $("#edit_relasi_id").val("");
            $("#edit_hutan_adat").val("");
            $("#edit_provinsi").val("");
            $("#edit_kabupaten").val("");
            $("#edit_kecamatan").val("");

            // reset required
            $("#edit_hutan_adat").prop("required", false);
            $("#edit_provinsi").prop("required", false);
            $("#edit_kabupaten").prop("required", false);
            $("#edit_kecamatan").prop("required", false);

            if (tipe === "hutan_adat") {
                $("#group_hutan_adat").show();
                $("#edit_hutan_adat").prop("required", true);
            } else if (tipe === "provinsi") {
                $("#group_provinsi").show();
                $("#edit_provinsi").prop("required", true);
            } else if (tipe === "kabupaten") {
                $("#group_kabupaten").show();
                $("#edit_kabupaten").prop("required", true);
            } else if (tipe === "kecamatan") {
                $("#group_kecamatan").show();
                $("#edit_kecamatan").prop("required", true);
            }
        });

        // set relasi_id dari dropdown aktif
        $(".relasi-dropdown").on("change", function () {
            let val = $(this).val();
            $("#edit_relasi_id").val(val);
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
            showToast("Data Polygon berhasil ditambahkan", "created");
        }
        if (success === "updated") {
            showToast("Data Polygon berhasil diperbarui", "updated");
        }
        if (success === "deleted") {
            showToast("Data Polygon berhasil dihapus", "deleted");
        }
    </script>

    <script>
        $(document).on("click", ".btn-geom", function () {
            let wkt = $(this).data("geom");

            if (!wkt) {
                $("#geom_content").html("<p>Tidak ada data</p>");
                return;
            }

            // bersihkan MULTIPOLYGON
            wkt = wkt.replace(/MULTIPOLYGON\s*\(\(\(/, '').replace(/\)\)\)/, '');

            let points = wkt.split(',');

            // hapus titik terakhir (duplikat)
            if (points.length > 1) {
                points.pop();
            }

            let html = `
                <table class="table table-bordered table-striped">
                    <tr>
                        <th>No</th>
                        <th>Latitude</th>
                        <th>Longitude</th>
                    </tr>
            `;

            points.forEach((p, i) => {
                let coord = p.trim().split(' ');

                if (coord.length === 2) {
                    let lng = coord[0];
                    let lat = coord[1];

                    html += `
                <tr>
                    <td>${i + 1}</td>
                    <td><span class="badge badge-success">${lat}</span></td>
                    <td><span class="badge badge-primary">${lng}</span></td>
                </tr>
            `;
                }
            });

            html += '</table>';

            $("#geom_content").html(html);
        });
    </script>

</body>

</html>