<?php

require_once __DIR__ . '/../../app/models/User.php';

if ($menu != 'dashboard') {
    require_once '../../app/helpers/permission_helper.php';
    require_once "../../app/core/permission.php";
}

/*
|--------------------------------------------------------------------------
| Ambil nama user yang sedang login
|--------------------------------------------------------------------------
*/

$currentUserName = 'Admin';

if (isset($_SESSION['user_id'])) {

    $userModel = new User($pdo);

    $currentUser = $userModel->findById($_SESSION['user_id']);

    if ($currentUser && !empty($currentUser['nama_lengkap'])) {
        $currentUserName = $currentUser['nama_lengkap'];
    }
}

$canDashboard = canView($pdo, 'Dashboard');
$canMasterUser = canView($pdo, 'Master User');
$canPengguna = canView($pdo, 'Pengguna');
$canRoles = canView($pdo, 'Roles');
$canMenus = canView($pdo, 'Menus');
$canMasterWilayah = canView($pdo, 'Master Wilayah');
$canProvinsi = canView($pdo, 'Provinsi');
$canKabupaten = canView($pdo, 'Kabupaten');
$canKecamatan = canView($pdo, 'Kecamatan');
$canDesa = canView($pdo, 'Desa');
$canMasterKelompok = canView($pdo, 'Master Kelompok');
$canKategoriKelompok = canView($pdo, 'Kategori Kelompok');
$canJabatanKelompok = canView($pdo, 'Jabatan Kelompok');
$canMasterJalan = canView($pdo, 'Master Jalan');
$canAksesPerjalanan = canView($pdo, 'Akses Perjalanan');
$canKondisiJalan = canView($pdo, 'Kondisi Jalan');
$canMasterLahan = canView($pdo, 'Master Lahan');
$canLegalitasLahan = canView($pdo, 'Legalitas Lahan');
$canStatusKawasan = canView($pdo, 'Status Kawasan');
$canMasterBenih = canView($pdo, 'Master Benih');
$canTipePenyimpananBenih = canView($pdo, 'Tipe Penyimpanan Benih');
$canTipePenanaman = canView($pdo, 'Tipe Penanaman');
$canProgressMonitoring = canView($pdo, 'Progress Monitoring');
$canNegara = canView($pdo, 'Negara');
$canMasterObservasi = canView($pdo, 'Master Observasi');
$canKategoriArea = canView($pdo, 'Kategori Area');
$canPenggunaanLainnya = canView($pdo, 'Penggunaan Lainnya');
$canPenggunaanPertanian = canView($pdo, 'Penggunaan Pertanian');
$canLanskap = canView($pdo, 'Lanskap');
$canFiturTambahan = canView($pdo, 'Fitur Tambahan');
$canJenisPohon = canView($pdo, 'Jenis Pohon');
$canFungsiPohon = canView($pdo, 'Fungsi Pohon');
$canWarnaAir = canView($pdo, 'Warna Air');
$canJenisPalung = canView($pdo, 'Jenis Palung');
$canKecepatanAliran = canView($pdo, 'Kecepatan Aliran');
$canDataPenerimaManfaat = canView($pdo, 'Data Penerima Manfaat');
$canPenerimaManfaat = canView($pdo, 'Penerima Manfaat');
$canKelompok = canView($pdo, 'Kelompok');
$canKelompokPenerima = canView($pdo, 'Kelompok Penerima');
$canDataTanah = canView($pdo, 'Data Tanah');
$canKaleka = canView($pdo, 'Kaleka');
$canHutanAdat = canView($pdo, 'Hutan Adat');
$canTanah = canView($pdo, 'Tanah');
$canPolygon = canView($pdo, 'Polygon');
$canDataBenih = canView($pdo, 'Data Benih');
$canBankBenih = canView($pdo, 'Bank Benih');
$canMonitoringPenanaman = canView($pdo, 'Monitoring Penanaman');
$canDetailMonitoring = canView($pdo, 'Detail Monitoring');
$canDataObservasi = canView($pdo, 'Data Observasi');
$canInfrastrukturObservasi = canView($pdo, 'Infrastruktur Observasi');
$canLandCoverObservasi = canView($pdo, 'Land Cover Observasi');
$canPerairanObservasi = canView($pdo, 'Perairan Observasi');
$canPohonObservasi = canView($pdo, 'Pohon Observasi');
$canTopografiObservasi = canView($pdo, 'Topografi Observasi');
$canLogHistory = canView($pdo, 'Log History');
$canGlosarium = canView($pdo, 'Glosarium');
?>

