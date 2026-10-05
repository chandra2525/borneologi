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
    'create'
);

$id_bank_benih = isset($_GET['id_bank_benih'])
    ? (int) $_GET['id_bank_benih']
    : 0;

if ($id_bank_benih <= 0) {

    header('Location: ../index.php');
    exit;
}

$model =
    new DetailMonitoringPenanaman($pdo);

$bankBenih =
    $model->getBankBenih($id_bank_benih);

if (!$bankBenih) {

    header('Location: ../index.php');
    exit;
}

$monitoring =
    $model->getMonitoringPenanaman();

?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Tambah Detail Monitoring</title>

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

            <h4 class="mt-3 mb-1">

                Tambah Detail Monitoring Penanaman

            </h4>

            <p class="text-muted">

                Bank Benih:

                <strong>

                    <?= e(
                        $bankBenih['nomor_aksesi']
                    ) ?>

                    -

                    <?= e(
                        $bankBenih['nama_lokal']
                    ) ?>

                </strong>

            </p>

        </div>


        <!-- Informasi Stok -->

        <div class="alert alert-info">

            <div class="row">

                <div class="col-md-6">

                    <strong>
                        Stok tersedia:
                    </strong>

                    <?= number_format(
                        (float) 
                        $bankBenih['jumlah_stok'],
                        2,
                        ',',
                        '.'
                    ) ?>

                    <?= e(
                        $bankBenih['satuan_stok']
                    ) ?>

                </div>

                <div class="col-md-6">

                    <strong>
                        Satuan:
                    </strong>

                    <?= e(
                        $bankBenih['satuan_stok']
                    ) ?>

                </div>

            </div>

        </div>


        <form method="POST" action="store.php">

            <?= csrfField() ?>

            <input type="hidden" name="id_bank_benih" value="<?= $id_bank_benih ?>">

            <div class="card shadow-sm">

                <div class="card-body">

                    <div class="row g-3">

                        <div class="col-md-6">

                            <label class="form-label">

                                Monitoring Penanaman

                                <span class="text-danger">
                                    *
                                </span>

                            </label>

                            <select name="id_monitoring" class="form-select" required>

                                <option value="">

                                    -- Pilih Monitoring --

                                </option>

                                <?php foreach (
                                    $monitoring
                                    as $row
                                ): ?>

                                    <option value="<?= (int) $row['id'] ?>">

                                        <?= e(
                                            $row[
                                                'kode_monitoring'
                                            ]
                                        ) ?>

                                        -

                                        <?= e(
                                            $row['periode_pengecekan']
                                        ) ?>

                                        (<?= e(
                                            $row['tanggal_tanam']
                                        ) ?>)

                                    </option>

                                <?php endforeach; ?>

                            </select>

                        </div>


                        <div class="col-md-6">

                            <label class="form-label">

                                Bank Benih

                            </label>

                            <input type="text" class="form-control" value="<?= e(
                                $bankBenih['nomor_aksesi']
                            ) ?>
                            - <?= e(
                                $bankBenih['nama_lokal']
                            ) ?>" readonly>

                            <input type="hidden" name="id_bank_benih" value="<?= $id_bank_benih ?>">

                        </div>


                        <div class="col-md-4">

                            <label class="form-label">

                                Jumlah Ditanam

                                <span class="text-danger">
                                    *
                                </span>

                            </label>

                            <input type="number" name="jumlah_ditanam" id="jumlah_ditanam" class="form-control"
                                min="0.01" step="0.01" max="<?= e(
                                    $bankBenih[
                                        'jumlah_stok'
                                    ]
                                ) ?>" required>

                            <div class="form-text">

                                Maksimal:

                                <?= number_format(
                                    (float) 
                                    $bankBenih[
                                        'jumlah_stok'
                                    ],
                                    2,
                                    ',',
                                    '.'
                                ) ?>

                                <?= e(
                                    $bankBenih[
                                        'satuan_stok'
                                    ]
                                ) ?>

                            </div>

                        </div>


                        <div class="col-md-4">

                            <label class="form-label">

                                Satuan

                            </label>

                            <input type="text" class="form-control" value="<?= e(
                                $bankBenih[
                                    'satuan_stok'
                                ]
                            ) ?>" readonly>

                            <input type="hidden" name="satuan" value="<?= e(
                                $bankBenih[
                                    'satuan_stok'
                                ]
                            ) ?>">

                        </div>


                        <div class="col-md-4">

                            <label class="form-label">

                                Sisa Stok Setelah Tanam

                            </label>

                            <input type="text" id="sisa_stok" class="form-control" value="<?= number_format(
                                (float) 
                                $bankBenih[
                                    'jumlah_stok'
                                ],
                                2,
                                ',',
                                '.'
                            ) ?>" readonly>

                        </div>


                        <div class="col-md-6">

                            <label class="form-label">

                                Jumlah Hidup

                            </label>

                            <input type="number" name="jumlah_hidup" class="form-control" min="0" step="1">

                        </div>


                        <div class="col-md-6">

                            <label class="form-label">

                                Jumlah Mati

                            </label>

                            <input type="number" name="jumlah_mati" class="form-control" min="0" step="1">

                        </div>


                        <div class="col-md-6">

                            <label class="form-label">

                                Tinggi Rata-rata (cm)

                            </label>

                            <input type="number" name="tinggi_rata2_cm" class="form-control" min="0" step="0.01">

                        </div>


                        <div class="col-md-6">

                            <label class="form-label">

                                Diameter Rata-rata (cm)

                            </label>

                            <input type="number" name="diameter_rata2_cm" class="form-control" min="0" step="0.01">

                        </div>


                        <div class="col-12">

                            <label class="form-label">

                                Catatan

                            </label>

                            <textarea name="catatan" class="form-control" rows="4"></textarea>

                        </div>

                    </div>

                </div>


                <div class="card-footer d-flex justify-content-between">

                    <a href="index.php?id_bank_benih=<?= $id_bank_benih ?>" class="btn btn-secondary">

                        Batal

                    </a>

                    <button type="submit" class="btn btn-primary">

                        <i class="bi bi-save"></i>

                        Simpan

                    </button>

                </div>

            </div>

        </form>

    </div>


    <script>

        const jumlahInput =
            document.getElementById(
                'jumlah_ditanam'
            );

        const sisaInput =
            document.getElementById(
                'sisa_stok'
            );

        const stokAwal =
            <?= json_encode(
                (float) 
                $bankBenih['jumlah_stok']
            ) ?>;

        jumlahInput.addEventListener(
            'input',
            function () {

                const jumlah =
                    parseFloat(this.value) || 0;

                const sisa =
                    stokAwal - jumlah;

                sisaInput.value =
                    sisa.toLocaleString(
                        'id-ID',
                        {
                            minimumFractionDigits: 2,
                            maximumFractionDigits: 2
                        }
                    );

            }
        );

    </script>

</body>

</html>