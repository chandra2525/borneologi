<?php

require_once '../../app/config/database.php';
require_once '../../app/controllers/BankBenihController.php';
require_once '../../app/core/session.php';
require_once '../../app/core/csrf.php';
require_once "../../app/core/auth.php";
require_once '../../app/helpers/escape.php';
require_once '../../app/core/permission.php';

secureSessionStart();
checkAuth("non_dashboard");

Permission::authorize(
    $pdo,
    'Bank Benih',
    'create'
);

$controller = new BankBenihController($pdo);

$tanahs = $controller->getTanah();
$negaras = $controller->getNegara();
$tipePenyimpananBenihs =
    $controller->getTipePenyimpananBenih();

?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="utf-8">

    <meta name="viewport"
        content="width=device-width, initial-scale=1">

    <title>Tambah Benih</title>

    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700">

    <link rel="stylesheet"
        href="../assets/adminlte/plugins/fontawesome-free/css/all.min.css">

    <link rel="stylesheet"
        href="../assets/adminlte/dist/css/adminlte.min.css">

</head>

<body class="hold-transition sidebar-mini">

<div class="wrapper">

<?php include "../feature/navbar.php"; ?>

<?php
$menu = "bank_benih";
include "../feature/sidebar.php";
?>


<div class="content-wrapper">


<section class="content-header">

    <div class="container-fluid">

        <div class="row mb-2">

            <div class="col-sm-6">

                <h1>

                    <i class="fas fa-seedling"></i>

                    Tambah Benih

                </h1>

            </div>

        </div>

    </div>

</section>


<section class="content">

<div class="container-fluid">

<form
    method="POST"
    action="store.php"
    enctype="multipart/form-data"
    id="formBenih">

<?= csrfField() ?>


<!-- ===================================================== -->
<!-- IDENTITAS -->
<!-- ===================================================== -->

<div class="card card-primary">

<div class="card-header">

<h3 class="card-title">

<i class="fas fa-seedling"></i>

Informasi Benih

</h3>

</div>


<div class="card-body">

<div class="row">


<div class="col-md-6">

<div class="form-group">

<label>

Nomor Aksesi

</label>

<input
    type="text"
    name="nomor_aksesi"
    class="form-control"
    maxlength="50"
    placeholder="Masukkan Nomor Aksesi">

<small class="text-muted">

Nomor aksesi harus unik.

</small>

</div>

</div>


<div class="col-md-6">

<div class="form-group">

<label>

Nama Lokal <code>*</code>

</label>

<input
    type="text"
    name="nama_lokal"
    class="form-control"
    maxlength="200"
    required
    placeholder="Masukkan Nama Lokal">

</div>

</div>


<div class="col-md-6">

<div class="form-group">

<label>

Nama Ilmiah

</label>

<input
    type="text"
    name="nama_ilmiah"
    class="form-control"
    maxlength="200"
    placeholder="Masukkan Nama Ilmiah">

</div>

</div>


<div class="col-md-6">

<div class="form-group">

<label>

Famili Tanaman

</label>

<input
    type="text"
    name="famili_tanaman"
    class="form-control"
    maxlength="150"
    placeholder="Masukkan Famili Tanaman">

</div>

</div>


<div class="col-md-6">

<div class="form-group">

<label>

Provenance

</label>

<input
    type="text"
    name="provenance"
    class="form-control"
    maxlength="200"
    placeholder="Masukkan Provenance">

</div>

</div>


<div class="col-md-6">

<div class="form-group">

<label>

Negara <code>*</code>

</label>

<select
    name="id_negara"
    class="form-control"
    required>

<option value="">
-- Pilih Negara --
</option>

<?php foreach ($negaras as $negara): ?>

<option value="<?= $negara['id'] ?>">

<?= htmlspecialchars(
    $negara['nama']
) ?>

</option>

<?php endforeach; ?>

</select>

</div>

</div>


</div>

</div>

</div>



<!-- ===================================================== -->
<!-- STOK -->
<!-- ===================================================== -->

<div class="card card-success">

<div class="card-header">

<h3 class="card-title">

<i class="fas fa-boxes"></i>

Stok dan Kondisi Benih

</h3>

</div>


<div class="card-body">

<div class="row">


<div class="col-md-6">

<div class="form-group">

<label>

Jumlah Stok <code>*</code>

</label>

<input
    type="number"
    name="jumlah_stok"
    class="form-control"
    min="0"
    step="0.01"
    value="0"
    required>

</div>

</div>


<div class="col-md-6">

<div class="form-group">

<label>

Satuan Stok <code>*</code>

</label>

<select
    name="satuan_stok"
    class="form-control"
    required>

<option value="">
-- Pilih Satuan --
</option>

<option value="butir">
Butir
</option>

<option value="gram">
Gram
</option>

<option value="kg">
Kilogram
</option>

<option value="paket">
Paket
</option>

<option value="bibit">
Bibit
</option>

</select>

</div>

</div>


<div class="col-md-4">

<div class="form-group">

<label>

Kadar Air (%)

</label>

<input
    type="number"
    name="kadar_air_persen"
    class="form-control"
    min="0"
    max="100"
    step="0.01"
    placeholder="0 - 100">

</div>

</div>


<div class="col-md-4">

<div class="form-group">

<label>

Viabilitas (%)

</label>

<input
    type="number"
    name="viabilitas_persen"
    class="form-control"
    min="0"
    max="100"
    step="0.01"
    placeholder="0 - 100">

</div>

</div>


<div class="col-md-4">

