<?php

require_once '../../app/config/database.php';
require_once '../../app/core/session.php';
require_once '../../app/core/csrf.php';
require_once "../../app/core/auth.php";
require_once '../../app/helpers/escape.php';
require_once "../../app/core/permission.php";
require_once '../../app/models/ActivityLog.php';

secureSessionStart();
checkAuth("non_dashboard");

// $controller = new UserController($pdo);
// $users = $controller->index();
// $roles = $controller->getRoles();
$activityLog = new ActivityLog($pdo);
$logs = $activityLog->getAll(100);
Permission::authorize($pdo, 'Log History', 'view');

/**
 * Format data JSON agar tampil rapi di modal.
 */
function formatLogJson($json)
{
    if (empty($json)) {
        return 'Tidak ada';
    }

    // Jika data berupa JSON string
    if (is_string($json)) {
        $decoded = json_decode($json, true);

        if (json_last_error() === JSON_ERROR_NONE) {
            return json_encode(
                $decoded,
                JSON_PRETTY_PRINT |
                    JSON_UNESCAPED_UNICODE |
                    JSON_UNESCAPED_SLASHES
            );
        }

        // Jika bukan JSON valid, tampilkan apa adanya
        return $json;
    }

    // Jika data sudah berupa array
    if (is_array($json)) {
        return json_encode(
            $json,
            JSON_PRETTY_PRINT |
                JSON_UNESCAPED_UNICODE |
                JSON_UNESCAPED_SLASHES
        );
    }

    return (string) $json;
}

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

    <style>
        .json-viewer {
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 5px;
            padding: 15px;
            font-family: Consolas, Monaco, "Courier New", monospace;
            font-size: 14px;
            line-height: 1.6;
            white-space: pre-wrap;
            word-break: break-word;
            max-height: 500px;
            overflow-y: auto;
        }
    </style>
</head>

