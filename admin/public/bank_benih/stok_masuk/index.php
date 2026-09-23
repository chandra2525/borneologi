<?php

secureSessionStart();

require_once '../../../config/database.php';
require_once '../../../app/controllers/MutasiStokBenihController.php';
require_once '../../../app/helpers/csrf.php';
require_once '../../../app/helpers/permission.php';

Permission::authorize($pdo, 'Stok Masuk', 'create');

$controller = new MutasiStokBenihController($pdo);

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
    return e($_SESSION['old_stok_masuk'][$key] ?? $default);
}

$errors = $_SESSION['stok_masuk_errors'] ?? [];

unset(
    $_SESSION['old_stok_masuk'],
    $_SESSION['stok_masuk_errors']
);

?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Stok Masuk</title>

    <!-- Sesuaikan dengan template AdminLTE Anda -->
    <link rel="stylesheet" href="../assets/adminlte.min.css">

    <link rel="stylesheet" href="../assets/fontawesome-free/css/all.min.css">

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</head>

<body>

    <div class="container-fluid mt-4">

        <div class="card">

            <div class="card-header">

                <h3 class="card-title">
                    <i class="fas fa-plus-circle mr-2"></i>
                    Stok Masuk
                </h3>

            </div>

            <form action="store.php" method="POST" id="formStokMasuk">

                <?= csrfField(); ?>

                <div class="card-body">

                    <div class="alert alert-info">

                        <i class="fas fa-info-circle mr-1"></i>

                        Stok masuk akan menambah
                        <strong>Stok Saat Ini</strong>
                        dan otomatis tercatat pada
                        <strong>Riwayat Mutasi</strong>.

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
                                          oldValue(
                                              'id_bank_benih'
                                          ) == $bank['id']
                                      ) ? 'selected' : ''; ?>>

                                    <?= e($bank['nomor_aksesi']); ?>
                                    -
                                    <?= e($bank['nama_lokal']); ?>

                                </option>

                            <?php endforeach; ?>

                        </select>

                    </div>

                    <!-- INFO STOK -->

                    <div id="infoStok" class="alert alert-secondary d-none">

                        <div class="row">

                            <div class="col-md-6">

                                <strong>
                                    Stok Saat Ini
                                </strong>

                                <div id="stokSekarang" class="h5 mt-1">
                                    -
                                </div>

                            </div>

                            <div class="col-md-6">

                                <strong>
                                    Satuan
                                </strong>

                                <div id="satuanStok" class="h5 mt-1">
                                    -
                                </div>

                            </div>

                        </div>

                    </div>

                    <!-- JUMLAH -->

                    <div class="form-group">

                        <label for="jumlah">

                            Jumlah Stok Masuk

                            <span class="text-danger">*</span>

                        </label>

                        <input type="number" name="jumlah" id="jumlah" class="form-control" min="0.01" step="0.01"
                            value="<?= oldValue('jumlah'); ?>" required>

                        <small class="form-text text-muted">

                            Jumlah harus lebih besar dari 0
                            dan menggunakan satuan yang sama
                            dengan Bank Benih.

                        </small>

                    </div>

                    <!-- TANGGAL -->

                    <div class="form-group">

                        <label for="tanggal_mutasi">

                            Tanggal Mutasi

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
                            Keterangan
                        </label>

                        <textarea name="alasan" id="alasan" class="form-control" rows="4" maxlength="1000"
                            placeholder="Contoh: Penerimaan benih hasil pengumpulan..."><?= oldValue('alasan'); ?></textarea>

                    </div>

                    <!-- SUMBER -->

                    <input type="hidden" name="sumber" value="STOK_MASUK">

                    <div id="previewStok" class="alert alert-success d-none">

                        <strong>
                            Preview Stok
                        </strong>

                        <div class="mt-2">

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

                    <button type="submit" class="btn btn-primary">

                        <i class="fas fa-save mr-1"></i>
                        Simpan Stok Masuk

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

        const satuanStok =
            document.getElementById('satuanStok');

        const previewStok =
            document.getElementById('previewStok');

        const previewSebelum =
            document.getElementById('previewSebelum');

        const previewSesudah =
            document.getElementById('previewSesudah');

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

            satuanStok.textContent =
                satuan;

            const jumlah =
                parseFloat(
                    jumlahInput.value || 0
                );

            if (jumlah > 0) {

                const sesudah =
                    stok + jumlah;

                previewStok.classList.remove('d-none');

                previewSebelum.textContent =
                    stok + ' ' + satuan;

                previewSesudah.textContent =
                    sesudah + ' ' + satuan;

            } else {

                previewStok.classList.add('d-none');

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

        updatePreview();


        document
            .getElementById('formStokMasuk')
            .addEventListener(
                'submit',
                function (event) {

                    const option =
                        bankSelect.options[
                        bankSelect.selectedIndex
                        ];

                    const jumlah =
                        parseFloat(
                            jumlahInput.value || 0
                        );

                    if (!option.value) {

                        event.preventDefault();

                        Swal.fire({
                            icon: 'warning',
                            title: 'Bank Benih belum dipilih',
                            text: 'Silakan pilih Bank Benih terlebih dahulu.'
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
                            text: 'Jumlah stok harus lebih besar dari 0.'
                        });

                        return;
                    }

                    event.preventDefault();

                    Swal.fire({
                        title: 'Simpan Stok Masuk?',
                        text: 'Stok akan bertambah dan transaksi dicatat ke riwayat mutasi.',
                        icon: 'question',
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