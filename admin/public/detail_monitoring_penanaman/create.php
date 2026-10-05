<?php

require_once '../../app/config/database.php';
require_once '../../app/models/DetailMonitoringPenanaman.php';
require_once '../../app/core/session.php';
require_once '../../app/core/csrf.php';
require_once '../../app/core/auth.php';
require_once '../../app/core/Permission.php';
require_once '../../app/helpers/escape.php';

secureSessionStart();

Permission::authorize($pdo, 'Detail Monitoring', 'create');

$model = new DetailMonitoringPenanaman($pdo);

$bankBenih = $model->getBankBenih();
$monitoring = $model->getMonitoringPenanaman();

?>
<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Tambah Detail Monitoring</title>

    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">

    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

</head>

<body>

<div class="container py-4">

    <div class="mb-4">

        <h4>
            <i class="bi bi-plus-circle"></i>
            Tambah Detail Monitoring Penanaman
        </h4>

        <p class="text-muted mb-0">
            Penambahan data akan otomatis mengurangi stok Bank Benih.
        </p>

    </div>

    <form method="POST" action="store.php">

        <?= csrfField() ?>

        <div class="card shadow-sm">

            <div class="card-body">

                <div class="row g-3">

                    <div class="col-md-6">

                        <label class="form-label">
                            Monitoring Penanaman
                            <span class="text-danger">*</span>
                        </label>

                        <select
                            name="id_monitoring"
                            class="form-select"
                            required>

                            <option value="">
                                -- Pilih Monitoring --
                            </option>

                            <?php foreach ($monitoring as $row): ?>

                                <option value="<?= (int) $row['id'] ?>">

                                    <?= e($row['kode_monitoring']) ?>

                                </option>

                            <?php endforeach; ?>

                        </select>

                    </div>

                    <div class="col-md-6">

                        <label class="form-label">

                            Bank Benih

                            <span class="text-danger">*</span>

                        </label>

                        <select
                            name="id_bank_benih"
                            id="id_bank_benih"
                            class="form-select"
                            required>

                            <option value="">
                                -- Pilih Bank Benih --
                            </option>

                            <?php foreach ($bankBenih as $row): ?>

                                <option
                                    value="<?= (int) $row['id'] ?>"
                                    data-stok="<?= e($row['jumlah_stok']) ?>"
                                    data-satuan="<?= e($row['satuan_stok']) ?>">

                                    <?= e($row['nomor_aksesi']) ?>
                                    -
                                    <?= e($row['nama_lokal']) ?>

                                </option>

                            <?php endforeach; ?>

                        </select>

                    </div>

                    <div class="col-md-4">

                        <label class="form-label">
                            Stok Tersedia
                        </label>

                        <input
                            type="text"
                            id="stok_tersedia"
                            class="form-control bg-light"
                            readonly
                            value="-">

                    </div>

                    <div class="col-md-4">

                        <label class="form-label">
                            Satuan
                        </label>

                        <input
                            type="text"
                            name="satuan"
                            id="satuan"
                            class="form-control bg-light"
                            readonly>

                    </div>

                    <div class="col-md-4">

                        <label class="form-label">

                            Jumlah Ditanam

                            <span class="text-danger">*</span>

                        </label>

                        <input
                            type="number"
                            name="jumlah_ditanam"
                            id="jumlah_ditanam"
                            class="form-control"
                            min="0.01"
                            step="0.01"
                            required>

                        <small
                            class="text-muted"
                            id="stockHelp">

                            Jumlah tidak boleh melebihi stok.

                        </small>

                    </div>

                    <div class="col-md-6">

                        <label class="form-label">
                            Jumlah Hidup
                        </label>

                        <input
                            type="number"
                            name="jumlah_hidup"
                            class="form-control"
                            min="0"
                            step="1">

                    </div>

                    <div class="col-md-6">

                        <label class="form-label">
                            Jumlah Mati
                        </label>

                        <input
                            type="number"
                            name="jumlah_mati"
                            class="form-control"
                            min="0"
                            step="1">

                    </div>

                    <div class="col-md-6">

                        <label class="form-label">
                            Tinggi Rata-rata (cm)
                        </label>

                        <input
                            type="number"
                            name="tinggi_rata2_cm"
                            class="form-control"
                            min="0"
                            step="0.01">

                    </div>

                    <div class="col-md-6">

                        <label class="form-label">
                            Diameter Rata-rata (cm)
                        </label>

                        <input
                            type="number"
                            name="diameter_rata2_cm"
                            class="form-control"
                            min="0"
                            step="0.01">

                    </div>

                    <div class="col-12">

                        <label class="form-label">
                            Catatan
                        </label>

                        <textarea
                            name="catatan"
                            class="form-control"
                            rows="4"></textarea>

                    </div>

                </div>

            </div>

            <div class="card-footer d-flex justify-content-between">

                <a
                    href="index.php"
                    class="btn btn-secondary">

                    <i class="bi bi-arrow-left"></i>
                    Kembali

                </a>

                <button
                    type="submit"
                    class="btn btn-primary">

                    <i class="bi bi-save"></i>
                    Simpan

                </button>

            </div>

        </div>

    </form>

</div>

<script>

const bankSelect = document.getElementById('id_bank_benih');
const stokInput = document.getElementById('stok_tersedia');
const satuanInput = document.getElementById('satuan');
const jumlahInput = document.getElementById('jumlah_ditanam');

bankSelect.addEventListener('change', function() {

    const option = this.options[this.selectedIndex];

    if (!option.value) {

        stokInput.value = '-';
        satuanInput.value = '';
        return;

    }

    const stok = parseFloat(option.dataset.stok || 0);
    const satuan = option.dataset.satuan || '';

    stokInput.value = stok.toLocaleString('id-ID', {
        maximumFractionDigits: 2
    });

    satuanInput.value = satuan;

    jumlahInput.max = stok;

});

jumlahInput.addEventListener('input', function() {

    const max = parseFloat(this.max || 0);
    const value = parseFloat(this.value || 0);

    if (max > 0 && value > max) {

        this.setCustomValidity(
            'Jumlah ditanam melebihi stok yang tersedia.'
        );

    } else {

        this.setCustomValidity('');

    }

});

</script>

</body>
</html>