<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="index.php" class="brand-link">
        <img src="<?= ($menu != 'dashboard') ? '../' : '' ?>assets/adminlte/dist/img/AdminLTELogo.png"
            alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-light">Admin Borneologi</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="<?= ($menu != 'dashboard') ? '../' : '' ?>assets/adminlte/dist/img/user2-160x160.jpg"
                    class="img-circle elevation-2" alt="User Image">
            </div>
            <div class="info">
                <a href="#" class="d-block">
                    <?= htmlspecialchars($currentUserName, ENT_QUOTES, 'UTF-8') ?>
                </a>
            </div>
        </div>

        <!-- SidebarSearch Form -->
        <div class="form-inline">
            <div class="input-group" data-widget="sidebar-search">
                <input class="form-control form-control-sidebar" type="search" placeholder="Search" aria-label="Search">
                <div class="input-group-append">
                    <button class="btn btn-sidebar">
                        <i class="fas fa-search fa-fw"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <?php if ($canDashboard): ?>
                    <li class="nav-item">
                        <a href="<?= ($menu != 'dashboard') ? '../' : '' ?>index.php"
                            class="nav-link <?= ($menu == 'dashboard') ? 'active' : '' ?>">
                            <i class="nav-icon fas fa-tachometer-alt"></i>
                            <p>Dashboard</p>
                        </a>
                    </li>
                <?php endif; ?>
                <?php if ($canLogHistory): ?>
                    <li class="nav-item">
                        <a href="<?= ($menu == 'dashboard') ? 'activity_log/' : '../activity_log/' ?>index.php"
                            class="nav-link <?= ($menu == 'activity_log') ? 'active' : '' ?>">
                            <i class="nav-icon fas fa-history"></i>
                            <p>Log History</p>
                        </a>
                    </li>
                <?php endif; ?>
                <li
                    class="nav-item <?= ($menu == 'user' || $menu == 'roles' || $menu == 'menus' || $menu == 'provinsi' || $menu == 'kabupaten' || $menu == 'kecamatan' || $menu == 'desa' || $menu == 'kategori_kelompok' || $menu == 'jabatan_kelompok' || $menu == 'akses_perjalanan' || $menu == 'kondisi_jalan' || $menu == 'status_kawasan' || $menu == 'legalitas_lahan' || $menu == 'tipe_penyimpanan_benih' || $menu == 'tipe_penanaman' || $menu == 'kategori_area' || $menu == 'penggunaan_lainnya' || $menu == 'penggunaan_pertanian' || $menu == 'lanskap' || $menu == 'fitur_tambahan' || $menu == 'jenis_pohon' || $menu == 'fungsi_pohon' || $menu == 'warna_air' || $menu == 'jenis_palung' || $menu == 'kecepatan_aliran' || $menu == 'progress_status_monitoring' || $menu == 'negara') ? 'menu-open' : '' ?>">
                    <a href="#"
                        class="nav-link <?= ($menu == 'user' || $menu == 'roles' || $menu == 'menus' || $menu == 'provinsi' || $menu == 'kabupaten' || $menu == 'kecamatan' || $menu == 'desa' || $menu == 'kategori_kelompok' || $menu == 'jabatan_kelompok' || $menu == 'akses_perjalanan' || $menu == 'kondisi_jalan' || $menu == 'status_kawasan' || $menu == 'legalitas_lahan' || $menu == 'tipe_penyimpanan_benih' || $menu == 'tipe_penanaman' || $menu == 'kategori_area' || $menu == 'penggunaan_lainnya' || $menu == 'penggunaan_pertanian' || $menu == 'lanskap' || $menu == 'fitur_tambahan' || $menu == 'jenis_pohon' || $menu == 'fungsi_pohon' || $menu == 'warna_air' || $menu == 'jenis_palung' || $menu == 'kecepatan_aliran' || $menu == 'progress_status_monitoring' || $menu == 'negara') ? 'active' : '' ?>">
                        <i class="nav-icon fas fa-table"></i>
                        <p>
                            Data Master
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <?php if ($canMasterUser): ?>
                            <li
                                class="nav-item <?= ($menu == 'user' || $menu == 'roles' || $menu == 'menus') ? 'menu-open' : '' ?>">
                                <a href="#"
                                    class="nav-link <?= ($menu == 'user' || $menu == 'roles' || $menu == 'menus') ? 'active' : '' ?>">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>
                                        Master User
                                        <i class="fas fa-angle-left right"></i>
                                    </p>
                                </a>
                                <ul class="nav nav-treeview">
                                    <?php if ($canPengguna): ?>
                                        <li class="nav-item">
                                            <a href="<?= ($menu == 'dashboard') ? 'users/' : '../users/' ?>index.php"
                                                class="nav-link <?= ($menu == 'user') ? 'active' : '' ?>">
                                                <i class="far fa-circle nav-icon"></i>
                                                <p>Pengguna</p>
                                            </a>
                                        </li>
                                    <?php endif; ?>
                                    <?php if ($canRoles): ?>
                                        <li class="nav-item">
                                            <a href="<?= ($menu == 'dashboard') ? 'roles/' : '../roles/' ?>index.php"
                                                class="nav-link <?= ($menu == 'roles') ? 'active' : '' ?>">
                                                <i class="far fa-circle nav-icon"></i>
                                                <p>Roles</p>
                                            </a>
                                        </li>
                                    <?php endif; ?>
                                    <?php if ($canMenus): ?>
                                        <li class="nav-item">
                                            <a href="<?= ($menu == 'dashboard') ? 'menus/' : '../menus/' ?>index.php"
                                                class="nav-link <?= ($menu == 'menus') ? 'active' : '' ?>">
                                                <i class="far fa-circle nav-icon"></i>
                                                <p>Menus</p>
                                            </a>
                                        </li>
                                    <?php endif; ?>
                                </ul>
                            </li>
                        <?php endif; ?>
                        <?php if ($canMasterWilayah): ?>
                            <li
                                class="nav-item <?= ($menu == 'provinsi' || $menu == 'kabupaten' || $menu == 'kecamatan' || $menu == 'desa') ? 'menu-open' : '' ?>">
                                <a href="#"
                                    class="nav-link <?= ($menu == 'provinsi' || $menu == 'kabupaten' || $menu == 'kecamatan' || $menu == 'desa') ? 'active' : '' ?>">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>
                                        Master Wilayah
                                        <i class="fas fa-angle-left right"></i>
                                    </p>
                                </a>
                                <ul class="nav nav-treeview">
                                    <?php if ($canProvinsi): ?>
                                        <li class="nav-item">
                                            <a href="<?= ($menu == 'dashboard') ? 'provinsi/' : '../provinsi/' ?>index.php"
                                                class="nav-link <?= ($menu == 'provinsi') ? 'active' : '' ?>">
                                                <i class="far fa-circle nav-icon"></i>
                                                <p>Provinsi</p>
                                            </a>
                                        </li>
                                    <?php endif; ?>
                                    <?php if ($canKabupaten): ?>
                                        <li class="nav-item">
                                            <a href="<?= ($menu == 'dashboard') ? 'kabupaten/' : '../kabupaten/' ?>index.php"
                                                class="nav-link <?= ($menu == 'kabupaten') ? 'active' : '' ?>">
                                                <i class="far fa-circle nav-icon"></i>
                                                <p>Kabupaten</p>
                                            </a>
                                        </li>
                                    <?php endif; ?>
                                    <?php if ($canKecamatan): ?>
                                        <li class="nav-item">
                                            <a href="<?= ($menu == 'dashboard') ? 'kecamatan/' : '../kecamatan/' ?>index.php"
                                                class="nav-link <?= ($menu == 'kecamatan') ? 'active' : '' ?>">
                                                <i class="far fa-circle nav-icon"></i>
                                                <p>Kecamatan</p>
                                            </a>
                                        </li>
                                    <?php endif; ?>
                                    <?php if ($canDesa): ?>
                                        <li class="nav-item">
                                            <a href="<?= ($menu == 'dashboard') ? 'desa/' : '../desa/' ?>index.php"
                                                class="nav-link <?= ($menu == 'desa') ? 'active' : '' ?>">
                                                <i class="far fa-circle nav-icon"></i>
                                                <p>Desa</p>
                                            </a>
                                        </li>
                                    <?php endif; ?>
                                </ul>
                            </li>
                        <?php endif; ?>
                        <?php if ($canMasterKelompok): ?>
                            <li
                                class="nav-item <?= ($menu == 'kategori_kelompok' || $menu == 'jabatan_kelompok') ? 'menu-open' : '' ?>">
                                <a href="#"
                                    class="nav-link <?= ($menu == 'kategori_kelompok' || $menu == 'jabatan_kelompok') ? 'active' : '' ?>">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>
                                        Master Kelompok
                                        <i class="fas fa-angle-left right"></i>
                                    </p>
                                </a>
                                <ul class="nav nav-treeview">
                                    <?php if ($canKategoriKelompok): ?>
                                        <li class="nav-item">
                                            <a href="<?= ($menu == 'dashboard') ? 'kategori_kelompok/' : '../kategori_kelompok/' ?>index.php"
                                                class="nav-link <?= ($menu == 'kategori_kelompok') ? 'active' : '' ?>">
                                                <i class="far fa-circle nav-icon"></i>
                                                <p>Kategori Kelompok</p>
                                            </a>
                                        </li>
                                    <?php endif; ?>
                                    <?php if ($canJabatanKelompok): ?>
                                        <li class="nav-item">
                                            <a href="<?= ($menu == 'dashboard') ? 'jabatan_kelompok/' : '../jabatan_kelompok/' ?>index.php"
                                                class="nav-link <?= ($menu == 'jabatan_kelompok') ? 'active' : '' ?>">
                                                <i class="far fa-circle nav-icon"></i>
                                                <p>Jabatan Kelompok</p>
                                            </a>
                                        </li>
                                    <?php endif; ?>
                                </ul>
                            </li>
                        <?php endif; ?>
                        <?php if ($canMasterJalan): ?>
                            <li
                                class="nav-item <?= ($menu == 'akses_perjalanan' || $menu == 'kondisi_jalan') ? 'menu-open' : '' ?>">
                                <a href="#"
                                    class="nav-link <?= ($menu == 'akses_perjalanan' || $menu == 'kondisi_jalan') ? 'active' : '' ?>">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>
                                        Master Jalan
                                        <i class="fas fa-angle-left right"></i>
                                    </p>
                                </a>
                                <ul class="nav nav-treeview">
                                    <?php if ($canAksesPerjalanan): ?>
                                        <li class="nav-item">
                                            <a href="<?= ($menu == 'dashboard') ? 'akses_perjalanan/' : '../akses_perjalanan/' ?>index.php"
                                                class="nav-link <?= ($menu == 'akses_perjalanan') ? 'active' : '' ?>">
                                                <i class="far fa-circle nav-icon"></i>
                                                <p>Akses Perjalanan</p>
                                            </a>
                                        </li>
                                    <?php endif; ?>
                                    <?php if ($canKondisiJalan): ?>
                                        <li class="nav-item">
                                            <a href="<?= ($menu == 'dashboard') ? 'kondisi_jalan/' : '../kondisi_jalan/' ?>index.php"
                                                class="nav-link <?= ($menu == 'kondisi_jalan') ? 'active' : '' ?>">
                                                <i class="far fa-circle nav-icon"></i>
                                                <p>Kondisi Jalan</p>
                                            </a>
                                        </li>
                                    <?php endif; ?>
                                </ul>
                            </li>
                        <?php endif; ?>
                        <?php if ($canMasterLahan): ?>
                            <li
                                class="nav-item <?= ($menu == 'legalitas_lahan' || $menu == 'status_kawasan') ? 'menu-open' : '' ?>">
                                <a href="#"
                                    class="nav-link <?= ($menu == 'legalitas_lahan' || $menu == 'status_kawasan') ? 'active' : '' ?>">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>
                                        Master Lahan
                                        <i class="fas fa-angle-left right"></i>
                                    </p>
                                </a>
                                <ul class="nav nav-treeview">
                                    <?php if ($canLegalitasLahan): ?>
                                        <li class="nav-item">
                                            <a href="<?= ($menu == 'dashboard') ? 'legalitas_lahan/' : '../legalitas_lahan/' ?>index.php"
                                                class="nav-link <?= ($menu == 'legalitas_lahan') ? 'active' : '' ?>">
                                                <i class="far fa-circle nav-icon"></i>
                                                <p>Legalitas Lahan</p>
                                            </a>
                                        </li>
                                    <?php endif; ?>
                                    <?php if ($canStatusKawasan): ?>
                                        <li class="nav-item">
                                            <a href="<?= ($menu == 'dashboard') ? 'status_kawasan/' : '../status_kawasan/' ?>index.php"
                                                class="nav-link <?= ($menu == 'status_kawasan') ? 'active' : '' ?>">
                                                <i class="far fa-circle nav-icon"></i>
                                                <p>Status Kawasan</p>
                                            </a>
                                        </li>
                                    <?php endif; ?>
                                </ul>
                            </li>
                        <?php endif; ?>
                        <?php if ($canMasterBenih): ?>
                            <li
                                class="nav-item <?= ($menu == 'tipe_penyimpanan_benih' || $menu == 'tipe_penanaman' || $menu == 'progress_status_monitoring' || $menu == 'negara') ? 'menu-open' : '' ?>">
                                <a href="#"
                                    class="nav-link <?= ($menu == 'tipe_penyimpanan_benih' || $menu == 'tipe_penanaman' || $menu == 'progress_status_monitoring' || $menu == 'negara') ? 'active' : '' ?>">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>
                                        Master Benih
                                        <i class="fas fa-angle-left right"></i>
                                    </p>
                                </a>
                                <ul class="nav nav-treeview">
                                    <?php if ($canTipePenyimpananBenih): ?>
                                        <li class="nav-item">
                                            <a href="<?= ($menu == 'dashboard') ? 'tipe_penyimpanan_benih/' : '../tipe_penyimpanan_benih/' ?>index.php"
                                                class="nav-link <?= ($menu == 'tipe_penyimpanan_benih') ? 'active' : '' ?>">
                                                <i class="far fa-circle nav-icon"></i>
                                                <p>Tipe Penyimpanan Benih</p>
                                            </a>
                                        </li>
                                    <?php endif; ?>
                                    <?php if ($canTipePenanaman): ?>
                                        <li class="nav-item">
                                            <a href="<?= ($menu == 'dashboard') ? 'tipe_penanaman/' : '../tipe_penanaman/' ?>index.php"
                                                class="nav-link <?= ($menu == 'tipe_penanaman') ? 'active' : '' ?>">
                                                <i class="far fa-circle nav-icon"></i>
                                                <p>Tipe Penanaman</p>
                                            </a>
                                        </li>
                                    <?php endif; ?>
                                    <?php if ($canProgressMonitoring): ?>
                                        <li class="nav-item">
                                            <a href="<?= ($menu == 'dashboard') ? 'progress_status_monitoring/' : '../progress_status_monitoring/' ?>index.php"
                                                class="nav-link <?= ($menu == 'progress_status_monitoring') ? 'active' : '' ?>">
                                                <i class="far fa-circle nav-icon"></i>
                                                <p>Progress Monitoring</p>
                                            </a>
                                        </li>
                                    <?php endif; ?>
                                    <?php if ($canNegara): ?>
                                        <li class="nav-item">
                                            <a href="<?= ($menu == 'dashboard') ? 'negara/' : '../negara/' ?>index.php"
                                                class="nav-link <?= ($menu == 'negara') ? 'active' : '' ?>">
                                                <i class="far fa-circle nav-icon"></i>
                                                <p>Negara</p>
                                            </a>
                                        </li>
                                    <?php endif; ?>
                                </ul>
                            </li>
                        <?php endif; ?>
                        <?php if ($canMasterObservasi): ?>
                            <li
                                class="nav-item <?= ($menu == 'kategori_area' || $menu == 'penggunaan_lainnya' || $menu == 'penggunaan_pertanian' || $menu == 'lanskap' || $menu == 'fitur_tambahan' || $menu == 'jenis_pohon' || $menu == 'fungsi_pohon' || $menu == 'warna_air' || $menu == 'jenis_palung' || $menu == 'kecepatan_aliran') ? 'menu-open' : '' ?>">
                                <a href="#"
                                    class="nav-link <?= ($menu == 'kategori_area' || $menu == 'penggunaan_lainnya' || $menu == 'penggunaan_pertanian' || $menu == 'lanskap' || $menu == 'fitur_tambahan' || $menu == 'jenis_pohon' || $menu == 'fungsi_pohon' || $menu == 'warna_air' || $menu == 'jenis_palung' || $menu == 'kecepatan_aliran') ? 'active' : '' ?>">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>
                                        Master Observasi
                                        <i class="fas fa-angle-left right"></i>
                                    </p>
                                </a>
                                <ul class="nav nav-treeview">
                                    <?php if ($canKategoriArea): ?>
                                        <li class="nav-item">
                                            <a href="<?= ($menu == 'dashboard') ? 'kategori_area/' : '../kategori_area/' ?>index.php"
                                                class="nav-link <?= ($menu == 'kategori_area') ? 'active' : '' ?>">
                                                <i class="far fa-circle nav-icon"></i>
                                                <p>Kategori Area</p>
                                            </a>
                                        </li>
                                    <?php endif; ?>
                                    <?php if ($canPenggunaanLainnya): ?>
                                        <li class="nav-item">
                                            <a href="<?= ($menu == 'dashboard') ? 'penggunaan_lainnya/' : '../penggunaan_lainnya/' ?>index.php"
                                                class="nav-link <?= ($menu == 'penggunaan_lainnya') ? 'active' : '' ?>">
                                                <i class="far fa-circle nav-icon"></i>
                                                <p>Penggunaan Lainnya</p>
                                            </a>
                                        </li>
                                    <?php endif; ?>
                                    <?php if ($canPenggunaanPertanian): ?>
                                        <li class="nav-item">
                                            <a href="<?= ($menu == 'dashboard') ? 'penggunaan_pertanian/' : '../penggunaan_pertanian/' ?>index.php"
                                                class="nav-link <?= ($menu == 'penggunaan_pertanian') ? 'active' : '' ?>">
                                                <i class="far fa-circle nav-icon"></i>
                                                <p>Penggunaan Pertanian</p>
                                            </a>
                                        </li>
                                    <?php endif; ?>
                                    <?php if ($canLanskap): ?>
                                        <li class="nav-item">
                                            <a href="<?= ($menu == 'dashboard') ? 'lanskap/' : '../lanskap/' ?>index.php"
                                                class="nav-link <?= ($menu == 'lanskap') ? 'active' : '' ?>">
                                                <i class="far fa-circle nav-icon"></i>
                                                <p>Lanskap</p>
                                            </a>
                                        </li>
                                    <?php endif; ?>
                                    <?php if ($canFiturTambahan): ?>
                                        <li class="nav-item">
                                            <a href="<?= ($menu == 'dashboard') ? 'fitur_tambahan/' : '../fitur_tambahan/' ?>index.php"
                                                class="nav-link <?= ($menu == 'fitur_tambahan') ? 'active' : '' ?>">
                                                <i class="far fa-circle nav-icon"></i>
                                                <p>Fitur Tambahan</p>
                                            </a>
                                        </li>
                                    <?php endif; ?>
                                    <?php if ($canJenisPohon): ?>
                                        <li class="nav-item">
                                            <a href="<?= ($menu == 'dashboard') ? 'jenis_pohon/' : '../jenis_pohon/' ?>index.php"
                                                class="nav-link <?= ($menu == 'jenis_pohon') ? 'active' : '' ?>">
                                                <i class="far fa-circle nav-icon"></i>
                                                <p>Jenis Pohon</p>
                                            </a>
                                        </li>
                                    <?php endif; ?>
                                    <?php if ($canFungsiPohon): ?>
                                        <li class="nav-item">
                                            <a href="<?= ($menu == 'dashboard') ? 'fungsi_pohon/' : '../fungsi_pohon/' ?>index.php"
                                                class="nav-link <?= ($menu == 'fungsi_pohon') ? 'active' : '' ?>">
                                                <i class="far fa-circle nav-icon"></i>
                                                <p>Fungsi Pohon</p>
                                            </a>
                                        </li>
                                    <?php endif; ?>
                                    <?php if ($canWarnaAir): ?>
                                        <li class="nav-item">
                                            <a href="<?= ($menu == 'dashboard') ? 'warna_air/' : '../warna_air/' ?>index.php"
                                                class="nav-link <?= ($menu == 'warna_air') ? 'active' : '' ?>">
                                                <i class="far fa-circle nav-icon"></i>
                                                <p>Warna Air</p>
                                            </a>
                                        </li>
                                    <?php endif; ?>
                                    <?php if ($canJenisPalung): ?>
                                        <li class="nav-item">
                                            <a href="<?= ($menu == 'dashboard') ? 'jenis_palung/' : '../jenis_palung/' ?>index.php"
                                                class="nav-link <?= ($menu == 'jenis_palung') ? 'active' : '' ?>">
                                                <i class="far fa-circle nav-icon"></i>
                                                <p>Jenis Palung</p>
                                            </a>
                                        </li>
                                    <?php endif; ?>
                                    <?php if ($canKecepatanAliran): ?>
                                        <li class="nav-item">
                                            <a href="<?= ($menu == 'dashboard') ? 'kecepatan_aliran/' : '../kecepatan_aliran/' ?>index.php"
                                                class="nav-link <?= ($menu == 'kecepatan_aliran') ? 'active' : '' ?>">
                                                <i class="far fa-circle nav-icon"></i>
                                                <p>Kecepatan Aliran</p>
                                            </a>
                                        </li>
                                    <?php endif; ?>
                                </ul>
                            </li>
                        <?php endif; ?>
                    </ul>
                </li>
                <?php if ($canDataPenerimaManfaat): ?>
                    <li
                        class="nav-item <?= ($menu == 'petani' || $menu == 'kelompok_tani' || $menu == 'petani_kelompok') ? 'menu-open' : '' ?>">
                        <a href="#"
                            class="nav-link <?= ($menu == 'petani' || $menu == 'kelompok_tani' || $menu == 'petani_kelompok') ? 'active' : '' ?>">
                            <i class="nav-icon fas fa-users"></i>
                            <p>
                                Data Penerima Manfaat
                                <i class="fas fa-angle-left right"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <?php if ($canPenerimaManfaat): ?>
                                <li class="nav-item">
                                    <a href="<?= ($menu == 'dashboard') ? 'petani/' : '../petani/' ?>index.php"
                                        class="nav-link <?= ($menu == 'petani') ? 'active' : '' ?>">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Penerima Manfaat</p>
                                    </a>
                                </li>
                            <?php endif; ?>
                            <?php if ($canKelompok): ?>
                                <li class="nav-item">
                                    <a href="<?= ($menu == 'dashboard') ? 'kelompok_tani/' : '../kelompok_tani/' ?>index.php"
                                        class="nav-link <?= ($menu == 'kelompok_tani') ? 'active' : '' ?>">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Kelompok</p>
                                    </a>
                                </li>
                            <?php endif; ?>
                            <?php if ($canKelompokPenerima): ?>
                                <li class="nav-item">
                                    <a href="<?= ($menu == 'dashboard') ? 'petani_kelompok/' : '../petani_kelompok/' ?>index.php"
                                        class="nav-link <?= ($menu == 'petani_kelompok') ? 'active' : '' ?>">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Kelompok Penerima</p>
                                    </a>
                                </li>
                            <?php endif; ?>
                        </ul>
                    </li>
                <?php endif; ?>
                <?php if ($canDataTanah): ?>
                    <li
                        class="nav-item <?= ($menu == 'kaleka' || $menu == 'hutan_adat' || $menu == 'tanah' || $menu == 'polygon') ? 'menu-open' : '' ?>">
                        <a href="#"
                            class="nav-link <?= ($menu == 'kaleka' || $menu == 'hutan_adat' || $menu == 'tanah' || $menu == 'polygon') ? 'active' : '' ?>">
                            <i class="nav-icon fas fa-map-marked-alt"></i>
                            <!-- <i class="nav-icon fas fa-draw-polygon"></i> -->
                            <p>
                                Data Tanah
                                <i class="fas fa-angle-left right"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <?php if ($canKaleka): ?>
                                <li class="nav-item">
                                    <a href="<?= ($menu == 'dashboard') ? 'kaleka/' : '../kaleka/' ?>index.php"
                                        class="nav-link <?= ($menu == 'kaleka') ? 'active' : '' ?>">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Kaleka</p>
                                    </a>
                                </li>
                            <?php endif; ?>
                            <?php if ($canHutanAdat): ?>
                                <li class="nav-item">
                                    <a href="<?= ($menu == 'dashboard') ? 'hutan_adat/' : '../hutan_adat/' ?>index.php"
                                        class="nav-link <?= ($menu == 'hutan_adat') ? 'active' : '' ?>">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Hutan Adat</p>
                                    </a>
                                </li>
                            <?php endif; ?>
                            <?php if ($canTanah): ?>
                                <li class="nav-item">
                                    <a href="<?= ($menu == 'dashboard') ? 'tanah/' : '../tanah/' ?>index.php"
                                        class="nav-link <?= ($menu == 'tanah') ? 'active' : '' ?>">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Tanah</p>
                                    </a>
                                </li>
                            <?php endif; ?>
                            <?php if ($canPolygon): ?>
                                <li class="nav-item">
                                    <a href="<?= ($menu == 'dashboard') ? 'polygon/' : '../polygon/' ?>index.php"
                                        class="nav-link <?= ($menu == 'polygon') ? 'active' : '' ?>">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Polygon</p>
                                    </a>
                                </li>
                            <?php endif; ?>
                        </ul>
                    </li>
                <?php endif; ?>
                <?php if ($canDataBenih): ?>
                    <li
                        class="nav-item <?= ($menu == 'bank_benih' || $menu == 'monitoring_penanaman' || $menu == 'detail_monitoring_penanaman') ? 'menu-open' : '' ?>">
                        <a href="#"
                            class="nav-link <?= ($menu == 'bank_benih' || $menu == 'monitoring_penanaman' || $menu == 'detail_monitoring_penanaman') ? 'active' : '' ?>">
                            <i class="nav-icon fas fa-seedling"></i>
                            <p>
                                Data Benih
                                <i class="fas fa-angle-left right"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <?php if ($canBankBenih): ?>
                                <li class="nav-item">
                                    <a href="<?= ($menu == 'dashboard') ? 'bank_benih/' : '../bank_benih/' ?>index.php"
                                        class="nav-link <?= ($menu == 'bank_benih') ? 'active' : '' ?>">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Bank Benih</p>
                                    </a>
                                </li>
                            <?php endif; ?>
                            <?php if ($canMonitoringPenanaman): ?>
                                <li class="nav-item">
                                    <a href="<?= ($menu == 'dashboard') ? 'monitoring_penanaman/' : '../monitoring_penanaman/' ?>index.php"
                                        class="nav-link <?= ($menu == 'monitoring_penanaman') ? 'active' : '' ?>">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Monitoring Penanaman</p>
                                    </a>
                                </li>
                            <?php endif; ?>
                            <?php if ($canDetailMonitoring): ?>
                                <li class="nav-item">
                                    <a href="<?= ($menu == 'dashboard') ? 'detail_monitoring_penanaman/' : '../detail_monitoring_penanaman/' ?>index.php"
                                        class="nav-link <?= ($menu == 'detail_monitoring_penanaman') ? 'active' : '' ?>">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Detail Monitoring</p>
                                    </a>
                                </li>
                            <?php endif; ?>
                        </ul>
                    </li>
                <?php endif; ?>
                <?php if ($canDataObservasi): ?>
                    <li
                        class="nav-item <?= ($menu == 'infrastruktur_observasi' || $menu == 'land_cover_observasi' || $menu == 'perairan_observasi' || $menu == 'pohon_observasi' || $menu == 'topografi_observasi') ? 'menu-open' : '' ?>">
                        <a href="#"
                            class="nav-link <?= ($menu == 'infrastruktur_observasi' || $menu == 'land_cover_observasi' || $menu == 'perairan_observasi' || $menu == 'pohon_observasi' || $menu == 'topografi_observasi') ? 'active' : '' ?>">
                            <i class="nav-icon fas fa-tree"></i>
                            <p>
                                Data Observasi
                                <i class="fas fa-angle-left right"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <?php if ($canInfrastrukturObservasi): ?>
                                <li class="nav-item">
                                    <a href="<?= ($menu == 'dashboard') ? 'infrastruktur_observasi/' : '../infrastruktur_observasi/' ?>index.php"
                                        class="nav-link <?= ($menu == 'infrastruktur_observasi') ? 'active' : '' ?>">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Infrastruktur Observasi</p>
                                    </a>
                                </li>
                            <?php endif; ?>
                            <?php if ($canLandCoverObservasi): ?>
                                <li class="nav-item">
                                    <a href="<?= ($menu == 'dashboard') ? 'land_cover_observasi/' : '../land_cover_observasi/' ?>index.php"
                                        class="nav-link <?= ($menu == 'land_cover_observasi') ? 'active' : '' ?>">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Land Cover Observasi</p>
                                    </a>
                                </li>
                            <?php endif; ?>
                            <?php if ($canPerairanObservasi): ?>
                                <li class="nav-item">
                                    <a href="<?= ($menu == 'dashboard') ? 'perairan_observasi/' : '../perairan_observasi/' ?>index.php"
                                        class="nav-link <?= ($menu == 'perairan_observasi') ? 'active' : '' ?>">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Perairan Observasi</p>
                                    </a>
                                </li>
                            <?php endif; ?>
                            <?php if ($canPohonObservasi): ?>
                                <li class="nav-item">
                                    <a href="<?= ($menu == 'dashboard') ? 'pohon_observasi/' : '../pohon_observasi/' ?>index.php"
                                        class="nav-link <?= ($menu == 'pohon_observasi') ? 'active' : '' ?>">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Pohon Observasi</p>
                                    </a>
                                </li>
                            <?php endif; ?>
                            <?php if ($canTopografiObservasi): ?>
                                <li class="nav-item">
                                    <a href="<?= ($menu == 'dashboard') ? 'topografi_observasi/' : '../topografi_observasi/' ?>index.php"
                                        class="nav-link <?= ($menu == 'topografi_observasi') ? 'active' : '' ?>">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Topografi Observasi</p>
                                    </a>
                                </li>
                            <?php endif; ?>
                        </ul>
                    </li>
                <?php endif; ?>
                <?php if ($canGlosarium): ?>
                    <li class="nav-item">
                        <a href="<?= ($menu == 'dashboard') ? 'glosarium/' : '../glosarium/' ?>index.php"
                            class="nav-link <?= ($menu == 'glosarium') ? 'active' : '' ?>">
                            <i class="nav-icon fas fa-book"></i>
                            <p>Glosarium</p>
                        </a>
                    </li>
                <?php endif; ?>
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>