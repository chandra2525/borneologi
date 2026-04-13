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

// function cleanGeom($wkt)
// {
//     return preg_replace('/MULTIPOLYGON\s*\(\(\((.*)\)\)\)/', '$1', $wkt);
// }

function formatGeomTable($wkt)
{
    $wkt = preg_replace('/MULTIPOLYGON\s*\(\(\((.*)\)\)\)/', '$1', $wkt);
    $points = explode(',', $wkt);

    if (count($points) > 1) {
        array_pop($points);
    }

    $html = '<table class="table table-sm table-bordered">';
    $html .= '<tr><th>No</th><th>Latitude</th><th>Longitude</th></tr>';

    $no = 1;
    foreach ($points as $point) {
        $coord = explode(' ', trim($point));

        if (count($coord) == 2) {
            $lng = $coord[0];
            $lat = $coord[1];

            $html .= "<tr>
                        <td>$no</td>
                        <td><span class='badge badge-success'>$lat</span></td>
                        <td><span class='badge badge-primary'>$lng</span></td>
                      </tr>";
            $no++;
        }
    }

    $html .= '</table>';

    return $html;
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
                                                <th>Tipe Lokasi</th>
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
                                                    <!-- <td><?= htmlspecialchars($kaleka['geom_area']) ?></td> -->
                                                    <!-- <td><?= formatGeomTable($kaleka['geom_area']) ?></td> -->
                                                    <td>
                                                        <?php if (!empty($kaleka['geom_area'])): ?>
                                                            <span class="">Geometri Area</span><br>
                                                            <button class="btn btn-block btn-primary btn-geom btn-sm"
                                                                data-geom="<?= htmlspecialchars($kaleka['geom_area']) ?>"
                                                                data-toggle="modal" data-target="#modalGeom">
                                                                <i class="fas fa-list"></i>
                                                                Lihat
                                                            </button>
                                                        <?php else: ?>
                                                            <div>
                                                                <span class="">LatLong (Point)</span><br>
                                                                <span class="badge badge-success">
                                                                    Lat: <?= htmlspecialchars($kaleka['centroid_lat']) ?>
                                                                </span>
                                                                <br>
                                                                <span class="badge badge-primary">
                                                                    Long: <?= htmlspecialchars($kaleka['centroid_lng']) ?>
                                                                </span>
                                                            </div>
                                                        <?php endif; ?>
                                                    </td>

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
                                                                data-centroid_lat="<?= htmlspecialchars($kaleka['centroid_lat']) ?>"
                                                                data-centroid_lng="<?= htmlspecialchars($kaleka['centroid_lng']) ?>"
                                                                data-geom_area="<?= htmlspecialchars($kaleka['geom_area']) ?>"
                                                                data-tipe_lokasi="<?= $kaleka['geom_area'] ? 'area' : 'point' ?>"
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
                                                <th>Tipe Lokasi</th>
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
                                    <label for="tipe_lokasi">Tipe Lokasi<code>*</code></label>
                                    <select name="tipe_lokasi" id="tipe_lokasi" class="form-control">
                                        <option value="">-- Pilih Tipe --</option>
                                        <option value="point">LatLong (Point)</option>
                                        <option value="area">Geometri Area</option>
                                    </select>
                                </div>
                                <div id="point_input" style="display: none;">
                                    <div class="form-group">
                                        <label for="centroid_lat">Latitude<code>*</code></label>
                                        <input type="number" step="any" name="centroid_lat" class="form-control"
                                            id="centroid_lat" placeholder="Masukkan Latitude">
                                    </div>
                                    <div class="form-group">
                                        <label for="centroid_lng">Longitude<code>*</code></label>
                                        <input type="number" step="any" name="centroid_lng" class="form-control"
                                            id="centroid_lng" placeholder="Masukkan Longitude">
                                    </div>
                                </div>
                                <div id="area_input" style="display: none;">
                                    <div id="geom_area"></div>
                                    <input type="hidden" name="geom_area" id="geom_area_hidden">
                                    <button type="button" class="btn btn-info mb-2" onclick="addCoordinate()">
                                        + Tambah Titik
                                    </button>
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
                                    <label for="tipe_lokasi">Tipe Lokasi<code>*</code></label>
                                    <select name="tipe_lokasi" id="edit_tipe_lokasi" class="form-control">
                                        <option value="">-- Pilih Tipe --</option>
                                        <option value="point">LatLong (Point)</option>
                                        <option value="area">Geometri Area</option>
                                    </select>
                                </div>
                                <div id="edit_point_input" style="display: none;">
                                    <div class="form-group">
                                        <label for="edit_centroid_lat">Latitude</label>
                                        <input type="number" step="any" name="centroid_lat" class="form-control"
                                            id="edit_centroid_lat" placeholder="Masukkan Latitude">
                                    </div>
                                    <div class="form-group">
                                        <label for="edit_centroid_lng">Longitude</label>
                                        <input type="number" step="any" name="centroid_lng" class="form-control"
                                            id="edit_centroid_lng" placeholder="Masukkan Longitude">
                                    </div>
                                </div>
                                <div id="edit_area_input" style="display: none;">
                                    <div id="edit_geom_area"></div>
                                    <input type="hidden" name="geom_area" id="edit_geom_area_hidden">
                                    <button type="button" class="btn btn-info mb-2" onclick="addCoordinateEdit()">
                                        + Tambah Titik
                                    </button>
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
                        tipe_lokasi: {
                            required: true
                        },
                        centroid_lat: {
                            required: true
                        },
                        centroid_lng: {
                            required: true
                        },
                        // geom_area: {
                        //     required: true
                        // },
                        geom_area: {
                            required: function () {
                                return $('#tipe_lokasi').val() === 'area';
                            }
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
                        tipe_lokasi: {
                            required: "Silahkan pilih Tipe Lokasi"
                        },
                        centroid_lat: {
                            required: "Silahkan masukkan Latitude"
                        },
                        centroid_lng: {
                            required: "Silahkan masukkan Longitude"
                        },
                        geom_area: {
                            required: "Silahkan masukkan LatLong Area"
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
            let tipe = $(this).data("tipe_lokasi");
            let geom = $(this).data("geom_area");

            // set dropdown
            $("#edit_tipe_lokasi").val(tipe).trigger("change");

            if (tipe === 'point') {
                $("#edit_point_input").show();
                $("#edit_area_input").hide();
            } else if (tipe === 'area') {
                $("#edit_point_input").hide();
                $("#edit_area_input").show();

                let container = $("#edit_geom_area");
                container.html('');

                if (geom) {
                    // bersihkan WKT
                    geom = geom.replace(/MULTIPOLYGON\s*\(\(\(/, '').replace(/\)\)\)/, '');
                    let points = geom.split(',');

                    // hapus duplikat terakhir
                    if (points.length > 1) {
                        points.pop();
                    }

                    points.forEach((p, i) => {
                        let coord = p.trim().split(' ');

                        if (coord.length === 2) {
                            let lng = coord[0];
                            let lat = coord[1];

                            let html = `
                    <div class="coordinate-item mb-2 row">
                        <div class="col-md-5">
                            <input type="number" step="any" name="coordinates[${i}][lat]" value="${lat}" class="form-control mb-1">
                        </div>
                        <div class="col-md-5">
                            <input type="number" step="any" name="coordinates[${i}][lng]" value="${lng}" class="form-control mb-1">
                        </div>
                        <div class="col-md-2">
                            <button type="button" class="btn btn-danger" onclick="removeCoordinate(this)">Hapus</button>
                        </div>
                    </div>
                    `;

                            container.append(html);
                        }
                    });
                }
            }

            let id = $(this).data("id");
            let kode_kaleka = $(this).data("kode_kaleka");
            let nama_kaleka = $(this).data("nama_kaleka");
            let id_petani = $(this).data("id_petani");
            let id_desa = $(this).data("id_desa");
            let luas_ha = $(this).data("luas_ha");
            let centroid_lat = $(this).data("centroid_lat");
            let centroid_lng = $(this).data("centroid_lng");
            let geom_area = $(this).data("geom_area");
            let keterangan = $(this).data("keterangan");
            let status = $(this).data("status");

            $("#edit_id").val(id);
            $("#edit_kode_kaleka").val(kode_kaleka);
            $("#edit_nama_kaleka").val(nama_kaleka);
            $("#edit_id_petani").val(id_petani);
            $("#edit_id_desa").val(id_desa);
            $("#edit_luas_ha").val(luas_ha);
            $("#edit_centroid_lat").val(centroid_lat);
            $("#edit_centroid_lng").val(centroid_lng);
            $("#edit_geom_area").val(geom_area);
            $("#edit_keterangan").val(keterangan);
            $("input[name='is_active'][value='" + status + "']").prop("checked", true);
        });

        $('#edit_tipe_lokasi').on('change', function () {
            if (this.value === 'point') {
                $('#edit_point_input').show();
                $('#edit_area_input').hide();
            } else if (this.value === 'area') {
                $('#edit_point_input').hide();
                $('#edit_area_input').show();

                if ($('#edit_geom_area .coordinate-item').length === 0) {
                    addCoordinateEdit();
                }
            } else {
                $('#edit_point_input').hide();
                $('#edit_area_input').hide();
            }
        });

        function addCoordinateEdit() {
            let index = $('#edit_geom_area .coordinate-item').length;

            let html = `
            <div class="coordinate-item mb-2 row">
                <div class="col-md-5">
                    <input type="number" step="any" name="coordinates[${index}][lat]" class="form-control mb-1">
                </div>
                <div class="col-md-5">
                    <input type="number" step="any" name="coordinates[${index}][lng]" class="form-control mb-1">
                </div>
                <div class="col-md-2">
                    <button type="button" class="btn btn-danger" onclick="removeCoordinate(this)">Hapus</button>
                </div>
            </div>
            `;

            $('#edit_geom_area').append(html);
        }

        function generateWKTEdit() {
            let coords = [];

            $('#edit_geom_area .coordinate-item').each(function () {
                let lat = $(this).find('[name*="[lat]"]').val();
                let lng = $(this).find('[name*="[lng]"]').val();

                if (lat !== '' && lng !== '' && !isNaN(lat) && !isNaN(lng)) {
                    coords.push(`${lng} ${lat}`);
                }
            });

            // MINIMAL 3 TITIK
            if (coords.length < 3) {
                alert("Minimal 3 titik untuk membuat area!");
                return null;
            }

            // tutup polygon
            coords.push(coords[0]);

            return `MULTIPOLYGON(((${coords.join(', ')})))`;
        }

        $("#formEdit").on("submit", function (e) {
            let tipe = $('#edit_tipe_lokasi').val();

            if (tipe === 'area') {
                let wkt = generateWKTEdit();

                if (!wkt) {
                    e.preventDefault(); // STOP SUBMIT
                    return false;
                }

                $("#edit_geom_area_hidden").val(wkt);
            }
        });

        console.log("WKT:", wkt);
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

    <script>
        const tipeLokasi = document.getElementById('tipe_lokasi');
        const pointInput = document.getElementById('point_input');
        const areaInput = document.getElementById('area_input');
        const areaContainer = document.getElementById('geom_area');

        tipeLokasi.addEventListener('change', function () {
            if (this.value === 'point') {
                pointInput.style.display = 'block';
                areaInput.style.display = 'none';
            } else if (this.value === 'area') {
                pointInput.style.display = 'none';
                areaInput.style.display = 'block';

                // reset dulu
                areaContainer.innerHTML = '';
                addCoordinate();
            } else {
                pointInput.style.display = 'none';
                areaInput.style.display = 'none';
            }
        });

        function addCoordinate() {
            const index = document.querySelectorAll('.coordinate-item').length;

            const html = `
            <div class="coordinate-item mb-2 row">
                <div class="col-md-5">
                    <input type="number" step="any" name="coordinates[${index}][lat]" placeholder="Latitude" class="form-control mb-1">
                </div>
                <div class="col-md-5">
                    <input type="number" step="any" name="coordinates[${index}][lng]" placeholder="Longitude" class="form-control mb-1">
                </div>
                <div class="col-md-2">
                    <button type="button" class="btn btn-danger btn" onclick="removeCoordinate(this)">Hapus</button>
                </div>
            </div>
            `;

            areaContainer.insertAdjacentHTML('beforeend', html);
        }

        function removeCoordinate(button) {
            button.closest('.coordinate-item').remove();
        }
    </script>

    <script>
        function generateWKT() {
            let coords = [];

            document.querySelectorAll('.coordinate-item').forEach(item => {
                let lat = item.querySelector('input[name*="[lat]"]').value;
                let lng = item.querySelector('input[name*="[lng]"]').value;

                if (lat && lng) {
                    coords.push(`${lng} ${lat}`); // INGAT: format = lng lat
                }
            });

            // minimal polygon 3 titik
            if (coords.length < 3) {
                return '';
            }

            // tutup polygon (titik pertama = terakhir)
            coords.push(coords[0]);

            // MULTIPOLYGON format
            let wkt = `MULTIPOLYGON(((${coords.join(', ')})))`;

            return wkt;
        }

        // sebelum submit
        document.getElementById("formTambah").addEventListener("submit", function (e) {
            let tipe = $('#tipe_lokasi').val();

            if (tipe === 'area') {
                let total = document.querySelectorAll('.coordinate-item').length;
                if (total < 3) {
                    e.preventDefault();
                    alert("Minimal 3 titik untuk Geometri Area!");
                    return false;
                }
            }

            if (tipe === 'area') {
                let valid = true;

                document.querySelectorAll('.coordinate-item').forEach(item => {
                    let lat = item.querySelector('[name*="[lat]"]').value;
                    let lng = item.querySelector('[name*="[lng]"]').value;

                    if (!lat || !lng) {
                        valid = false;
                    }
                });

                if (!valid) {
                    e.preventDefault();
                    alert("Semua Latitude & Longitude harus diisi!");
                    return false;
                }
            }

            let wkt = generateWKT();
            document.getElementById("geom_area_hidden").value = wkt;
        });

        $('#tipe_lokasi').on('change', function () {
            if (this.value === 'area') {
                $('#geom_area_hidden').rules('add', { required: true });
            } else {
                $('#geom_area_hidden').rules('remove');
            }
        });
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