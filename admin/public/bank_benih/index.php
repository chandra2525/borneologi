<?php

require_once '../../app/config/database.php';
require_once '../../app/controllers/BankBenihController.php';
require_once '../../app/core/session.php';
require_once '../../app/core/csrf.php';
require_once "../../app/core/auth.php";
require_once '../../app/helpers/escape.php';

secureSessionStart();
checkAuth("non_dashboard");

$controller = new BankBenihController($pdo);
$bankBenihs = $controller->index();
$tanahs = $controller->getTanah();
$negaras = $controller->getNegara();
$tipePenyimpananBenihs = $controller->getTipePenyimpananBenih();

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
        $menu = "bank_benih";
        include "../feature/sidebar.php";
        ?>

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1>Data Bank Benih</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="#">Master Data</a></li>
                                <li class="breadcrumb-item active">Data Bank Benih</li>
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
                                    <h3 class="card-title col-9">Berikut adalah list dari Data Bank Benih</h3>
                                    <button class="col-3 btn btn-block btn-success" data-toggle="modal"
                                        data-target="#modalTambah">
                                        Tambah Bank Benih
                                    </button>
                                </div>
                                <!-- /.card-header -->
                                <div class="card-body">
                                    <table id="example1" class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th>Nomor Aksesi</th>
                                                <th>Nama Lahan</th>
                                                <th>Nama Negara</th>
                                                <th>Nama Lokal</th>
                                                <th>Nama Ilmiah</th>
                                                <th>Famili Tanaman</th>
                                                <th>Provenance</th>
                                                <th>Nama Tipe Penyimpanan Benih</th>
                                                <th>Tanggal Masuk</th>
                                                <th>Jumlah Stok</th>
                                                <th>Satuan Stok</th>
                                                <th>Kadar Air Persen</th>
                                                <th>Viabilitas Persen</th>
                                                <th>Ketinggian Mdpl</th>
                                                <th>Masa Berlaku Sampai</th>
                                                <th>Lokasi Penyimpanan</th>
                                                <th>Titik Koleksi Lat</th>
                                                <th>Titik Koleksi Lng</th>
                                                <th>Foto Benih</th>
                                                <th>Catatan</th>
                                                <th>Status Aktif</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($bankBenihs as $bankBenih): ?>
                                                <tr>
                                                    <td><?= htmlspecialchars($bankBenih['nomor_aksesi']) ?></td>
                                                    <td><?= htmlspecialchars($bankBenih['nama_lahan']) ?></td>
                                                    <td><?= htmlspecialchars($bankBenih['nama_negara']) ?></td>
                                                    <td><?= htmlspecialchars($bankBenih['nama_lokal']) ?></td>
                                                    <td><?= htmlspecialchars($bankBenih['nama_ilmiah']) ?></td>
                                                    <td><?= htmlspecialchars($bankBenih['famili_tanaman']) ?></td>
                                                    <td><?= htmlspecialchars($bankBenih['provenance']) ?></td>
                                                    <td><?= htmlspecialchars($bankBenih['nama_tipe_penyimpanan_benih']) ?></td>
                                                    <td><?= htmlspecialchars($bankBenih['tanggal_masuk']) ?></td>
                                                    <td><?= htmlspecialchars($bankBenih['jumlah_stok']) ?></td>
                                                    <td><?= htmlspecialchars($bankBenih['satuan_stok']) ?></td>
                                                    <td><?= htmlspecialchars($bankBenih['kadar_air_persen']) ?></td>
                                                    <td><?= htmlspecialchars($bankBenih['viabilitas_persen']) ?></td>
                                                    <td><?= htmlspecialchars($bankBenih['ketinggian_mdpl']) ?></td>
                                                    <td><?= htmlspecialchars($bankBenih['masa_berlaku_sampai']) ?></td>
                                                    <td><?= htmlspecialchars($bankBenih['lokasi_penyimpanan']) ?></td>
                                                    <td><?= htmlspecialchars($bankBenih['titik_koleksi_lat']) ?></td>
                                                    <td><?= htmlspecialchars($bankBenih['titik_koleksi_lng']) ?></td>
                                                    <td>
                                                        <img class="img-circle elevation-2" src="<?= !empty($bankBenih['foto_benih'])
                                                            ? '../../uploads/bank_benih/' . htmlspecialchars($bankBenih['foto_benih'])
                                                            : '../../../assets/image/benih_placeholder.jpg' ?>"
                                                            width="80">
                                                    </td>
                                                    <td><?= htmlspecialchars($bankBenih['catatan']) ?></td>
                                                    <td><?= $bankBenih['is_active'] ? 'Aktif' : 'Nonaktif' ?></td>
                                                    <td class="row">
                                                        <div class="col">
                                                            <button class="btn btn-block btn-info btn-edit"
                                                                data-id="<?= $bankBenih['id'] ?>"
                                                                data-nomor_aksesi="<?= htmlspecialchars($bankBenih['nomor_aksesi']) ?>"
                                                                data-id_tanah="<?= htmlspecialchars($bankBenih['id_tanah']) ?>"
                                                                data-id_negara="<?= htmlspecialchars($bankBenih['id_negara']) ?>"
                                                                data-nama_lokal="<?= htmlspecialchars($bankBenih['nama_lokal']) ?>"
                                                                data-nama_ilmiah="<?= htmlspecialchars($bankBenih['nama_ilmiah']) ?>"
                                                                data-famili_tanaman="<?= htmlspecialchars($bankBenih['famili_tanaman']) ?>"
                                                                data-provenance="<?= htmlspecialchars($bankBenih['provenance']) ?>"
                                                                data-id_tipe_penyimpanan_benih="<?= htmlspecialchars($bankBenih['id_tipe_penyimpanan_benih']) ?>"
                                                                data-tanggal_masuk="<?= htmlspecialchars($bankBenih['tanggal_masuk']) ?>"
                                                                data-jumlah_stok="<?= htmlspecialchars($bankBenih['jumlah_stok']) ?>"
                                                                data-satuan_stok="<?= htmlspecialchars($bankBenih['satuan_stok']) ?>"
                                                                data-kadar_air_persen="<?= htmlspecialchars($bankBenih['kadar_air_persen']) ?>"
                                                                data-viabilitas_persen="<?= htmlspecialchars($bankBenih['viabilitas_persen']) ?>"
                                                                data-ketinggian_mdpl="<?= htmlspecialchars($bankBenih['ketinggian_mdpl']) ?>"
                                                                data-masa_berlaku_sampai="<?= htmlspecialchars($bankBenih['masa_berlaku_sampai']) ?>"
                                                                data-lokasi_penyimpanan="<?= htmlspecialchars($bankBenih['lokasi_penyimpanan']) ?>"
                                                                data-titik_koleksi_lat="<?= htmlspecialchars($bankBenih['titik_koleksi_lat']) ?>"
                                                                data-titik_koleksi_lng="<?= htmlspecialchars($bankBenih['titik_koleksi_lng']) ?>"
                                                                data-foto="<?= $bankBenih['foto_benih'] ?>"
                                                                data-catatan="<?= htmlspecialchars($bankBenih['catatan']) ?>"
                                                                data-status="<?= $bankBenih['is_active'] ?>"
                                                                data-toggle="modal" data-target="#modalEdit">
                                                                <i class="fas fa-edit"></i> Edit
                                                            </button>
                                                        </div>

                                                        <form method="POST" action="delete.php" class="form-delete col">
                                                            <?= csrfField() ?>
                                                            <input type="hidden" name="id" value="<?= $bankBenih['id'] ?>">
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
                                                <th>Nomor Aksesi</th>
                                                <th>Nama Lahan</th>
                                                <th>Nama Negara</th>
                                                <th>Nama Lokal</th>
                                                <th>Nama Ilmiah</th>
                                                <th>Famili Tanaman</th>
                                                <th>Provenance</th>
                                                <th>Nama Tipe Penyimpanan Benih</th>
                                                <th>Tanggal Masuk</th>
                                                <th>Jumlah Stok</th>
                                                <th>Satuan Stok</th>
                                                <th>Kadar Air Persen</th>
                                                <th>Viabilitas Persen</th>
                                                <th>Ketinggian Mdpl</th>
                                                <th>Masa Berlaku Sampai</th>
                                                <th>Lokasi Penyimpanan</th>
                                                <th>Titik Koleksi Lat</th>
                                                <th>Titik Koleksi Lng</th>
                                                <th>Foto Petani</th>
                                                <th>Catatan</th>
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
                            <h4 class="modal-title">Tambah Bank Benih</h4>
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                        </div>
                        <form id="formTambah" method="POST" action="store.php" enctype="multipart/form-data">
                            <?= csrfField() ?>
                            <div class="modal-body">
                                <div class="form-group">
                                    <label for="nomor_aksesi">Nomor Aksesi<code>*</code></label>
                                    <input type="text" name="nomor_aksesi" class="form-control" id="nomor_aksesi"
                                        placeholder="Masukkan Nomor Aksesi" maxlength="50">
                                </div>
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
                                    <label for="id_negara">Negara<code>*</code></label>
                                    <select name="id_negara" class="form-control" id="id_negara">
                                        <option value="">-- Pilih Negara --</option>
                                        <?php foreach ($negaras as $ne): ?>
                                            <option value="<?= $ne['id'] ?>">
                                                <?= htmlspecialchars($ne['nama']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="nama_lokal">Nama Lokal<code>*</code></label>
                                    <input type="text" name="nama_lokal" class="form-control" id="nama_lokal"
                                        placeholder="Masukkan Nama Lokal" maxlength="100">
                                </div>
                                <div class="form-group">
                                    <label for="nama_ilmiah">Nama Ilmiah<code>*</code></label>
                                    <input type="text" name="nama_ilmiah" class="form-control" id="nama_ilmiah"
                                        placeholder="Masukkan Nama Ilmiah" maxlength="100">
                                </div>
                                <div class="form-group">
                                    <label for="famili_tanaman">Famili Tanaman<code>*</code></label>
                                    <input type="text" name="famili_tanaman" class="form-control" id="famili_tanaman"
                                        placeholder="Masukkan Famili Tanaman" maxlength="100">
                                </div>
                                <div class="form-group">
                                    <label for="provenance">Provenance<code>*</code></label>
                                    <input type="text" name="provenance" class="form-control" id="provenance"
                                        placeholder="Masukkan Provenance" maxlength="100">
                                </div>
                                <div class="form-group">
                                    <label for="id_tipe_penyimpanan_benih">Tipe Penyimpanan Benih<code>*</code></label>
                                    <select name="id_tipe_penyimpanan_benih" class="form-control" id="id_tipe_penyimpanan_benih">
                                        <option value="">-- Pilih Tipe Penyimpanan Benih --</option>
                                        <?php foreach ($tipePenyimpananBenihs as $tipe): ?>
                                            <option value="<?= $tipe['id'] ?>">
                                                <?= htmlspecialchars($tipe['nama']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="tanggal_masuk">Tanggal Masuk<code>*</code></label>
                                    <input type="date" name="tanggal_masuk" class="form-control" id="tanggal_masuk">
                                </div>
                                <div class="form-group">
                                    <label for="jumlah_stok">Jumlah Stok<code>*</code></label>
                                    <input type="number" name="jumlah_stok" class="form-control" id="jumlah_stok"
                                        placeholder="Masukkan Jumlah Stok" min="0">
                                </div>
                                <div class="form-group">
                                    <label for="satuan_stok">Satuan Stok<code>*</code></label>
                                    <select name="satuan_stok" class="form-control" id="satuan_stok">
                                        <option value="">-- Pilih Satuan --</option>
                                        <option value="butir">Butir</option>
                                        <option value="gram">Gram</option>
                                        <option value="kg">Kilogram</option>
                                        <option value="paket">Paket</option>
                                        <option value="bibit">Bibit</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="kadar_air_persen">Kadar Air (%)<code>*</code></label>
                                    <input type="number" name="kadar_air_persen" class="form-control" id="kadar_air_persen"
                                        placeholder="Masukkan Kadar Air" min="0" max="100">
                                </div>
                                <div class="form-group">
                                    <label for="viabilitas_persen">Viabilitas (%)<code>*</code></label>
                                    <input type="number" name="viabilitas_persen" class="form-control" id="viabilitas_persen"
                                        placeholder="Masukkan Viabilitas" min="0" max="100">
                                </div>
                                <div class="form-group">
                                    <label for="ketinggian_mdpl">Ketinggian (mdpl)<code>*</code></label>
                                    <input type="number" name="ketinggian_mdpl" class="form-control" id="ketinggian_mdpl"
                                        placeholder="Masukkan Ketinggian" min="0">
                                </div>
                                <div class="form-group">
                                    <label for="masa_berlaku_sampai">Masa Berlaku Sampai<code>*</code></label>
                                    <input type="date" name="masa_berlaku_sampai" class="form-control" id="masa_berlaku_sampai">
                                </div>
                                <div class="form-group">
                                    <label for="lokasi_penyimpanan">Lokasi Penyimpanan<code>*</code></label>
                                    <input type="text" name="lokasi_penyimpanan" class="form-control" id="lokasi_penyimpanan"
                                        placeholder="Masukkan Lokasi Penyimpanan" maxlength="30">
                                </div>
                                <div class="form-group">
                                    <label for="titik_koleksi_lat">Titik Koleksi Latitude<code>*</code></label>
                                    <input type="number" name="titik_koleksi_lat" class="form-control" id="titik_koleksi_lat"
                                        placeholder="Masukkan Latitude">
                                </div>
                                <div class="form-group">
                                    <label for="titik_koleksi_lng">Titik Koleksi Longitude<code>*</code></label>
                                    <input type="number" name="titik_koleksi_lng" class="form-control" id="titik_koleksi_lng"
                                        placeholder="Masukkan Longitude" min="-180" max="180">
                                </div>
                                <div class="form-group">
                                    <label for="foto_benih">Foto Benih</label>
                                    <input type="file" name="foto_benih" class="form-control"
                                        id="foto_benih" accept="image/*">
                                </div>
                                <div class="form-group">
                                    <label for="catatan">Catatan<code>*</code></label>
                                    <textarea name="catatan" class="form-control" id="catatan"
                                        placeholder="Masukkan Catatan"></textarea>
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
                            <h4 class="modal-title">Edit Bank Benih</h4>
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                        </div>

                        <form id="formEdit" method="POST" action="update.php" enctype="multipart/form-data">
                            <?= csrfField() ?>
                            <input type="hidden" name="id" id="edit_id">
                            <div class="modal-body">
                                <div class="form-group">
                                    <label for="nomor_aksesi">Nomor Aksesi<code>*</code></label>
                                    <input type="text" name="nomor_aksesi" class="form-control" id="edit_nomor_aksesi"
                                        placeholder="Masukkan Nomor Aksesi" maxlength="50">
                                </div>
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
                                    <label for="id_negara">Negara<code>*</code></label>
                                    <select name="id_negara" class="form-control" id="edit_id_negara">
                                        <option value="">-- Pilih Negara --</option>
                                        <?php foreach ($negaras as $ne): ?>
                                            <option value="<?= $ne['id'] ?>">
                                                <?= htmlspecialchars($ne['nama']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="nama_lokal">Nama Lokal<code>*</code></label>
                                    <input type="text" name="nama_lokal" class="form-control" id="edit_nama_lokal"
                                        placeholder="Masukkan Nama Lokal" maxlength="100">
                                </div>
                                <div class="form-group">
                                    <label for="nama_ilmiah">Nama Ilmiah<code>*</code></label>
                                    <input type="text" name="nama_ilmiah" class="form-control" id="edit_nama_ilmiah"
                                        placeholder="Masukkan Nama Ilmiah" maxlength="100">
                                </div>
                                <div class="form-group">
                                    <label for="famili_tanaman">Famili Tanaman<code>*</code></label>
                                    <input type="text" name="famili_tanaman" class="form-control" id="edit_famili_tanaman"
                                        placeholder="Masukkan Famili Tanaman" maxlength="100">
                                </div>
                                <div class="form-group">
                                    <label for="provenance">Provenance<code>*</code></label>
                                    <input type="text" name="provenance" class="form-control" id="edit_provenance"
                                        placeholder="Masukkan Provenance" maxlength="100">
                                </div>
                                <div class="form-group">
                                    <label for="id_tipe_penyimpanan_benih">Tipe Penyimpanan Benih<code>*</code></label>
                                    <select name="id_tipe_penyimpanan_benih" class="form-control" id="edit_id_tipe_penyimpanan_benih">
                                        <option value="">-- Pilih Tipe Penyimpanan Benih --</option>
                                        <?php foreach ($tipePenyimpananBenihs as $tipe): ?>
                                            <option value="<?= $tipe['id'] ?>">
                                                <?= htmlspecialchars($tipe['nama']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="tanggal_masuk">Tanggal Masuk<code>*</code></label>
                                    <input type="date" name="tanggal_masuk" class="form-control" id="edit_tanggal_masuk">
                                </div>
                                <div class="form-group">
                                    <label for="jumlah_stok">Jumlah Stok<code>*</code></label>
                                    <input type="number" name="jumlah_stok" class="form-control" id="edit_jumlah_stok"
                                        placeholder="Masukkan Jumlah Stok" min="0">
                                </div>
                                <div class="form-group">
                                    <label for="satuan_stok">Satuan Stok<code>*</code></label>
                                    <select name="satuan_stok" class="form-control" id="edit_satuan_stok">
                                        <option value="">-- Pilih Satuan --</option>
                                        <option value="butir">Butir</option>
                                        <option value="gram">Gram</option>
                                        <option value="kg">Kilogram</option>
                                        <option value="paket">Paket</option>
                                        <option value="bibit">Bibit</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="kadar_air_persen">Kadar Air (%)<code>*</code></label>
                                    <input type="number" name="kadar_air_persen" class="form-control" id="edit_kadar_air_persen"
                                        placeholder="Masukkan Kadar Air" min="0" max="100">
                                </div>
                                <div class="form-group">
                                    <label for="viabilitas_persen">Viabilitas (%)<code>*</code></label>
                                    <input type="number" name="viabilitas_persen" class="form-control" id="edit_viabilitas_persen"
                                        placeholder="Masukkan Viabilitas" min="0" max="100">
                                </div>
                                <div class="form-group">
                                    <label for="ketinggian_mdpl">Ketinggian (mdpl)<code>*</code></label>
                                    <input type="number" name="ketinggian_mdpl" class="form-control" id="edit_ketinggian_mdpl"
                                        placeholder="Masukkan Ketinggian" min="0">
                                </div>
                                <div class="form-group">
                                    <label for="masa_berlaku_sampai">Masa Berlaku Sampai<code>*</code></label>
                                    <input type="date" name="masa_berlaku_sampai" class="form-control" id="edit_masa_berlaku_sampai">
                                </div>
                                <div class="form-group">
                                    <label for="lokasi_penyimpanan">Lokasi Penyimpanan<code>*</code></label>
                                    <input type="text" name="lokasi_penyimpanan" class="form-control" id="edit_lokasi_penyimpanan"
                                        placeholder="Masukkan Lokasi Penyimpanan" maxlength="30">
                                </div>
                                <div class="form-group">
                                    <label for="titik_koleksi_lat">Titik Koleksi Latitude<code>*</code></label>
                                    <input type="number" name="titik_koleksi_lat" class="form-control" id="edit_titik_koleksi_lat"
                                        placeholder="Masukkan Latitude">
                                </div>
                                <div class="form-group">
                                    <label for="titik_koleksi_lng">Titik Koleksi Longitude<code>*</code></label>
                                    <input type="number" name="titik_koleksi_lng" class="form-control" id="edit_titik_koleksi_lng"
                                        placeholder="Masukkan Longitude" min="-180" max="180">
                                </div>
                                <input type="hidden" name="foto_lama" id="edit_foto_lama">
                                <div class="form-group">
                                    <label for="edit_foto_benih">Foto Benih</label>
                                    <input type="file" name="foto_benih" class="form-control"
                                        id="edit_foto_benih" accept="image/*">

                                    <small class="text-muted">
                                        Kosongkan jika tidak ingin mengganti foto
                                    </small>
                                </div>
                                <div class="form-group">
                                    <label for="catatan">Catatan<code>*</code></label>
                                    <textarea name="catatan" class="form-control" id="edit_catatan"
                                        placeholder="Masukkan Catatan"></textarea>
                                </div>
                                <div class="form-group">
                                    <label>Status Aktif<code>*</code></label>
                                    <div class="form-group row">
                                        <div class="col-sm-2">
                                            <div class="custom-control custom-radio">
                                                <input class="custom-control-input" type="radio" id="edit_status_aktif"
                                                    name="is_active" value="1" checked>
                                                <label for="edit_status_aktif" class="custom-control-label">Aktif</label>
                                            </div>
                                        </div>
                                        <div class="col-sm-2">
                                            <div class="custom-control custom-radio">
                                                <input class="custom-control-input" type="radio"
                                                    id="edit_status_nonaktif" name="is_active" value="0">
                                                <label for="edit_status_nonaktif"
                                                    class="custom-control-label">Nonaktif</label>
                                            </div>
                                        </div>
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
                        nomor_aksesi: {
                            required: true
                        },
                        id_tanah: {
                            required: true
                        },
                        id_negara: {
                            required: true
                        },
                        nama_lokal: {
                            required: true
                        },
                        nama_ilmiah: {
                            required: true
                        },
                        famili_tanaman: {
                            required: true
                        },
                        provenance: {
                            required: true
                        },
                        id_tipe_penyimpanan_benih: {
                            required: true
                        },
                        tanggal_masuk: {
                            required: true
                        },
                        jumlah_stok: {
                            required: true
                        },
                        satuan_stok: {
                            required: true
                        },
                        kadar_air_persen: {
                            required: true
                        },
                        viabilitas_persen: {
                            required: true
                        },
                        ketinggian_mdpl: {
                            required: true
                        },
                        masa_berlaku_sampai: {
                            required: true
                        },
                        lokasi_penyimpanan: {
                            required: true
                        },
                        titik_koleksi_lat: {
                            required: true
                        },
                        titik_koleksi_lng: {
                            required: true
                        },
                        catatan: {
                            required: true
                        },
                        is_active: {
                            required: true
                        }
                    },
                    messages: {
                        nomor_aksesi: {
                            required: "Silahkan masukkan Nomor Aksesi"
                        },
                        id_tanah: {
                            required: "Silahkan pilih Tanah"
                        },
                        id_negara: {
                            required: "Silahkan pilih Negara"
                        },
                        nama_lokal: {
                            required: "Silahkan masukkan Nama Lokal"
                        },
                        nama_ilmiah: {
                            required: "Silahkan masukkan Nama Ilmiah"
                        },
                        famili_tanaman: {
                            required: "Silahkan masukkan Famili Tanaman"
                        },
                        provenance: {
                            required: "Silahkan masukkan Provenance"
                        },
                        id_tipe_penyimpanan_benih: {
                            required: "Silahkan pilih Tipe Penyimpanan Benih"
                        },
                        tanggal_masuk: {
                            required: "Silahkan masukkan Tanggal Masuk"
                        },
                        jumlah_stok: {
                            required: "Silahkan masukkan Jumlah Stok"
                        },
                        satuan_stok: {
                            required: "Silahkan masukkan Satuan Stok"
                        },
                        kadar_air_persen: {
                            required: "Silahkan masukkan Kadar Air"
                        },
                        viabilitas_persen: {
                            required: "Silahkan masukkan Viabilitas"
                        },
                        ketinggian_mdpl: {
                            required: "Silahkan masukkan Ketinggian MDPL"
                        },
                        masa_berlaku_sampai: {
                            required: "Silahkan masukkan Masa Berlaku Sampai"
                        },
                        lokasi_penyimpanan: {
                            required: "Silahkan masukkan Lokasi Penyimpanan"
                        },
                        titik_koleksi_lat: {
                            required: "Silahkan masukkan Titik Koleksi Latitude"
                        },
                        titik_koleksi_lng: {
                            required: "Silahkan masukkan Titik Koleksi Longitude"
                        },
                        catatan: {
                            required: "Silahkan masukkan Catatan"
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
            let nomor_aksesi = $(this).data("nomor_aksesi");
            let id_tanah = $(this).data("id_tanah");
            let id_negara = $(this).data("id_negara");
            let nama_lokal = $(this).data("nama_lokal");
            let nama_ilmiah = $(this).data("nama_ilmiah");
            let famili_tanaman = $(this).data("famili_tanaman");
            let provenance = $(this).data("provenance");
            let id_tipe_penyimpanan_benih = $(this).data("id_tipe_penyimpanan_benih");
            let tanggal_masuk = $(this).data("tanggal_masuk");
            let jumlah_stok = $(this).data("jumlah_stok");
            let satuan_stok = $(this).data("satuan_stok");
            let kadar_air_persen = $(this).data("kadar_air_persen");
            let viabilitas_persen = $(this).data("viabilitas_persen");
            let ketinggian_mdpl = $(this).data("ketinggian_mdpl");
            let masa_berlaku_sampai = $(this).data("masa_berlaku_sampai");
            let lokasi_penyimpanan = $(this).data("lokasi_penyimpanan");
            let titik_koleksi_lat = $(this).data("titik_koleksi_lat");
            let titik_koleksi_lng = $(this).data("titik_koleksi_lng");
            let foto = $(this).data("foto");
            let catatan = $(this).data("catatan");
            let status = $(this).data("status");

            $("#edit_id").val(id);
            $("#edit_nomor_aksesi").val(nomor_aksesi);
            $("#edit_id_tanah").val(id_tanah);
            $("#edit_id_negara").val(id_negara);
            $("#edit_nama_lokal").val(nama_lokal);
            $("#edit_nama_ilmiah").val(nama_ilmiah);
            $("#edit_famili_tanaman").val(famili_tanaman);
            $("#edit_provenance").val(provenance);
            $("#edit_id_tipe_penyimpanan_benih").val(id_tipe_penyimpanan_benih);
            $("#edit_tanggal_masuk").val(tanggal_masuk);
            $("#edit_jumlah_stok").val(jumlah_stok);
            $("#edit_satuan_stok").val(satuan_stok);
            $("#edit_kadar_air_persen").val(kadar_air_persen);
            $("#edit_viabilitas_persen").val(viabilitas_persen);
            $("#edit_ketinggian_mdpl").val(ketinggian_mdpl);
            $("#edit_masa_berlaku_sampai").val(masa_berlaku_sampai);
            $("#edit_lokasi_penyimpanan").val(lokasi_penyimpanan);
            $("#edit_titik_koleksi_lat").val(titik_koleksi_lat);
            $("#edit_titik_koleksi_lng").val(titik_koleksi_lng);
            $("#edit_foto_lama").val(foto);
            $("#edit_catatan").val(catatan);
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
            showToast("Data Bank Benih berhasil ditambahkan", "created");
        }
        if (success === "updated") {
            showToast("Data Bank Benih berhasil diperbarui", "updated");
        }
        if (success === "deleted") {
            showToast("Data Bank Benih berhasil dihapus", "deleted");
        }
    </script>

    <script>
        document.getElementById('tanggal_masuk').addEventListener('focus', function () {
            this.showPicker();
        });
        document.getElementById('edit_tanggal_masuk').addEventListener('focus', function () {
            this.showPicker();
        });
        document.getElementById('masa_berlaku_sampai').addEventListener('focus', function () {
            this.showPicker();
        });
        document.getElementById('edit_masa_berlaku_sampai').addEventListener('focus', function () {
            this.showPicker();
        });
    </script>
</body>

</html>