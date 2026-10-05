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
    'update'
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

    <title>Edit Detail Monitoring</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">

</head>

<body>

    <div class="container py-4">

        <div class="mb-4">

            <a href="index.php?id_bank_benih=<?= $id_bank_benih ?>" class="text-decoration-none">

                ← Kembali

            </a>

            <h4 class="mt-3">

                Edit Detail Monitoring Penanaman

            </h4>

        </div>


        <div class="alert alert-warning">

            <strong>Perhatian:</strong>

            Bank Benih dan Monitoring tidak dapat diganti
            pada halaman Edit karena Detail Monitoring
            sudah terhubung dengan histori mutasi stok.

        </div>


        <form method="POST" action="update.php">

            <?= csrfField() ?>

            <input type="hidden" name="id" value="<?= $id ?>">

            <input type="hidden" name="id_monitoring" value="<?= (int) 
                $data['id_monitoring'] ?>">

            <input type="hidden" name="id_bank_benih" value="<?= $id_bank_benih ?>">

            <div class="card shadow-sm">

                <div class="card-body">

                    <div class="row g-3">


                        <div class="col-md-6">

                            <label class="form-label">
                                Monitoring
                            </label>

                            <input type="text" class="form-control" value="<?= e(
                                $data[
                                    'kode_monitoring'
                                ]
                            ) ?>" readonly>

                        </div>


                        <div class="col-md-6">

                            <label class="form-label">
                                Bank Benih
                            </label>

                            <input type="text" class="form-control" value="<?= e(
                                $data[
                                    'nomor_aksesi'
                                ]
                            ) ?>
                            - <?= e(
                                $data[
                                    'nama_lokal'
                                ]
                            ) ?>" readonly>

                        </div>


                        <div class="col-md-4">

                            <label class="form-label">

                                Jumlah Ditanam

                                <span class="text-danger">
                                    *
                                </span>

                            </label>

                            <input type="number" name="jumlah_ditanam" class="form-control" min="0.01" step="0.01"
                                value="<?= e(
                                    $data[
                                        'jumlah_ditanam'
                                    ]
                                ) ?>" required>

                        </div>


                        <div class="col-md-4">

                            <label class="form-label">

                                Satuan

                            </label>

                            <input type="text" class="form-control" value="<?= e(
                                $data['satuan']
                            ) ?>" readonly>

                            <input type="hidden" name="satuan" value="<?= e(
                                $data['satuan']
                            ) ?>">

                        </div>


                        <div class="col-md-4">

                            <label class="form-label">

                                Stok Saat Ini

                            </label>

                            <input type="text" class="form-control" value="<?= number_format(
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
                            ) ?>" readonly>

                        </div>


                        <div class="col-md-6">

                            <label class="form-label">

                                Jumlah Hidup

                            </label>

                            <input type="number" name="jumlah_hidup" class="form-control" min="0" step="1" value="<?= e(
                                $data[
                                    'jumlah_hidup'
                                ] ?? ''
                            ) ?>">

                        </div>


                        <div class="col-md-6">

                            <label class="form-label">

                                Jumlah Mati

                            </label>

                            <input type="number" name="jumlah_mati" class="form-control" min="0" step="1" value="<?= e(
                                $data[
                                    'jumlah_mati'
                                ] ?? ''
                            ) ?>">

                        </div>


                        <div class="col-md-6">

                            <label class="form-label">

                                Tinggi Rata-rata (cm)

                            </label>

                            <input type="number" name="tinggi_rata2_cm" class="form-control" min="0" step="0.01" value="<?= e(
                                $data[
                                    'tinggi_rata2_cm'
                                ] ?? ''
                            ) ?>">

                        </div>


                        <div class="col-md-6">

                            <label class="form-label">

                                Diameter Rata-rata (cm)

                            </label>

                            <input type="number" name="diameter_rata2_cm" class="form-control" min="0" step="0.01"
                                value="<?= e(
                                    $data[
                                        'diameter_rata2_cm'
                                    ] ?? ''
                                ) ?>">

                        </div>


                        <div class="col-12">

                            <label class="form-label">

                                Catatan

                            </label>

                            <textarea name="catatan" class="form-control" rows="4"><?= e(
                                $data[
                                    'catatan'
                                ] ?? ''
                            ) ?></textarea>

                        </div>

                    </div>

                </div>


                <div class="card-footer d-flex justify-content-between">

                    <a href="index.php?id_bank_benih=<?= $id_bank_benih ?>" class="btn btn-secondary">

                        Batal

                    </a>

                    <button type="submit" class="btn btn-primary">

                        Simpan Perubahan

                    </button>

                </div>

            </div>

        </form>

    </div>

</body>

</html>