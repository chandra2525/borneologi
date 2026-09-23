<?php

require_once '../../app/config/database.php';
require_once '../../app/controllers/BankBenihController.php';
require_once '../../app/core/session.php';
require_once '../../app/core/csrf.php';
require_once '../../app/core/auth.php';
require_once '../../app/helpers/escape.php';
require_once '../../app/core/permission.php';

secureSessionStart();
checkAuth("non_dashboard");

Permission::authorize(
    $pdo,
    'Bank Benih',
    'update'
);

$controller = new BankBenihController($pdo);

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id <= 0) {
    header("Location: index.php?error=invalid_id");
    exit;
}

$bankBenih = $controller->find($id);

if (!$bankBenih) {
    header("Location: index.php?error=not_found");
    exit;
}

$tanahs = $controller->getTanah();
$negaras = $controller->getNegara();
$tipePenyimpananBenihs =
    $controller->getTipePenyimpananBenih();

function oldValue($value)
{
    return htmlspecialchars(
        $value ?? '',
        ENT_QUOTES,
        'UTF-8'
    );
}

?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="utf-8">

    <meta name="viewport"
        content="width=device-width, initial-scale=1">

    <title>Edit Data Benih - Admin Borneologi</title>

    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700">

    <link rel="stylesheet"
        href="../assets/adminlte/plugins/fontawesome-free/css/all.min.css">

    <link rel="stylesheet"
        href="../assets/adminlte/dist/css/adminlte.min.css">

    <style>

        .required {
            color: #dc3545;
        }

        .current-photo {
            max-width: 220px;
            max-height: 220px;
            object-fit: cover;
            border-radius: 8px;
            border: 1px solid #ddd;
        }

        .section-title {
            font-weight: 600;
        }

        .form-text-muted {
            font-size: 12px;
            color: #6c757d;
        }

    </style>

</head>


<body class="hold-transition sidebar-mini">

<div class="wrapper">


<?php include "../feature/navbar.php"; ?>


<?php

$menu = "bank_benih";

include "../feature/sidebar.php";

?>


<div class="content-wrapper">


<!-- ===================================================== -->
<!-- HEADER -->
<!-- ===================================================== -->

<section class="content-header">

    <div class="container-fluid">

        <div class="row mb-2">

            <div class="col-sm-6">

                <h1>

                    <i class="fas fa-edit"></i>

                    Edit Data Benih

                </h1>

            </div>


            <div class="col-sm-6">

                <ol class="breadcrumb float-sm-right">

                    <li class="breadcrumb-item">

                        <a href="index.php">
                            Data Benih
                        </a>

                    </li>

                    <li class="breadcrumb-item active">
                        Edit
                    </li>

                </ol>

            </div>

        </div>

    </div>

</section>


<!-- ===================================================== -->
<!-- CONTENT -->
<!-- ===================================================== -->

<section class="content">

<div class="container-fluid">


<form
    id="formEditBenih"
    method="POST"
    action="update.php"
    enctype="multipart/form-data"
    novalidate>


<?= csrfField() ?>


<input
    type="hidden"
    name="id"
    value="<?= (int)$bankBenih['id'] ?>">


<input
    type="hidden"
    name="foto_lama"
    value="<?= oldValue($bankBenih['foto_benih']) ?>">



<!-- ===================================================== -->
<!-- INFORMASI IDENTITAS -->
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


            <!-- NOMOR AKSESI -->

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
                        value="<?= oldValue(
                            $bankBenih['nomor_aksesi']
                        ) ?>"
                        placeholder="Masukkan Nomor Aksesi">

                    <small class="form-text-muted">

                        Nomor aksesi harus unik.

                    </small>

                </div>

            </div>


            <!-- NAMA LOKAL -->

            <div class="col-md-6">

                <div class="form-group">

                    <label>
                        Nama Lokal
                        <span class="required">*</span>
                    </label>

                    <input
                        type="text"
                        name="nama_lokal"
                        class="form-control"
                        maxlength="200"
                        required
                        value="<?= oldValue(
                            $bankBenih['nama_lokal']
                        ) ?>"
                        placeholder="Masukkan Nama Lokal">

                    <div
                        class="invalid-feedback">

                        Nama lokal wajib diisi.

                    </div>

                </div>

            </div>


            <!-- NAMA ILMIAH -->

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
                        value="<?= oldValue(
                            $bankBenih['nama_ilmiah']
                        ) ?>"
                        placeholder="Masukkan Nama Ilmiah">

                </div>

            </div>


            <!-- FAMILI -->

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
                        value="<?= oldValue(
                            $bankBenih['famili_tanaman']
                        ) ?>"
                        placeholder="Masukkan Famili Tanaman">

                </div>

            </div>


            <!-- PROVENANCE -->

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
                        value="<?= oldValue(
                            $bankBenih['provenance']
                        ) ?>"
                        placeholder="Masukkan Provenance">

                </div>

            </div>


            <!-- NEGARA -->

            <div class="col-md-6">

                <div class="form-group">

                    <label>
                        Negara
                        <span class="required">*</span>
                    </label>

                    <select
                        name="id_negara"
                        class="form-control"
                        required>

                        <option value="">
                            -- Pilih Negara --
                        </option>

                        <?php foreach (
                            $negaras as $negara
                        ): ?>

                            <option
                                value="<?= (int)$negara['id'] ?>"
                                <?= (
                                    (string)$bankBenih['id_negara']
                                    ===
                                    (string)$negara['id']
                                )
                                    ? 'selected'
                                    : ''
                                ?>>

                                <?= oldValue(
                                    $negara['nama']
                                ) ?>

                            </option>

                        <?php endforeach; ?>

                    </select>

                    <div
                        class="invalid-feedback">

                        Negara wajib dipilih.

                    </div>

                </div>

            </div>


        </div>

    </div>

