<?php

require_once '../../app/config/database.php';
require_once '../../app/controllers/KelompokTaniController.php';
require_once '../../app/controllers/PetaniKelompokController.php';

require_once '../../app/core/session.php';
require_once '../../app/core/csrf.php';
require_once "../../app/core/auth.php";
require_once '../../app/helpers/escape.php';
require_once "../../app/core/permission.php";

error_reporting(E_ALL);
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');

secureSessionStart();
checkAuth("non_dashboard");

Permission::authorize(
    $pdo,
    'Detail Kelompok CBO',
    'view'
);

$id_kelompok_tani = (int) ($_GET['id_kelompok_tani'] ?? 0);

if ($id_kelompok_tani <= 0) {
    die('ID Kelompok Tani tidak valid.');
}

$kelompokTaniController =
    new KelompokTaniController($pdo);

$detailController =
    new PetaniKelompokController($pdo);

/*
|--------------------------------------------------------------------------
| Ambil data kelompok_tani
|--------------------------------------------------------------------------
*/

$kelompok_tani =
    $kelompokTaniController->find($id_kelompok_tani);

if (!$kelompok_tani) {
    die('Data Kelompok Tani tidak ditemukan.');
}

/*
|--------------------------------------------------------------------------
| Ambil detail berdasarkan kelompok_tani
|--------------------------------------------------------------------------
*/

$details =
    $detailController->getByKelompokTani(
        $id_kelompok_tani
    );

/*
|--------------------------------------------------------------------------
| Data CBO dan Jabatan Kelompok
|--------------------------------------------------------------------------
*/

$cbos =
    $detailController->getPetani();

$jabatanKelompoks =
    $detailController->getJabatanKelompok();

/*
|--------------------------------------------------------------------------
| Hitung summary
|--------------------------------------------------------------------------
*/

$totalCBO = count($details);

$totalLakiLaki = 0;
$totalPerempuan = 0;

