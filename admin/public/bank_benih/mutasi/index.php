<?php

secureSessionStart();

require_once '../../../config/database.php';
require_once '../../../app/controllers/MutasiStokBenihController.php';
require_once '../../../app/helpers/permission.php';

Permission::authorize(
    $pdo,
    'Riwayat Mutasi',
    'view'
);

$controller =
    new MutasiStokBenihController($pdo);

$idBankBenih =
    filter_input(
        INPUT_GET,
        'id_bank_benih',
        FILTER_VALIDATE_INT,
        [
            'options' => [
                'min_range' => 1
            ]
        ]
    );

if ($idBankBenih) {

    $bankBenih =
        $controller->getBankBenih(
            $idBankBenih
        );

    if (!$bankBenih) {

        header(
            'Location: ../index.php?error=notfound'
        );

        exit;
    }

    $mutasi =
        $controller->getRiwayatByBankBenih(
            $idBankBenih
        );

} else {

    $bankBenih = null;

    $mutasi =
        $controller->getAllRiwayat();
}

function e($value)
{
    return htmlspecialchars(
        (string) ($value ?? ''),
        ENT_QUOTES,
        'UTF-8'
    );
}

function formatNumber($number)
{
    return number_format(
        (float) $number,
        2,
        ',',
        '.'
    );
}

function tipeLabel($tipe)
{
    switch ($tipe) {

        case 'STOK_AWAL':
            return 'Stok Awal';

        case 'MASUK':
            return 'Stok Masuk';

        case 'KELUAR':
            return 'Stok Keluar';

        case 'KOREKSI':
            return 'Koreksi';

        default:
            return $tipe;
    }
}

function tipeBadge($tipe)
{
    switch ($tipe) {

        case 'STOK_AWAL':
            return 'secondary';

        case 'MASUK':
            return 'success';

        case 'KELUAR':
            return 'danger';

        case 'KOREKSI':
            return 'warning';

        default:
            return 'secondary';
    }
}

?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Riwayat Mutasi Stok</title>

    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/jquery.dataTables.min.css">

</head>