</div>



<!-- ===================================================== -->
<!-- STOK DAN KONDISI -->
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


            <!-- JUMLAH STOK -->

            <div class="col-md-6">

                <div class="form-group">

                    <label>
                        Jumlah Stok
                        <span class="required">*</span>
                    </label>

                    <input
                        type="number"
                        name="jumlah_stok"
                        class="form-control"
                        min="0"
                        step="0.01"
                        required
                        value="<?= oldValue(
                            $bankBenih['jumlah_stok']
                        ) ?>">

                    <small class="form-text-muted">

                        Gunakan menu pengelolaan stok
                        apabila nantinya stok sudah
                        terintegrasi dengan monitoring.

                    </small>

                </div>

            </div>


            <!-- SATUAN -->

            <div class="col-md-6">

                <div class="form-group">

                    <label>
                        Satuan Stok
                        <span class="required">*</span>
                    </label>

                    <select
                        name="satuan_stok"
                        class="form-control"
                        required>

                        <option value="">
                            -- Pilih Satuan --
                        </option>

                        <?php

                        $satuanOptions = [
                            'butir' => 'Butir',
                            'gram' => 'Gram',
                            'kg' => 'Kilogram',
                            'paket' => 'Paket',
                            'bibit' => 'Bibit'
                        ];

                        foreach (
                            $satuanOptions
                            as $value => $label
                        ):

                        ?>

                            <option
                                value="<?= $value ?>"
                                <?= (
                                    $bankBenih['satuan_stok']
                                    === $value
                                )
                                    ? 'selected'
                                    : ''
                                ?>>

                                <?= $label ?>

                            </option>

                        <?php endforeach; ?>

                    </select>

                </div>

            </div>


            <!-- KADAR AIR -->

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
                        value="<?= oldValue(
                            $bankBenih['kadar_air_persen']
                        ) ?>"
                        placeholder="0 - 100">

                </div>

            </div>


            <!-- VIABILITAS -->

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
                        value="<?= oldValue(
                            $bankBenih['viabilitas_persen']
                        ) ?>"
                        placeholder="0 - 100">

                </div>

            </div>


            <!-- KETINGGIAN -->

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
                        step="0.01"
                        value="<?= oldValue(
                            $bankBenih['ketinggian_mdpl']
                        ) ?>"
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


            <!-- TIPE -->

            <div class="col-md-6">

                <div class="form-group">

                    <label>
                        Tipe Penyimpanan
                        <span class="required">*</span>
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

                            <option
                                value="<?= (int)$tipe['id'] ?>"
                                <?= (
                                    (string)$bankBenih[
                                        'id_tipe_penyimpanan_benih'
                                    ]
                                    ===
                                    (string)$tipe['id']
                                )
                                    ? 'selected'
                                    : ''
                                ?>>

                                <?= oldValue(
                                    $tipe['nama']
                                ) ?>

                            </option>

                        <?php endforeach; ?>

                    </select>

                    <div
                        class="invalid-feedback">

                        Tipe penyimpanan wajib dipilih.

                    </div>

                </div>

            </div>


            <!-- LOKASI -->

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
                        value="<?= oldValue(
                            $bankBenih['lokasi_penyimpanan']
                        ) ?>"
                        placeholder="Contoh: Ruang Benih A - Rak 03">

                </div>

            </div>


            <!-- TANGGAL MASUK -->

            <div class="col-md-6">

                <div class="form-group">

                    <label>
                        Tanggal Masuk
                        <span class="required">*</span>
                    </label>

                    <input
                        type="date"
                        name="tanggal_masuk"
                        id="tanggal_masuk"
                        class="form-control"
                        required
                        value="<?= oldValue(
                            $bankBenih['tanggal_masuk']
                        ) ?>">

                    <div
                        class="invalid-feedback">

                        Tanggal masuk wajib diisi.

                    </div>

                </div>

            </div>


            <!-- MASA BERLAKU -->

            <div class="col-md-6">

                <div class="form-group">

                    <label>
                        Masa Berlaku Sampai
                    </label>

                    <input
                        type="date"
                        name="masa_berlaku_sampai"
                        id="masa_berlaku_sampai"
                        class="form-control"
                        value="<?= oldValue(
                            $bankBenih['masa_berlaku_sampai']
                        ) ?>">

                    <small class="form-text-muted">

                        Kosongkan apabila tidak ada
                        tanggal kedaluwarsa.

                    </small>

                </div>

            </div>


        </div>

    </div>

