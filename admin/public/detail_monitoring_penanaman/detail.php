<?php

require_once '../../app/config/database.php';
require_once '../../app/models/DetailMonitoringPenanaman.php';
require_once '../../app/core/session.php';
require_once '../../app/core/csrf.php';
require_once '../../app/core/auth.php';
require_once '../../app/core/Permission.php';
require_once '../../app/helpers/escape.php';

secureSessionStart();

Permission::authorize($pdo, 'Detail Monitoring', 'view');

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

if ($id <= 0) {
    header('Location: index.php');
    exit;
}

$model = new DetailMonitoringPenanaman($pdo);

$data = $model->findById($id);

if (!$data) {
    header('Location: index.php?error=' . urlencode('Data tidak ditemukan.'));
    exit;
}

function formatNumber($value)
{
    if ($value === null || $value === '') {
        return '-';
    }

    return rtrim(
        rtrim(number_format((float) $value, 2, ',', '.'), '0'),
        ','
    );
}

?>
<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Detail Monitoring Penanaman</title>

    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">

    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

</head>

<body>

<div class="container py-4">

    <div class="d-flex justify-content-between mb-4">

        <div>

            <h4>
                <i class="bi bi-eye"></i>
                Detail Monitoring Penanaman
            </h4>

            <div class="text-muted">
                <?= e($data['kode_monitoring']) ?>
            </div>

        </div>

        <div>

            <a
                href="edit.php?id=<?= (int) $data['id'] ?>"
                class="btn btn-warning">

                <i class="bi bi-pencil"></i>
                Edit

            </a>

            <a
                href="index.php"
                class="btn btn-secondary">

                Kembali

            </a>

        </div>

    </div>

    <div class="card shadow-sm">

        <div class="card-header">

            <strong>
                Informasi Penanaman
            </strong>

        </div>

        <div class="card-body">

            <div class="row g-4">

                <div class="col-md-6">

                    <label class="text-muted">
                        Kode Monitoring
                    </label>

                    <div class="fw-semibold">
                        <?= e($data['kode_monitoring']) ?>
                    </div>

                </div>

                <div class="col-md-6">

                    <label class="text-muted">
                        Nomor Aksesi
                    </label>

                    <div class="fw-semibold">
                        <?= e($data['nomor_aksesi']) ?>
                    </div>

                </div>

                <div class="col-md-6">

                    <label class="text-muted">
                        Nama Lokal
                    </label>

                    <div class="fw-semibold">
                        <?= e($data['nama_bank_benih']) ?>
                    </div>

                </div>

                <div class="col-md-6">

                    <label class="text-muted">
                        Nama Ilmiah
                    </label>

                    <div class="fw-semibold fst-italic">
                        <?= e($data['nama_ilmiah'] ?: '-') ?>
                    </div>

                </div>

                <div class="col-md-4">

                    <label class="text-muted">
                        Jumlah Ditanam
                    </label>

                    <div class="fs-5 fw-semibold">

                        <?= formatNumber($data['jumlah_ditanam']) ?>

                        <?= e($data['satuan']) ?>

                    </div>

                </div>

                <div class="col-md-4">

                    <label class="text-muted">
                        Jumlah Hidup
                    </label>

                    <div class="fs-5 fw-semibold">

                        <?= $data['jumlah_hidup'] !== null
                            ? e($data['jumlah_hidup'])
                            : '-' ?>

                    </div>

                </div>

                <div class="col-md-4">

                    <label class="text-muted">
                        Jumlah Mati
                    </label>

                    <div class="fs-5 fw-semibold">

                        <?= $data['jumlah_mati'] !== null
                            ? e($data['jumlah_mati'])
                            : '-' ?>

                    </div>

                </div>

                <div class="col-md-6">

                    <label class="text-muted">
                        Tinggi Rata-rata
                    </label>

                    <div>
                        <?= $data['tinggi_rata2_cm'] !== null
                            ? e($data['tinggi_rata2_cm']) . ' cm'
                            : '-' ?>
                    </div>

                </div>

                <div class="col-md-6">

                    <label class="text-muted">
                        Diameter Rata-rata
                    </label>

                    <div>
                        <?= $data['diameter_rata2_cm'] !== null
                            ? e($data['diameter_rata2_cm']) . ' cm'
                            : '-' ?>
                    </div>

                </div>

                <div class="col-12">

                    <label class="text-muted">
                        Catatan
                    </label>

                    <div class="border rounded p-3 bg-light">

                        <?= nl2br(e($data['catatan'] ?: '-')) ?>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

</body>
</html>