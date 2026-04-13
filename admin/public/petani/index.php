<?php

require_once '../../app/config/database.php';
require_once '../../app/controllers/PetaniController.php';
require_once '../../app/core/session.php';
require_once '../../app/core/csrf.php';
require_once "../../app/core/auth.php";
require_once '../../app/helpers/escape.php';

secureSessionStart();
checkAuth("non_dashboard");

$controller = new PetaniController($pdo);
$petanis = $controller->index();
$desas = $controller->getDesa();

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
        $menu = "petani";
        include "../feature/sidebar.php";
        ?>

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1>Data Petani</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="#">Master Data</a></li>
                                <li class="breadcrumb-item active">Data Petani</li>
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
                                    <h3 class="card-title col-9">Berikut adalah list dari Data Petani</h3>
                                    <button class="col-3 btn btn-block btn-success" data-toggle="modal"
                                        data-target="#modalTambah">
                                        Tambah Petani
                                    </button>
                                </div>
                                <!-- /.card-header -->
                                <div class="card-body">
                                    <table id="example1" class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th>NIK</th>
                                                <th>No KK</th>
                                                <th>Nama Lengkap</th>
                                                <th>Nama Panggilan</th>
                                                <th>Jenis Kelamin</th>
                                                <th>Tanggal Lahir</th>
                                                <th>Nomor HP</th>
                                                <th>Desa</th>
                                                <th>Alamat</th>
                                                <th>Status Petani</th>
                                                <th>Status Aktif</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($petanis as $petani): ?>
                                                <tr>
                                                    <td><?= htmlspecialchars($petani['nik']) ?></td>
                                                    <td><?= htmlspecialchars($petani['no_kk']) ?></td>
                                                    <td><?= htmlspecialchars($petani['nama_lengkap']) ?></td>
                                                    <td><?= htmlspecialchars($petani['nama_panggilan']) ?></td>
                                                    <td><?= $petani['jenis_kelamin'] == 'L' ? 'Laki-laki' : 'Perempuan' ?>
                                                    </td>
                                                    <td><?= htmlspecialchars($petani['tanggal_lahir']) ?></td>
                                                    <td><?= htmlspecialchars($petani['nomor_hp']) ?></td>
                                                    <td><?= htmlspecialchars($petani['nama_desa']) ?></td>
                                                    <td><?= htmlspecialchars($petani['alamat']) ?></td>
                                                    <td><?= $petani['status_petani'] == 'aktif' ? 'Aktif' : 'Nonaktif' ?>
                                                    </td>
                                                    <td><?= $petani['is_active'] ? 'Aktif' : 'Nonaktif' ?></td>
                                                    <td class="row">
                                                        <div class="col">
                                                            <button class="btn btn-block btn-info btn-edit"
                                                                data-id="<?= $petani['id'] ?>"
                                                                data-nik="<?= htmlspecialchars($petani['nik']) ?>"
                                                                data-no_kk="<?= htmlspecialchars($petani['no_kk']) ?>"
                                                                data-nama_lengkap="<?= htmlspecialchars($petani['nama_lengkap']) ?>"
                                                                data-nama_panggilan="<?= htmlspecialchars($petani['nama_panggilan']) ?>"
                                                                data-jenis_kelamin="<?= htmlspecialchars($petani['jenis_kelamin']) ?>"
                                                                data-tanggal_lahir="<?= htmlspecialchars($petani['tanggal_lahir']) ?>"
                                                                data-nomor_hp="<?= htmlspecialchars($petani['nomor_hp']) ?>"
                                                                data-id_desa="<?= htmlspecialchars($petani['id_desa']) ?>"
                                                                data-alamat="<?= htmlspecialchars($petani['alamat']) ?>"
                                                                data-status_petani="<?= htmlspecialchars($petani['status_petani']) ?>"
                                                                data-status="<?= $petani['is_active'] ?>"
                                                                data-toggle="modal" data-target="#modalEdit">
                                                                <i class="fas fa-edit"></i> Edit
                                                            </button>
                                                        </div>

                                                        <form method="POST" action="delete.php" class="form-delete col">
                                                            <?= csrfField() ?>
                                                            <input type="hidden" name="id" value="<?= $petani['id'] ?>">
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
                                                <th>NIK</th>
                                                <th>No KK</th>
                                                <th>Nama Lengkap</th>
                                                <th>Nama Panggilan</th>
                                                <th>Jenis Kelamin</th>
                                                <th>Tanggal Lahir</th>
                                                <th>Nomor HP</th>
                                                <th>Desa</th>
                                                <th>Alamat</th>
                                                <th>Status Petani</th>
                                                <th>Status Aktif</th>
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
                            <h4 class="modal-title">Tambah Petani</h4>
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                        </div>
                        <form id="formTambah" method="POST" action="store.php">
                            <?= csrfField() ?>
                            <div class="modal-body">
                                <div class="form-group">
                                    <label for="nik">NIK<code>*</code></label>
                                    <input type="number" name="nik" class="form-control" id="nik"
                                        placeholder="Masukkan NIK" minlength="16" maxlength="16">
                                </div>
                                <div class="form-group">
                                    <label for="no_kk">No KK<code>*</code></label>
                                    <input type="number" name="no_kk" class="form-control" id="no_kk"
                                        placeholder="Masukkan No KK" minlength="16" maxlength="16">
                                </div>
                                <div class="form-group">
                                    <label for="nama_lengkap">Nama Lengkap<code>*</code></label>
                                    <input type="text" name="nama_lengkap" class="form-control" id="nama_lengkap"
                                        placeholder="Masukkan Nama Lengkap" maxlength="200">
                                </div>
                                <div class="form-group">
                                    <label for="nama_panggilan">Nama Panggilan<code>*</code></label>
                                    <input type="text" name="nama_panggilan" class="form-control" id="nama_panggilan"
                                        placeholder="Masukkan Nama Panggilan" maxlength="100">
                                </div>
                                <div class="form-group">
                                    <label for="jenis_kelamin">Jenis Kelamin<code>*</code></label>
                                    <select name="jenis_kelamin" class="form-control" id="jenis_kelamin">
                                        <option value="L">Laki-laki</option>
                                        <option value="P">Perempuan</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="tanggal_lahir">Tanggal Lahir<code>*</code></label>
                                    <input type="date" name="tanggal_lahir" class="form-control" id="tanggal_lahir">
                                </div>
                                <div class="form-group">
                                    <label for="nomor_hp">Nomor HP<code>*</code></label>
                                    <input type="number" name="nomor_hp" class="form-control" id="nomor_hp"
                                        placeholder="Masukkan Nomor HP" minlength="10" maxlength="13">
                                </div>
                                <div class="form-group">
                                    <label for="id_desa">Desa<code>*</code></label>
                                    <select name="id_desa" class="form-control" id="id_desa">
                                        <option value="">-- Pilih Desa --</option>
                                        <?php foreach ($desas as $d): ?>
                                            <option value="<?= $d['id'] ?>">
                                                <?= htmlspecialchars($d['nama_desa']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="alamat">Alamat<code>*</code></label>
                                    <textarea name="alamat" class="form-control" id="alamat"
                                        placeholder="Masukkan Alamat"></textarea>
                                </div>
                                <div class="form-group">
                                    <label>Status Petani<code>*</code></label>
                                    <div class="form-group row">
                                        <div class="col-sm-2">
                                            <div class="custom-control custom-radio">
                                                <input class="custom-control-input" type="radio"
                                                    id="add_status_petani_aktif" name="status_petani" value="aktif"
                                                    checked>
                                                <label for="add_status_petani_aktif"
                                                    class="custom-control-label">Aktif</label>
                                            </div>
                                        </div>
                                        <div class="col-sm-2">
                                            <div class="custom-control custom-radio">
                                                <input class="custom-control-input" type="radio"
                                                    id="add_status_petani_nonaktif" name="status_petani"
                                                    value="nonaktif">
                                                <label for="add_status_petani_nonaktif"
                                                    class="custom-control-label">Nonaktif</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>Status Aktif<code>*</code></label>
                                    <div class="form-group row">
                                        <div class="col-sm-2">
                                            <div class="custom-control custom-radio">
                                                <input class="custom-control-input" type="radio" id="add_status_aktif"
                                                    name="is_active" value="1" checked>
                                                <label for="add_status_aktif" class="custom-control-label">Aktif</label>
                                            </div>
                                        </div>
                                        <div class="col-sm-2">
                                            <div class="custom-control custom-radio">
                                                <input class="custom-control-input" type="radio"
                                                    id="add_status_nonaktif" name="is_active" value="0">
                                                <label for="add_status_nonaktif"
                                                    class="custom-control-label">Nonaktif</label>
                                            </div>
                                        </div>
                                    </div>
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
                            <h4 class="modal-title">Edit Petani</h4>
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                        </div>

                        <form id="formEdit" method="POST" action="update.php">
                            <?= csrfField() ?>
                            <input type="hidden" name="id" id="edit_id">
                            <div class="modal-body">
                                <div class="form-group">
                                    <label for="nik">NIK<code>*</code></label>
                                    <input type="text" name="nik" id="edit_nik" class="form-control" minlength="16"
                                        maxlength="16">
                                </div>
                                <div class="form-group">
                                    <label for="no_kk">No KK<code>*</code></label>
                                    <input type="text" name="no_kk" id="edit_no_kk" class="form-control" minlength="16"
                                        maxlength="16">
                                </div>
                                <div class="form-group">
                                    <label for="nama_lengkap">Nama Lengkap<code>*</code></label>
                                    <input type="text" name="nama_lengkap" class="form-control" id="edit_nama_lengkap"
                                        placeholder="Masukkan Nama Lengkap" maxlength="200">
                                </div>
                                <div class="form-group">
                                    <label for="nama_panggilan">Nama Panggilan<code>*</code></label>
                                    <input type="text" name="nama_panggilan" class="form-control"
                                        id="edit_nama_panggilan" placeholder="Masukkan Nama Panggilan" maxlength="100">
                                </div>
                                <div class="form-group">
                                    <label for="jenis_kelamin">Jenis Kelamin<code>*</code></label>
                                    <select name="jenis_kelamin" class="form-control" id="edit_jenis_kelamin">
                                        <option value="L">Laki-laki</option>
                                        <option value="P">Perempuan</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="tanggal_lahir">Tanggal Lahir<code>*</code></label>
                                    <input type="date" name="tanggal_lahir" class="form-control"
                                        id="edit_tanggal_lahir">
                                </div>
                                <div class="form-group">
                                    <label for="nomor_hp">Nomor HP<code>*</code></label>
                                    <input type="number" name="nomor_hp" class="form-control" id="edit_nomor_hp"
                                        placeholder="Masukkan Nomor HP" minlength="10" maxlength="13">
                                </div>
                                <div class="form-group">
                                    <label for="edit_id_desa">Desa<code>*</code></label>
                                    <select name="id_desa" class="form-control" id="edit_id_desa">
                                        <option value="">-- Pilih Desa --</option>
                                        <?php foreach ($desas as $d): ?>
                                            <option value="<?= $d['id'] ?>">
                                                <?= htmlspecialchars($d['nama_desa']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="alamat">Alamat<code>*</code></label>
                                    <textarea name="alamat" class="form-control" id="edit_alamat"
                                        placeholder="Masukkan Alamat"></textarea>
                                </div>
                                <div class="form-group">
                                    <label>Status Petani<code>*</code></label>
                                    <div class="form-group row">
                                        <div class="col-sm-2">
                                            <div class="custom-control custom-radio">
                                                <input class="custom-control-input" type="radio"
                                                    id="edit_status_petani_aktif" name="status_petani" value="aktif"
                                                    checked>
                                                <label for="edit_status_petani_aktif"
                                                    class="custom-control-label">Aktif</label>
                                            </div>
                                        </div>
                                        <div class="col-sm-2">
                                            <div class="custom-control custom-radio">
                                                <input class="custom-control-input" type="radio"
                                                    id="edit_status_petani_nonaktif" name="status_petani"
                                                    value="nonaktif">
                                                <label for="edit_status_petani_nonaktif"
                                                    class="custom-control-label">Nonaktif</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>Status Aktif <code>*</code></label>
                                    <div class="custom-control custom-radio">
                                        <input class="custom-control-input edit_status" type="radio"
                                            id="edit_status_aktif" name="is_active" value="1">
                                        <label for="edit_status_aktif" class="custom-control-label">
                                            Aktif
                                        </label>
                                    </div>
                                    <div class="custom-control custom-radio">
                                        <input class="custom-control-input edit_status" type="radio"
                                            id="edit_status_nonaktif" name="is_active" value="0">
                                        <label for="edit_status_nonaktif" class="custom-control-label">
                                            Nonaktif
                                        </label>
                                    </div>
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
                        nik: {
                            required: true
                        },
                        no_kk: {
                            required: true
                        },
                        nama_lengkap: {
                            required: true
                        },
                        nama_panggilan: {
                            required: true
                        },
                        jenis_kelamin: {
                            required: true
                        },
                        tanggal_lahir: {
                            required: true
                        },
                        nomor_hp: {
                            required: true
                        },
                        id_desa: {
                            required: true
                        },
                        alamat: {
                            required: true
                        },
                        status_petani: {
                            required: true
                        },
                        is_active: {
                            required: true
                        }
                    },
                    messages: {
                        nik: {
                            required: "Silahkan masukkan NIK"
                        },
                        no_kk: {
                            required: "Silahkan masukkan No KK"
                        },
                        nama_lengkap: {
                            required: "Silahkan masukkan Nama Lengkap"
                        },
                        nama_panggilan: {
                            required: "Silahkan masukkan Nama Panggilan"
                        },
                        jenis_kelamin: {
                            required: "Silahkan masukkan Jenis Kelamin"
                        },
                        tanggal_lahir: {
                            required: "Silahkan masukkan Tanggal Lahir"
                        },
                        nomor_hp: {
                            required: "Silahkan masukkan Nomor HP"
                        },
                        id_desa: {
                            required: "Silahkan masukkan Desa"
                        },
                        alamat: {
                            required: "Silahkan masukkan Alamat"
                        },
                        status_petani: {
                            required: "Silahkan pilih Status Petani"
                        },
                        is_active: {
                            required: "Silahkan pilih Status Aktif"
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
            let nik = $(this).data("nik");
            let no_kk = $(this).data("no_kk");
            let nama_lengkap = $(this).data("nama_lengkap");
            let nama_panggilan = $(this).data("nama_panggilan");
            let jenis_kelamin = $(this).data("jenis_kelamin");
            let tanggal_lahir = $(this).data("tanggal_lahir");
            let nomor_hp = $(this).data("nomor_hp");
            let id_desa = $(this).data("id_desa");
            let alamat = $(this).data("alamat");
            let status_petani = $(this).data("status_petani");
            let status = $(this).data("status");

            $("#edit_id").val(id);
            $("#edit_nik").val(nik);
            $("#edit_no_kk").val(no_kk);
            $("#edit_nama_lengkap").val(nama_lengkap);
            $("#edit_nama_panggilan").val(nama_panggilan);
            $("#edit_jenis_kelamin").val(jenis_kelamin);
            $("#edit_tanggal_lahir").val(tanggal_lahir);
            $("#edit_nomor_hp").val(nomor_hp);
            $("#edit_id_desa").val(id_desa);
            $("#edit_alamat").val(alamat);
            $("input[name='status_petani'][value='" + status_petani + "']").prop("checked", true);
            $("input[name='is_active'][value='" + status + "']").prop("checked", true);
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
            showToast("Data Petani berhasil ditambahkan", "created");
        }
        if (success === "updated") {
            showToast("Data Petani berhasil diperbarui", "updated");
        }
        if (success === "deleted") {
            showToast("Data Petani berhasil dihapus", "deleted");
        }
    </script>

    <script>
        document.getElementById('tanggal_lahir').addEventListener('focus', function () {
            this.showPicker();
        });
        document.getElementById('edit_tanggal_lahir').addEventListener('focus', function () {
            this.showPicker();
        });
    </script>
</body>

</html>