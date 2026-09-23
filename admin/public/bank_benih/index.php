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

$controller = new BankBenihController($pdo);

$bankBenihs = $controller->index();
$stats = $controller->statistics();

Permission::authorize(
    $pdo,
    'Bank Benih',
    'view'
);

?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="utf-8">

    <meta name="viewport"
        content="width=device-width, initial-scale=1">

    <title>Data Bank Benih - Admin Borneologi</title>

    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">

    <link rel="stylesheet"
        href="../assets/adminlte/plugins/fontawesome-free/css/all.min.css">

    <link rel="stylesheet"
        href="../assets/adminlte/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">

    <link rel="stylesheet"
        href="../assets/adminlte/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">

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

        <!-- HEADER -->

        <section class="content-header">

            <div class="container-fluid">

                <div class="row mb-2">

                    <div class="col-sm-6">

                        <h1>
                            <i class="fas fa-seedling"></i>
                            Data Benih
                        </h1>

                    </div>

                    <div class="col-sm-6">

                        <ol class="breadcrumb float-sm-right">

                            <li class="breadcrumb-item">
                                <a href="#">Master Data</a>
                            </li>

                            <li class="breadcrumb-item active">
                                Data Benih
                            </li>

                        </ol>

                    </div>

                </div>

            </div>

        </section>


        <!-- CONTENT -->

        <section class="content">

            <div class="container-fluid">


                <!-- ===================================================== -->
                <!-- STATISTICS -->
                <!-- ===================================================== -->

                <div class="row">

                    <!-- TOTAL ACCESSION -->

                    <div class="col-lg-3 col-6">

                        <div class="small-box bg-info">

                            <div class="inner">

                                <h3>
                                    <?= number_format(
                                        $stats['total_accession'] ?? 0
                                    ) ?>
                                </h3>

                                <p>Total Accession</p>

                            </div>

                            <div class="icon">
                                <i class="fas fa-seedling"></i>
                            </div>

                        </div>

                    </div>


                    <!-- TOTAL STOK -->

                    <div class="col-lg-3 col-6">

                        <div class="small-box bg-success">

                            <div class="inner">

                                <h3>
                                    <?= number_format(
                                        $stats['total_stok'] ?? 0
                                    ) ?>
                                </h3>

                                <p>Total Stok</p>

                            </div>

                            <div class="icon">
                                <i class="fas fa-boxes"></i>
                            </div>

                        </div>

                    </div>


                    <!-- SEGERA KADALUARSA -->

                    <div class="col-lg-3 col-6">

                        <div class="small-box bg-warning">

                            <div class="inner">

                                <h3>
                                    <?= number_format(
                                        $stats['segera_kadaluarsa'] ?? 0
                                    ) ?>
                                </h3>

                                <p>Segera Kedaluwarsa</p>

                            </div>

                            <div class="icon">
                                <i class="fas fa-exclamation-triangle"></i>
                            </div>

                        </div>

                    </div>


                    <!-- STOK HABIS -->

                    <div class="col-lg-3 col-6">

                        <div class="small-box bg-danger">

                            <div class="inner">

                                <h3>
                                    <?= number_format(
                                        $stats['stok_habis'] ?? 0
                                    ) ?>
                                </h3>

                                <p>Stok Habis</p>

                            </div>

                            <div class="icon">
                                <i class="fas fa-box-open"></i>
                            </div>

                        </div>

                    </div>

                </div>


                <!-- ===================================================== -->
                <!-- DATA TABLE -->
                <!-- ===================================================== -->

                <div class="card">

                    <div class="card-header">

                        <div class="row">

                            <div class="col-md-8">

                                <h3 class="card-title">

                                    <i class="fas fa-list"></i>

                                    Daftar Data Benih

                                </h3>

                            </div>


                            <div class="col-md-4 text-right">

                                <?php
                                if (
                                    Permission::can(
                                        $pdo,
                                        'Bank Benih',
                                        'create'
                                    )
                                ):
                                ?>

                                    <a href="create.php"
                                        class="btn btn-success">

                                        <i class="fas fa-plus"></i>

                                        Tambah Benih

                                    </a>

                                <?php endif; ?>

                            </div>

                        </div>

                    </div>


                    <div class="card-body">


                        <div class="table-responsive">

                            <table
                                id="tableBenih"
                                class="table table-bordered table-striped">

                                <thead>

                                    <tr>

                                        <th width="40"
                                            class="text-center">
                                            No
                                        </th>

                                        <th>
                                            Nomor Aksesi
                                        </th>

                                        <th>
                                            Nama Lokal
                                        </th>

                                        <th>
                                            Nama Ilmiah
                                        </th>

                                        <th>
                                            Famili
                                        </th>

                                        <th>
                                            Provenance
                                        </th>

                                        <th>
                                            Stok
                                        </th>

                                        <th>
                                            Penyimpanan
                                        </th>

                                        <th>
                                            Masa Berlaku
                                        </th>

                                        <th>
                                            Status
                                        </th>

                                        <th width="180">
                                            Aksi
                                        </th>

                                    </tr>

                                </thead>


                                <tbody>

                                <?php $no = 1; ?>

                                <?php foreach (
                                    $bankBenihs
                                    as $bankBenih
                                ): ?>

                                    <tr>

                                        <td class="text-center">
                                            <?= $no++ ?>
                                        </td>


                                        <td>

                                            <strong>
                                                <?= htmlspecialchars(
                                                    $bankBenih['nomor_aksesi']
                                                    ?: '-'
                                                ) ?>
                                            </strong>

                                        </td>


                                        <td>

                                            <?= htmlspecialchars(
                                                $bankBenih['nama_lokal']
                                            ) ?>

                                        </td>


                                        <td>

                                            <i>
                                                <?= htmlspecialchars(
                                                    $bankBenih['nama_ilmiah']
                                                    ?: '-'
                                                ) ?>
                                            </i>

                                        </td>


                                        <td>

                                            <?= htmlspecialchars(
                                                $bankBenih['famili_tanaman']
                                                ?: '-'
                                            ) ?>

                                        </td>


                                        <td>

                                            <?= htmlspecialchars(
                                                $bankBenih['provenance']
                                                ?: '-'
                                            ) ?>

                                        </td>


                                        <!-- STOK -->

                                        <td>

                                            <?php
                                            $stok =
                                                (float)$bankBenih['jumlah_stok'];

                                            if ($stok <= 0):
                                            ?>

                                                <span
                                                    class="badge badge-danger">

                                                    Habis

                                                </span>

                                            <?php else: ?>

                                                <strong>

                                                    <?= number_format(
                                                        $stok,
                                                        2
                                                    ) ?>

                                                </strong>

                                                <?= htmlspecialchars(
                                                    $bankBenih['satuan_stok']
                                                ) ?>

                                            <?php endif; ?>

                                        </td>


                                        <!-- PENYIMPANAN -->

                                        <td>

                                            <?= htmlspecialchars(
                                                $bankBenih[
                                                    'nama_tipe_penyimpanan_benih'
                                                ]
                                                ?: '-'
                                            ) ?>

                                        </td>


                                        <!-- MASA BERLAKU -->

                                        <td>

                                            <?php

                                            $expiredClass =
                                                '';

                                            $expiredText =
                                                '-';

                                            if (
                                                !empty(
                                                    $bankBenih[
                                                        'masa_berlaku_sampai'
                                                    ]
                                                )
                                            ) {

                                                $masaBerlaku =
                                                    new DateTime(
                                                        $bankBenih[
                                                            'masa_berlaku_sampai'
                                                        ]
                                                    );

                                                $today =
                                                    new DateTime();

                                                $selisih =
                                                    (int)$today
                                                    ->diff($masaBerlaku)
                                                    ->format('%r%a');

                                                $expiredText =
                                                    $masaBerlaku
                                                    ->format('d/m/Y');

                                                if ($selisih < 0) {

                                                    $expiredClass =
                                                        'text-danger font-weight-bold';

                                                } elseif (
                                                    $selisih <= 30
                                                ) {

                                                    $expiredClass =
                                                        'text-warning font-weight-bold';

                                                }

                                            }

                                            ?>

                                            <span
                                                class="<?= $expiredClass ?>">

                                                <?= $expiredText ?>

                                            </span>

                                        </td>


                                        <!-- STATUS -->

                                        <td>

                                            <?php if (
                                                $bankBenih['is_active']
                                            ): ?>

                                                <span
                                                    class="badge badge-success">

                                                    Aktif

                                                </span>

                                            <?php else: ?>

                                                <span
                                                    class="badge badge-secondary">

                                                    Nonaktif

                                                </span>

                                            <?php endif; ?>

                                        </td>


                                        <!-- AKSI -->

                                        <td>

                                            <div class="btn-group">

                                                <!-- DETAIL -->

                                                <a href="detail.php?id=<?= $bankBenih['id'] ?>"
                                                    class="btn btn-sm btn-primary"
                                                    title="Detail">

                                                    <i class="fas fa-eye"></i>

                                                </a>


                                                <!-- EDIT -->

                                                <?php
                                                if (
                                                    Permission::can(
                                                        $pdo,
                                                        'Bank Benih',
                                                        'update'
                                                    )
                                                ):
                                                ?>

                                                    <a href="edit.php?id=<?= $bankBenih['id'] ?>"
                                                        class="btn btn-sm btn-info"
                                                        title="Edit">

                                                        <i class="fas fa-edit"></i>

                                                    </a>

                                                <?php endif; ?>


                                                <!-- DELETE -->

                                                <?php
                                                if (
                                                    Permission::can(
                                                        $pdo,
                                                        'Bank Benih',
                                                        'delete'
                                                    )
                                                ):
                                                ?>

                                                    <form
                                                        method="POST"
                                                        action="delete.php"
                                                        class="form-delete"
                                                        style="display:inline;">

                                                        <?= csrfField() ?>

                                                        <input
                                                            type="hidden"
                                                            name="id"
                                                            value="<?= $bankBenih['id'] ?>">

                                                        <button
                                                            type="submit"
                                                            class="btn btn-sm btn-danger"
                                                            title="Hapus">

                                                            <i class="fas fa-trash"></i>

                                                        </button>

                                                    </form>

                                                <?php endif; ?>

                                            </div>

                                        </td>

                                    </tr>

                                <?php endforeach; ?>

                                </tbody>

                            </table>

                        </div>

                    </div>

                </div>

            </div>

        </section>

    </div>


    <?php include "../feature/footer.php"; ?>


