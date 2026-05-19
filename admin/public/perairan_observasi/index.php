<?php

require_once '../../app/config/database.php';
require_once '../../app/controllers/PerairanObservasiController.php';
require_once '../../app/core/session.php';
require_once '../../app/core/csrf.php';
require_once "../../app/core/auth.php";
require_once '../../app/helpers/escape.php';

secureSessionStart();
checkAuth("non_dashboard");

$controller = new PerairanObservasiController($pdo);
$perairanObservasis = $controller->index();
$tanahs = $controller->getTanah();
$warnaAirs = $controller->getWarnaAir();
$jenisPalungs = $controller->getJenisPalung();
$kecepatanAlirans = $controller->getKecepatanAliran();

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
        $menu = "perairan_observasi";
        include "../feature/sidebar.php";
        ?>

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1>Data Perairan Observasi</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="#">Master Data</a></li>
                                <li class="breadcrumb-item active">Data Perairan Observasi</li>
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
                                    <h3 class="card-title col-9">Berikut adalah list dari Data Perairan Observasi</h3>
                                    <button class="col-3 btn btn-block btn-success" data-toggle="modal"
                                        data-target="#modalTambah">
                                        Tambah Perairan Observasi
                                    </button>
                                </div>
                                <!-- /.card-header -->
                                <div class="card-body">
                                    <table id="example1" class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th>Nama Lahan</th>
                                                <th>Periode Pengecekan</th>
                                                <th>Warna Air</th>
                                                <th>Jenis Palung</th>
                                                <th>Kecepatan Aliran</th>
                                                <th>Kedalaman (cm)</th>
                                                <th>Lebar (m)</th>
                                                <th>Debit (lps)</th>
                                                <th>PH</th>
                                                <th>Kekeruhan (NTU)</th>
                                                <th>Catatan</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($perairanObservasis as $PerairanObservasi): ?>
                                                <tr>
                                                    <td><?= htmlspecialchars($PerairanObservasi['nama_lahan']) ?></td>
                                                    <td><?= htmlspecialchars($PerairanObservasi['periode_pengecekan']) ?></td>
                                                    <td><?= htmlspecialchars($PerairanObservasi['warna_air']) ?></td>
                                                    <td><?= htmlspecialchars($PerairanObservasi['jenis_palung']) ?></td>
                                                    <td><?= htmlspecialchars($PerairanObservasi['kecepatan_aliran']) ?></td>
                                                    <td><?= htmlspecialchars($PerairanObservasi['kedalaman_cm']) ?></td>
                                                    <td><?= htmlspecialchars($PerairanObservasi['lebar_m']) ?></td>
                                                    <td><?= htmlspecialchars($PerairanObservasi['debit_lps']) ?></td>
                                                    <td><?= htmlspecialchars($PerairanObservasi['ph']) ?></td>
                                                    <td><?= htmlspecialchars($PerairanObservasi['kekeruhan_ntu']) ?></td>
                                                    <td><?= htmlspecialchars($PerairanObservasi['catatan']) ?></td>
                                                    <td class="row">
                                                        <div class="col">
                                                            <button class="btn btn-block btn-info btn-edit"
                                                                data-id="<?= $PerairanObservasi['id'] ?>"
                                                                data-id_tanah="<?= htmlspecialchars($PerairanObservasi['id_tanah']) ?>"
                                                                data-periode_pengecekan="<?= htmlspecialchars($PerairanObservasi['periode_pengecekan']) ?>"
                                                                data-id_warna_air="<?= htmlspecialchars($PerairanObservasi['id_warna_air']) ?>"
                                                                data-id_jenis_palung="<?= htmlspecialchars($PerairanObservasi['id_jenis_palung']) ?>"
                                                                data-id_kecepatan_aliran="<?= htmlspecialchars($PerairanObservasi['id_kecepatan_aliran']) ?>"
                                                                data-kedalaman_cm="<?= htmlspecialchars($PerairanObservasi['kedalaman_cm']) ?>"
                                                                data-lebar_m="<?= htmlspecialchars($PerairanObservasi['lebar_m']) ?>"
                                                                data-debit_lps="<?= htmlspecialchars($PerairanObservasi['debit_lps']) ?>"
                                                                data-ph="<?= htmlspecialchars($PerairanObservasi['ph']) ?>"
                                                                data-kekeruhan_ntu="<?= htmlspecialchars($PerairanObservasi['kekeruhan_ntu']) ?>"
                                                                data-catatan="<?= htmlspecialchars($PerairanObservasi['catatan']) ?>"
                                                                data-toggle="modal" data-target="#modalEdit">
                                                                <i class="fas fa-edit"></i> Edit
                                                            </button>
                                                        </div>

                                                        <form method="POST" action="delete.php" class="form-delete col">
                                                            <?= csrfField() ?>
                                                            <input type="hidden" name="id" value="<?= $PerairanObservasi['id'] ?>">
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
                                                <th>Nama Lahan</th>
                                                <th>Periode Pengecekan</th>
                                                <th>Warna Air</th>
                                                <th>Jenis Palung</th>
                                                <th>Kecepatan Aliran</th>
                                                <th>Kedalaman (cm)</th>
                                                <th>Lebar (m)</th>
                                                <th>Debit (lps)</th>
                                                <th>PH</th>
                                                <th>Kekeruhan (NTU)</th>
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
                            <h4 class="modal-title">Tambah Perairan Observasi</h4>
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                        </div>
                        <form id="formTambah" method="POST" action="store.php">
                            <?= csrfField() ?>
                            <div class="modal-body">
                                <div class="form-group">
                                    <label for="id_tanah">Lahan<code>*</code></label>
                                    <select name="id_tanah" class="form-control" id="id_tanah">
                                        <option value="">-- Pilih Lahan --</option>
                                        <?php foreach ($tanahs as $la): ?>
                                            <option value="<?= $la['id'] ?>">
                                                <?= htmlspecialchars($la['nama_lahan']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="periode_pengecekan">Periode Pengecekan<code>*</code></label>
                                    <input type="date" name="periode_pengecekan" class="form-control" id="periode_pengecekan">
                                </div>
                                <div class="form-group">
                                    <label for="id_warna_air">Warna Air<code>*</code></label>
                                    <select name="id_warna_air" class="form-control" id="id_warna_air">
                                        <option value="">-- Pilih Warna Air --</option>
                                        <?php foreach ($warnaAirs as $wa): ?>
                                            <option value="<?= $wa['id'] ?>">
                                                <?= htmlspecialchars($wa['nama']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="id_jenis_palung">Jenis Palung<code>*</code></label>
                                    <select name="id_jenis_palung" class="form-control" id="id_jenis_palung">
                                        <option value="">-- Pilih Jenis Palung --</option>
                                        <?php foreach ($jenisPalungs as $jp): ?>
                                            <option value="<?= $jp['id'] ?>">
                                                <?= htmlspecialchars($jp['nama']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="id_kecepatan_aliran">Kecepatan Aliran<code>*</code></label>
                                    <select name="id_kecepatan_aliran" class="form-control" id="id_kecepatan_aliran">
                                        <option value="">-- Pilih Kecepatan Aliran --</option>
                                        <?php foreach ($kecepatanAlirans as $ka): ?>
                                            <option value="<?= $ka['id'] ?>">
                                                <?= htmlspecialchars($ka['nama']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="kedalaman_cm">Kedalaman (cm)<code>*</code></label>
                                    <input type="number" name="kedalaman_cm" class="form-control" id="kedalaman_cm"
                                        placeholder="Masukkan Kedalaman (cm)" min="0" max="99999999" step="0.01">
                                </div>
                                <div class="form-group">
                                    <label for="lebar_m">Lebar (m)<code>*</code></label>
                                    <input type="number" name="lebar_m" class="form-control" id="lebar_m"
                                        placeholder="Masukkan Lebar (m)" min="0" max="99999999" step="0.01">
                                </div>
                                <div class="form-group">
                                    <label for="debit_lps">Debit (l/s)<code>*</code></label>
                                    <input type="number" name="debit_lps" class="form-control" id="debit_lps"
                                        placeholder="Masukkan Debit (l/s)" min="0" max="99999999" step="0.01">
                                </div>
                                <div class="form-group">
                                    <label for="ph">PH<code>*</code></label>
                                    <input type="number" name="ph" class="form-control" id="ph"
                                        placeholder="Masukkan PH" min="0" max="9999" step="0.01">
                                </div>
                                <div class="form-group">
                                    <label for="kekeruhan_ntu">Kekeruhan (NTU)<code>*</code></label>
                                    <input type="number" name="kekeruhan_ntu" class="form-control" id="kekeruhan_ntu"
                                        placeholder="Masukkan Kekeruhan (NTU)" min="0" max="99999999" step="0.01">
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
                            <h4 class="modal-title">Edit Perairan Observasi</h4>
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                        </div>

                        <form id="formEdit" method="POST" action="update.php">
                            <?= csrfField() ?>
                            <input type="hidden" name="id" id="edit_id">
                            <div class="modal-body">
                                <div class="form-group">
                                    <label for="id_tanah">Lahan<code>*</code></label>
                                    <select name="id_tanah" class="form-control" id="edit_id_tanah">
                                        <option value="">-- Pilih Lahan --</option>
                                        <?php foreach ($tanahs as $la): ?>
                                            <option value="<?= $la['id'] ?>">
                                                <?= htmlspecialchars($la['nama_lahan']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="periode_pengecekan">Periode Pengecekan<code>*</code></label>
                                    <input type="date" name="periode_pengecekan" class="form-control" id="edit_periode_pengecekan">
                                </div>
                                <div class="form-group">
                                    <label for="id_warna_air">Warna Air<code>*</code></label>
                                    <select name="id_warna_air" class="form-control" id="edit_id_warna_air">
                                        <option value="">-- Pilih Warna Air --</option>
                                        <?php foreach ($warnaAirs as $wa): ?>
                                            <option value="<?= $wa['id'] ?>">
                                                <?= htmlspecialchars($wa['nama']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="id_jenis_palung">Jenis Palung<code>*</code></label>
                                    <select name="id_jenis_palung" class="form-control" id="edit_id_jenis_palung">
                                        <option value="">-- Pilih Jenis Palung --</option>
                                        <?php foreach ($jenisPalungs as $jp): ?>
                                            <option value="<?= $jp['id'] ?>">
                                                <?= htmlspecialchars($jp['nama']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="id_kecepatan_aliran">Kecepatan Aliran<code>*</code></label>
                                    <select name="id_kecepatan_aliran" class="form-control" id="edit_id_kecepatan_aliran">
                                        <option value="">-- Pilih Kecepatan Aliran --</option>
                                        <?php foreach ($kecepatanAlirans as $ka): ?>
                                            <option value="<?= $ka['id'] ?>">
                                                <?= htmlspecialchars($ka['nama']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="kedalaman_cm">Kedalaman (cm)<code>*</code></label>
                                    <input type="number" name="kedalaman_cm" class="form-control" id="edit_kedalaman_cm"
                                        placeholder="Masukkan Kedalaman (cm)" min="0" max="100" step="0.01">
                                </div>
                                <div class="form-group">
                                    <label for="lebar_m">Lebar (m)<code>*</code></label>
                                    <input type="number" name="lebar_m" class="form-control" id="edit_lebar_m"
                                        placeholder="Masukkan Lebar (m)" min="0" max="99999999" step="0.01">
                                </div>
                                <div class="form-group">
                                    <label for="debit_lps">Debit (l/s)<code>*</code></label>
                                    <input type="number" name="debit_lps" class="form-control" id="edit_debit_lps"
                                        placeholder="Masukkan Debit (l/s)" min="0" max="99999999" step="0.01">
                                </div>
                                <div class="form-group">
                                    <label for="ph">PH<code>*</code></label>
                                    <input type="number" name="ph" class="form-control" id="edit_ph"
                                        placeholder="Masukkan PH" min="0" max="9999" step="0.01">
                                </div>
                                <div class="form-group">
                                    <label for="kekeruhan_ntu">Kekeruhan (NTU)<code>*</code></label>
                                    <input type="number" name="kekeruhan_ntu" class="form-control" id="edit_kekeruhan_ntu"
                                        placeholder="Masukkan Kekeruhan (NTU)" min="0" max="99999999" step="0.01">
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
                        id_tanah: {
                            required: true
                        },
                        periode_pengecekan: {
                            required: true
                        },
                        id_warna_air: {
                            required: true
                        },
                        id_jenis_palung: {
                            required: true
                        },
                        id_kecepatan_aliran: {
                            required: true
                        },
                        kedalaman_cm: {
                            required: true
                        },
                        lebar_m: {
                            required: true
                        },
                        debit_lps: {
                            required: true
                        },
                        ph: {
                            required: true
                        },
                        kekeruhan_ntu: {
                            required: true
                        },
                        catatan: {
                            required: true
                        }
                    },
                    messages: {
                        id_tanah: {
                            required: "Silahkan pilih Tanah"
                        },
                        periode_pengecekan: {
                            required: "Silahkan masukkan Periode Pengecekan"
                        },
                        id_warna_air: {
                            required: "Silahkan pilih Warna Air"
                        },
                        id_jenis_palung : {
                            required: "Silahkan pilih Jenis Palung"
                        },
                        id_kecepatan_aliran: {
                            required: "Silahkan pilih Kecepatan Aliran"
                        },
                        kedalaman_cm: {
                            required: "Silahkan masukkan Kedalaman (cm)"
                        },
                        lebar_m: {
                            required: "Silahkan masukkan Lebar (m)"
                        },
                        debit_lps: {
                            required: "Silahkan masukkan Debit (lps)"
                        },
                        ph: {
                            required: "Silahkan masukkan pH"
                        },
                        kekeruhan_ntu: {
                            required: "Silahkan masukkan Kekeruhan (NTU)"
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
            let id_tanah = $(this).data("id_tanah");
            let periode_pengecekan = $(this).data("periode_pengecekan");
            let id_warna_air = $(this).data("id_warna_air");
            let id_jenis_palung = $(this).data("id_jenis_palung");
            let id_kecepatan_aliran = $(this).data("id_kecepatan_aliran");
            let kedalaman_cm = $(this).data("kedalaman_cm");
            let lebar_m = $(this).data("lebar_m");
            let debit_lps = $(this).data("debit_lps");
            let ph = $(this).data("ph");
            let kekeruhan_ntu = $(this).data("kekeruhan_ntu");
            let catatan = $(this).data("catatan");

            $("#edit_id").val(id);
            $("#edit_id_tanah").val(id_tanah);
            $("#edit_periode_pengecekan").val(periode_pengecekan);
            $("#edit_id_warna_air").val(id_warna_air);
            $("#edit_id_jenis_palung").val(id_jenis_palung);
            $("#edit_id_kecepatan_aliran").val(id_kecepatan_aliran);
            $("#edit_kedalaman_cm").val(kedalaman_cm);
            $("#edit_lebar_m").val(lebar_m);
            $("#edit_debit_lps").val(debit_lps);
            $("#edit_ph").val(ph);
            $("#edit_kekeruhan_ntu").val(kekeruhan_ntu);
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
            showToast("Data Perairan Observasi berhasil ditambahkan", "created");
        }
        if (success === "updated") {
            showToast("Data Perairan Observasi berhasil diperbarui", "updated");
        }
        if (success === "deleted") {
            showToast("Data Perairan Observasi berhasil dihapus", "deleted");
        }
    </script>

    <script>
        document.getElementById('periode_pengecekan').addEventListener('focus', function () {
            this.showPicker();
        });
        document.getElementById('edit_periode_pengecekan').addEventListener('focus', function () {
            this.showPicker();
        });
    </script>
</body>

</html>