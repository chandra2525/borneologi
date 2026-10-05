<?php

require_once '../../../app/config/database.php';
require_once '../../../app/models/MonitoringPenanaman.php';
require_once '../../../app/core/session.php';
require_once '../../../app/core/csrf.php';
require_once '../../../app/core/auth.php';
require_once '../../../app/core/Permission.php';
require_once '../../../app/helpers/escape.php';

secureSessionStart();

Permission::authorize($pdo, 'Monitoring Penanaman', 'view');

$id = (int) ($_GET['id'] ?? 0);
$id_bank_benih = (int) ($_GET['id_bank_benih'] ?? 0);

if ($id <= 0 || $id_bank_benih <= 0) {
    header('Location: ../index.php');
    exit;
}

$model = new MonitoringPenanaman($pdo);

$monitoring = $model->findById($id);
$bankBenih = $model->getBankBenih($id_bank_benih);

if (!$monitoring || !$bankBenih) {
    header(
        'Location: index.php?id_bank_benih=' .
        $id_bank_benih .
        '&error=' .
        urlencode('Data tidak ditemukan.')
    );

    exit;
}

?>
<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Detail Monitoring Penanaman</title>

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
                            $monitoring['kode_monitoring']
                        ) ?>

                    </span>

                </div>

                <a href="edit.php?id=<?= $id ?>&id_bank_benih=<?= $id_bank_benih ?>" class="btn btn-warning">

                    <i class="bi bi-pencil"></i>
                    Edit

                </a>

            </div>

        </div>

        <div class="card shadow-sm">

            <div class="card-header">

                <strong>
                    Informasi Monitoring
                </strong>

            </div>

            <div class="card-body">

                <div class="row g-4">

                    <div class="col-md-6">

                        <small class="text-muted">
                            Kode Monitoring
                        </small>

                        <div class="fw-semibold">

                            <?= e(
                                $monitoring[
                                    'kode_monitoring'
                                ]
                            ) ?>

                        </div>

                    </div>

                    <div class="col-md-6">

                        <small class="text-muted">
                            Bank Benih
                        </small>

                        <div class="fw-semibold">

                            <?= e(
                                $bankBenih['nomor_aksesi']
                            ) ?>

                            -

                            <?= e(
                                $bankBenih['nama_lokal']
                            ) ?>

                        </div>

                    </div>

                    <div class="col-md-6">

                        <small class="text-muted">
                            Tanah
                        </small>

                        <div>
                            <?= e(
                                $monitoring['nama_lahan']
                                ?: '-'
                            ) ?>
                        </div>

                    </div>

                    <div class="col-md-6">

                        <small class="text-muted">
                            Tipe Penanaman
                        </small>

                        <div>
                            <?= e(
                                $monitoring[
                                    'nama'
                                ] ?: '-'
                            ) ?>
                        </div>

                    </div>

                    <div class="col-md-4">

                        <small class="text-muted">
                            Periode
                        </small>

                        <div>
                            <?= e(
                                $monitoring['periode_pengecekan']
                            ) ?>
                        </div>

                    </div>

                    <div class="col-md-4">

                        <small class="text-muted">
                            Tanggal Tanam
                        </small>

                        <div>
                            <?= e(
                                $monitoring['tanggal_tanam']
                            ) ?>
                        </div>

                    </div>

                    <div class="col-md-4">

                        <small class="text-muted">
                            Tanggal Monitoring
                        </small>

                        <div>
                            <?= e(
                                $monitoring[
                                    'tanggal_monitoring'
                                ] ?: '-'
                            ) ?>
                        </div>

                    </div>

                    <div class="col-md-6">

                        <small class="text-muted">
                            Luas Tanam
                        </small>

                        <div class="fs-5 fw-semibold">

                            <?= number_format(
                                (float) 
                                $monitoring[
                                    'luas_tanam_ha'
                                ],
                                2,
                                ',',
                                '.'
                            ) ?>

                            Ha

                        </div>

                    </div>

                    <div class="col-md-6">

                        <small class="text-muted">
                            Status
                        </small>

                        <div class="fw-semibold">

                            <?= e(
                                $monitoring[
                                    'nama_status_monitoring'
                                ] ?: '-'
                            ) ?>

                        </div>

                    </div>

                    <div class="col-12">

                        <small class="text-muted">
                            Catatan
                        </small>

                        <div class="bg-light border rounded p-3">

                            <?= nl2br(
                                e(
                                    $monitoring['catatan']
                                    ?: '-'
                                )
                            ) ?>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</body>

</html>