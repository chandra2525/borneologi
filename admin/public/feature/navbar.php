<?php
$menu1 = $menu ?? '';
?>

<nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>
        <li class="nav-item d-none d-sm-inline-block">
            <a href="#"
                class="nav-link"
                data-toggle="modal"
                data-target="#changePasswordModal">
                <i class="fas fa-key mr-1"></i>
                Ubah Password
            </a>
        </li>
        <li class="nav-item d-none d-sm-inline-block">
            <form method="POST" action="<?php echo $menu1 != 'dashboard' ? '../logout.php' : 'logout.php'; ?>" class="d-inline">
                <?= csrfField() ?>

                <button type="submit" class="nav-link btn btn-link border-0">
                    <i class="fas fa-sign-out-alt mr-1"></i>
                    Logout
                </button>
            </form>
        </li>
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
        <!-- Notifications Dropdown Menu -->
        <!-- <li class="nav-item dropdown">
            <a class="nav-link" data-toggle="dropdown" href="#">
                <i class="far fa-bell"></i>
                <span class="badge badge-warning navbar-badge">15</span>
            </a>
            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                <span class="dropdown-item dropdown-header">15 Notifications</span>
                <div class="dropdown-divider"></div>
                <a href="#" class="dropdown-item">
                    <i class="fas fa-envelope mr-2"></i> 4 new messages
                    <span class="float-right text-muted text-sm">3 mins</span>
                </a>
                <div class="dropdown-divider"></div>
                <a href="#" class="dropdown-item">
                    <i class="fas fa-users mr-2"></i> 8 friend requests
                    <span class="float-right text-muted text-sm">12 hours</span>
                </a>
                <div class="dropdown-divider"></div>
                <a href="#" class="dropdown-item">
                    <i class="fas fa-file mr-2"></i> 3 new reports
                    <span class="float-right text-muted text-sm">2 days</span>
                </a>
                <div class="dropdown-divider"></div>
                <a href="#" class="dropdown-item dropdown-footer">See All Notifications</a>
            </div>
        </li> -->
        <li class="nav-item">
            <a class="nav-link" data-widget="fullscreen" href="#" role="button">
                <i class="fas fa-expand-arrows-alt"></i>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-widget="control-sidebar" data-controlsidebar-slide="true" href="#" role="button">
                <i class="fas fa-th-large"></i>
            </a>
        </li>
    </ul>
</nav>


