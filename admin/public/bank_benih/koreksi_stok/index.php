<?php

secureSessionStart();

require_once '../../../config/database.php';
require_once '../../../app/controllers/MutasiStokBenihController.php';
require_once '../../../app/helpers/csrf.php';
require_once '../../../app/helpers/permission.php';

Permission::authorize(
    $pdo,
    'Koreksi Stok',
    'create'
);

$controller =
    new MutasiStokBenihController($pdo);

$bankBenihList = $pdo->query("
    SELECT
        id,
        nomor_aksesi,
        nama_lokal,
        nama_ilmiah,
        jumlah_stok,
        satuan_stok
    FROM t_bank_benih
    WHERE deleted_at IS NULL
      AND is_active = 1
    ORDER BY nama_lokal ASC
")->fetchAll(PDO::FETCH_ASSOC);

function e($value)
{
    return htmlspecialchars(
        (string) ($value ?? ''),
        ENT_QUOTES,
        'UTF-8'
    );
}

function oldValue($key, $default = '')
{
    return e(
        $_SESSION['old_koreksi_stok'][$key]
        ?? $default
    );
}

$errors =
    $_SESSION['koreksi_stok_errors']
    ?? [];

unset(
    $_SESSION['old_koreksi_stok'],
    $_SESSION['koreksi_stok_errors']
);

?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Koreksi Stok</title>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</head>

<body>

    <div class="container-fluid mt-4">

        <div class="card">

            <div class="card-header">

                <h3 class="card-title">

                    <i class="fas fa-edit mr-2"></i>

                    Koreksi Stok

                </h3>

            </div>

            <form action="store.php" method="POST" id="formKoreksi">

                <?= csrfField(); ?>

                <div class="card-body">

                    <div class="alert alert-warning">

                        <i class="fas fa-exclamation-triangle mr-1"></i>

                        Koreksi digunakan untuk menyesuaikan
                        stok sistem dengan kondisi fisik.
                        <strong>Alasan koreksi wajib diisi.</strong>

                    </div>

                    <!-- BANK BENIH -->

                    <div class="form-group">

                        <label for="id_bank_benih">

                            Bank Benih
                            <span class="text-danger">*</span>

                        </label>

                        <select name="id_bank_benih" id="id_bank_benih" class="form-control" required>

                            <option value="">
                                -- Pilih Bank Benih --
                            </option>

                            <?php foreach ($bankBenihList as $bank): ?>

                                <option value="<?= (int) $bank['id']; ?>" data-stok="<?= e($bank['jumlah_stok']); ?>"
                                    data-satuan="<?= e($bank['satuan_stok']); ?>" <?= (
                                          oldValue('id_bank_benih')
                                          == $bank['id']
                                      )
                                          ? 'selected'
                                          : ''; ?>>

                                    <?= e($bank['nomor_aksesi']); ?>
                                    -
                                    <?= e($bank['nama_lokal']); ?>

                                </option>

                            <?php endforeach; ?>

                        </select>

                    </div>

                    <!-- STOK SEKARANG -->

                    <div id="infoStok" class="alert alert-secondary d-none">

                        <strong>
                            Stok Sistem Saat Ini
                        </strong>

                        <div id="stokSekarang" class="h5 mt-1">
                            -
                        </div>

                    </div>

                    <!-- ARAH -->

                    <div class="form-group">

                        <label>

                            Jenis Koreksi
                            <span class="text-danger">*</span>

                        </label>

                        <div>

                            <div class="custom-control custom-radio">

                                <input type="radio" id="koreksiTambah" name="arah_koreksi" value="TAMBAH"
                                    class="custom-control-input" <?= (
                                        oldValue(
                                            'arah_koreksi'
                                        ) === 'TAMBAH'
                                    )
                                        ? 'checked'
                                        : ''; ?>>

                                <label class="custom-control-label" for="koreksiTambah">

                                    Tambah Stok

                                </label>

                            </div>

                            <div class="custom-control custom-radio mt-2">

                                <input type="radio" id="koreksiKurang" name="arah_koreksi" value="KURANG"
                                    class="custom-control-input" <?= (
                                        oldValue(
                                            'arah_koreksi'
                                        ) === 'KURANG'
                                    )
                                        ? 'checked'
                                        : ''; ?>>

                                <label class="custom-control-label" for="koreksiKurang">

                                    Kurangi Stok

                                </label>

                            </div>

                        </div>

                    </div>

                    <!-- JUMLAH -->

                    <div class="form-group">

                        <label for="jumlah">

                            Jumlah Koreksi
                            <span class="text-danger">*</span>

                        </label>

                        <input type="number" name="jumlah" id="jumlah" class="form-control" min="0.01" step="0.01"
                            value="<?= oldValue('jumlah'); ?>" required>

                    </div>

                    <!-- TANGGAL -->

                    <div class="form-group">

                        <label for="tanggal_mutasi">

                            Tanggal Koreksi
                            <span class="text-danger">*</span>

                        </label>

                        <input type="datetime-local" name="tanggal_mutasi" id="tanggal_mutasi" class="form-control"
                            value="<?= oldValue(
                                'tanggal_mutasi',
                                date('Y-m-d\TH:i')
                            ); ?>" required>

                    </div>

                    <!-- ALASAN -->

                    <div class="form-group">

                        <label for="alasan">

                            Alasan Koreksi
                            <span class="text-danger">*</span>

                        </label>

                        <textarea name="alasan" id="alasan" class="form-control" rows="4" maxlength="1000" required
                            placeholder="Contoh: Hasil stock opname menunjukkan stok fisik lebih sedikit..."><?= oldValue('alasan'); ?></textarea>

                        <small class="form-text text-muted">

                            Jelaskan alasan perubahan stok secara jelas
                            agar dapat ditelusuri pada riwayat mutasi.

                        </small>

                    </div>

                    <input type="hidden" name="sumber" value="KOREKSI_MANUAL">

                    <!-- PREVIEW -->

                    <div id="previewStok" class="alert alert-info d-none">

                        <strong>
                            Preview Stok Setelah Koreksi
                        </strong>

                        <div class="h5 mt-2">

                            <span id="previewSebelum">
                                -
                            </span>

                            <i class="fas fa-arrow-right mx-2"></i>

                            <strong id="previewSesudah">
                                -
                            </strong>

                        </div>

                    </div>

                </div>

                <div class="card-footer">

                    <a href="../index.php" class="btn btn-secondary">

                        <i class="fas fa-arrow-left mr-1"></i>
                        Kembali

                    </a>

                    <button type="submit" class="btn btn-warning">

                        <i class="fas fa-save mr-1"></i>
                        Simpan Koreksi

                    </button>

                </div>

            </form>

        </div>

    </div>

    <script>

        const bankSelect =
            document.getElementById('id_bank_benih');

        const jumlahInput =
            document.getElementById('jumlah');

        const infoStok =
            document.getElementById('infoStok');

        const stokSekarang =
            document.getElementById('stokSekarang');

        const previewStok =
            document.getElementById('previewStok');

        const previewSebelum =
            document.getElementById('previewSebelum');

        const previewSesudah =
            document.getElementById('previewSesudah');

        function getArah() {
            const selected =
                document.querySelector(
                    'input[name="arah_koreksi"]:checked'
                );

            return selected
                ? selected.value
                : '';
        }

        function updatePreview() {
            const option =
                bankSelect.options[
                bankSelect.selectedIndex
                ];

            if (
                !option ||
                !option.value
            ) {

                infoStok.classList.add('d-none');
                previewStok.classList.add('d-none');

                return;
            }

            const stok =
                parseFloat(
                    option.dataset.stok || 0
                );

            const satuan =
                option.dataset.satuan || '';

            infoStok.classList.remove('d-none');

            stokSekarang.textContent =
                stok + ' ' + satuan;

            const jumlah =
                parseFloat(
                    jumlahInput.value || 0
                );

            const arah =
                getArah();

            if (
                jumlah <= 0 ||
                !arah
            ) {

                previewStok.classList.add('d-none');

                return;
            }

            let sesudah;

            if (arah === 'TAMBAH') {

                sesudah =
                    stok + jumlah;

            } else {

                sesudah =
                    stok - jumlah;

            }

            previewStok.classList.remove('d-none');

            previewSebelum.textContent =
                stok + ' ' + satuan;

            previewSesudah.textContent =
                sesudah + ' ' + satuan;

            if (sesudah < 0) {

                previewSesudah.classList
                    .add('text-danger');

            } else {

                previewSesudah.classList
                    .remove('text-danger');

            }
        }

        bankSelect.addEventListener(
            'change',
            updatePreview
        );

        jumlahInput.addEventListener(
            'input',
            updatePreview
        );

        document
            .querySelectorAll(
                'input[name="arah_koreksi"]'
            )
            .forEach(function (input) {

                input.addEventListener(
                    'change',
                    updatePreview
                );

            });

        updatePreview();


        document
            .getElementById('formKoreksi')
            .addEventListener(
                'submit',
                function (event) {

                    const arah =
                        getArah();

                    const jumlah =
                        parseFloat(
                            jumlahInput.value || 0
                        );

                    const alasan =
                        document
                            .getElementById('alasan')
                            .value
                            .trim();

                    if (!bankSelect.value) {

                        event.preventDefault();

                        Swal.fire({
                            icon: 'warning',
                            title: 'Bank Benih belum dipilih'
                        });

                        return;
                    }

                    if (!arah) {

                        event.preventDefault();

                        Swal.fire({
                            icon: 'warning',
                            title: 'Jenis koreksi belum dipilih'
                        });

                        return;
                    }

                    if (
                        !Number.isFinite(jumlah) ||
                        jumlah <= 0
                    ) {

                        event.preventDefault();

                        Swal.fire({
                            icon: 'warning',
                            title: 'Jumlah tidak valid',
                            text: 'Jumlah koreksi harus lebih besar dari 0.'
                        });

                        return;
                    }

                    if (!alasan) {

                        event.preventDefault();

                        Swal.fire({
                            icon: 'warning',
                            title: 'Alasan wajib diisi'
                        });

                        return;
                    }

                    event.preventDefault();

                    Swal.fire({
                        title: 'Simpan Koreksi?',
                        text: 'Perubahan stok akan dicatat sebagai mutasi dan tidak menghapus histori sebelumnya.',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Ya, Simpan',
                        cancelButtonText: 'Batal'
                    }).then((result) => {

                        if (result.isConfirmed) {
                            this.submit();
                        }

                    });

                }
            );

    </script>

    <?php if (!empty($errors)): ?>

        <script>

            Swal.fire({
                icon: 'error',
                title: 'Gagal menyimpan',
                html: <?= json_encode(
                    implode('<br>', array_map(
                        'htmlspecialchars',
                        $errors
                    ))
                ); ?>
            });

        </script>

    <?php endif; ?>

</body>

</html>