</div>


<!-- ===================================================== -->
<!-- JAVASCRIPT -->
<!-- ===================================================== -->

<script src="../assets/adminlte/plugins/jquery/jquery.min.js"></script>

<script src="../assets/adminlte/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>

<script src="../assets/adminlte/plugins/datatables/jquery.dataTables.min.js"></script>

<script src="../assets/adminlte/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>

<script src="../assets/adminlte/plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>

<script src="../assets/adminlte/plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>

<script src="../assets/adminlte/dist/js/adminlte.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>

$(function () {

    const params =
        new URLSearchParams(
            window.location.search
        );

    const success =
        params.get('success');

    const error =
        params.get('error');


    /*
     * ======================================================
     * SUCCESS
     * ======================================================
     */

    if (success === 'created') {

        Swal.fire({

            icon: 'success',

            title: 'Berhasil!',

            text:
                'Data Bank Benih berhasil ditambahkan.',

            toast: true,

            position: 'top-end',

            showConfirmButton: false,

            timer: 3000,

            timerProgressBar: true

        });

    }


    if (success === 'updated') {

        Swal.fire({

            icon: 'success',

            title: 'Berhasil!',

            text:
                'Data Bank Benih berhasil diperbarui.',

            toast: true,

            position: 'top-end',

            showConfirmButton: false,

            timer: 3000,

            timerProgressBar: true

        });

    }


    if (success === 'deleted') {

        Swal.fire({

            icon: 'success',

            title: 'Berhasil!',

            text:
                'Data Bank Benih berhasil dihapus.',

            toast: true,

            position: 'top-end',

            showConfirmButton: false,

            timer: 3000,

            timerProgressBar: true

        });

    }


    /*
     * ======================================================
     * ERROR
     * ======================================================
     */

    if (error === 'invalid_id') {

        Swal.fire({

            icon: 'error',

            title: 'ID Tidak Valid',

            text:
                'ID data Bank Benih tidak valid.',

            toast: true,

            position: 'top-end',

            showConfirmButton: false,

            timer: 3500

        });

    }


    if (error === 'not_found') {

        Swal.fire({

            icon: 'error',

            title: 'Data Tidak Ditemukan',

            text:
                'Data Bank Benih yang diminta tidak ditemukan.',

            toast: true,

            position: 'top-end',

            showConfirmButton: false,

            timer: 3500

        });

    }


    if (error === 'create_failed') {

        Swal.fire({

            icon: 'error',

            title: 'Gagal',

            text:
                'Data Bank Benih gagal ditambahkan.',

            toast: true,

            position: 'top-end',

            showConfirmButton: false,

            timer: 3500

        });

    }


    /*
     * Bersihkan query dari URL
     */

    if (success || error) {

        const cleanUrl =
            window.location.pathname;

        window.history.replaceState(
            {},
            document.title,
            cleanUrl
        );

    }

});

