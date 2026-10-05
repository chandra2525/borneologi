<?php

require_once '../../../app/config/database.php';
require_once '../../../app/models/MonitoringPenanaman.php';
require_once '../../../app/core/session.php';
require_once '../../../app/core/csrf.php';
require_once '../../../app/core/auth.php';
require_once '../../../app/core/Permission.php';
require_once '../../../app/helpers/escape.php';

secureSessionStart();

error_reporting(E_ALL);
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');

Permission::authorize($pdo, 'Monitoring Penanaman', 'create');

$id_bank_benih = isset($_GET['id_bank_benih'])
    ? (int) $_GET['id_bank_benih']
    : 0;

if ($id_bank_benih <= 0) {
    header('Location: ../index.php');
    exit;
}

$model = new MonitoringPenanaman($pdo);

$bankBenih = $model->getBankBenih($id_bank_benih);

if (!$bankBenih) {
    header('Location: ../index.php');
    exit;
}

$tanah = $model->getTanah();
$tipePenanaman = $model->getTipePenanaman();
$statusMonitoring = $model->getStatusMonitoring();

?>
<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Tambah Monitoring Penanaman</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

</head>

<body>

    <div class="container py-4">

        <div class="mb-4">

            <a href="index.php?id_bank_benih=<?= $id_bank_benih ?>" class="text-decoration-none">

                <i class="bi bi-arrow-left"></i>
                Kembali ke Monitoring

            </a>

            <h4 class="mt-3 mb-1">

                <i class="bi bi-plus-circle"></i>

                Tambah Monitoring Penanaman

            </h4>

            <p class="text-muted">

                Bank Benih:

                <strong>
                    <?= e($bankBenih['nomor_aksesi']) ?>
                    -
                    <?= e($bankBenih['nama_lokal']) ?>
                </strong>

            </p>

        </div>

        <form method="POST" action="store.php">

            <?= csrfField() ?>

            <input type="hidden" name="id_bank_benih" value="<?= $id_bank_benih ?>">

            <div class="card shadow-sm">

                <div class="card-body">

                    <div class="row g-3">

                        <div class="col-md-6">

                            <label class="form-label">
                                Tanah
                                <span class="text-danger">*</span>
                            </label>

                            <select name="id_tanah" class="form-select" required>

                                <option value="">
                                    -- Pilih Tanah --
                                </option>

                                <?php foreach ($tanah as $row): ?>

                                    <option value="<?= (int) $row['id'] ?>">

                                        <?= e($row['nama_lahan']) ?>

                                    </option>

                                <?php endforeach; ?>

                            </select>

                        </div>

                        <div class="col-md-6">

                            <label class="form-label">
                                Tipe Penanaman
                                <span class="text-danger">*</span>
                            </label>

                            <select name="id_tipe_penanaman" class="form-select" required>

                                <option value="">
                                    -- Pilih Tipe --
                                </option>

                                <?php foreach (
                                    $tipePenanaman
                                    as $row
                                ): ?>

                                    <option value="<?= (int) $row['id'] ?>">

                                        <?= e(
                                            $row[
                                                'nama'
                                            ]
                                        ) ?>

                                    </option>

                                <?php endforeach; ?>

                            </select>

                        </div>

                        <div class="col-md-4">

                            <label class="form-label">

                                Periode

                                <span class="text-danger">*</span>

                            </label>

                            <input type="date" name="periode_pengecekan" class="form-control" required>

                        </div>

                        <div class="col-md-4">

                            <label class="form-label">

                                Tanggal Tanam

                                <span class="text-danger">*</span>

                            </label>

                            <input type="date" name="tanggal_tanam" id="tanggal_tanam" class="form-control" required>

                        </div>

                        <div class="col-md-4">

                            <label class="form-label">

                                Tanggal Monitoring

                            </label>

                            <input type="date" name="tanggal_monitoring" id="tanggal_monitoring" class="form-control">

                        </div>

                        <div class="col-md-6">

                            <label class="form-label">

                                Luas Tanam (Ha)

                                <span class="text-danger">*</span>

                            </label>

                            <input type="number" name="luas_tanam_ha" class="form-control" min="0.01" step="0.01"
                                required>

                        </div>

                        <div class="col-md-6">

                            <label class="form-label">

                                Status Monitoring

                                <span class="text-danger">*</span>

                            </label>

                            <select name="id_progress_status_monitoring" class="form-select" required>

                                <option value="">
                                    -- Pilih Status --
                                </option>

                                <?php foreach (
                                    $statusMonitoring
                                    as $row
                                ): ?>

                                    <option value="<?= (int) $row['id'] ?>">

                                        <?= e(
                                            $row['nama']
                                        ) ?>

                                    </option>

                                <?php endforeach; ?>

                            </select>

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

        const tanggalTanam =
            document.getElementById('tanggal_tanam');

        const tanggalMonitoring =
            document.getElementById('tanggal_monitoring');

        tanggalTanam.addEventListener('change', function () {

            tanggalMonitoring.min = this.value;

        });

    </script>

</body>

</html>