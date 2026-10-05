<?php

require_once '../../../app/config/database.php';
require_once '../../../app/models/DetailMonitoringPenanaman.php';
require_once '../../../app/core/session.php';
require_once '../../../app/core/csrf.php';
require_once '../../../app/core/auth.php';
require_once '../../../app/core/Permission.php';
require_once '../../../app/helpers/escape.php';

secureSessionStart();

Permission::authorize(
    $pdo,
    'Detail Monitoring',
    'view'
);

$id =
    (int) ($_GET['id'] ?? 0);

$id_bank_benih =
    (int) ($_GET['id_bank_benih'] ?? 0);

if (
    $id <= 0
    || $id_bank_benih <= 0
) {
    header('Location: ../index.php');
    exit;
}

$model =
    new DetailMonitoringPenanaman($pdo);

$data =
    $model->findById($id);

if (
    !$data
    || (int) $data['id_bank_benih']
    !== $id_bank_benih
) {

    header(
        'Location: index.php?id_bank_benih='
        . $id_bank_benih
    );

    exit;
}

?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Detail Monitoring</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

</head>

<body>

    <div class="container py-4">

        <div class="mb-4">

            <a href="index.php?id_bank_benih=<?= $id_bank_benih ?>" class="text-decoration-none">

                <i class="bi bi-arrow-left"></i>

                Kembali

            </a>

            <div class="d-flex justify-content-between align-items-start mt-3">

                <div>

                    <h4 class="mb-1">

                        Detail Monitoring Penanaman

                    </h4>

                    <span class="badge bg-primary">

                        <?= e(
                            $data['kode_monitoring']
                        ) ?>

                    </span>

                </div>

                <a href="edit.php?id=<?= $id ?>&id_bank_benih=<?= $id_bank_benih ?>" class="btn btn-warning">

                    <i class="bi bi-pencil"></i>

                    Edit

                </a>

            </div>

        </div>


        <div class="row g-4">


            <!-- Monitoring -->

            <div class="col-md-6">

                <div class="card shadow-sm h-100">

                    <div class="card-header">

                        <strong>
                            Monitoring Penanaman
                        </strong>

                    </div>

                    <div class="card-body">

                        <div class="mb-3">

                            <small class="text-muted">
                                Kode Monitoring
                            </small>

                            <div class="fw-semibold">

                                <?= e(
                                    $data[
                                        'kode_monitoring'
                                    ]
                                ) ?>

                            </div>

                        </div>

                        <div class="mb-3">

                            <small class="text-muted">
                                Periode
                            </small>

                            <div>

                                <?= e(
                                    $data['periode_pengecekan']
                                ) ?>

                            </div>

                        </div>

                        <div class="mb-3">

                            <small class="text-muted">
                                Tanggal Tanam
                            </small>

                            <div>

                                <?= e(
                                    $data[
                                        'tanggal_tanam'
                                    ]
                                ) ?>

                            </div>

                        </div>

                        <div>

                            <small class="text-muted">
                                Tanggal Monitoring
                            </small>

                            <div>

                                <?= e(
                                    $data[
                                        'tanggal_monitoring'
                                    ] ?: '-'
                                ) ?>

                            </div>

                        </div>

                    </div>

                </div>

            </div>


            <!-- Bank Benih -->

            <div class="col-md-6">

                <div class="card shadow-sm h-100">

                    <div class="card-header">

                        <strong>
                            Bank Benih
                        </strong>

                    </div>

                    <div class="card-body">

                        <div class="mb-3">

                            <small class="text-muted">
                                Nomor Aksesi
                            </small>

                            <div class="fw-semibold">

                                <?= e(
                                    $data[
                                        'nomor_aksesi'
                                    ]
                                ) ?>

                            </div>

                        </div>

                        <div class="mb-3">

                            <small class="text-muted">
                                Nama Lokal
                            </small>

                            <div>

                                <?= e(
                                    $data[
                                        'nama_lokal'
                                    ]
                                ) ?>

                            </div>

                        </div>

                        <div class="mb-3">

                            <small class="text-muted">
                                Nama Ilmiah
                            </small>

                            <div class="fst-italic">

                                <?= e(
                                    $data[
                                        'nama_ilmiah'
                                    ] ?: '-'
                                ) ?>

                            </div>

                        </div>

                        <div>

                            <small class="text-muted">
                                Stok Saat Ini
                            </small>

                            <div class="fs-5 fw-semibold">

                                <?= number_format(
                                    (float) 
                                    $data[
                                        'jumlah_stok'
                                    ],
                                    2,
                                    ',',
                                    '.'
                                ) ?>

                                <?= e(
                                    $data[
                                        'satuan_stok'
                                    ]
                                ) ?>

                            </div>

                        </div>

                    </div>

                </div>

            </div>


            <!-- Detail Penanaman -->

            <div class="col-12">

                <div class="card shadow-sm">

                    <div class="card-header">

                        <strong>
                            Data Detail Penanaman
                        </strong>

                    </div>

                    <div class="card-body">

                        <div class="row g-4">

                            <div class="col-md-4">

                                <small class="text-muted">
                                    Jumlah Ditanam
                                </small>

                                <div class="fs-4 fw-semibold">

                                    <?= number_format(
                                        (float) 
                                        $data[
                                            'jumlah_ditanam'
                                        ],
                                        2,
                                        ',',
                                        '.'
                                    ) ?>

                                    <?= e(
                                        $data[
                                            'satuan'
                                        ]
                                    ) ?>

                                </div>

                            </div>

                            <div class="col-md-4">

                                <small class="text-muted">
                                    Jumlah Hidup
                                </small>

                                <div class="fs-4 fw-semibold">

                                    <?= $data[
                                        'jumlah_hidup'
                                    ] !== null
                                        ? number_format(
                                            (float) 
                                            $data[
                                                'jumlah_hidup'
                                            ],
                                            0,
                                            ',',
                                            '.'
                                        )
                                        : '-' ?>

                                </div>

                            </div>

                            <div class="col-md-4">

                                <small class="text-muted">
                                    Jumlah Mati
                                </small>

                                <div class="fs-4 fw-semibold">

                                    <?= $data[
                                        'jumlah_mati'
                                    ] !== null
                                        ? number_format(
                                            (float) 
                                            $data[
                                                'jumlah_mati'
                                            ],
                                            0,
                                            ',',
                                            '.'
                                        )
                                        : '-' ?>

                                </div>

                            </div>

                            <div class="col-md-6">

                                <small class="text-muted">
                                    Tinggi Rata-rata
                                </small>

                                <div>

                                    <?= $data[
                                        'tinggi_rata2_cm'
                                    ] !== null
                                        ? number_format(
                                            (float) 
                                            $data[
                                                'tinggi_rata2_cm'
                                            ],
                                            2,
                                            ',',
                                            '.'
                                        )
                                        . ' cm'
                                        : '-' ?>

                                </div>

                            </div>

                            <div class="col-md-6">

                                <small class="text-muted">
                                    Diameter Rata-rata
                                </small>

                                <div>

                                    <?= $data[
                                        'diameter_rata2_cm'
                                    ] !== null
                                        ? number_format(
                                            (float) 
                                            $data[
                                                'diameter_rata2_cm'
                                            ],
                                            2,
                                            ',',
                                            '.'
                                        )
                                        . ' cm'
                                        : '-' ?>

                                </div>

                            </div>

                            <div class="col-12">

                                <small class="text-muted">
                                    Catatan
                                </small>

                                <div class="bg-light border rounded p-3">

                                    <?= nl2br(
                                        e(
                                            $data[
                                                'catatan'
                                            ] ?: '-'
                                        )
                                    ) ?>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</body>

</html>