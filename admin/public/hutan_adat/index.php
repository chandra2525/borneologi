<?php

require_once '../../app/config/database.php';
require_once '../../app/controllers/HutanAdatController.php';
require_once '../../app/core/session.php';
require_once '../../app/core/csrf.php';
require_once "../../app/core/auth.php";
require_once '../../app/helpers/escape.php';

secureSessionStart();
checkAuth("non_dashboard");

$controller = new HutanAdatController($pdo);
$hutanAdats = $controller->index();
$kelompokTanis = $controller->getKelompokTani();
$desas = $controller->getDesa();
$statusKawasans = $controller->getStatusKawasan();
$polygons = $controller->getPolygon();

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
        $menu = "hutan_adat";
        include "../feature/sidebar.php";
        ?>

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1>Data Hutan Adat</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="#">Master Data</a></li>
                                <li class="breadcrumb-item active">Data Hutan Adat</li>
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
                                    <h3 class="card-title col-9">Berikut adalah list dari Data Hutan Adat</h3>
                                    <button class="col-3 btn btn-block btn-success" data-toggle="modal"
                                        data-target="#modalTambah">
                                        Tambah Hutan Adat
                                    </button>
                                </div>
                                <!-- /.card-header -->
                                <div class="card-body">
                                    <table id="example1" class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th>Kode Hutan Adat</th>
                                                <th>Nama Hutan Adat</th>
                                                <th>Masyarakat Hukum Adat</th>
                                                <th>Desa</th>
                                                <th>Nomor SK</th>
                                                <th>Tanggal SK</th>
                                                <th>Status Kawasan</th>
                                                <th>Luas (ha)</th>
                                                <!-- <th>Nama Polygon</th> -->
                                                <!-- <th>Geometri Area</th> -->
                                                <th>Keterangan</th>
                                                <th>Status Aktif</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($hutanAdats as $hutanAdat): ?>
                                                <tr>
                                                    <td><?= htmlspecialchars($hutanAdat['kode_hutan_adat']) ?></td>
                                                    <td><?= htmlspecialchars($hutanAdat['nama_hutan_adat']) ?></td>
                                                    <td><?= htmlspecialchars($hutanAdat['nama_masyarakat_hukum_adat']) ?>
                                                    </td>
                                                    <td><?= htmlspecialchars($hutanAdat['nama_desa']) ?></td>
                                                    <td><?= htmlspecialchars($hutanAdat['nomor_sk']) ?></td>
                                                    <td><?= htmlspecialchars($hutanAdat['tanggal_sk']) ?></td>
                                                    <td><?= htmlspecialchars($hutanAdat['nama_status_kawasan']) ?></td>
                                                    <td><?= htmlspecialchars($hutanAdat['luas_ha']) ?></td>
                                                    <!-- <td><?= htmlspecialchars($hutanAdat['nama_polygon']) ?></td> -->
                                                    <!-- <td>
                                                        <?php if (!empty($hutanAdat['geom_area'])): ?>
                                                            <button class="btn btn-block btn-primary btn-geom btn-sm"
                                                                data-geom="<?= htmlspecialchars($hutanAdat['geom_area']) ?>"
                                                                data-toggle="modal" data-target="#modalGeom">
                                                                <i class="fas fa-list"></i>
                                                                Lihat
                                                            </button>
                                                        <?php else: ?>
                                                            <span class="text-muted">Belum ada area</span>
                                                        <?php endif; ?>
                                                    </td> -->

                                                    <td><?= htmlspecialchars($hutanAdat['keterangan']) ?></td>
                                                    <td><?= $hutanAdat['is_active'] ? 'Aktif' : 'Nonaktif' ?></td>
                                                    <td class="row">
                                                        <div class="col">
                                                            <button class="btn btn-block btn-info btn-edit"
                                                                data-id="<?= $hutanAdat['id'] ?>"
                                                                data-kode_hutan_adat="<?= htmlspecialchars($hutanAdat['kode_hutan_adat']) ?>"
                                                                data-nama_hutan_adat="<?= htmlspecialchars($hutanAdat['nama_hutan_adat']) ?>"
                                                                data-id_masyarakat_hukum_adat="<?= htmlspecialchars($hutanAdat['id_masyarakat_hukum_adat']) ?>"
                                                                data-id_desa="<?= htmlspecialchars($hutanAdat['id_desa']) ?>"
                                                                data-nomor_sk="<?= htmlspecialchars($hutanAdat['nomor_sk']) ?>"
                                                                data-tanggal_sk="<?= htmlspecialchars($hutanAdat['tanggal_sk']) ?>"
                                                                data-id_status_kawasan="<?= htmlspecialchars($hutanAdat['id_status_kawasan']) ?>"
                                                                data-luas_ha="<?= htmlspecialchars($hutanAdat['luas_ha']) ?>"
                                                                data-keterangan="<?= htmlspecialchars($hutanAdat['keterangan']) ?>"
                                                                data-status="<?= $hutanAdat['is_active'] ?>"
                                                                data-toggle="modal" data-target="#modalEdit">
                                                                <i class="fas fa-edit"></i> Edit
                                                                <!-- data-id_polygon="<?= htmlspecialchars($hutanAdat['id_polygon']) ?>"
                                                                data-geom_area="<?= htmlspecialchars($hutanAdat['geom_area']) ?>" -->
                                                            </button>
                                                        </div>

                                                        <form method="POST" action="delete.php" class="form-delete col">
                                                            <?= csrfField() ?>
                                                            <input type="hidden" name="id" value="<?= $hutanAdat['id'] ?>">
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
                                                <th>Kode Hutan Adat</th>
                                                <th>Nama Hutan Adat</th>
                                                <th>Masyarakat Hukum Adat</th>
                                                <th>Desa</th>
                                                <th>Nomor SK</th>
                                                <th>Tanggal SK</th>
                                                <th>Status Kawasan</th>
                                                <th>Luas (ha)</th>
                                                <!-- <th>Nama Polygon</th> -->
                                                <!-- <th>Geometri Area</th> -->
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
                            <h4 class="modal-title">Tambah Hutan Adat</h4>
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                        </div>
                        <form id="formTambah" method="POST" action="store.php" enctype="multipart/form-data">
                            <?= csrfField() ?>
                            <div class="modal-body">
                                <div class="form-group">
                                    <label for="kode_hutan_adat">Kode Hutan Adat<code>*</code></label>
                                    <input type="text" name="kode_hutan_adat" class="form-control" id="kode_hutan_adat"
                                        value="<?= (new HutanAdat($pdo))->generateKode() ?>"
                                        placeholder="Masukkan Kode Hutan Adat">
                                </div>
                                <div class="form-group">
                                    <label for="nama_hutan_adat">Nama Hutan Adat<code>*</code></label>
                                    <input type="text" name="nama_hutan_adat" class="form-control" id="nama_hutan_adat"
                                        placeholder="Masukkan Nama Hutan Adat">
                                </div>
                                <div class="form-group">
                                    <label for="id_masyarakat_hukum_adat">Masyarakat Hukum Adat<code>*</code></label>
                                    <select name="id_masyarakat_hukum_adat" class="form-control"
                                        id="id_masyarakat_hukum_adat">
                                        <option value="">-- Pilih Masyarakat Hukum Adat --</option>
                                        <?php foreach ($kelompokTanis as $kt): ?>
                                            <option value="<?= $kt['id'] ?>">
                                                <?= htmlspecialchars($kt['nama_kelompok']) ?>
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
                                    <label for="nomor_sk">Nomor SK<code>*</code></label>
                                    <input type="text" name="nomor_sk" class="form-control" id="nomor_sk"
                                        placeholder="Masukkan Nomor SK">
                                </div>
                                <div class="form-group">
                                    <label for="tanggal_sk">Tanggal SK<code>*</code></label>
                                    <input type="date" name="tanggal_sk" class="form-control" id="tanggal_sk">
                                </div>
                                <div class="form-group">
                                    <label for="id_status_kawasan">Status Kawasan<code>*</code></label>
                                    <select name="id_status_kawasan" class="form-control" id="id_status_kawasan">
                                        <option value="">-- Pilih Status Kawasan --</option>
                                        <?php foreach ($statusKawasans as $sk): ?>
                                            <option value="<?= $sk['id'] ?>">
                                                <?= htmlspecialchars($sk['nama']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="luas_ha">Luas (ha)<code>*</code></label>
                                    <input type="number" name="luas_ha" class="form-control" id="luas_ha"
                                        placeholder="Masukkan Luas dalam hektar">
                                </div>
                                <!-- <div class="form-group">
                                    <label for="id_polygon">Polygon<code>*</code></label>
                                    <select name="id_polygon" class="form-control" id="id_polygon">
                                        <option value="">-- Pilih Polygon --</option>
                                        <?php foreach ($polygons as $p): ?>
                                            <option value="<?= $p['id'] ?>">
                                                <?= htmlspecialchars($p['nama_polygon']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div> -->
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
                            <h4 class="modal-title">Edit Hutan Adat</h4>
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                        </div>

                        <form id="formEdit" method="POST" action="update.php">
                            <?= csrfField() ?>
                            <input type="hidden" name="id" id="edit_id">
                            <div class="modal-body">
                                <div class="form-group">
                                    <label for="kode_hutan_adat">Kode Hutan Adat<code>*</code></label>
                                    <input type="text" name="kode_hutan_adat" class="form-control"
                                        id="edit_kode_hutan_adat" value="<?= (new HutanAdat($pdo))->generateKode() ?>"
                                        placeholder="Masukkan Kode Hutan Adat">
                                </div>
                                <div class="form-group">
                                    <label for="nama_hutan_adat">Nama Hutan Adat<code>*</code></label>
                                    <input type="text" name="nama_hutan_adat" class="form-control"
                                        id="edit_nama_hutan_adat" placeholder="Masukkan Nama Hutan Adat">
                                </div>
                                <div class="form-group">
                                    <label for="edit_id_masyarakat_hukum_adat">Masyarakat Hukum
                                        Adat<code>*</code></label>
                                    <select name="id_masyarakat_hukum_adat" class="form-control"
                                        id="edit_id_masyarakat_hukum_adat">
                                        <option value="">-- Pilih Masyarakat Hukum Adat --</option>
                                        <?php foreach ($kelompokTanis as $p): ?>
                                            <option value="<?= $p['id'] ?>">
                                                <?= htmlspecialchars($p['nama_kelompok']) ?>
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
                                    <label for="edit_nomor_sk">Nomor SK<code>*</code></label>
                                    <input type="text" name="nomor_sk" class="form-control" id="edit_nomor_sk"
                                        placeholder="Masukkan Nomor SK">
                                </div>
                                <div class="form-group">
                                    <label for="edit_tanggal_sk">Tanggal SK<code>*</code></label>
                                    <input type="date" name="tanggal_sk" class="form-control" id="edit_tanggal_sk"
                                        placeholder="Masukkan Tanggal SK">
                                </div>
                                <div class="form-group">
                                    <label for="edit_id_status_kawasan">Status Kawasan<code>*</code></label>
                                    <select name="id_status_kawasan" class="form-control" id="edit_id_status_kawasan">
                                        <option value="">-- Pilih Status Kawasan --</option>
                                        <?php foreach ($statusKawasans as $sk): ?>
                                            <option value="<?= $sk['id'] ?>">
                                                <?= htmlspecialchars($sk['nama']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="edit_luas_ha">Luas (ha)<code>*</code></label>
                                    <input type="number" name="luas_ha" class="form-control" id="edit_luas_ha"
                                        placeholder="Masukkan Luas dalam hektar">
                                </div>
                                <!-- <div class="form-group">
                                    <label for="edit_id_polygon">Polygon<code>*</code></label>
                                    <select name="id_polygon" class="form-control" id="edit_id_polygon">
                                        <option value="">-- Pilih Polygon --</option>
                                        <?php foreach ($polygons as $p): ?>
                                            <option value="<?= $p['id'] ?>">
                                                <?= htmlspecialchars($p['nama_polygon']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div> -->
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
                        kode_hutan_adat: {
                            required: true
                        },
                        nama_hutan_adat: {
                            required: true
                        },
                        id_masyarakat_hukum_adat: {
                            required: true
                        },
                        id_desa: {
                            required: true
                        },
                        nomor_sk: {
                            required: true
                        },
                        tanggal_sk: {
                            required: true
                        },
                        id_status_kawasan: {
                            required: true
                        },
                        luas_ha: {
                            required: true
                        },
                        // id_polygon: {
                        //     required: true
                        // },
                        keterangan: {
                            required: true
                        },
                        is_active: {
                            required: true
                        }
                    },
                    messages: {
                        kode_hutan_adat: {
                            required: "Silahkan masukkan Kode Hutan Adat"
                        },
                        nama_hutan_adat: {
                            required: "Silahkan masukkan Nama Hutan Adat"
                        },
                        id_masyarakat_hukum_adat: {
                            required: "Silahkan pilih Petani"
                        },
                        id_desa: {
                            required: "Silahkan pilih Desa"
                        },
                        nomor_sk: {
                            required: "Silahkan masukkan Nomor SK"
                        },
                        tanggal_sk: {
                            required: "Silahkan masukkan Tanggal SK"
                        },
                        id_status_kawasan: {
                            required: "Silahkan pilih Status Kawasan"
                        },
                        luas_ha: {
                            required: "Silahkan masukkan Luas dalam hektar"
                        },
                        // id_polygon : {
                        //     required: "Silahkan pilih Polygon"
                        // },
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
            let kode_hutan_adat = $(this).data("kode_hutan_adat");
            let nama_hutan_adat = $(this).data("nama_hutan_adat");
            let id_masyarakat_hukum_adat = $(this).data("id_masyarakat_hukum_adat");
            let id_desa = $(this).data("id_desa");
            let nomor_sk = $(this).data("nomor_sk");
            let tanggal_sk = $(this).data("tanggal_sk");
            let id_status_kawasan = $(this).data("id_status_kawasan");
            let luas_ha = $(this).data("luas_ha");
            let id_polygon = $(this).data("id_polygon");
            let keterangan = $(this).data("keterangan");
            let status = $(this).data("status");

            $("#edit_id").val(id);
            $("#edit_kode_hutan_adat").val(kode_hutan_adat);
            $("#edit_nama_hutan_adat").val(nama_hutan_adat);
            $("#edit_id_masyarakat_hukum_adat").val(id_masyarakat_hukum_adat);
            $("#edit_id_desa").val(id_desa);
            $("#edit_nomor_sk").val(nomor_sk);
            $("#edit_tanggal_sk").val(tanggal_sk);
            $("#edit_id_status_kawasan").val(id_status_kawasan);
            $("#edit_luas_ha").val(luas_ha);
            $("#edit_id_polygon").val(id_polygon);
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
            showToast("Data Hutan Adat berhasil ditambahkan", "created");
        }
        if (success === "updated") {
            showToast("Data Hutan Adat berhasil diperbarui", "updated");
        }
        if (success === "deleted") {
            showToast("Data Hutan Adat berhasil dihapus", "deleted");
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

    <script>
        document.getElementById('tanggal_sk').addEventListener('focus', function () {
            this.showPicker();
        });
        document.getElementById('edit_tanggal_sk').addEventListener('focus', function () {
            this.showPicker();
        });
    </script>

</body>

</html>