</div>



<!-- ===================================================== -->
<!-- LOKASI KOLEKSI -->
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


            <!-- TANAH -->

            <div class="col-md-12">

                <div class="form-group">

                    <label>
                        Tanah
                        <span class="required">*</span>
                    </label>

                    <select
                        name="id_tanah"
                        class="form-control"
                        required>

                        <option value="">
                            -- Pilih Tanah --
                        </option>

                        <?php foreach (
                            $tanahs as $tanah
                        ): ?>

                            <option
                                value="<?= (int)$tanah['id'] ?>"
                                <?= (
                                    (string)$bankBenih['id_tanah']
                                    ===
                                    (string)$tanah['id']
                                )
                                    ? 'selected'
                                    : ''
                                ?>>

                                <?= oldValue(
                                    $tanah['nama_lahan']
                                ) ?>

                            </option>

                        <?php endforeach; ?>

                    </select>

                    <div
                        class="invalid-feedback">

                        Tanah wajib dipilih.

                    </div>

                </div>

            </div>


            <!-- LATITUDE -->

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
                        value="<?= oldValue(
                            $bankBenih['titik_koleksi_lat']
                        ) ?>"
                        placeholder="-2.123456789">

                </div>

            </div>


            <!-- LONGITUDE -->

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
                        value="<?= oldValue(
                            $bankBenih['titik_koleksi_lng']
                        ) ?>"
                        placeholder="113.123456789">

                </div>

            </div>


        </div>

    </div>

</div>



<!-- ===================================================== -->
<!-- FOTO -->
<!-- ===================================================== -->

<div class="card card-secondary">

    <div class="card-header">

        <h3 class="card-title">

            <i class="fas fa-image"></i>

            Foto Benih

        </h3>

    </div>


    <div class="card-body">


        <?php if (
            !empty($bankBenih['foto_benih'])
        ): ?>

            <div class="mb-3">

                <p class="mb-2">

                    Foto Saat Ini:

                </p>

                <img
                    src="../../uploads/bank_benih/<?= oldValue(
                        $bankBenih['foto_benih']
                    ) ?>"
                    alt="Foto Benih"
                    class="current-photo">

            </div>

        <?php else: ?>

            <div class="alert alert-secondary">

                <i class="fas fa-image"></i>

                Belum ada foto benih.

            </div>

        <?php endif; ?>


        <div class="form-group">

            <label>
                Ganti Foto
            </label>

            <input
                type="file"
                name="foto_benih"
                id="foto_benih"
                class="form-control"
                accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp">

            <small class="form-text-muted">

                JPG, JPEG, PNG atau WEBP.
                Maksimal 2 MB.

            </small>

        </div>


        <div
            id="previewFoto"
            class="mt-3"
            style="display:none;">

            <p class="mb-2">
                Preview Foto Baru:
            </p>

            <img
                id="previewImage"
                class="current-photo"
                alt="Preview">

        </div>


    </div>

</div>



<!-- ===================================================== -->
<!-- CATATAN -->
<!-- ===================================================== -->

<div class="card card-default">

    <div class="card-header">

        <h3 class="card-title">

            <i class="fas fa-sticky-note"></i>

            Catatan

        </h3>

    </div>


    <div class="card-body">

        <div class="form-group mb-0">

            <textarea
                name="catatan"
                class="form-control"
                rows="5"
                maxlength="2000"
                placeholder="Masukkan catatan..."><?= oldValue(
                    $bankBenih['catatan']
                ) ?></textarea>

        </div>

    </div>

</div>



<!-- ===================================================== -->
<!-- STATUS -->
<!-- ===================================================== -->