</script>

<script>

$(function () {

    $('#tableBenih').DataTable({

        responsive: true,

        autoWidth: false,

        pageLength: 10,

        lengthMenu: [
            [10, 25, 50, 100],
            [10, 25, 50, 100]
        ],

        language: {

            search: "Cari:",

            lengthMenu:
                "Tampilkan _MENU_ data",

            info:
                "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",

            infoEmpty:
                "Tidak ada data",

            zeroRecords:
                "Data tidak ditemukan",

            paginate: {

                first: "Pertama",

                last: "Terakhir",

                next: "Berikutnya",

                previous: "Sebelumnya"

            }

        },

        order: [
            [0, 'asc']
        ]

    });


    /**
     * DELETE CONFIRMATION
     */

    $(document).on(
        "submit",
        ".form-delete",
        function (e) {

            e.preventDefault();

            const form = this;

            Swal.fire({

                title: 'Apakah Anda yakin?',

                text:
                    'Data akan dinonaktifkan dari daftar.',

                icon: 'warning',

                showCancelButton: true,

                confirmButtonColor: '#d33',

                cancelButtonColor: '#6c757d',

                confirmButtonText:
                    'Ya, hapus!',

                cancelButtonText:
                    'Batal'

            }).then(function (result) {

                if (result.isConfirmed) {

                    form.submit();

                }

            });

        }
    );

});

</script>


</body>

</html>