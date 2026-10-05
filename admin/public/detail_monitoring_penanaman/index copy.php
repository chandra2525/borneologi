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

$model = new DetailMonitoringPenanaman($pdo);

$data = $model->getAll();

function formatJumlah($value)
{
    if ($value === null || $value === '') {
        return '-';
    }

    return rtrim(rtrim(number_format((float) $value, 2, ',', '.'), '0'), ',');
}

$success = $_GET['success'] ?? '';
$error   = $_GET['error'] ?? '';

?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Detail Monitoring Penanaman</title>

    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">

    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>

<div class="container-fluid py-4">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h4 class="mb-1">
                <i class="bi bi-tree"></i>
                Detail Monitoring Penanaman
            </h4>

            <small class="text-muted">
                Data detail tanaman pada setiap kegiatan monitoring penanaman.
            </small>
        </div>

        <a href="create.php" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i>
            Tambah Detail
        </a>

    </div>

    <div class="card shadow-sm">

        <div class="card-body">

            <div class="table-responsive">

                <table class="table table-bordered table-hover align-middle">

                    <thead class="table-light">

                    <tr>

                        <th width="60">No</th>

                        <th>Kode Monitoring</th>

                        <th>No. Aksesi</th>

                        <th>Bank Benih</th>

                        <th>Jumlah Ditanam</th>

                        <th>Hidup</th>

                        <th>Mati</th>

                        <th>Rata-rata Tinggi</th>

                        <th>Rata-rata Diameter</th>

                        <th width="180">Aksi</th>

                    </tr>

                    </thead>

                    <tbody>

                    <?php if (empty($data)): ?>

                        <tr>

                            <td colspan="10" class="text-center text-muted py-4">

                                Belum ada data detail monitoring.

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
                                        <?= e($row['kode_monitoring']) ?>
                                    </span>
                                </td>

                                <td>
                                    <?= e($row['nomor_aksesi']) ?>
                                </td>

                                <td>

                                    <strong>
                                        <?= e($row['nama_bank_benih']) ?>
                                    </strong>

                                    <?php if (!empty($row['nama_ilmiah'])): ?>

                                        <br>

                                        <small class="text-muted fst-italic">
                                            <?= e($row['nama_ilmiah']) ?>
                                        </small>

                                    <?php endif; ?>

                                </td>

                                <td>

                                    <?= formatJumlah($row['jumlah_ditanam']) ?>

                                    <small class="text-muted">
                                        <?= e($row['satuan']) ?>
                                    </small>

                                </td>

                                <td>
                                    <?= $row['jumlah_hidup'] !== null
                                        ? e($row['jumlah_hidup'])
                                        : '-' ?>
                                </td>

                                <td>
                                    <?= $row['jumlah_mati'] !== null
                                        ? e($row['jumlah_mati'])
                                        : '-' ?>
                                </td>

                                <td>
                                    <?= $row['tinggi_rata2_cm'] !== null
                                        ? e($row['tinggi_rata2_cm']) . ' cm'
                                        : '-' ?>
                                </td>

                                <td>
                                    <?= $row['diameter_rata2_cm'] !== null
                                        ? e($row['diameter_rata2_cm']) . ' cm'
                                        : '-' ?>
                                </td>

                                <td>

                                    <div class="btn-group">

                                        <a
                                            href="detail.php?id=<?= (int) $row['id'] ?>"
                                            class="btn btn-sm btn-info text-white"
                                            title="Detail">

                                            <i class="bi bi-eye"></i>

                                        </a>

                                        <a
                                            href="edit.php?id=<?= (int) $row['id'] ?>"
                                            class="btn btn-sm btn-warning"
                                            title="Edit">

                                            <i class="bi bi-pencil"></i>

                                        </a>

                                        <button
                                            type="button"
                                            class="btn btn-sm btn-danger btn-delete"
                                            data-id="<?= (int) $row['id'] ?>"
                                            title="Hapus">

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

</form>

<script>

document.querySelectorAll('.btn-delete').forEach(function(button) {

    button.addEventListener('click', function() {

        const id = this.dataset.id;

        Swal.fire({
            title: 'Hapus data?',
            text: 'Jumlah yang sudah ditanam akan dikembalikan ke stok Bank Benih.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, hapus',
            cancelButtonText: 'Batal'
        }).then((result) => {

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
    text: 'Detail monitoring berhasil ditambahkan.',
    timer: 1800,
    showConfirmButton: false
});

<?php elseif ($success === 'updated'): ?>

Swal.fire({
    icon: 'success',
    title: 'Berhasil',
    text: 'Detail monitoring berhasil diperbarui.',
    timer: 1800,
    showConfirmButton: false
});

<?php elseif ($success === 'deleted'): ?>

Swal.fire({
    icon: 'success',
    title: 'Berhasil',
    text: 'Detail monitoring berhasil dihapus dan stok dikembalikan.',
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

</body>
</html>