<div class="card">

    <div class="card-header">

        <h3 class="card-title">

            <i class="fas fa-toggle-on"></i>

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
                <?= (
                    (int)$bankBenih['is_active'] === 1
                )
                    ? 'checked'
                    : ''
                ?>>

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
                class="custom-control-input"
                <?= (
                    (int)$bankBenih['is_active'] === 0
                )
                    ? 'checked'
                    : ''
                ?>>

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
        href="detail.php?id=<?= (int)$bankBenih['id'] ?>"
        class="btn btn-secondary">

        <i class="fas fa-arrow-left"></i>

        Batal

    </a>


    <button
        type="submit"
        id="btnSimpan"
        class="btn btn-primary">

        <i class="fas fa-save"></i>

        Simpan Perubahan

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

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


<script>

$(function () {


    /*
     * ======================================================
     * PREVIEW FOTO
     * ======================================================
     */

    $('#foto_benih').on('change', function () {

        const file = this.files[0];

        if (!file) {

            $('#previewFoto').hide();

            return;

        }


        const allowedTypes = [
            'image/jpeg',
            'image/png',
            'image/webp'
        ];


        if (!allowedTypes.includes(file.type)) {

            Swal.fire({

                icon: 'error',

                title: 'Format tidak didukung',

                text:
                    'Gunakan JPG, JPEG, PNG atau WEBP.'

            });

            this.value = '';

            $('#previewFoto').hide();

            return;

        }


        if (file.size > 2 * 1024 * 1024) {

            Swal.fire({

                icon: 'error',

                title: 'Ukuran terlalu besar',

                text:
                    'Ukuran foto maksimal 2 MB.'

            });

            this.value = '';

            $('#previewFoto').hide();

            return;

        }


        const reader = new FileReader();

        reader.onload = function (e) {

            $('#previewImage')
                .attr('src', e.target.result);

            $('#previewFoto').show();

        };

        reader.readAsDataURL(file);

    });



    /*
     * ======================================================
     * VALIDASI FORM
     * ======================================================
     */

    $('#formEditBenih').on(
        'submit',
        function (e) {

            e.preventDefault();

            const form = this;


            if (!form.checkValidity()) {

                e.stopPropagation();

                $(form).addClass('was-validated');

                Swal.fire({

                    icon: 'warning',

                    title: 'Data belum lengkap',

                    text:
                        'Silakan periksa kembali field yang wajib diisi.'

                });

                return;

            }


            const tanggalMasuk =
                $('#tanggal_masuk').val();

            const masaBerlaku =
                $('#masa_berlaku_sampai').val();


            /*
             * Masa berlaku tidak boleh
             * sebelum tanggal masuk
             */

            if (
                masaBerlaku !== '' &&
                tanggalMasuk !== '' &&
                masaBerlaku < tanggalMasuk
            ) {

                Swal.fire({

                    icon: 'warning',

                    title: 'Tanggal tidak valid',

                    text:
                        'Masa berlaku tidak boleh sebelum tanggal masuk.'

                });

                return;

            }


            /*
             * KONFIRMASI
             */

            Swal.fire({

                title: 'Simpan perubahan?',

                text:
                    'Perubahan data Bank Benih akan disimpan.',

                icon: 'question',

                showCancelButton: true,

                confirmButtonText:
                    'Ya, simpan',

                cancelButtonText:
                    'Batal'

            }).then(function (result) {

                if (!result.isConfirmed) {
                    return;
                }


                $('#btnSimpan')
                    .prop('disabled', true)
                    .html(
                        '<i class="fas fa-spinner fa-spin"></i> Menyimpan...'
                    );


                form.submit();

            });

        }
    );

});

</script>

<script>

$(function () {

    const params =
        new URLSearchParams(
            window.location.search
        );

    const error =
        params.get('error');


    const messages = {

        upload_failed:
            'Foto gagal diupload.',

        file_too_large:
            'Ukuran foto maksimal 2 MB.',

        invalid_file:
            'Format file tidak didukung. Gunakan JPG, JPEG, PNG atau WEBP.',

        invalid_image:
            'File yang dipilih bukan gambar yang valid.',

        upload_folder:
            'Folder upload foto tidak dapat dibuat.',

        update_failed:
            'Data Bank Benih gagal diperbarui.'

    };


    if (
        error &&
        messages[error]
    ) {

        Swal.fire({

            icon: 'error',

            title: 'Gagal',

            text: messages[error],

            toast: true,

            position: 'top-end',

            showConfirmButton: false,

            timer: 4000,

            timerProgressBar: true

        });


        window.history.replaceState(
            {},
            document.title,
            window.location.pathname
            + '?id='
            + <?= (int)$id ?>
        );

    }

});

</script>

</body>

</html>