<body class="hold-transition sidebar-mini">
    <div class="wrapper">
        <!-- Navbar -->
        <?php include "../feature/navbar.php" ?>
        <!-- /.navbar -->

        <!-- Main Sidebar Container -->
        <?php
        $menu = "activity_log";
        include "../feature/sidebar.php";
        ?>

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1>Data Log History</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="#">Master Data</a></li>
                                <li class="breadcrumb-item active">Data Log History</li>
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
                                    <h3 class="card-title col-9">
                                        Berikut adalah list dari Data Log History
                                    </h3>
                                </div>
                                <!-- /.card-header -->
                                <div class="card-body">
                                    <table id="example1" class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th class="text-center">No</th>
                                                <th>Nama Lengkap</th>
                                                <th>Username</th>
                                                <th>Aktivitas</th>
                                                <th>Menu</th>
                                                <th>Target ID</th>
                                                <th>Status</th>
                                                <th>IP Address</th>
                                                <th>Waktu</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $no = 1; ?>
                                            <?php foreach ($logs as $index => $log): ?>
                                                <tr>
                                                    <td class="text-center"><?= $no++ ?></td>
                                                    <td><?= htmlspecialchars($log['nama_lengkap'], ENT_QUOTES, 'UTF-8') ?></td>
                                                    <td><?= htmlspecialchars($log['username'], ENT_QUOTES, 'UTF-8') ?></td>
                                                    <!-- <td><?= htmlspecialchars($log['activity'], ENT_QUOTES, 'UTF-8') ?></td> -->
                                                    <td><?php if ($log['activity'] === 'LOGIN_FAILED'): ?>
                                                            <span class="badge badge-warning">LOGIN_FAILED</span>
                                                        <?php elseif ($log['activity'] === 'LOGIN'): ?>
                                                            <span class="badge badge-primary">LOGIN</span>
                                                        <?php elseif ($log['activity'] === 'LOGOUT'): ?>
                                                            <span class="badge badge-secondary">LOGOUT</span>
                                                        <?php elseif ($log['activity'] === 'CREATE'): ?>
                                                            <span class="badge badge-success">CREATE</span>
                                                        <?php elseif ($log['activity'] === 'UPDATE'): ?>
                                                            <span class="badge badge-info">UPDATE</span>
                                                        <?php elseif ($log['activity'] === 'DELETE'): ?>
                                                            <span class="badge badge-danger">DELETE</span>
                                                        <?php else: ?>
                                                            <span class="badge badge-danger">ERROR</span>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td><?= htmlspecialchars($log['menu_name'] ?? '-', ENT_QUOTES, 'UTF-8') ?></td>
                                                    <td><?= htmlspecialchars($log['target_id'] ?? '-', ENT_QUOTES, 'UTF-8') ?></td>
                                                    <td><?php if ($log['status'] === 'SUCCESS'): ?>
                                                            <span class="badge badge-success">SUCCESS</span>
                                                        <?php else: ?>
                                                            <span class="badge badge-danger">FAILED</span>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td> <?= htmlspecialchars($log['ip_address'] ?? '-', ENT_QUOTES, 'UTF-8') ?></td>
                                                    <td><?= htmlspecialchars($log['created_at'], ENT_QUOTES, 'UTF-8') ?></td>
                                                    <td>
                                                        <div class="col">
                                                            <button class="btn btn-block btn-info btn-edit"
                                                                type="button"
                                                                data-toggle="modal"
                                                                data-target="#detailModal<?= $log['id'] ?>">
                                                                <i class="fas fa-eye"></i>
                                                                Detail
                                                            </button>
                                                        </div>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th class="text-center">No</th>
                                                <th>Nama Lengkap</th>
                                                <th>Username</th>
                                                <th>Aktivitas</th>
                                                <th>Menu</th>
                                                <th>Target ID</th>
                                                <th>Status</th>
                                                <th>IP Address</th>
                                                <th>Waktu</th>
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

            <?php foreach ($logs as $log): ?>
                <div class="modal fade" id="detailModal<?= $log['id'] ?>" tabindex="-1" role="dialog">
                    <div class="modal-dialog modal-xl" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title">Detail Log History</h4>
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                            </div>
                            <div class="modal-body">
                                <table class="table table-sm">
                                    <tr>
                                        <th width="180">
                                            Nama Lengkap
                                        </th>
                                        <td>
                                            <?= htmlspecialchars($log['nama_lengkap'], ENT_QUOTES, 'UTF-8') ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>
                                            Username
                                        </th>
                                        <td>
                                            <?= htmlspecialchars($log['username'], ENT_QUOTES, 'UTF-8') ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>
                                            Aktivitas
                                        </th>
                                        <td><?php if ($log['activity'] === 'LOGIN_FAILED'): ?>
                                                <span class="badge badge-warning">LOGIN_FAILED</span>
                                            <?php elseif ($log['activity'] === 'LOGIN'): ?>
                                                <span class="badge badge-primary">LOGIN</span>
                                            <?php elseif ($log['activity'] === 'LOGOUT'): ?>
                                                <span class="badge badge-secondary">LOGOUT</span>
                                            <?php elseif ($log['activity'] === 'CREATE'): ?>
                                                <span class="badge badge-success">CREATE</span>
                                            <?php elseif ($log['activity'] === 'UPDATE'): ?>
                                                <span class="badge badge-info">UPDATE</span>
                                            <?php elseif ($log['activity'] === 'DELETE'): ?>
                                                <span class="badge badge-danger">DELETE</span>
                                            <?php else: ?>
                                                <span class="badge badge-danger">ERROR</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>
                                            Menu
                                        </th>
                                        <td>
                                            <?= htmlspecialchars($log['menu_name'] ?? '-', ENT_QUOTES, 'UTF-8') ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>
                                            Target ID
                                        </th>
                                        <td>
                                            <?= htmlspecialchars($log['target_id'] ?? '-', ENT_QUOTES, 'UTF-8') ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>
                                            Status
                                        </th>
                                        <td><?php if ($log['status'] === 'SUCCESS'): ?>
                                                <span class="badge badge-success">SUCCESS</span>
                                            <?php else: ?>
                                                <span class="badge badge-danger">FAILED</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>
                                            Resource
                                        </th>
                                        <td>
                                            <?= htmlspecialchars($log['resource'] ?? '-', ENT_QUOTES, 'UTF-8') ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>
                                            Keterangan
                                        </th>
                                        <td>
                                            <?= htmlspecialchars($log['description'] ?? '-', ENT_QUOTES, 'UTF-8') ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>
                                            IP Address
                                        </th>
                                        <td>
                                            <?= htmlspecialchars($log['ip_address'] ?? '-', ENT_QUOTES, 'UTF-8') ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>
                                            Request ID
                                        </th>
                                        <td>
                                            <code>
                                                <?= htmlspecialchars($log['request_id'] ?? '-', ENT_QUOTES, 'UTF-8') ?>
                                            </code>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>
                                            Waktu
                                        </th>
                                        <td>
                                            <?= htmlspecialchars($log['created_at'], ENT_QUOTES, 'UTF-8') ?>
                                        </td>
                                    </tr>
                                </table>

                                <hr>

                                <div class="row">
                                    <div class="col-md-6">
                                        <h6>Data Sebelum Perubahan</h6>
                                        <pre class="json-viewer"><?= htmlspecialchars(formatLogJson($log['old_data'] ?? null), ENT_QUOTES, 'UTF-8') ?></pre>
                                    </div>
                                    <div class="col-md-6">
                                        <h6>Data Sesudah Perubahan</h6>
                                        <pre class="json-viewer"><?= htmlspecialchars(formatLogJson($log['new_data'] ?? null), ENT_QUOTES, 'UTF-8') ?></pre>
                                    </div>
                                </div>

                                <h6>User Agent</h6>
                                <pre class="json-viewer"><?= htmlspecialchars($log['user_agent'] ?? '-', ENT_QUOTES, 'UTF-8') ?></pre>
                            </div>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                                    Tutup
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
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
        $(function() {
            $("#example1").DataTable({
                "responsive": true,
                "lengthChange": false,
                "autoWidth": false,
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

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</body>

</html>