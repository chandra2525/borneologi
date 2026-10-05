<?php

require_once '../../../app/config/database.php';
require_once '../../../app/models/MonitoringPenanaman.php';
require_once '../../../app/core/session.php';
require_once '../../../app/core/csrf.php';
require_once '../../../app/core/auth.php';
require_once '../../../app/core/Permission.php';
require_once '../../../app/helpers/escape.php';

secureSessionStart();

Permission::authorize($pdo, 'Monitoring Penanaman', 'update');

$id = (int) ($_GET['id'] ?? 0);
$id_bank_benih = (int) ($_GET['id_bank_benih'] ?? 0);

if ($id <= 0 || $id_bank_benih <= 0) {
    header('Location: ../index.php');
    exit;
}

$model = new MonitoringPenanaman($pdo);

$data = $model->findById($id);
$bankBenih = $model->getBankBenih($id_bank_benih);

if (!$data || !$bankBenih) {
    header(
        'Location: index.php?id_bank_benih=' .
        $id_bank_benih
    );

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

    <title>Edit Monitoring Penanaman</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">

</head>

<body>

    <div class="container py-4">

        <div class="mb-4">

            <a href="index.php?id_bank_benih=<?= $id_bank_benih ?>" class="text-decoration-none">

                ← Kembali

            </a>

            <h4 class="mt-3">
                Edit Monitoring Penanaman
            </h4>

        </div>

        <form method="POST" action="update.php">

            <?= csrfField() ?>

            <input type="hidden" name="id" value="<?= $id ?>">

            <input type="hidden" name="id_bank_benih" value="<?= $id_bank_benih ?>">

            <div class="card shadow-sm">

                <div class="card-body">

                    <div class="mb-3">

                        <label class="form-label">
                            Kode Monitoring
                        </label>

                        <input type="text" class="form-control" value="<?= e(
                            $data['kode_monitoring']
                        ) ?>" readonly>

                    </div>

                    <div class="row g-3">

                        <div class="col-md-6">

                            <label class="form-label">
                                Tanah
                            </label>

                            <select name="id_tanah" class="form-select" required>

                                <?php foreach (
                                    $tanah
                                    as $row
                                ): ?>

                                    <option value="<?= (int) $row['id'] ?>" <?= (int) $row['id']
                                           === (int) $data['id_tanah']
                                           ? 'selected'
                                           : '' ?>>

                                        <?= e(
                                            $row['nama_lahan']
                                        ) ?>

                                    </option>

                                <?php endforeach; ?>

                            </select>

                        </div>

                        <div class="col-md-6">

                            <label class="form-label">
                                Tipe Penanaman
                            </label>

                            <select name="id_tipe_penanaman" class="form-select" required>

                                <?php foreach (
                                    $tipePenanaman
                                    as $row
                                ): ?>

                                    <option value="<?= (int) $row['id'] ?>" <?= (int) $row['id']
                                           === (int) $data[
                                               'id_tipe_penanaman'
                                           ]
                                           ? 'selected'
                                           : '' ?>>

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
                            </label>

                            <input type="text" name="periode_pengecekan" class="form-control" value="<?= e(
                                $data['periode_pengecekan']
                            ) ?>" required>

                        </div>

                        <div class="col-md-4">

                            <label class="form-label">
                                Tanggal Tanam
                            </label>

                            <input type="date" name="tanggal_tanam" id="tanggal_tanam" class="form-control" value="<?= e(
                                $data['tanggal_tanam']
                            ) ?>" required>

                        </div>

                        <div class="col-md-4">

                            <label class="form-label">
                                Tanggal Monitoring
                            </label>

                            <input type="date" name="tanggal_monitoring" id="tanggal_monitoring" class="form-control"
                                value="<?= e(
                                    $data['tanggal_monitoring']
                                    ?? ''
                                ) ?>">

                        </div>

                        <div class="col-md-6">

                            <label class="form-label">
                                Luas Tanam (Ha)
                            </label>

                            <input type="number" name="luas_tanam_ha" class="form-control" min="0.01" step="0.01" value="<?= e(
                                $data['luas_tanam_ha']
                            ) ?>" required>

                        </div>

                        <div class="col-md-6">

                            <label class="form-label">
                                Status Monitoring
                            </label>

                            <select name="id_progress_status_monitoring" class="form-select" required>

                                <?php foreach (
                                    $statusMonitoring
                                    as $row
                                ): ?>

                                    <option value="<?= (int) $row['id'] ?>" <?= (int) $row['id']
                                           === (int) $data[
                                               'id_progress_status_monitoring'
                                           ]
                                           ? 'selected'
                                           : '' ?>>

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

                            <textarea name="catatan" class="form-control" rows="4"><?= e(
                                $data['catatan'] ?? ''
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