<div class="form-group">

<label>

Ketinggian (MDPL)

</label>

<input
    type="number"
    name="ketinggian_mdpl"
    class="form-control"
    min="0"
    placeholder="Masukkan ketinggian">

</div>

</div>


</div>

</div>

</div>



<!-- ===================================================== -->
<!-- PENYIMPANAN -->
<!-- ===================================================== -->

<div class="card card-warning">

<div class="card-header">

<h3 class="card-title">

<i class="fas fa-warehouse"></i>

Penyimpanan

</h3>

</div>


<div class="card-body">

<div class="row">


<div class="col-md-6">

<div class="form-group">

<label>

Tipe Penyimpanan <code>*</code>

</label>

<select
    name="id_tipe_penyimpanan_benih"
    class="form-control"
    required>

<option value="">
-- Pilih Tipe Penyimpanan --
</option>

<?php foreach (
    $tipePenyimpananBenihs
    as $tipe
): ?>

<option value="<?= $tipe['id'] ?>">

<?= htmlspecialchars(
    $tipe['nama']
) ?>

</option>

<?php endforeach; ?>

</select>

</div>

</div>


<div class="col-md-6">

<div class="form-group">

<label>

Lokasi Penyimpanan

</label>

<input
    type="text"
    name="lokasi_penyimpanan"
    class="form-control"
    maxlength="200"
    placeholder="Contoh: Ruang Benih A - Rak 03">

</div>

</div>


<div class="col-md-6">

<div class="form-group">

<label>

Tanggal Masuk <code>*</code>

</label>

<input
    type="date"
    name="tanggal_masuk"
    class="form-control"
    required>

</div>

</div>


<div class="col-md-6">

<div class="form-group">

<label>

Masa Berlaku Sampai

</label>

<input
    type="date"
    name="masa_berlaku_sampai"
    class="form-control">

</div>

</div>


</div>

</div>

</div>



<!-- ===================================================== -->
<!-- LOKASI -->
<!-- ===================================================== -->

<div class="card card-info">

<div class="card-header">

<h3 class="card-title">

<i class="fas fa-map-marker-alt"></i>

Lokasi Koleksi

</h3>

</div>


<div class="card-body">

<div class="row">


<div class="col-md-12">

<div class="form-group">

<label>

Tanah <code>*</code>

</label>

<select
    name="id_tanah"
    class="form-control"
    required>

<option value="">
-- Pilih Tanah --
</option>

<?php foreach ($tanahs as $tanah): ?>

<option value="<?= $tanah['id'] ?>">

<?= htmlspecialchars(
    $tanah['nama_lahan']
) ?>

</option>

<?php endforeach; ?>

</select>

</div>

</div>


<div class="col-md-6">

<div class="form-group">

<label>

Latitude

</label>

<input
    type="number"
    name="titik_koleksi_lat"
    class="form-control"
    min="-90"
    max="90"
    step="0.000000000000001"
    placeholder="-2.123456789">

</div>

</div>


<div class="col-md-6">

<div class="form-group">

<label>

Longitude

</label>

<input
    type="number"
    name="titik_koleksi_lng"
    class="form-control"
    min="-180"
    max="180"
    step="0.000000000000001"
    placeholder="113.123456789">

</div>

</div>


</div>

</div>

</div>



<!-- ===================================================== -->
<!-- FOTO DAN CATATAN -->
<!-- ===================================================== -->

<div class="card card-secondary">

<div class="card-header">

<h3 class="card-title">

<i class="fas fa-image"></i>

Foto dan Catatan

</h3>

</div>


<div class="card-body">

<div class="form-group">

<label>

Foto Benih

</label>

<input
    type="file"
    name="foto_benih"
    class="form-control"
    accept=".jpg,.jpeg,.png,.webp">

<small class="text-muted">

Format JPG, JPEG, PNG atau WEBP.
Maksimal 2 MB.

</small>

</div>


<div class="form-group">

<label>

Catatan

</label>

<textarea
    name="catatan"
    class="form-control"
    rows="4"
    placeholder="Masukkan catatan..."></textarea>

</div>

</div>

</div>



<!-- ===================================================== -->
<!-- STATUS -->
<!-- ===================================================== -->

<div class="card">

<div class="card-header">

<h3 class="card-title">

Status Data

</h3>

</div>


<div class="card-body">

<div class="custom-control custom-radio">

<input
    type="radio"
    id="aktif"
    name="is_active"
    value="1"
    class="custom-control-input"
    checked>

<label
    for="aktif"
    class="custom-control-label">

Aktif

</label>

</div>


<div class="custom-control custom-radio">

<input
    type="radio"
    id="nonaktif"
    name="is_active"
    value="0"
    class="custom-control-input">

<label
    for="nonaktif"
    class="custom-control-label">

Nonaktif

</label>

</div>

</div>

</div>



<!-- ===================================================== -->
<!-- BUTTON -->
<!-- ===================================================== -->

<div class="mb-4">

<a
    href="index.php"
    class="btn btn-secondary">

<i class="fas fa-arrow-left"></i>

Kembali

</a>


<button
    type="submit"
    class="btn btn-primary">

<i class="fas fa-save"></i>

Simpan Benih

</button>

</div>


</form>

</div>

</section>


</div>


<?php include "../feature/footer.php"; ?>

</div>


<script src="../assets/adminlte/plugins/jquery/jquery.min.js"></script>

<script src="../assets/adminlte/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>

<script src="../assets/adminlte/dist/js/adminlte.min.js"></script>


</body>

</html>