<body>

    <div class="container-fluid mt-4">

        <?php if ($bankBenih): ?>

            <div class="card mb-3">

                <div class="card-body">

                    <div class="d-flex justify-content-between">

                        <div>

                            <h4>

                                <?= e(
                                    $bankBenih['nama_lokal']
                                ); ?>

                            </h4>

                            <div class="text-muted">

                                Aksesi:
                                <strong>
                                    <?= e(
                                        $bankBenih['nomor_aksesi']
                                    ); ?>
                                </strong>

                            </div>

                        </div>

                        <div class="text-right">

                            <small class="text-muted">
                                Stok Saat Ini
                            </small>

                            <h3 class="mb-0">

                                <?= formatNumber(
                                    $bankBenih['jumlah_stok']
                                ); ?>

                                <?= e(
                                    $bankBenih['satuan_stok']
                                ); ?>

                            </h3>

                        </div>

                    </div>

                </div>

            </div>

        <?php endif; ?>


        <div class="card">

            <div class="card-header">

                <h3 class="card-title">

                    <i class="fas fa-history mr-2"></i>

                    Riwayat Mutasi Stok

                </h3>

            </div>

            <div class="card-body">

                <div class="table-responsive">

                    <table id="tableMutasi" class="table table-bordered table-striped">

                        <thead>

                            <tr>

                                <th>No</th>

                                <th>Tanggal</th>

                                <?php if (!$bankBenih): ?>

                                    <th>Bank Benih</th>

                                <?php endif; ?>

                                <th>Tipe</th>

                                <th>Arah</th>

                                <th>Jumlah</th>

                                <th>Stok Sebelum</th>

                                <th>Stok Sesudah</th>

                                <th>Sumber</th>

                                <th>Alasan</th>

                            </tr>

                        </thead>

                        <tbody>

                            <?php foreach (
                                $mutasi as $index => $row
                            ): ?>

                                <tr>

                                    <td>
                                        <?= $index + 1; ?>
                                    </td>

                                    <td>

                                        <?= e(
                                            date(
                                                'd-m-Y H:i',
                                                strtotime(
                                                    $row['tanggal_mutasi']
                                                )
                                            )
                                        ); ?>

                                    </td>

                                    <?php if (!$bankBenih): ?>

                                        <td>

                                            <strong>
                                                <?= e(
                                                    $row['nomor_aksesi']
                                                ); ?>
                                            </strong>

                                            <br>

                                            <small>
                                                <?= e(
                                                    $row['nama_lokal']
                                                ); ?>
                                            </small>

                                        </td>

                                    <?php endif; ?>

                                    <td>

                                        <span class="badge badge-<?= tipeBadge(
                                            $row['tipe_mutasi']
                                        ); ?>">

                                            <?= e(
                                                tipeLabel(
                                                    $row['tipe_mutasi']
                                                )
                                            ); ?>

                                        </span>

                                    </td>

                                    <td>

                                        <?php if (
                                            $row['tipe_mutasi']
                                            === 'KOREKSI'
                                        ): ?>

                                            <?= e(
                                                $row['arah_koreksi']
                                            ); ?>

                                        <?php else: ?>

                                            -

                                        <?php endif; ?>

                                    </td>

                                    <td>

                                        <?php

                                        $sign = '';

                                        if (
                                            $row['tipe_mutasi']
                                            === 'MASUK'
                                            ||
                                            (
                                                $row['tipe_mutasi']
                                                === 'KOREKSI'
                                                &&
                                                $row['arah_koreksi']
                                                === 'TAMBAH'
                                            )
                                            ||
                                            $row['tipe_mutasi']
                                            === 'STOK_AWAL'
                                        ) {
                                            $sign = '+';
                                        }

                                        if (
                                            $row['tipe_mutasi']
                                            === 'KELUAR'
                                            ||
                                            (
                                                $row['tipe_mutasi']
                                                === 'KOREKSI'
                                                &&
                                                $row['arah_koreksi']
                                                === 'KURANG'
                                            )
                                        ) {
                                            $sign = '-';
                                        }

                                        ?>

                                        <strong>

                                            <?= $sign; ?>

                                            <?= formatNumber(
                                                $row['jumlah']
                                            ); ?>

                                            <?= e(
                                                $row['satuan']
                                            ); ?>

                                        </strong>

                                    </td>

                                    <td>

                                        <?= formatNumber(
                                            $row['stok_sebelum']
                                        ); ?>

                                        <?= e(
                                            $row['satuan']
                                        ); ?>

                                    </td>

                                    <td>

                                        <strong>

                                            <?= formatNumber(
                                                $row['stok_sesudah']
                                            ); ?>

                                            <?= e(
                                                $row['satuan']
                                            ); ?>

                                        </strong>

                                    </td>

                                    <td>

                                        <?= e(
                                            $row['sumber']
                                            ?: '-'
                                        ); ?>

                                    </td>

                                    <td>

                                        <?= nl2br(
                                            e(
                                                $row['alasan']
                                                ?: '-'
                                            )
                                        ); ?>

                                    </td>

                                </tr>

                            <?php endforeach; ?>

                        </tbody>

                    </table>

                </div>

            </div>

        </div>

    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js">
    </script>

    <script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js">
    </script>

    <script>

        $(document).ready(function () {

            $('#tableMutasi').DataTable({

                pageLength: 25,

                order: [
                    [1, 'desc']
                ],

                language: {

                    search: 'Pencarian:',

                    lengthMenu:
                        'Tampilkan _MENU_ data',

                    info:
                        'Menampilkan _START_ sampai _END_ dari _TOTAL_ data',

                    infoEmpty:
                        'Tidak ada data',

                    zeroRecords:
                        'Data tidak ditemukan',

                    paginate: {

                        first: 'Pertama',

                        last: 'Terakhir',

                        next: 'Berikutnya',

                        previous: 'Sebelumnya'

                    }

                }

            });

        });

    </script>

    <script>

        const params =
            new URLSearchParams(
                window.location.search
            );

        if (
            params.get('success') === 'created'
        ) {

            Swal.fire({

                toast: true,

                position: 'top-end',

                icon: 'success',

                title: 'Berhasil',

                text: 'Mutasi stok berhasil disimpan.',

                showConfirmButton: false,

                timer: 3000,

                timerProgressBar: true

            });

        }

    </script>

    <script>

        if (
            params.get('error') === 'notfound'
        ) {

            Swal.fire({

                toast: true,

                position: 'top-end',

                icon: 'error',

                title: 'Data tidak ditemukan',

                showConfirmButton: false,

                timer: 3000

            });

        }

    </script>

</body>

</html>