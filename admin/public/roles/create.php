<?php
require_once "../../app/config/database.php";
require_once "../../app/models/Role.php";
require_once '../../app/core/session.php';
require_once "../../app/core/csrf.php";
require_once "../../app/core/auth.php";

secureSessionStart();
checkAuth("non_dashboard");

$roleModel = new Role($pdo);
$kode = $roleModel->generateKode();
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
        $menu = "roles";
        include "../feature/sidebar.php";
        ?>

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1>Tambah Role</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="#">Master Data</a></li>
                                <li class="breadcrumb-item"><a href="#">Data Role</a></li>
                                <li class="breadcrumb-item active">Tambah Role</li>
                            </ol>
                        </div>
                    </div>
                </div><!-- /.container-fluid -->
            </section>

            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">
                    <div class="row">
                        <!-- left column -->
                        <div class="col-md-12">
                            <!-- jquery validation -->
                            <div class="card card-primary">
                                <div class="card-header">
                                    <h3 class="card-title"><small>Silahkan isi form dibawah untuk menambahkan Role baru
                                            yang bertanda <code>*</code> wajib diisi.</small></h3>
                                </div>
                                <!-- /.card-header -->
                                <!-- form start -->
                                <form id="quickForm" method="POST" action="store.php">
                                    <?= csrfField() ?>
                                    <div class="card-body">
                                        <div class="form-group">
                                            <label for="kode">Kode Role<code>*</code></label>
                                            <input type="text" name="kode" value="<?= $kode ?>" class="form-control" id="kode"
                                                placeholder="Masukkan kode role">
                                        </div>
                                        <div class="form-group">
                                            <label for="nama">Nama Role<code>*</code></label>
                                            <input type="text" name="nama" class="form-control" id="nama"
                                                placeholder="Masukkan nama role">
                                        </div>
                                        <div class="form-group">
                                            <label for="deskripsi">Deskripsi</label>
                                            <textarea type="text" name="deskripsi" class="form-control" id="deskripsi"
                                                placeholder="Masukkan deskripsi"></textarea>
                                        </div>
                                        <div class="form-group">
                                            <label for="urutan">Urutan<code>*</code></label>
                                            <input type="number" name="urutan" class="form-control" id="urutan"
                                                placeholder="Masukkan urutan">
                                        </div>
                                        <div class="form-group">
                                            <label for="urutan">Status Aktif<code>*</code></label>
                                            <div class="form-group row">
                                                <div class="col-sm-1">
                                                    <div class="custom-control custom-radio">
                                                        <input class="custom-control-input" type="radio" id="radioAktif"
                                                            name="is_active" checked>
                                                        <label for="radioAktif" class="custom-control-label">Aktif</label>
                                                    </div>
                                                </div>
                                                <div class="col-sm-1">
                                                    <div class="custom-control custom-radio">
                                                        <input class="custom-control-input" type="radio" id="radioNonaktif"
                                                            name="is_active">
                                                        <label for="radioNonaktif" class="custom-control-label">Nonaktif</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- /.card-body -->
                                    <div class="card-footer">
                                        <button type="submit" class="btn btn-primary">Submit</button>
                                    </div>
                                </form>
                            </div>
                            <!-- /.card -->
                        </div>
                        <!--/.col (left) -->
                        <!-- right column -->
                        <div class="col-md-6">

                        </div>
                        <!--/.col (right) -->
                    </div>
                    <!-- /.row -->
                </div><!-- /.container-fluid -->
            </section>
            <!-- /.content -->
        </div>
        <!-- /.content-wrapper -->
        <?php include "../feature/footer.php" ?>
    </div>
    <!-- ./wrapper -->

    <!-- jQuery -->
    <script src="../assets/adminlte/plugins/jquery/jquery.min.js"></script>
    <!-- Bootstrap 4 -->
    <script src="../assets/adminlte/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- jquery-validation -->
    <script src="../assets/adminlte/plugins/jquery-validation/jquery.validate.min.js"></script>
    <script src="../assets/adminlte/plugins/jquery-validation/additional-methods.min.js"></script>
    <!-- AdminLTE App -->
    <script src="../assets/adminlte/dist/js/adminlte.min.js"></script>
    <!-- AdminLTE for demo purposes -->
    <script src="../assets/adminlte/dist/js/demo.js"></script>
    <!-- Page specific script -->
    <script>
        $(function () {
            // $.validator.setDefaults({
            //     submitHandler: function () {
            //         alert("Form successful submitted!");
            //     }
            // });
            $('#quickForm').validate({
                rules: {
                    kode: {
                        required: true,
                    },
                    nama: {
                        required: true,
                    },
                    urutan: {
                        required: true,
                        // minlength: 5
                    },
                },
                messages: {
                    kode: {
                        required: "Silahkan masukkan Kode Role"
                    },
                    nama: {
                        required: "Silahkan masukkan Nama Role"
                    },
                    urutan: {
                        required: "Silahkan masukkan Urutan",
                        // minlength: "Your password must be at least 5 characters long"
                    },
                },
                errorElement: 'span',
                errorPlacement: function (error, element) {
                    error.addClass('invalid-feedback');
                    element.closest('.form-group').append(error);
                },
                highlight: function (element, errorClass, validClass) {
                    $(element).addClass('is-invalid');
                },
                unhighlight: function (element, errorClass, validClass) {
                    $(element).removeClass('is-invalid');
                }
            });
        });
    </script>
</body>

</html>