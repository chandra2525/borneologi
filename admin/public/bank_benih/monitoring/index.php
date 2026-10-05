<?php

require_once '../../../app/config/database.php';
require_once '../../../app/models/MonitoringPenanaman.php';
require_once '../../../app/core/session.php';
require_once '../../../app/core/csrf.php';
require_once '../../../app/core/auth.php';
require_once '../../../app/core/Permission.php';
require_once '../../../app/helpers/escape.php';

error_reporting(E_ALL);
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');

secureSessionStart();

Permission::authorize($pdo, 'Monitoring Penanaman', 'view');

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
    header(
        'Location: ../index.php?error=' .
        urlencode('Bank Benih tidak ditemukan.')
    );
    exit;
}

$data = $model->getAll($id_bank_benih);

$success = $_GET['success'] ?? '';
$error = $_GET['error'] ?? '';

?>
<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Monitoring Penanaman</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</head>

<body>

    <div class="container-fluid py-4">

        <div class="d-flex justify-content-between align-items-start mb-4">

            <div>

                <div class="mb-2">

                    <a href="../index.php" class="text-decoration-none">

                        <i class="bi bi-arrow-left"></i>
                        Bank Benih

                    </a>

                </div>

                <h4 class="mb-1">

                    <i class="bi bi-tree"></i>

                    Monitoring Penanaman

                </h4>

                <div class="text-muted">

                    Bank Benih:

                    <strong>
                        <?= e($bankBenih['nomor_aksesi']) ?>
                        -
                        <?= e($bankBenih['nama_lokal']) ?>
                    </strong>

                </div>

            </div>

            <a href="create.php?id_bank_benih=<?= $id_bank_benih ?>" class="btn btn-primary">

                <i class="bi bi-plus-circle"></i>

                Tambah Monitoring

            </a>

        </div>

        <div class="card shadow-sm mb-4">

            <div class="card-body">

                <div class="row">

                    <div class="col-md-3">

                        <small class="text-muted">
                            Nomor Aksesi
                        </small>

                        <div class="fw-semibold">
                            <?= e($bankBenih['nomor_aksesi']) ?>
                        </div>

                    </div>

                    <div class="col-md-3">

                        <small class="text-muted">
                            Nama Lokal
                        </small>

                        <div class="fw-semibold">
                            <?= e($bankBenih['nama_lokal']) ?>
                        </div>

                    </div>

                    <div class="col-md-3">

                        <small class="text-muted">
                            Nama Ilmiah
                        </small>

                        <div class="fw-semibold fst-italic">
                            <?= e($bankBenih['nama_ilmiah'] ?: '-') ?>
                        </div>

                    </div>

                    <div class="col-md-3">

                        <small class="text-muted">
                            Stok Saat Ini
                        </small>

                        <div class="fw-semibold">

                            <?= number_format(
                                (float) $bankBenih['jumlah_stok'],
                                2,
                                ',',
                                '.'
                            ) ?>

                            <?= e($bankBenih['satuan_stok']) ?>

                        </div>

                    </div>

                </div>

            </div>

        </div>

        <div class="card shadow-sm">

            <div class="card-body">

                <div class="table-responsive">

                    <table class="table table-bordered table-hover align-middle">

                        <thead class="table-light">

                            <tr>

                                <th width="60">No</th>

                                <th>Kode Monitoring</th>

                                <th>Periode</th>

                                <th>Tanggal Tanam</th>

                                <th>Tanggal Monitoring</th>

                                <th>Luas Tanam</th>

                                <th>Status</th>

                                <th>Detail Benih</th>

                                <th width="150">Aksi</th>

                            </tr>

                        </thead>

                        <tbody>

                            <?php if (empty($data)): ?>

                                <tr>

                                    <td colspan="9" class="text-center text-muted py-4">

                                        Belum ada monitoring penanaman
                                        yang menggunakan Bank Benih ini.

                                    </td>

                                </tr>

                            <?php else: ?>

                                <?php foreach ($data as $i => $row): ?>

                                    <tr>

                                        <td>
                                            <?= $i + 1 ?>
                                        </td>

                                        <td>

                                            <span class="badge bg-primary">

                                                <?= e(
                                                    $row['kode_monitoring']
                                                ) ?>

                                            </span>

                                        </td>

                                        <td>
                                            <?= e($row['periode_pengecekan']) ?>
                                        </td>

                                        <td>
                                            <?= e($row['tanggal_tanam']) ?>
                                        </td>

                                        <td>
                                            <?= e(
                                                $row['tanggal_monitoring']
                                                ?: '-'
                                            ) ?>
                                        </td>

                                        <td>

                                            <?= number_format(
                                                (float) $row['luas_tanam_ha'],
                                                2,
                                                ',',
                                                '.'
                                            ) ?>

                                            ha

                                        </td>

                                        <td>

                                            <?= e(
                                                $row['nama_status_monitoring']
                                                ?: '-'
                                            ) ?>

                                        </td>

                                        <td>

                                            <span class="badge bg-secondary">

                                                <?= (int) 
                                                    $row['jumlah_detail'] ?>

                                            </span>

                                        </td>

                                        <td>

                                            <div class="btn-group">

                                                <a href="detail.php?id=<?= (int) $row['id'] ?>&id_bank_benih=<?= $id_bank_benih ?>"
                                                    class="btn btn-sm btn-info text-white" title="Detail">

                                                    <i class="bi bi-eye"></i>

                                                </a>

                                                <a href="edit.php?id=<?= (int) $row['id'] ?>&id_bank_benih=<?= $id_bank_benih ?>"
                                                    class="btn btn-sm btn-warning" title="Edit">

                                                    <i class="bi bi-pencil"></i>

                                                </a>

                                                <button type="button" class="btn btn-sm btn-danger btn-delete"
                                                    data-id="<?= (int) $row['id'] ?>">

                                                    <i class="bi bi-trash"></i>

                                                </button>

                                            </div>

                                        </td>

                                    </tr>

                                <?php endforeach; ?>

                            <?php endif; ?>

                        </tbody>

                    </table>

                </div>

            </div>

        </div>

    </div>

    <form id="deleteForm" method="POST" action="delete.php">

        <?= csrfField() ?>

        <input type="hidden" name="id" id="deleteId">

        <input type="hidden" name="id_bank_benih" value="<?= $id_bank_benih ?>">

    </form>

    <script>

        document.querySelectorAll('.btn-delete').forEach(function (button) {

            button.addEventListener('click', function () {

                const id = this.dataset.id;

                Swal.fire({

                    title: 'Hapus monitoring?',

                    text: 'Data monitoring akan dihapus secara soft delete.',

                    icon: 'warning',

                    showCancelButton: true,

                    confirmButtonText: 'Ya, hapus',

                    cancelButtonText: 'Batal'

                }).then(function (result) {

                    if (result.isConfirmed) {

                        document.getElementById('deleteId').value = id;

                        document.getElementById('deleteForm').submit();

                    }

                });

            });

        });

        <?php if ($success === 'created'): ?>

            Swal.fire({
                icon: 'success',
                title: 'Berhasil',
                text: 'Monitoring penanaman berhasil ditambahkan.',
                timer: 1800,
                showConfirmButton: false
            });

        <?php elseif ($success === 'updated'): ?>

            Swal.fire({
                icon: 'success',
                title: 'Berhasil',
                text: 'Monitoring penanaman berhasil diperbarui.',
                timer: 1800,
                showConfirmButton: false
            });

        <?php elseif ($success === 'deleted'): ?>

            Swal.fire({
                icon: 'success',
                title: 'Berhasil',
                text: 'Monitoring penanaman berhasil dihapus.',
                timer: 1800,
                showConfirmButton: false
            });

        <?php endif; ?>

        <?php if ($error): ?>

            Swal.fire({
                icon: 'error',
                title: 'Gagal',
                text: <?= json_encode($error) ?>
            });

        <?php endif; ?>

    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js">
    </script>

</body>

</html>