foreach ($details as $detail) {

    $totalLakiLaki +=
        (float) ($detail['jenis_kelamin'] === 'L' ? 1 : 0);

    $totalPerempuan +=
        (float) ($detail['jenis_kelamin'] === 'P' ? 1 : 0);
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
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css"
        rel="stylesheet" />
</head>

<body class="hold-transition sidebar-mini">
    <div class="wrapper">
        <?php include "../feature/navbar.php" ?>
        <?php
        $menu = "kelompok_tani";
        include "../feature/sidebar.php";
        ?>

        <div class="content-wrapper">
            <!-- =========================================================
             HEADER
            ========================================================== -->
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1>Detail Kelompok CBO</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item">
                                    <a href="../index.php">
                                        Kelompok CBO
                                    </a>
                                </li>
                                <li class="breadcrumb-item active">Detail</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </section>

            <section class="content">
                <div class="container-fluid">
                    <!-- =====================================================
                     INFORMASI Kelompok CBO
                    ====================================================== -->
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-info-circle mr-1"></i>
                                Informasi Kelompok CBO
                            </h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <!-- KODE -->
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>
                                            Kode Kelompok CBO
                                        </label>
                                        <div>
                                            <span class="badge badge-primary" style="font-size: 15px;">
                                                <?= htmlspecialchars(
                                                    $kelompok_tani['kode_kelompok'] ?? '-',
                                                    ENT_QUOTES,
                                                    'UTF-8'
                                                ) ?>
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <!-- NAMA KELOMPOK -->
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>
                                            Nama Kelompok
                                        </label>
                                        <div>
                                            <?= htmlspecialchars(
                                                $kelompok_tani['nama_kelompok'] ?? '-',
                                                ENT_QUOTES,
                                                'UTF-8'
                                            ) ?>
                                        </div>
                                    </div>
                                </div>

                                <!-- KATEGORI KELOMPOK -->
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>
                                            Kategori Kelompok
                                        </label>
                                        <div>
                                            <?= htmlspecialchars(
                                                $kelompok_tani['nama_kategori_kelompok'] ?? '-',
                                                ENT_QUOTES,
                                                'UTF-8'
                                            ) ?>
                                        </div>
                                    </div>
                                </div>

                                <!-- DESA -->
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>
                                            Desa
                                        </label>
                                        <div>
                                            <?= htmlspecialchars(
                                                $kelompok_tani['nama_desa'] ?? '-',
                                                ENT_QUOTES,
                                                'UTF-8'
                                            ) ?>
                                        </div>
                                    </div>
                                </div>

                                <!-- AKSES PERJALANAN -->
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>
                                            Akses Perjalanan
                                        </label>
                                        <div>
                                            <?= htmlspecialchars(
                                                $kelompok_tani['nama_akses_perjalanan'] ?? '-',
                                                ENT_QUOTES,
                                                'UTF-8'
                                            ) ?>
                                        </div>
                                    </div>
                                </div>

                                <!-- KONDISI JALAN -->
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>
                                            Kondisi Jalan
                                        </label>
                                        <div>
                                            <?= htmlspecialchars(
                                                $kelompok_tani['nama_kondisi_jalan'] ?? '-',
                                                ENT_QUOTES,
                                                'UTF-8'
                                            ) ?>
                                        </div>
                                    </div>
                                </div>

                                <!-- TAHUN BENTUK -->
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>
                                            Tahun Bentuk
                                        </label>
                                        <div>
                                            <?= htmlspecialchars(
                                                $kelompok_tani['tahun_bentuk'] ?? '-',
                                                ENT_QUOTES,
                                                'UTF-8'
                                            ) ?>
                                        </div>
                                    </div>
                                </div>

                                <!-- NOMOR SK -->
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>
                                            Nomor SK
                                        </label>
                                        <div>
                                            <?= htmlspecialchars(
                                                $kelompok_tani['nomor_sk'] ?? '-',
                                                ENT_QUOTES,
                                                'UTF-8'
                                            ) ?>
                                        </div>
                                    </div>
                                </div>

                                <!-- TANGGAL SK -->
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>
                                            Tanggal SK
                                        </label>
                                        <div>
                                            <?= !empty(
                                                $kelompok_tani['tanggal_sk']
                                            )
                                                ? date(
                                                    'd/m/Y',
                                                    strtotime(
                                                        $kelompok_tani['tanggal_sk']
                                                    )
                                                )
                                                : '-'
                                                ?>
                                        </div>
                                    </div>
                                </div>

                                <!-- STATUS KELOMPOK -->
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>
                                            Status Kelompok
                                        </label>
                                        <div>
                                            <?php
                                            $statusKelompok = $kelompok_tani['status_kelompok'] ?? '';
                                            $badgeStatusKelompok = ($statusKelompok === 'nonaktif')
                                                ? 'badge-danger'
                                                : 'badge-success';
                                            ?>
                                            <span
                                                class="badge <?= htmlspecialchars($badgeStatusKelompok, ENT_QUOTES, 'UTF-8') ?>">
                                                <?= htmlspecialchars(
                                                    $kelompok_tani['status_kelompok'] == 'aktif' ? 'Aktif' : 'Nonaktif',
                                                    ENT_QUOTES,
                                                    'UTF-8'
                                                ) ?>
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <!-- ALAMAT -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>
                                            Alamat
                                        </label>
                                        <div>
                                            <?= !empty(
                                                $kelompok_tani['alamat']
                                            )
                                                ? nl2br(
                                                    htmlspecialchars(
                                                        $kelompok_tani['alamat'],
                                                        ENT_QUOTES,
                                                        'UTF-8'
                                                    )
                                                )
                                                : '-'
                                                ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- =====================================================
                     SUMMARY
                    ====================================================== -->
                    <div class="row">
                        <!-- JUMLAH CBO -->
                        <div class="col-lg-4 col-md-4 col-sm-6">
                            <div class="small-box bg-primary">
                                <div class="inner">
                                    <h3>
                                        <?= $totalCBO ?>
                                    </h3>
                                    <p>
                                        Jumlah CBO
                                    </p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-list"></i>
                                </div>
                            </div>
                        </div>

                        <!-- JUMLAH CBO LAKI-LAKI -->
                        <div class="col-lg-4 col-md-4 col-sm-6">
                            <div class="small-box bg-success">
                                <div class="inner">
                                    <h3>
                                        <?= $totalLakiLaki ?>
                                    </h3>
                                    <p>
                                        Jumlah CBO Laki-Laki
                                    </p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-list"></i>
                                </div>
                            </div>
                        </div>

                        <!-- JUMLAH CBO PEREMPUAN -->
                        <div class="col-lg-4 col-md-4 col-sm-6">
                            <div class="small-box bg-info">
                                <div class="inner">
                                    <h3>
                                        <?= $totalPerempuan ?>
                                    </h3>
                                    <p>
                                        Jumlah CBO Perempuan
                                    </p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-list"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- =====================================================
                     TABLE DETAIL
                    ====================================================== -->
                    <div class="card">
                        <div class="card-header">
                            <div class="row align-items-center">
                                <div class="col-md-8">
                                    <h3 class="card-title">
                                        <i class="fas fa-users mr-1"></i>
                                        List Detail CBO
                                    </h3>
                                </div>

                                <div class="col-md-4 text-right">
                                    <?php
                                    if (
                                        Permission::can(
                                            $pdo,
                                            'Detail Kelompok CBO',
                                            'create'
                                        )
                                    ):
                                        ?>
                                        <button type="button" class="btn btn-success" data-toggle="modal"
                                            data-target="#modalTambah">
                                            <i class="fas fa-plus"></i>
                                            Tambah CBO
                                        </button>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>

                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="tableDetail" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th class="text-center">No</th>
                                            <th class="text-center">CBO</th>
                                            <th class="text-center">Jenis Kelamin</th>
                                            <th class="text-center">Jabatan Kelompok</th>
                                            <th class="text-center">Tanggal Gabung</th>
                                            <th class="text-center">Desa</th>
                                            <th class="text-center">Status CBO</th>
                                            <th class="text-center">Foto Profil CBO</th>
                                            <th class="text-center">Aksi</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        <?php $no = 1; ?>
                                        <?php foreach (
                                            $details
                                            as $detail
                                        ): ?>
                                            <tr>
                                                <td class="text-center">
                                                    <?= $no++ ?>
                                                </td>
                                                <td>
                                                    <strong>
                                                        <?= htmlspecialchars(
                                                            $detail['nama_lengkap'] ?? '-',
                                                            ENT_QUOTES,
                                                            'UTF-8'
                                                        ) ?>
                                                    </strong>
                                                    <br>
                                                    <small class="text-muted">
                                                        Panggilan:
                                                        <?= htmlspecialchars(
                                                            $detail['nama_panggilan'] ?? '-',
                                                            ENT_QUOTES,
                                                            'UTF-8'
                                                        ) ?>
                                                        <br>
                                                        Panggilan:
                                                        <?= htmlspecialchars(
                                                            $detail['nik'] ?? '-',
                                                            ENT_QUOTES,
                                                            'UTF-8'
                                                        ) ?>
                                                        <br>
                                                        Panggilan:
                                                        <?= htmlspecialchars(
                                                            $detail['no_kk'] ?? '-',
                                                            ENT_QUOTES,
                                                            'UTF-8'
                                                        ) ?>
                                                        <br>
                                                    </small>
                                                </td>
                                                <td>
                                                    <?php
                                                    $jenisKelamin1 = $detail['jenis_kelamin'] ?? '';
                                                    $badgeJenisKelamin = ($jenisKelamin1 === 'L')
                                                        ? 'badge-success'
                                                        : 'badge-info';
                                                    ?>
                                                    <span
                                                        class="badge <?= htmlspecialchars($badgeJenisKelamin, ENT_QUOTES, 'UTF-8') ?>">
                                                        <?= htmlspecialchars(
                                                            $detail['jenis_kelamin'] == 'L' ? 'Laki-laki' : 'Perempuan',
                                                            ENT_QUOTES,
                                                            'UTF-8'
                                                        ) ?>
                                                    </span>
                                                </td>
                                                <td class="text-left">
                                                    <?= htmlspecialchars(
                                                        $detail['nama_jabatan_kelompok'] ?? '-',
                                                        ENT_QUOTES,
                                                        'UTF-8'
                                                    ) ?>
                                                </td>
                                                <td class="text-center">
                                                    <?=
                                                        date(
                                                            'd/m/Y',
                                                            strtotime(
                                                                htmlspecialchars(
                                                                    $detail['tanggal_gabung'] ?? '-',
                                                                    ENT_QUOTES,
                                                                    'UTF-8'
                                                                )
                                                            )
                                                        )
                                                        ?>
                                                </td>
                                                <td class="text-left">
                                                    <?= htmlspecialchars(
                                                        $detail['nama_desa'] ?? '-',
                                                        ENT_QUOTES,
                                                        'UTF-8'
                                                    ) ?>
                                                </td>
                                                <td class="text-center">
                                                    <?php
                                                    $statusDetail = $detail['status_petani'] ?? '';
                                                    if ($statusDetail === 'aktif') {
                                                        $badgeStatusDetail = 'badge-success';
                                                    } elseif ($statusDetail === 'nonaktif') {
                                                        $badgeStatusDetail = 'badge-danger';
                                                    } else {
                                                        $badgeStatusDetail = 'badge-secondary';
                                                    }
                                                    ?>
                                                    <span
                                                        class="badge <?= htmlspecialchars($badgeStatusDetail, ENT_QUOTES, 'UTF-8') ?>">
                                                        <?= htmlspecialchars($statusDetail, ENT_QUOTES, 'UTF-8') ?>
                                                    </span>
                                                    <?= htmlspecialchars(
                                                        $detail['status_petani'] == 'aktif' ? 'Aktif' : 'Nonaktif',
                                                        ENT_QUOTES,
                                                        'UTF-8'
                                                    ) ?>
                                                    </span>
                                                </td>
                                                <td>
                                                    <img class="img-circle elevation-2" src="<?= !empty($detail['foto_profil_petani'])
                                                        ? '../../uploads/petani/' . htmlspecialchars($detail['foto_profil_petani'] ?? '')
                                                        : '../../../assets/image/petani_placeholder.jpg' ?>"
                                                        width="80">
                                                </td>
                                                <td class="text-center">
                                                    <div class="btn-group">
                                                        <?php
                                                        if (
                                                            Permission::can(
                                                                $pdo,
                                                                'Detail Kelompok CBO',
                                                                'update'
                                                            )
                                                        ):
                                                            ?>
                                                            <button type="button" class="btn btn-info btn-edit"
                                                                data-id="<?= (int) $detail['id'] ?>"
                                                                data-id-petani="<?= (int) $detail['id_petani'] ?>"
                                                                data-nik="<?= htmlspecialchars($detail['nik'] ?? '') ?>"
                                                                data-no-kk="<?= htmlspecialchars($detail['no_kk'] ?? '') ?>"
                                                                data-nama-lengkap="<?= htmlspecialchars($detail['nama_lengkap'] ?? '') ?>"
                                                                data-jenis-kelamin="<?= htmlspecialchars($detail['jenis_kelamin'] == 'L' ? 'Laki-Laki' : 'Perempuan' ?? '-') ?>"
                                                                data-tanggal-lahir="<?= htmlspecialchars($detail['tanggal_lahir'] ?? '') ?>"
                                                                data-nomor-hp="<?= htmlspecialchars($detail['nomor_hp'] ?? '') ?>"
                                                                data-nama-desa="<?= htmlspecialchars($detail['nama_desa'] ?? '') ?>"
                                                                data-foto-profil-petani="<?= htmlspecialchars($detail['foto_profil_petani'] ?? '') ?>"
                                                                data-status-petani="<?= htmlspecialchars($detail['status_petani'] == 'aktif' ? 'Aktif' : 'Nonaktif' ?? '-') ?>"
                                                                data-id-jabatan="<?= (int) $detail['id_jabatan_kelompok'] ?>"
                                                                data-tanggal-gabung="<?= htmlspecialchars($detail['tanggal_gabung']) ?>"
                                                                data-tanggal-keluar="<?= htmlspecialchars($detail['tanggal_keluar']) ?>"
                                                                data-keterangan="<?= htmlspecialchars($detail['keterangan']) ?>"
                                                                data-status="<?= $detail['is_active'] ?>" data-toggle="modal"
                                                                data-target="#modalEdit" title="Edit">
                                                                <i class="fas fa-edit"></i>
                                                            </button>
                                                        <?php endif; ?>
                                                        <?php
                                                        if (
                                                            Permission::can(
                                                                $pdo,
                                                                'Detail Kelompok CBO',
                                                                'delete'
                                                            )
                                                        ):
                                                            ?>
                                                            <form method="POST" action="detail_delete.php" class="form-delete"
                                                                style="display:inline;">
                                                                <?= csrfField() ?>
                                                                <input type="hidden" name="id"
                                                                    value="<?= (int) $detail['id'] ?>">
                                                                <button type="submit" class="btn btn-danger" title="Hapus">
                                                                    <i class="fas fa-trash"></i>
                                                                </button>
                                                            </form>
                                                        <?php endif; ?>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th class="text-center">No</th>
                                            <th class="text-center">CBO</th>
                                            <th class="text-center">Jenis Kelamin</th>
                                            <th class="text-center">Jabatan Kelompok</th>
                                            <th class="text-center">Tanggal Gabung</th>
                                            <th class="text-center">Desa</th>
                                            <th class="text-center">Status CBO</th>
                                            <th class="text-center">Foto Profil CBO</th>
                                            <th class="text-center">Aksi</th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                        <div class="card-footer">
                            <a href="../index.php" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i>
                                Kembali
                            </a>
                        </div>
                    </div>
                </div>
            </section>

            <div class="modal fade" id="modalTambah">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">
                                Tambah Detail Kelompok CBO
                            </h4>
                            <button type="button" class="close" data-dismiss="modal">
                                &times;
                            </button>
                        </div>
                        <form method="POST" action="detail_store.php" id="formTambah">
                            <?= csrfField() ?>
                            <input type="hidden" name="id_kelompok_tani" value="<?= $id_kelompok_tani ?>">
                            <div class="modal-body">
                                <div class="form-group">
                                    <label>
                                        Kelompok CBO
                                    </label>
                                    <input type="text" class="form-control" value="<?= htmlspecialchars(
                                        $kelompok_tani['nama_kelompok']
                                    ) ?>" readonly>
                                </div>
                                <div class="form-group">
                                    <label for="id_petani">CBO<code>*</code></label>
                                    <select name="id_petani" id="add_id_petani" class="form-control">
                                        <option value="">-- Pilih CBO --</option>
                                        <?php foreach ($cbos as $cbo): ?>
                                            <option value="<?= (int) $cbo['id'] ?>" data-nik="<?= htmlspecialchars(
                                                   $cbo['nik'] ?? '-',
                                                   ENT_QUOTES,
                                                   'UTF-8'
                                               ) ?>" data-no-kk="<?= htmlspecialchars(
                                                    $cbo['no_kk'] ?? '-',
                                                    ENT_QUOTES,
                                                    'UTF-8'
                                                ) ?>" data-jenis-kelamin="<?= htmlspecialchars(
                                                     $cbo['jenis_kelamin'] == 'L' ? 'Laki-Laki' : 'Perempuan' ?? '-',
                                                     ENT_QUOTES,
                                                     'UTF-8'
                                                 ) ?>" data-tanggal-lahir="<?= htmlspecialchars(
                                                      $cbo['tanggal_lahir'] ?? '-',
                                                      ENT_QUOTES,
                                                      'UTF-8'
                                                  ) ?>" data-nomor-hp="<?= htmlspecialchars(
                                                       $cbo['nomor_hp'] ?? '-',
                                                       ENT_QUOTES,
                                                       'UTF-8'
                                                   ) ?>" data-nama-desa="<?= htmlspecialchars(
                                                        $cbo['nama_desa'] ?? '-',
                                                        ENT_QUOTES,
                                                        'UTF-8'
                                                    ) ?>" data-status-petani="<?= htmlspecialchars(
                                                         $cbo['status_petani'] == 'aktif' ? 'Aktif' : 'Nonaktif' ?? '-',
                                                         ENT_QUOTES,
                                                         'UTF-8'
                                                     ) ?>" data-foto-profil-petani="<?= htmlspecialchars(
                                                          $cbo['foto_profil_petani'] ?? '-',
                                                          ENT_QUOTES,
                                                          'UTF-8'
                                                      ) ?>">
                                                Nama lengkap : <?= htmlspecialchars($cbo['nama_lengkap']) ?>
                                                -
                                                Panggilan : <?= htmlspecialchars($cbo['nama_panggilan']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>
                                                NIK
                                            </label>
                                            <input type="text" name="nik" id="add_nik" class="form-control" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>
                                                No KK
                                            </label>
                                            <input type="text" name="no_kk" id="add_no_kk" class="form-control"
                                                readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>
                                                Jenis Kelamin
                                            </label>
                                            <input type="text" name="jenis_kelamin" id="add_jenis_kelamin"
                                                class="form-control" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>
                                                Tanggal Lahir
                                            </label>
                                            <input type="text" name="tanggal_lahir" id="add_tanggal_lahir"
                                                class="form-control" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>
                                                Nomor HP
                                            </label>
                                            <input type="text" name="nomor_hp" id="add_nomor_hp" class="form-control"
                                                readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>
                                                Desa
                                            </label>
                                            <input type="text" name="nama_desa" id="add_nama_desa" class="form-control"
                                                readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>
                                                Status CBO
                                            </label>
                                            <input type="text" name="status_petani" id="add_status_petani"
                                                class="form-control" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>
                                                Foto Profil CBO
                                            </label>
                                            <div>
                                                <img id="add_preview_foto_profil_petani"
                                                    src="../../../assets/image/petani_placeholder.jpg"
                                                    alt="Foto Profil CBO" class="img-circle elevation-2" width="80"
                                                    height="80" style="object-fit: cover;">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>Jabatan Kelompok<code>*</code></label>
                                    <select name="id_jabatan_kelompok" class="form-control"
                                        id="add_id_jabatan_kelompok">
                                        <option value="">-- Pilih Jabatan Kelompok --</option>
                                        <?php foreach ($jabatanKelompoks as $jk): ?>
                                            <option value="<?= $jk['id'] ?>">
                                                <?= htmlspecialchars($jk['nama']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="row">
                                    <div class="col-lg-6 col-6">
                                        <div class="form-group">
                                            <label for="tanggal_gabung">Tanggal Gabung<code>*</code></label>
                                            <input type="date" name="tanggal_gabung" class="form-control"
                                                id="tanggal_gabung" placeholder="Pilih Tanggal Gabung">
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-6">
                                        <div class="form-group">
                                            <label for="tanggal_keluar">Tanggal Keluar</label>
                                            <input type="date" name="tanggal_keluar" class="form-control"
                                                id="tanggal_keluar" placeholder="Pilih Tanggal Keluar">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="keterangan">Keterangan</label>
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
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                                    Batal
                                </button>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i>
                                    Simpan
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- =============================================================
             MODAL EDIT
            ============================================================= -->
            <div class="modal fade" id="modalEdit">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">
                                Edit Detail Kelompok CBO
                            </h4>
                            <button type="button" class="close" data-dismiss="modal">
                                &times;
                            </button>
                        </div>
                        <form method="POST" action="detail_update.php" id="formEdit">
                            <?= csrfField() ?>
                            <input type="hidden" name="id" id="edit_id">
                            <input type="hidden" name="id_kelompok_tani" value="<?= $id_kelompok_tani ?>">
                            <div class="modal-body">
                                <!-- MONITORING -->
                                <div class="form-group">
                                    <label>
                                        Kelompok CBO
                                    </label>
                                    <input type="text" class="form-control" value="<?= htmlspecialchars(
                                        $kelompok_tani['kode_kelompok']
                                        . ' - '
                                        . $kelompok_tani['nama_kelompok']
                                    ) ?>" readonly>
                                </div>
                                <!-- CBO -->
                                <div class="form-group">
                                    <label for="edit_id_petani">CBO<code>*</code></label>
                                    <select name="id_petani" class="form-control" id="edit_id_petani">
                                        <option value="">-- Pilih CBO --</option>
                                        <?php foreach ($cbos as $cbo): ?>
                                            <option value="<?= (int) $cbo['id'] ?>"
                                                data-nik="<?= htmlspecialchars($cbo['nik'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                                                data-no-kk="<?= htmlspecialchars($cbo['no_kk'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                                                data-jenis-kelamin="<?= htmlspecialchars(
                                                    ($cbo['jenis_kelamin'] ?? '') === 'L' ? 'Laki-Laki' :
                                                    (($cbo['jenis_kelamin'] ?? '') === 'P' ? 'Perempuan' : ''),
                                                    ENT_QUOTES,
                                                    'UTF-8'
                                                ) ?>"
                                                data-tanggal-lahir="<?= htmlspecialchars($cbo['tanggal_lahir'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                                                data-nomor-hp="<?= htmlspecialchars($cbo['nomor_hp'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                                                data-nama-desa="<?= htmlspecialchars($cbo['nama_desa'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                                                data-status-petani="<?= htmlspecialchars(
                                                    ($cbo['status_petani'] ?? '') === 'aktif' ? 'Aktif' : 'Nonaktif',
                                                    ENT_QUOTES,
                                                    'UTF-8'
                                                ) ?>"
                                                data-foto-profil-petani="<?= htmlspecialchars($cbo['foto_profil_petani'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
                                                Nama lengkap:
                                                <?= htmlspecialchars($cbo['nama_lengkap'] ?? '', ENT_QUOTES, 'UTF-8') ?>
                                                - Panggilan:
                                                <?= htmlspecialchars($cbo['nama_panggilan'] ?? '', ENT_QUOTES, 'UTF-8') ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>
                                                NIK
                                            </label>
                                            <input type="text" name="nik" id="edit_nik" class="form-control" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>
                                                No KK
                                            </label>
                                            <input type="text" name="no_kk" id="edit_no_kk" class="form-control"
                                                readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>
                                                Jenis Kelamin
                                            </label>
                                            <input type="text" name="jenis_kelamin" id="edit_jenis_kelamin"
                                                class="form-control" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>
                                                Tanggal Lahir<code>*</code>
                                            </label>
                                            <input type="text" name="tanggal_lahir" id="edit_tanggal_lahir"
                                                class="form-control" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>
                                                Nomor HP
                                            </label>
                                            <input type="text" name="nomor_hp" id="edit_nomor_hp" class="form-control"
                                                readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>
                                                Desa
                                            </label>
                                            <input type="text" name="nama_desa" id="edit_nama_desa" class="form-control"
                                                readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>
                                                Status CBO
                                            </label>
                                            <input type="text" name="status_petani" id="edit_status_petani"
                                                class="form-control" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>
                                                Foto Profil CBO
                                            </label>
                                            <div>
                                                <img id="edit_preview_foto_profil_petani"
                                                    src="../../../assets/image/petani_placeholder.jpg"
                                                    alt="Foto Profil CBO" class="img-circle elevation-2" width="80"
                                                    height="80" style="object-fit: cover;">
                                            </div>
                                        </div>
                                    </div>
                                    <!-- <div class="col-md-6">
                                        <div class="form-group">
                                            <label>
                                                Foto Profil CBO
                                            </label>
                                            <input type="text" name="foto_profil_petani" id="edit_foto_profil_petani"
                                                class="form-control" readonly>
                                        </div>
                                    </div> -->
                                </div>
                                <div class="form-group">
                                    <label for="edit_id_jabatan_kelompok">Jabatan Kelompok<code>*</code></label>
                                    <select name="id_jabatan_kelompok" id="edit_id_jabatan_kelompok"
                                        class="form-control">
                                        <option value="">-- Pilih Jabatan Kelompok --</option>
                                        <?php foreach ($jabatanKelompoks as $jk): ?>
                                            <option value="<?= $jk['id'] ?>">
                                                <?= htmlspecialchars($jk['nama']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="row">
                                    <div class="col-lg-6 col-6">
                                        <div class="form-group">
                                            <label for="tanggal_gabung">Tanggal Gabung<code>*</code></label>
                                            <input type="date" name="tanggal_gabung" class="form-control"
                                                id="edit_tanggal_gabung" placeholder="Pilih Tanggal Gabung">
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-6">
                                        <div class="form-group">
                                            <label for="tanggal_keluar">Tanggal Keluar</label>
                                            <input type="date" name="tanggal_keluar" class="form-control"
                                                id="edit_tanggal_keluar" placeholder="Pilih Tanggal Keluar">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="keterangan">Keterangan</label>
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
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                                    Batal
                                </button>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i>
                                    Update
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <?php include "../feature/footer.php" ?>

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
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

        <script>
            $(function () {
                $("#tableDetail").DataTable({
                    responsive: true,
                    lengthChange: false,
                    autoWidth: false,
                    ordering: true,
                    buttons: ["copy", "csv", "excel", "pdf", "print", "colvis"]
                }).buttons()
                    .container()
                    .appendTo(
                        '#tableDetail_wrapper .col-md-6:eq(0)'
                    );
            });
        </script>

        <!-- =============================================================
         EDIT
        ============================================================= -->
        <script>
            $(document).on("click", ".btn-edit", function () {
                let button = $(this);
                let id = button.data("id");
                let idCBO = button.data("id-petani");
                let nama_lengkap = button.data("nama-lengkap");
                let nik = button.data("nik");
                let no_kk = button.data("no-kk");
                let jenis_kelamin = button.data("jenis-kelamin");
                let tanggal_lahir = button.data("tanggal-lahir");
                let nomor_hp = button.data("nomor-hp");
                let nama_desa = button.data("nama-desa");
                let foto_profil_petani = button.data("foto-profil-petani");
                let status_petani = button.data("status-petani");
                let id_jabatan_kelompok = button.data("id-jabatan");
                let tanggal_gabung = $(this).data("tanggal-gabung");
                let tanggal_keluar = $(this).data("tanggal-keluar");
                let keterangan = $(this).data("keterangan");
                let status = $(this).data("status");

                $("#edit_id")
                    .val(id);
                // Set Select2
                $("#edit_id_petani")
                    .val(String(idCBO))
                    .trigger("change");
                $("#edit_id_jabatan_kelompok")
                    .val(String(id_jabatan_kelompok))
                    .trigger("change");
                $("#edit_tanggal_gabung").val(tanggal_gabung);
                $("#edit_tanggal_keluar").val(tanggal_keluar);
                $("#edit_keterangan").val(keterangan);
                $("input[name='is_active'][value='" + status + "']").prop("checked", true);
                let cboText = button.closest("tr").find("td:eq(1)").text().trim();
                $("#edit_nik")
                    .val(nik);
                $("#edit_no_kk")
                    .val(no_kk);
                $("#edit_jenis_kelamin")
                    .val(jenis_kelamin);
                $("#edit_tanggal_lahir")
                    .val(tanggal_lahir);
                $("#edit_nomor_hp")
                    .val(nomor_hp);
                $("#edit_nama_desa")
                    .val(nama_desa);
                $("#edit_foto_profil_petani")
                    .val(foto_profil_petani);
                $("#edit_status_petani")
                    .val(status_petani);
                $("#edit_petani")
                    .val(nama_lengkap + " - " + nama_panggilan);
            }
            );

            $(document).ready(function () {
                $('#add_id_petani').select2({
                    theme: 'bootstrap-5',
                    width: '100%',
                    placeholder: '-- Pilih CBO --',
                    allowClear: true,
                    dropdownParent: $('#modalTambah')
                });

                $('#edit_id_petani').select2({
                    theme: 'bootstrap-5',
                    width: '100%',
                    placeholder: '-- Pilih CBO --',
                    allowClear: true,
                    dropdownParent: $('#modalEdit') // jika dalam modal
                });

                $('#add_id_jabatan_kelompok').select2({
                    theme: 'bootstrap-5',
                    width: '100%',
                    placeholder: '-- Pilih Jabatan Kelompok --',
                    allowClear: true,
                    dropdownParent: $('#modalTambah')
                });

                $('#edit_id_jabatan_kelompok').select2({
                    theme: 'bootstrap-5',
                    width: '100%',
                    placeholder: '-- Pilih Jabatan Kelompok --',
                    allowClear: true,
                    dropdownParent: $('#modalEdit') // jika dalam modal
                });
            });

            $('#add_id_petani, #edit_id_petani, #add_id_jabatan_kelompok, #edit_id_jabatan_kelompok').on('select2:open', function () {
                setTimeout(() => {
                    document.querySelector('.select2-container--open .select2-search__field').focus();
                }, 0);
            });
        </script>

        <!-- =============================================================
         DELETE
        ============================================================= -->
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            $(document).on(
                "submit",
                ".form-delete",
                function (e) {
                    e.preventDefault();
                    let form = this;
                    Swal.fire({
                        title: 'Apakah anda yakin?',
                        text:
                            "Detail Kelompok CBO akan dihapus.",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#6c757d',
                        confirmButtonText:
                            'Ya, hapus!',
                        cancelButtonText:
                            'Batal'
                    }).then(
                        function (result) {
                            if (result.isConfirmed) {
                                form.submit();
                            }
                        }
                    );
                }
            );
        </script>

        <!-- =============================================================
         VALIDATION
        ============================================================= -->
        <script>
            $(function () {
                $("#formTambah").validate({
                    rules: {
                        id_petani: {
                            required: true
                        },
                        id_jabatan_kelompok: {
                            required: true
                        },
                        tanggal_gabung: {
                            required: true
                        },
                        tanggal_keluar: {
                            required: false
                        },
                        keterangan: {
                            required: false
                        },
                        is_active: {
                            required: true
                        }
                    },

                    messages: {
                        id_petani: {
                            required:
                                "Silahkan pilih CBO"
                        },
                        id_jabatan_kelompok: {
                            required:
                                "Silahkan pilih Jabatan Kelompok"
                        },
                        tanggal_gabung: {
                            required: "Silahkan pilih Tanggal Gabung"
                        },
                        // tanggal_keluar: {
                        //     required: "Silahkan pilih Tanggal Keluar"
                        // },
                        // keterangan: {
                        //     required: "Silahkan masukkan Keterangan"
                        // },
                        is_active: {
                            required: "Silahkan pilih Status Aktif"
                        }
                    },
                    errorElement: 'span',
                    errorPlacement:
                        function (error, element) {
                            error.addClass(
                                'invalid-feedback'
                            );
                            element
                                .closest(
                                    '.form-group'
                                )
                                .append(error);
                        },
                    highlight:
                        function (element) { $(element).addClass('is-invalid'); },
                    unhighlight:
                        function (element) { $(element).removeClass('is-invalid'); }
                });

                $("#formEdit").validate({
                    rules: {
                        id_petani: {
                            required: true
                        },
                        id_jabatan_kelompok: {
                            required: true
                        },
                        tanggal_gabung: {
                            required: true
                        },
                        tanggal_keluar: {
                            required: false
                        },
                        keterangan: {
                            required: false
                        },
                        is_active: {
                            required: true
                        }
                    },

                    messages: {
                        id_petani: {
                            required:
                                "Silahkan pilih CBO"
                        },
                        id_jabatan_kelompok: {
                            required:
                                "Silahkan pilih Jabatan Kelompok"
                        },
                        tanggal_gabung: {
                            required: "Silahkan pilih Tanggal Gabung"
                        },
                        // tanggal_keluar: {
                        //     required: "Silahkan pilih Tanggal Keluar"
                        // },
                        // keterangan: {
                        //     required: "Silahkan masukkan Keterangan"
                        // },
                        is_active: {
                            required: "Silahkan pilih Status Aktif"
                        }
                    },
                    errorElement: 'span',
                    errorPlacement:
                        function (error, element) {
                            error.addClass(
                                'invalid-feedback'
                            );
                            element
                                .closest('.form-group').append(error);
                        },
                    highlight:
                        function (element) { $(element).addClass('is-invalid'); },
                    unhighlight:
                        function (element) {
                            $(element)
                                .removeClass(
                                    'is-invalid'
                                );
                        }
                });
            });
        </script>

        <!-- =============================================================
         AUTO NIK DARI CBO
        ============================================================= -->
        <script>

            $(document).ready(function () {
                const placeholderFoto = '../../../assets/image/petani_placeholder.jpg';

                function isiDataCBO(selectId, prefix) {
                    const selected = $(selectId).find(':selected');

                    const nik = selected.attr('data-nik') || '';
                    const noKK = selected.attr('data-no-kk') || '';
                    const jenisKelamin = selected.attr('data-jenis-kelamin') || '';
                    const tanggalLahir = selected.attr('data-tanggal-lahir') || '';
                    const nomorHP = selected.attr('data-nomor-hp') || '';
                    const namaDesa = selected.attr('data-nama-desa') || '';
                    const statusPetani = selected.attr('data-status-petani') || '';
                    const foto = selected.attr('data-foto-profil-petani') || '';

                    // Perbarui seluruh informasi CBO
                    $('#' + prefix + '_nik').val(nik);
                    $('#' + prefix + '_no_kk').val(noKK);
                    $('#' + prefix + '_jenis_kelamin').val(jenisKelamin);
                    $('#' + prefix + '_tanggal_lahir').val(tanggalLahir);
                    $('#' + prefix + '_nomor_hp').val(nomorHP);
                    $('#' + prefix + '_nama_desa').val(namaDesa);
                    $('#' + prefix + '_status_petani').val(statusPetani);

                    // Perbarui foto profil
                    const preview = $('#' + prefix + '_preview_foto_profil_petani');

                    preview.attr('src', foto.trim() !== ''
                        ? '../../uploads/petani/' + foto
                        : placeholderFoto
                    );
                }

                // Modal Tambah
                $('#add_id_petani').on('change', function () {
                    isiDataCBO('#add_id_petani', 'add');
                });

                // Modal Edit
                $('#edit_id_petani').on('change', function () {
                    isiDataCBO('#edit_id_petani', 'edit');
                });

                // Jika foto tidak ditemukan, gunakan placeholder
                $('#add_preview_foto_profil_petani, #edit_preview_foto_profil_petani')
                    .on('error', function () {
                        this.onerror = null;
                        this.src = placeholderFoto;
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
                    title: 'Pesan',
                    body: message,
                    delay: 1000
                });
            }
            const urlParams = new URLSearchParams(window.location.search);
            const success = urlParams.get('success');
            if (success === "created") {
                showToast("Data Detail Kelompok CBO berhasil ditambahkan", "created");
            }
            if (success === "updated") {
                showToast("Data Detail Kelompok CBO berhasil diperbarui", "updated");
            }
            if (success === "deleted") {
                showToast("Data Detail Kelompok CBO berhasil dihapus", "deleted");
            }
            if (success === "validation1") {
                showToast("Monitoring, Jabatan Kelompok, CBO, dan Luas Tanam (Ha) wajib diisi.", "deleted");
            }
            if (success === "validation3") {
                showToast("CBO tersebut sudah memiliki Detail Kelompok CBO pada kelompok_tani ini.", "deleted");
            }
            if (success === "validation4") {
                showToast("Terjadi Error", "deleted");
            }
        </script>

        <script>
            document.getElementById('tanggal_gabung').addEventListener('focus', function () {
                this.showPicker();
            });
            document.getElementById('edit_tanggal_gabung').addEventListener('focus', function () {
                this.showPicker();
            });
            document.getElementById('tanggal_keluar').addEventListener('focus', function () {
                this.showPicker();
            });
            document.getElementById('edit_tanggal_keluar').addEventListener('focus', function () {
                this.showPicker();
            });
        </script>
    </div>
</body>

</html>