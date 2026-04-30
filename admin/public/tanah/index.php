<?php

require_once '../../app/config/database.php';
require_once '../../app/controllers/TanahController.php';
require_once '../../app/core/session.php';
require_once '../../app/core/csrf.php';
require_once "../../app/core/auth.php";
require_once '../../app/helpers/escape.php';

secureSessionStart();
checkAuth("non_dashboard");

$controller = new TanahController($pdo);
$tanahs = $controller->index();
$petanis = $controller->getPetani();
$hutanAdats = $controller->getHutanAdat();
$kalekas = $controller->getKaleka();
$legalitasLahans = $controller->getLegalitasLahan();
$statusKawasans = $controller->getStatusKawasan();

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
        $menu = "tanah";
        include "../feature/sidebar.php";
        ?>

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1>Data Tanah</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="#">Master Data</a></li>
                                <li class="breadcrumb-item active">Data Tanah</li>
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
                                    <h3 class="card-title col-9">Berikut adalah list dari Data Tanah</h3>
                                    <button class="col-3 btn btn-block btn-success" data-toggle="modal"
                                        data-target="#modalTambah">
                                        Tambah Tanah
                                    </button>
                                </div>
                                <!-- /.card-header -->
                                <div class="card-body">
                                    <table id="example1" class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th>Kode Tanah</th>
                                                <th>Petani</th>
                                                <th>Nama Relasi</th>
                                                <th>Tipe Relasi</th>
                                                <th>Nama Lahan</th>
                                                <th>Legalitas Lahan</th>
                                                <th>Status Kawasan</th>
                                                <th>Luas (ha)</th>
                                                <th>Tipe Lokasi</th>
                                                <th>Sudah Validasi</th>
                                                <th>Tanggal Validasi</th>
                                                <th>Sejarah</th>
                                                <th>Alamat Lokasi</th>
                                                <th>Keterangan</th>
                                                <th>Status Aktif</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($tanahs as $tanah): ?>
                                                <tr>
                                                    <td><?= htmlspecialchars($tanah['kode_tanah']) ?></td>
                                                    <td><?= htmlspecialchars($tanah['nama_petani']) ?></td>
                                                    <td><?= htmlspecialchars($tanah['nama_relasi']) ?></td>
                                                    <td><?= htmlspecialchars(getRelasiTipeLabel($tanah['tipe_relasi'])) ?>
                                                    <td><?= htmlspecialchars($tanah['nama_lahan']) ?></td>
                                                    <td><?= htmlspecialchars($tanah['nama_legalitas_lahan']) ?></td>
                                                    <td><?= htmlspecialchars($tanah['nama_status_kawasan']) ?></td>
                                                    <td><?= htmlspecialchars($tanah['luas_ha']) ?></td>
                                                    <!-- <td><?= htmlspecialchars($tanah['geom_area']) ?></td> -->
                                                    <!-- <td><?= formatGeomTable($tanah['geom_area']) ?></td> -->
                                                    <td>
                                                        <?php if (!empty($tanah['geom_area'])): ?>
                                                            <span class="">Geometri Area</span><br>
                                                            <button class="btn btn-block btn-primary btn-geom btn-sm"
                                                                data-geom="<?= htmlspecialchars($tanah['geom_area']) ?>"
                                                                data-toggle="modal" data-target="#modalGeom">
                                                                <i class="fas fa-list"></i>
                                                                Lihat
                                                            </button>
                                                        <?php else: ?>
                                                            <div>
                                                                <span class="">LatLong (Point)</span><br>
                                                                <span class="badge badge-success">
                                                                    Lat: <?= htmlspecialchars($tanah['centroid_lat']) ?>
                                                                </span>
                                                                <br>
                                                                <span class="badge badge-primary">
                                                                    Long: <?= htmlspecialchars($tanah['centroid_lng']) ?>
                                                                </span>
                                                            </div>
                                                        <?php endif; ?>
                                                    </td>

                                                    <td><?= $tanah['sudah_validasi'] ? 'Aktif' : 'Nonaktif' ?></td>
                                                    <td><?= htmlspecialchars($tanah['tanggal_validasi']) ?></td>
                                                    <td><?= htmlspecialchars($tanah['sejarah']) ?></td>
                                                    <td><?= htmlspecialchars($tanah['alamat_lokasi']) ?></td>
                                                    <td><?= htmlspecialchars($tanah['keterangan']) ?></td>
                                                    <td><?= $tanah['is_active'] ? 'Aktif' : 'Nonaktif' ?></td>
                                                    <td class="row">
                                                        <div class="col">
                                                            <button class="btn btn-block btn-info btn-edit"
                                                                data-id="<?= $tanah['id'] ?>"
                                                                data-kode_tanah="<?= htmlspecialchars($tanah['kode_tanah']) ?>"
                                                                data-id_petani="<?= htmlspecialchars($tanah['id_petani']) ?>"
                                                                data-id_relasi="<?= htmlspecialchars($tanah['id_relasi']) ?>"
                                                                data-tipe_relasi="<?= htmlspecialchars($tanah['tipe_relasi']) ?>"
                                                                data-nama_lahan="<?= htmlspecialchars($tanah['nama_lahan']) ?>"
                                                                data-id_legalitas_lahan="<?= htmlspecialchars($tanah['id_legalitas_lahan']) ?>"
                                                                data-id_status_kawasan="<?= htmlspecialchars($tanah['id_status_kawasan']) ?>"
                                                                data-luas_ha="<?= htmlspecialchars($tanah['luas_ha']) ?>"
                                                                data-centroid_lat="<?= htmlspecialchars($tanah['centroid_lat']) ?>"
                                                                data-centroid_lng="<?= htmlspecialchars($tanah['centroid_lng']) ?>"
                                                                data-geom_area="<?= htmlspecialchars($tanah['geom_area']) ?>"
                                                                data-tipe_lokasi="<?= $tanah['geom_area'] ? 'area' : 'point' ?>"
                                                                data-sudah_validasi="<?= $tanah['sudah_validasi'] ?>"
                                                                data-tanggal_validasi="<?= $tanah['tanggal_validasi'] ?>"
                                                                data-sejarah="<?= htmlspecialchars($tanah['sejarah']) ?>"
                                                                data-alamat_lokasi="<?= htmlspecialchars($tanah['alamat_lokasi']) ?>"
                                                                data-keterangan="<?= htmlspecialchars($tanah['keterangan']) ?>"
                                                                data-status="<?= $tanah['is_active'] ?>"
                                                                data-toggle="modal" data-target="#modalEdit">
                                                                <i class="fas fa-edit"></i> Edit
                                                            </button>
                                                        </div>

                                                        <form method="POST" action="delete.php" class="form-delete col">
                                                            <?= csrfField() ?>
                                                            <input type="hidden" name="id" value="<?= $tanah['id'] ?>">
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
                                                <th>Kode Tanah</th>
                                                <th>Petani</th>
                                                <th>Nama Relasi</th>
                                                <th>Tipe Relasi</th>
                                                <th>Nama Lahan</th>
                                                <th>Legalitas Lahan</th>
                                                <th>Status Kawasan</th>
                                                <th>Luas (ha)</th>
                                                <th>Tipe Lokasi</th>
                                                <th>Sudah Validasi</th>
                                                <th>Tanggal Validasi</th>
                                                <th>Sejarah</th>
                                                <th>Alamat Lokasi</th>
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
                            <h4 class="modal-title">Tambah Tanah</h4>
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                        </div>
                        <form id="formTambah" method="POST" action="store.php">
                            <?= csrfField() ?>
                            <div class="modal-body">
                                <div class="form-group">
                                    <label for="kode_tanah">Kode Tanah<code>*</code></label>
                                    <input type="text" name="kode_tanah" class="form-control" id="kode_tanah"
                                        value="<?= (new Tanah($pdo))->generateKode() ?>"
                                        placeholder="Masukkan Kode Tanah">
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
                                <!-- <div class="form-group">
                                    <label for="id_relasi">Kaleka<code>*</code></label>
                                    <select name="id_relasi" class="form-control" id="id_relasi">
                                        <option value="">-- Pilih Kaleka --</option>
                                        <?php foreach ($kalekas as $d): ?>
                                            <option value="<?= $d['id'] ?>">
                                                <?= htmlspecialchars($d['nama_kaleka']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div> -->
                                <div class="form-group">
                                    <label>Relasi Tipe<code>*</code></label>
                                    <select name="tipe_relasi" class="form-control" id="tipe_relasi">
                                        <option value="">-- Pilih Relasi --</option>
                                        <option value="hutan_adat">Hutan Adat</option>
                                        <option value="kaleka">Kaleka</option>
                                    </select>
                                </div>

                                <div class="form-group" id="group_hutan_adat_add" style="display:none;">
                                    <label>Hutan Adat<code>*</code></label>
                                    <select class="form-control relasi-dropdown-add" id="hutan_adat" name="hutan_adat">
                                        <option value="">-- Pilih Hutan Adat --</option>
                                        <?php foreach ($hutanAdats as $hA): ?>
                                            <option value="<?= $hA['id'] ?>">
                                                <?= htmlspecialchars($hA['nama_hutan_adat']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <div class="form-group" id="group_kaleka_add" style="display:none;">
                                    <label>Kaleka<code>*</code></label>
                                    <select class="form-control relasi-dropdown-add" id="kaleka" name="kaleka">
                                        <option value="">-- Pilih Kaleka --</option>
                                        <?php foreach ($kalekas as $p): ?>
                                            <option value="<?= $p['id'] ?>">
                                                <?= htmlspecialchars($p['nama_kaleka']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <!-- hidden untuk dikirim ke backend -->
                                <input type="hidden" name="id_relasi" id="id_relasi">

                                <div class="form-group">
                                    <label for="nama_lahan">Nama Lahan<code>*</code></label>
                                    <input type="text" name="nama_lahan" class="form-control" id="nama_lahan"
                                        placeholder="Masukkan nama lahan">
                                </div>
                                <div class="form-group">
                                    <label for="id_legalitas_lahan">Legalitas Lahan<code>*</code></label>
                                    <select name="id_legalitas_lahan" class="form-control" id="id_legalitas_lahan">
                                        <option value="">-- Pilih Legalitas Lahan --</option>
                                        <?php foreach ($legalitasLahans as $ll): ?>
                                            <option value="<?= $ll['id'] ?>">
                                                <?= htmlspecialchars($ll['nama']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
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
                                    <label>Sudah Validasi<code>*</code></label>
                                    <div class="form-group row">
                                        <div class="col-sm-2">
                                            <div class="custom-control custom-radio">
                                                <input class="custom-control-input" type="radio" id="add_sudah_validasi_aktif"
                                                    name="sudah_validasi" value="1" checked>
                                                <label for="add_sudah_validasi_aktif" class="custom-control-label">Sudah</label>
                                            </div>
                                        </div>
                                        <div class="col-sm-2">
                                            <div class="custom-control custom-radio">
                                                <input class="custom-control-input" type="radio"
                                                    id="add_sudah_validasi_nonaktif" name="sudah_validasi" value="0">
                                                <label for="add_sudah_validasi_nonaktif"
                                                    class="custom-control-label">Belum</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="tanggal_validasi">Tanggal Validasi<code>*</code></label>
                                    <input type="date" name="tanggal_validasi" class="form-control" id="tanggal_validasi">
                                </div>
                                <div class="form-group">
                                    <label for="sejarah">Sejarah<code>*</code></label>
                                    <input type="text" name="sejarah" class="form-control" id="sejarah"
                                        placeholder="Masukkan sejarah">
                                </div>
                                <div class="form-group">
                                    <label for="alamat_lokasi">Alamat Lokasi<code>*</code></label>
                                    <textarea name="alamat_lokasi" class="form-control" id="alamat_lokasi"
                                        placeholder="Masukkan alamat lokasi"></textarea>
                                </div>
                                <div class="form-group">
                                    <label for="keterangan">Keterangan<code>*</code></label>
                                    <textarea name="keterangan" class="form-control" id="keterangan"
                                        placeholder="Masukkan keterangan"></textarea>
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
                            <h4 class="modal-title">Edit Tanah</h4>
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                        </div>

                        <form id="formEdit" method="POST" action="update.php">
                            <?= csrfField() ?>
                            <input type="hidden" name="id" id="edit_id">
                            <div class="modal-body">
                                <div class="form-group">
                                    <label for="kode_tanah">Kode Tanah<code>*</code></label>
                                    <input type="text" name="kode_tanah" class="form-control" id="edit_kode_tanah"
                                        value="<?= (new Tanah($pdo))->generateKode() ?>"
                                        placeholder="Masukkan Kode Tanah">
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
                                <!-- <div class="form-group">
                                    <label for="edit_id_relasi">Kaleka<code>*</code></label>
                                    <select name="id_relasi" class="form-control" id="edit_id_relasi">
                                        <option value="">-- Pilih Kaleka --</option>
                                        <?php foreach ($kalekas as $d): ?>
                                            <option value="<?= $d['id'] ?>">
                                                <?= htmlspecialchars($d['nama_kaleka']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div> -->
                                <div class="form-group">
                                    <label>Relasi Tipe<code>*</code></label>
                                    <select name="tipe_relasi" class="form-control" id="edit_tipe_relasi">
                                        <option value="">-- Pilih Relasi --</option>
                                        <option value="hutan_adat">Hutan Adat</option>
                                        <option value="kaleka">Kaleka</option>
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

                                <div class="form-group" id="group_kaleka" style="display:none;">
                                    <label>Kaleka<code>*</code></label>
                                    <select class="form-control relasi-dropdown" id="edit_kaleka" name="kaleka">
                                        <option value="">-- Pilih Kaleka --</option>
                                        <?php foreach ($kalekas as $p): ?>
                                            <option value="<?= $p['id'] ?>">
                                                <?= htmlspecialchars($p['nama_kaleka']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <!-- hidden untuk dikirim ke backend -->
                                <input type="hidden" name="id_relasi" id="edit_id_relasi">
                                
                                <div class="form-group">
                                    <label for="edit_nama_lahan">Nama Lahan<code>*</code></label>
                                    <input type="text" name="nama_lahan" class="form-control" id="edit_nama_lahan"
                                        placeholder="Masukkan nama lahan">
                                </div>
                                <div class="form-group">
                                    <label for="edit_id_legalitas_lahan">Legalitas Lahan<code>*</code></label>
                                    <select name="id_legalitas_lahan" class="form-control" id="edit_id_legalitas_lahan">
                                        <option value="">-- Pilih Legalitas Lahan --</option>
                                        <?php foreach ($legalitasLahans as $ll): ?>
                                            <option value="<?= $ll['id'] ?>">
                                                <?= htmlspecialchars($ll['nama']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
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
                                    <label>Sudah Validasi<code>*</code></label>
                                    <div class="custom-control custom-radio">
                                        <input class="custom-control-input" type="radio"
                                            id="edit_sudah_validasi_aktif" name="sudah_validasi" value="1">
                                        <label for="edit_sudah_validasi_aktif" class="custom-control-label">
                                            Sudah
                                        </label>
                                    </div>
                                    <div class="custom-control custom-radio">
                                        <input class="custom-control-input" type="radio"
                                            id="edit_sudah_validasi_nonaktif" name="sudah_validasi" value="0">
                                        <label for="edit_sudah_validasi_nonaktif" class="custom-control-label">
                                            Belum
                                        </label>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="tanggal_validasi">Tanggal Validasi<code>*</code></label>
                                    <input type="date" name="tanggal_validasi" class="form-control" id="edit_tanggal_validasi">
                                </div>
                                <div class="form-group">
                                    <label for="edit_sejarah">Sejarah<code>*</code></label>
                                    <input type="text" name="sejarah" class="form-control" id="edit_sejarah"
                                        placeholder="Masukkan sejarah">
                                </div>
                                <div class="form-group">
                                    <label for="alamat_lokasi">Alamat Lokasi<code>*</code></label>
                                    <textarea name="alamat_lokasi" class="form-control" id="edit_alamat_lokasi"
                                        placeholder="Masukkan alamat lokasi"></textarea>
                                </div>
                                <div class="form-group">
                                    <label for="keterangan">Keterangan<code>*</code></label>
                                    <textarea name="keterangan" class="form-control" id="edit_keterangan"
                                        placeholder="Masukkan Keterangan"></textarea>
                                </div>
                                <div class="form-group">
                                    <label>Status Aktif<code>*</code></label>
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
                        kode_tanah: {
                            required: true
                        },
                        id_petani: {
                            required: true
                        },
                        id_relasi: {
                            required: true
                        },
                        tipe_relasi: {
                            required: true
                        },
                        nama_lahan: {
                            required: true
                        },
                        id_legalitas_lahan: {
                            required: true
                        },
                        id_status_kawasan: {
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
                        sudah_validasi: {
                            required: true
                        },
                        tanggal_validasi: {
                            required: true
                        },
                        sejarah: {
                            required: true
                        },
                        alamat_lokasi: {
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
                        kode_tanah: {
                            required: "Silahkan masukkan Kode Tanah"
                        },
                        id_petani: {
                            required: "Silahkan pilih Petani"
                        },
                        id_relasi: {
                            required: "Silahkan pilih"
                        },
                        tipe_relasi: {
                            required: "Silahkan pilih Tipe Relasi"
                        },
                        nama_lahan: {
                            required: "Silahkan masukkan nama lahan"
                        },
                        id_legalitas_lahan: {
                            required: "Silahkan pilih legalitas lahan"
                        },
                        id_status_kawasan: {
                            required: "Silahkan pilih status kawasan"
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
                        sudah_validasi: {
                            required: "Silahkan pilih Validasi"
                        },
                        tanggal_validasi: {
                            required: "Silahkan pilih tanggal validasi"
                        },
                        sejarah: {
                            required: "Silahkan masukkan sejarah"
                        },
                        alamat_lokasi: {
                            required: "Silahkan masukkan alamat lokasi"
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

            let validator = $("#formEdit").validate();

            // dynamic rules
            $("#edit_hutan_adat").rules("add", {
                required: function () {
                    return $("#edit_tipe_relasi").val() === "hutan_adat";
                },
                messages: {
                    required: "Silahkan pilih Hutan Adat"
                }
            });

            $("#edit_kaleka").rules("add", {
                required: function () {
                    return $("#edit_tipe_relasi").val() === "kaleka";
                },
                messages: {
                    required: "Silahkan pilih Kaleka"
                }
            });

            // dynamic rules add
            $("#hutan_adat").rules("add", {
                required: function () {
                    return $("#tipe_relasi").val() === "hutan_adat";
                },
                messages: {
                    required: "Silahkan pilih Hutan Adat"
                }
            });

            $("#kaleka").rules("add", {
                required: function () {
                    return $("#tipe_relasi").val() === "kaleka";
                },
                messages: {
                    required: "Silahkan pilih Kaleka"
                }
            });
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
            let kode_tanah = $(this).data("kode_tanah");
            let id_petani = $(this).data("id_petani");
            let id_relasi = $(this).data("id_relasi");
            let tipe_relasi = $(this).data("tipe_relasi");
            let nama_lahan = $(this).data("nama_lahan");
            let id_legalitas_lahan = $(this).data("id_legalitas_lahan");
            let id_status_kawasan = $(this).data("id_status_kawasan");
            let luas_ha = $(this).data("luas_ha");
            let centroid_lat = $(this).data("centroid_lat");
            let centroid_lng = $(this).data("centroid_lng");
            let geom_area = $(this).data("geom_area");
            let sudah_validasi = $(this).data("sudah_validasi");
            let tanggal_validasi = $(this).data("tanggal_validasi");
            let sejarah = $(this).data("sejarah");
            let alamat_lokasi = $(this).data("alamat_lokasi");
            let keterangan = $(this).data("keterangan");
            let status = $(this).data("status");

            $("#edit_id").val(id);
            $("#edit_kode_tanah").val(kode_tanah);
            $("#edit_id_petani").val(id_petani);
            // $("#edit_id_relasi").val(id_relasi);
            $("#edit_nama_lahan").val(nama_lahan);
            $("#edit_id_legalitas_lahan").val(id_legalitas_lahan);
            $("#edit_id_status_kawasan").val(id_status_kawasan);
            $("#edit_luas_ha").val(luas_ha);
            $("#edit_centroid_lat").val(centroid_lat);
            $("#edit_centroid_lng").val(centroid_lng);
            $("#edit_geom_area").val(geom_area);
            $("input[name='sudah_validasi'][value='" + sudah_validasi + "']").prop("checked", true);
            $("#edit_tanggal_validasi").val(tanggal_validasi);
            $("#edit_sejarah").val(sejarah);
            $("#edit_alamat_lokasi").val(alamat_lokasi);
            $("#edit_keterangan").val(keterangan);
            $("input[name='is_active'][value='" + status + "']").prop("checked", true);

            
            // set relasi tipe
            $("#edit_tipe_relasi").val(tipe_relasi).trigger("change");

            // delay biar dropdown muncul dulu
            setTimeout(() => {
                if (tipe_relasi === "hutan_adat") {
                    $("#edit_hutan_adat").val(id_relasi);
                } else if (tipe_relasi === "kaleka") {
                    $("#edit_kaleka").val(id_relasi);
                }

                $("#edit_id_relasi").val(id_relasi);
            }, 200);
        });

        // saat relasi tipe berubah
        $("#edit_tipe_relasi").on("change", function () {
            let tipe = $(this).val();

            $("#group_hutan_adat").hide();
            $("#group_kaleka").hide();
            // reset value
            $("#edit_id_relasi").val("");
            $("#edit_hutan_adat").val("");
            $("#edit_kaleka").val("");

            // reset required
            $("#edit_hutan_adat").prop("required", false);
            $("#edit_kaleka").prop("required", false);

            if (tipe === "hutan_adat") {
                $("#group_hutan_adat").show();
                $("#edit_hutan_adat").prop("required", true);
            } else if (tipe === "kaleka") {
                $("#group_kaleka").show();
                $("#edit_kaleka").prop("required", true);
            }
        });

        // saat relasi tipe berubah add
        $("#tipe_relasi").on("change", function () {
            let tipe = $(this).val();

            $("#group_hutan_adat_add").hide();
            $("#group_kaleka_add").hide();
            // reset value
            $("#id_relasi").val("");
            $("#hutan_adat").val("");
            $("#kaleka").val("");

            // reset required
            $("#hutan_adat").prop("required", false);
            $("#kaleka").prop("required", false);

            if (tipe === "hutan_adat") {
                $("#group_hutan_adat_add").show();
                $("#hutan_adat").prop("required", true);
            } else if (tipe === "kaleka") {
                $("#group_kaleka_add").show();
                $("#kaleka").prop("required", true);
            }
        });

        // set id_relasi dari dropdown aktif
        $(".relasi-dropdown").on("change", function () {
            let val = $(this).val();
            $("#edit_id_relasi").val(val);
        });

        // set id_relasi dari dropdown aktif add
        $(".relasi-dropdown-add").on("change", function () {
            let val = $(this).val();
            $("#id_relasi").val(val);
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
            showToast("Data Tanah berhasil ditambahkan", "created");
        }
        if (success === "updated") {
            showToast("Data Tanah berhasil diperbarui", "updated");
        }
        if (success === "deleted") {
            showToast("Data Tanah berhasil dihapus", "deleted");
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

    <script>
        document.getElementById('tanggal_validasi').addEventListener('focus', function () {
            this.showPicker();
        });
        document.getElementById('edit_tanggal_validasi').addEventListener('focus', function () {
            this.showPicker();
        });
    </script>

</body>

</html>