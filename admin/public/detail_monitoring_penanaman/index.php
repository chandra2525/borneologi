<?php

require_once '../../app/config/database.php';
require_once '../../app/controllers/DetailMonitoringPenanamanController.php';
require_once '../../app/core/session.php';
require_once '../../app/core/csrf.php';
require_once "../../app/core/auth.php";
require_once '../../app/helpers/escape.php';

secureSessionStart();
checkAuth("non_dashboard");

$controller = new DetailMonitoringPenanamanController($pdo);
$detailMonitoringPenanamans = $controller->index();
$monitoringPenanamans = $controller->getMonitoringPenanaman();
$bankBenihs = $controller->getBankBenih();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Borneologi</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="../assets/adminlte/plugins/fontawesome-free/css/all.min.css">
    <!-- DataTables -->
    <link rel="stylesheet" href="../assets/adminlte/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="../assets/adminlte/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
    <link rel="stylesheet" href="../assets/adminlte/plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="../assets/adminlte/dist/css/adminlte.min.css">
</head>

<body class="hold-transition sidebar-mini">
    <div class="wrapper">
        <!-- Navbar -->
        <?php include "../feature/navbar.php" ?>
        <!-- /.navbar -->

        <!-- Main Sidebar Container -->
        <?php
        $menu = "detail_monitoring_penanaman";
        include "../feature/sidebar.php";
        ?>

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1>Data Detail Monitoring Penanaman</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="#">Master Data</a></li>
                                <li class="breadcrumb-item active">Data Detail Monitoring Penanaman</li>
                            </ol>
                        </div>
                    </div>
                </div><!-- /.container-fluid -->
            </section>

            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header row">
                                    <h3 class="card-title col-9">Berikut adalah list dari Data Detail Monitoring Penanaman</h3>
                                    <button class="col-3 btn btn-block btn-success" data-toggle="modal"
                                        data-target="#modalTambah">
                                        Tambah Detail Monitoring Penanaman
                                    </button>
                                </div>
                                <!-- /.card-header -->
                                <div class="card-body">
                                    <table id="example1" class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th>Kode Monitoring</th>
                                                <th>Nama Bank Benih</th>
                                                <th>Jumlah Ditanam</th>
                                                <th>Satuan</th>
                                                <th>Jumlah Hidup</th>
                                                <th>Jumlah Mati</th>
                                                <th>Tinggi Rata-rata (cm)</th>
                                                <th>Diameter Rata-rata (cm)</th>
                                                <th>Catatan</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($detailMonitoringPenanamans as $detailMonitoringPenanaman): ?>
                                                <tr>
                                                    <td><?= htmlspecialchars($detailMonitoringPenanaman['kode_monitoring']) ?></td>
                                                    <td><?= htmlspecialchars($detailMonitoringPenanaman['nama_bank_benih']) ?></td>
                                                    <td><?= htmlspecialchars($detailMonitoringPenanaman['jumlah_ditanam']) ?></td>
                                                    <td><?= htmlspecialchars($detailMonitoringPenanaman['satuan']) ?></td>
                                                    <td><?= htmlspecialchars($detailMonitoringPenanaman['jumlah_hidup']) ?></td>
                                                    <td><?= htmlspecialchars($detailMonitoringPenanaman['jumlah_mati']) ?></td>
                                                    <td><?= htmlspecialchars($detailMonitoringPenanaman['tinggi_rata2_cm']) ?></td>
                                                    <td><?= htmlspecialchars($detailMonitoringPenanaman['diameter_rata2_cm']) ?></td>
                                                    <td><?= htmlspecialchars($detailMonitoringPenanaman['catatan']) ?></td>
                                                    <td class="row">
                                                        <div class="col">
                                                            <button class="btn btn-block btn-info btn-edit"
                                                                data-id="<?= $detailMonitoringPenanaman['id'] ?>"
                                                                data-id_monitoring="<?= htmlspecialchars($detailMonitoringPenanaman['id_monitoring']) ?>"
                                                                data-id_bank_benih="<?= htmlspecialchars($detailMonitoringPenanaman['id_bank_benih']) ?>"
                                                                data-jumlah_ditanam="<?= htmlspecialchars($detailMonitoringPenanaman['jumlah_ditanam']) ?>"
                                                                data-satuan="<?= htmlspecialchars($detailMonitoringPenanaman['satuan']) ?>"
                                                                data-jumlah_hidup="<?= htmlspecialchars($detailMonitoringPenanaman['jumlah_hidup']) ?>"
                                                                data-jumlah_mati="<?= htmlspecialchars($detailMonitoringPenanaman['jumlah_mati']) ?>"
                                                                data-tinggi_rata2_cm="<?= htmlspecialchars($detailMonitoringPenanaman['tinggi_rata2_cm']) ?>"
                                                                data-diameter_rata2_cm="<?= htmlspecialchars($detailMonitoringPenanaman['diameter_rata2_cm']) ?>"
                                                                data-catatan="<?= htmlspecialchars($detailMonitoringPenanaman['catatan']) ?>"
                                                                data-toggle="modal" data-target="#modalEdit">
                                                                <i class="fas fa-edit"></i> Edit
                                                            </button>
                                                        </div>

                                                        <form method="POST" action="delete.php" class="form-delete col">
                                                            <?= csrfField() ?>
                                                            <input type="hidden" name="id" value="<?= $detailMonitoringPenanaman['id'] ?>">
                                                            <button type="submit" class="btn btn-block btn-danger">
                                                                <i class="fas fa-trash"></i> Hapus
                                                            </button>
                                                        </form>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th>Kode Monitoring</th>
                                                <th>Nama Bank Benih</th>
                                                <th>Jumlah Ditanam</th>
                                                <th>Satuan</th>
                                                <th>Jumlah Hidup</th>
                                                <th>Jumlah Mati</th>
                                                <th>Tinggi Rata-rata (cm)</th>
                                                <th>Diameter Rata-rata (cm)</th>
                                                <th>Catatan</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                                <!-- /.card-body -->
                            </div>
                            <!-- /.card -->
                        </div>
                        <!-- /.col -->
                    </div>
                    <!-- /.row -->
                </div>
                <!-- /.container-fluid -->
            </section>

            <div class="modal fade" id="modalTambah">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">Tambah Detail Monitoring Penanaman</h4>
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                        </div>
                        <form id="formTambah" method="POST" action="store.php">
                            <?= csrfField() ?>
                            <div class="modal-body">
                                <div class="form-group">
                                    <label for="id_monitoring">Kode Monitoring<code>*</code></label>
                                    <select name="id_monitoring" class="form-control" id="id_monitoring">
                                        <option value="">-- Pilih Kode Monitoring --</option>
                                        <?php foreach ($monitoringPenanamans as $km): ?>
                                            <option value="<?= $km['id'] ?>">
                                                <?= htmlspecialchars($km['kode_monitoring']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="id_bank_benih">Bank Benih<code>*</code></label>
                                    <select name="id_bank_benih" class="form-control" id="id_bank_benih">
                                        <option value="">-- Pilih Bank Benih --</option>
                                        <?php foreach ($bankBenihs as $bb): ?>
                                            <option value="<?= $bb['id'] ?>">
                                                <?= htmlspecialchars($bb['nama_lokal']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="jumlah_ditanam">Jumlah Ditanam<code>*</code></label>
                                    <input type="number" name="jumlah_ditanam" class="form-control" id="jumlah_ditanam" placeholder="Masukkan Jumlah Ditanam" min="0">
                                </div>
                                <div class="form-group">
                                    <label for="satuan">Satuan<code>*</code></label>
                                    <select name="satuan" class="form-control" id="satuan">
                                        <option value="">-- Pilih Satuan --</option>
                                        <option value="butir">Butir</option>
                                        <option value="gram">Gram</option>
                                        <option value="kg">Kilogram</option>
                                        <option value="paket">Paket</option>
                                        <option value="bibit">Bibit</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="jumlah_hidup">Jumlah Hidup<code>*</code></label>
                                    <input type="number" name="jumlah_hidup" class="form-control" id="jumlah_hidup" placeholder="Masukkan Jumlah Hidup" min="0">
                                </div>
                                <div class="form-group">
                                    <label for="jumlah_mati">Jumlah Mati<code>*</code></label>
                                    <input type="number" name="jumlah_mati" class="form-control" id="jumlah_mati" placeholder="Masukkan Jumlah Mati" min="0">
                                </div>
                                <div class="form-group">
                                    <label for="tinggi_rata2_cm">Tinggi Rata-rata (cm)<code>*</code></label>
                                    <input type="number" name="tinggi_rata2_cm" class="form-control" id="tinggi_rata2_cm"
                                        placeholder="Masukkan Tinggi Rata-rata (cm)" min="0">
                                </div>
                                <div class="form-group">
                                    <label for="diameter_rata2_cm">Diameter Rata-rata (cm)<code>*</code></label>
                                    <input type="number" name="diameter_rata2_cm" class="form-control" id="diameter_rata2_cm"
                                        placeholder="Masukkan Diameter Rata-rata (cm)" min="0">
                                </div>
                                <div class="form-group">
                                    <label for="catatan">Catatan<code>*</code></label>
                                    <textarea name="catatan" class="form-control" id="catatan"
                                        placeholder="Masukkan Catatan"></textarea>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-primary">Simpan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="modalEdit">
                <div class="modal-dialog">
                    <div class="modal-content">

                        <div class="modal-header">
                            <h4 class="modal-title">Edit Detail Monitoring Penanaman</h4>
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                        </div>

                        <form id="formEdit" method="POST" action="update.php">
                            <?= csrfField() ?>
                            <input type="hidden" name="id" id="edit_id">
                            <div class="modal-body">
                                <div class="form-group">
                                    <label for="id_monitoring">Kode Monitoring<code>*</code></label>
                                    <select name="id_monitoring" class="form-control" id="edit_id_monitoring">
                                        <option value="">-- Pilih Kode Monitoring --</option>
                                        <?php foreach ($monitoringPenanamans as $km): ?>
                                            <option value="<?= $km['id'] ?>">
                                                <?= htmlspecialchars($km['kode_monitoring']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="id_bank_benih">Bank Benih<code>*</code></label>
                                    <select name="id_bank_benih" class="form-control" id="edit_id_bank_benih">
                                        <option value="">-- Pilih Bank Benih --</option>
                                        <?php foreach ($bankBenihs as $bb): ?>
                                            <option value="<?= $bb['id'] ?>">
                                                <?= htmlspecialchars($bb['nama_lokal']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="jumlah_ditanam">Jumlah Ditanam<code>*</code></label>
                                    <input type="number" name="jumlah_ditanam" class="form-control" id="edit_jumlah_ditanam" placeholder="Masukkan Jumlah Ditanam" min="0">
                                </div>
                                <div class="form-group">
                                    <label for="satuan">Satuan<code>*</code></label>
                                    <select name="satuan" class="form-control" id="edit_satuan">
                                        <option value="">-- Pilih Satuan --</option>
                                        <option value="butir">Butir</option>
                                        <option value="gram">Gram</option>
                                        <option value="kg">Kilogram</option>
                                        <option value="paket">Paket</option>
                                        <option value="bibit">Bibit</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="jumlah_hidup">Jumlah Hidup<code>*</code></label>
                                    <input type="number" name="jumlah_hidup" class="form-control" id="edit_jumlah_hidup" placeholder="Masukkan Jumlah Hidup" min="0">
                                </div>
                                <div class="form-group">
                                    <label for="jumlah_mati">Jumlah Mati<code>*</code></label>
                                    <input type="number" name="jumlah_mati" class="form-control" id="edit_jumlah_mati" placeholder="Masukkan Jumlah Mati" min="0">
                                </div>
                                <div class="form-group">
                                    <label for="tinggi_rata2_cm">Tinggi Rata-rata (cm)<code>*</code></label>
                                    <input type="number" name="tinggi_rata2_cm" class="form-control" id="edit_tinggi_rata2_cm"
                                        placeholder="Masukkan Tinggi Rata-rata (cm)" min="0">
                                </div>
                                <div class="form-group">
                                    <label for="diameter_rata2_cm">Diameter Rata-rata (cm)<code>*</code></label>
                                    <input type="number" name="diameter_rata2_cm" class="form-control" id="edit_diameter_rata2_cm"
                                        placeholder="Masukkan Diameter Rata-rata (cm)" min="0">
                                </div>
                                <div class="form-group">
                                    <label for="catatan">Catatan<code>*</code></label>
                                    <textarea name="catatan" class="form-control" id="edit_catatan"
                                        placeholder="Masukkan Catatan"></textarea>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-primary">Update</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <!-- /.content -->
        </div>
        <!-- /.content-wrapper -->
        <?php include "../feature/footer.php" ?>

        <div aria-live="polite" aria-atomic="true" style="position: fixed; top: 20px; right: 20px; z-index: 9999;">
            <div id="toast-container"></div>
        </div>
    </div>
    <!-- ./wrapper -->

    <!-- jQuery -->
    <script src="../assets/adminlte/plugins/jquery/jquery.min.js"></script>
    <!-- Bootstrap 4 -->
    <script src="../assets/adminlte/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- DataTables  & Plugins -->
    <script src="../assets/adminlte/plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="../assets/adminlte/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
    <script src="../assets/adminlte/plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
    <script src="../assets/adminlte/plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
    <script src="../assets/adminlte/plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
    <script src="../assets/adminlte/plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
    <script src="../assets/adminlte/plugins/jszip/jszip.min.js"></script>
    <script src="../assets/adminlte/plugins/pdfmake/pdfmake.min.js"></script>
    <script src="../assets/adminlte/plugins/pdfmake/vfs_fonts.js"></script>
    <script src="../assets/adminlte/plugins/datatables-buttons/js/buttons.html5.min.js"></script>
    <script src="../assets/adminlte/plugins/datatables-buttons/js/buttons.print.min.js"></script>
    <script src="../assets/adminlte/plugins/datatables-buttons/js/buttons.colVis.min.js"></script>
    <!-- AdminLTE App -->
    <script src="../assets/adminlte/dist/js/adminlte.min.js"></script>
    <!-- AdminLTE for demo purposes -->
    <script src="../assets/adminlte/dist/js/demo.js"></script>
    <!-- jquery-validation -->
    <script src="../assets/adminlte/plugins/jquery-validation/jquery.validate.min.js"></script>
    <script src="../assets/adminlte/plugins/jquery-validation/additional-methods.min.js"></script>
    <!-- Page specific script -->
    <script>
        $(function () {
            $("#example1").DataTable({
                "responsive": true, "lengthChange": false, "autoWidth": false,
                "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
            }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
            $('#example2').DataTable({
                "paging": true,
                "lengthChange": false,
                "searching": false,
                "ordering": true,
                "info": true,
                "autoWidth": false,
                "responsive": true,
            });
        });
    </script>

    <script>
        $(function () {
            function initValidation(formId) {
                $(formId).validate({
                    rules: {
                        id_monitoring: {
                            required: true
                        },
                        id_bank_benih: {
                            required: true
                        },
                        jumlah_ditanam: {
                            required: true
                        },
                        satuan: {
                            required: true
                        },
                        jumlah_hidup: {
                            required: true
                        },
                        jumlah_mati: {
                            required: true
                        },
                        tinggi_rata2_cm: {
                            required: true
                        },
                        diameter_rata2_cm: {
                            required: true
                        },
                        catatan: {
                            required: true
                        }
                    },
                    messages: {
                        id_monitoring: {
                            required: "Silahkan pilih Kode Monitoring"
                        },
                        id_bank_benih: {
                            required: "Silahkan pilih Bank Benih"
                        },
                        jumlah_ditanam: {
                            required: "Silahkan masukkan Jumlah Ditanam"
                        },
                        satuan: {
                            required: "Silahkan pilih Satuan"
                        },
                        jumlah_hidup: {
                            required: "Silahkan masukkan Jumlah Hidup"
                        },
                        jumlah_mati: {
                            required: "Silahkan masukkan Jumlah Mati"
                        },
                        tinggi_rata2_cm: {
                            required: "Silahkan masukkan Tinggi Rata-rata (cm)"
                        },
                        diameter_rata2_cm: {
                            required: "Silahkan masukkan Diameter Rata-rata (cm)"
                        },
                        catatan: {
                            required: "Silahkan masukkan Catatan"
                        }
                    },
                    errorElement: 'span',
                    errorPlacement: function (error, element) {
                        error.addClass('invalid-feedback');
                        element.closest('.form-group').append(error);
                    },
                    highlight: function (element) {
                        $(element).addClass('is-invalid');
                    },
                    unhighlight: function (element) {
                        $(element).removeClass('is-invalid');
                    }
                });
            }
            initValidation("#formTambah");
            initValidation("#formEdit");
        });
    </script>

    <script>
        $(document).on("click", ".btn-edit", function () {
            let id = $(this).data("id");
            let id_monitoring = $(this).data("id_monitoring");
            let id_bank_benih = $(this).data("id_bank_benih");
            let jumlah_ditanam = $(this).data("jumlah_ditanam");
            let satuan = $(this).data("satuan");
            let jumlah_hidup = $(this).data("jumlah_hidup");
            let jumlah_mati = $(this).data("jumlah_mati");
            let tinggi_rata2_cm = $(this).data("tinggi_rata2_cm");
            let diameter_rata2_cm = $(this).data("diameter_rata2_cm");
            let catatan = $(this).data("catatan");

            $("#edit_id").val(id);
            $("#edit_id_monitoring").val(id_monitoring);
            $("#edit_id_bank_benih").val(id_bank_benih);
            $("#edit_jumlah_ditanam").val(jumlah_ditanam);
            $("#edit_satuan").val(satuan);
            $("#edit_jumlah_hidup").val(jumlah_hidup);
            $("#edit_jumlah_mati").val(jumlah_mati);
            $("#edit_tinggi_rata2_cm").val(tinggi_rata2_cm);
            $("#edit_diameter_rata2_cm").val(diameter_rata2_cm);
            $("#edit_catatan").val(catatan);
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(document).on("submit", ".form-delete", function (e) {
            e.preventDefault();
            let form = this;
            Swal.fire({
                title: 'Apakah anda yakin?',
                text: "Data akan dihapus!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    </script>

    <script>
        function showToast(message, type) {
            let bgColor = 'bg-success';
            if (type === 'updated') {
                bgColor = 'bg-info';
            } else if (type === 'deleted') {
                bgColor = 'bg-danger';
            }
            $(document).Toasts('create', {
                class: bgColor,
                title: 'Berhasil',
                body: message,
                delay: 1000
            });
        }
        const urlParams = new URLSearchParams(window.location.search);
        const success = urlParams.get('success');
        if (success === "created") {
            showToast("Data Detail Monitoring Penanaman berhasil ditambahkan", "created");
        }
        if (success === "updated") {
            showToast("Data Detail Monitoring Penanaman berhasil diperbarui", "updated");
        }
        if (success === "deleted") {
            showToast("Data Detail Monitoring Penanaman berhasil dihapus", "deleted");
        }
    </script>

    <script>
        document.getElementById('satuan').addEventListener('focus', function () {
            this.showPicker();
        });
        document.getElementById('edit_tanggal_lahir').addEventListener('focus', function () {
            this.showPicker();
        });
    </script>
</body>

</html>