<!-- ========================================================= -->
<!-- MODAL UBAH PASSWORD -->
<!-- ========================================================= -->
<div class="modal fade"
    id="changePasswordModal"
    tabindex="-1"
    role="dialog"
    aria-labelledby="changePasswordModalLabel"
    aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered"
        role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"
                    id="changePasswordModalLabel">
                    <i class="fas fa-key mr-2"></i>
                    Ubah Password
                </h5>
                <button type="button"
                    class="close"
                    data-dismiss="modal"
                    aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="changePasswordForm"
                method="POST">
                <?= csrfField() ?>
                <div class="modal-body">
                    <!-- ALERT -->
                    <div id="passwordAlert"
                        class="alert d-none"
                        role="alert">
                        <span id="passwordAlertMessage"></span>
                    </div>

                    <!-- PASSWORD LAMA -->
                    <div class="form-group">
                        <label for="current_password">
                            Password Lama
                        </label>
                        <div class="input-group">
                            <input
                                type="password"
                                name="current_password"
                                id="current_password"
                                class="form-control"
                                placeholder="Masukkan password lama"
                                autocomplete="current-password"
                                required>
                            <div class="input-group-append">
                                <button
                                    type="button"
                                    class="btn btn-outline-secondary"
                                    onclick="togglePassword('current_password', this)">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- PASSWORD BARU -->
                    <div class="form-group">
                        <label for="new_password">
                            Password Baru
                        </label>
                        <div class="input-group">
                            <input
                                type="password"
                                name="new_password"
                                id="new_password"
                                class="form-control"
                                placeholder="Masukkan password baru"
                                minlength="8"
                                autocomplete="new-password"
                                required>
                            <div class="input-group-append">
                                <button
                                    type="button"
                                    class="btn btn-outline-secondary"
                                    onclick="togglePassword('new_password', this)">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                        </div>
                        <small class="form-text text-muted">
                            Password minimal 8 karakter.
                        </small>
                    </div>

                    <!-- KONFIRMASI PASSWORD -->
                    <div class="form-group mb-0">
                        <label for="confirm_password">
                            Konfirmasi Password Baru
                        </label>

                        <div class="input-group">
                            <input
                                type="password"
                                name="confirm_password"
                                id="confirm_password"
                                class="form-control"
                                placeholder="Masukkan kembali password baru"
                                minlength="8"
                                autocomplete="new-password"
                                required>
                            <div class="input-group-append">
                                <button
                                    type="button"
                                    class="btn btn-outline-secondary"
                                    onclick="togglePassword('confirm_password', this)">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button"
                        class="btn btn-secondary"
                        data-dismiss="modal">
                        <i class="fas fa-times mr-1"></i>
                        Batal
                    </button>
                    <button type="submit"
                        id="changePasswordButton"
                        class="btn btn-primary">
                        <i class="fas fa-save mr-1"></i>
                        Ubah Password
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.getElementById('changePasswordForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const form = this;
        const button = document.getElementById('changePasswordButton');

        const alertBox = document.getElementById('passwordAlert');
        const alertMessage = document.getElementById('passwordAlertMessage');

        /*
        |--------------------------------------------------------------------------
        | Reset alert
        |--------------------------------------------------------------------------
        */

        alertBox.classList.add('d-none');
        alertBox.classList.remove('alert-success', 'alert-danger');

        /*
        |--------------------------------------------------------------------------
        | Disable button
        |--------------------------------------------------------------------------
        */

        button.disabled = true;

        button.innerHTML =
            '<i class="fas fa-spinner fa-spin mr-1"></i> Menyimpan...';

        /*
        |--------------------------------------------------------------------------
        | Kirim AJAX
        |--------------------------------------------------------------------------
        */

        const formData = new FormData(form);
        fetch('../change_password_process.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                /*
                |--------------------------------------------------------------------------
                | Update CSRF token
                |--------------------------------------------------------------------------
                */
                if (data.csrf_token) {
                    document
                        .querySelectorAll('input[name="csrf_token"]')
                        .forEach(function(input) {
                            input.value = data.csrf_token;
                        });
                }

                /*
                |--------------------------------------------------------------------------
                | Tampilkan alert
                |--------------------------------------------------------------------------
                */
                alertBox.classList.remove('d-none');
                if (data.success) {
                    alertBox.classList.remove('alert-danger');
                    alertBox.classList.add('alert-success');
                    alertMessage.innerHTML =
                        '<i class="fas fa-check-circle mr-1"></i> ' +
                        data.message;
                    /*
                    |--------------------------------------------------------------------------
                    | Kosongkan field password
                    |--------------------------------------------------------------------------
                    */
                    document.getElementById('current_password').value = '';
                    document.getElementById('new_password').value = '';
                    document.getElementById('confirm_password').value = '';
                } else {
                    alertBox.classList.remove('alert-success');
                    alertBox.classList.add('alert-danger');
                    alertMessage.innerHTML =
                        '<i class="fas fa-exclamation-circle mr-1"></i> ' +
                        data.message;
                }
            })
            .catch(error => {
                alertBox.classList.remove('d-none');
                alertBox.classList.add('alert-danger');
                alertMessage.innerHTML =
                    '<i class="fas fa-exclamation-circle mr-1"></i> ' +
                    'Terjadi kesalahan pada server. Silakan coba kembali.';
                console.error(error);
            })
            .finally(() => {
                button.disabled = false;
                button.innerHTML =
                    '<i class="fas fa-save mr-1"></i> Ubah Password';
            });
    });

    /*
    |--------------------------------------------------------------------------
    | Show / Hide Password
    |--------------------------------------------------------------------------
    */
    function togglePassword(inputId, button) {
        const input = document.getElementById(inputId);
        const icon = button.querySelector('i');
        if (input.type === "password") {
            input.type = "text";
            icon.classList.remove("fa-eye");
            icon.classList.add("fa-eye-slash");
        } else {
            input.type = "password";
            icon.classList.remove("fa-eye-slash");
            icon.classList.add("fa-eye");
        }
    }
</script>