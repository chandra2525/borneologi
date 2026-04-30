<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="description" content="">
    <meta name="author" content="TemplateMo">

    <title>Borneologi</title>

    <!-- CSS FILES -->
    <link rel="preconnect" href="https://fonts.googleapis.com">

    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;700&display=swap" rel="stylesheet">

    <link href="css/bootstrap.min.css" rel="stylesheet">

    <link href="css/bootstrap-icons.css" rel="stylesheet">

    <link href="css/magnific-popup.css" rel="stylesheet">

    <link href="css/templatemo-first-portfolio-style.css" rel="stylesheet">

    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

    <!-- Leaflet Fullscreen CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet.fullscreen@1.6.0/Control.FullScreen.css" />

    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <!-- Leaflet Fullscreen JS -->
    <script src="https://unpkg.com/leaflet.fullscreen@1.6.0/Control.FullScreen.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        /* .navbar {
            position: sticky;
            top: 0;
            z-index: 1050;
            background-color: #fff;
        } */

        #map {
            height: 800px;
            width: 100%;
            z-index: 1;
        }

        .map-wrapper {
            position: relative;
        }

        .map-stats {
            position: absolute;
            top: 15px;
            left: 50%;
            transform: translateX(-50%);
            z-index: 200;
            display: flex;
            gap: 15px;
        }

        .stat-box {
            background: white;
            padding: 5px 5px;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            text-align: center;
            min-width: 80px;

            display: flex;
            flex-direction: column;
        }

        .stat-box span {
            font-size: 12px;
            color: #6c757d;
        }

        .stat-box h5 {
            margin: 0;
            font-weight: 700;
        }

        /* Warna teks tab saat tidak aktif */
        .nav-tabs .nav-link {
            color: #000 !important;
        }

        /* Warna saat hover */
        .nav-tabs .nav-link:hover {
            color: darkgreen !important;
        }

        /* Warna tab aktif (opsional, kalau mau beda) */
        .nav-tabs .nav-link.active {
            color: #0fb888 !important;
            background-color: #ffffff !important;
            border-color: #0fb888 #0fb888 #fff !important;
        }

        td,
        th {
            padding: 8px;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        tr:hover {
            background-color: #ddd;
        }

        th {
            padding-top: 12px;
            padding-bottom: 12px;
            text-align: left;
            background-color: #0fb888;
            color: white;
        }

        /* Background seluruh area tab */
        .custom-tabs {
            background-color: #0fb888;
            /* Hijau */
            padding: 10px;
            border-radius: 8px;
        }

        /* Hilangkan garis default bootstrap */
        .custom-tabs .nav-link {
            border: none;
            border-radius: 6px;
            color: white;
            margin-bottom: 5px;
            transition: 0.3s;
        }

        /* Hover effect */
        .custom-tabs .nav-link:hover {
            background-color: rgba(255, 255, 255, 0.2);
            color: white;
        }

        /* Tab aktif jadi putih */
        .custom-tabs .nav-link.active {
            background-color: white;
            color: #ffffff;
            font-weight: 600;
        }
    </style>

    <!--

TemplateMo 578 First Portfolio

https://templatemo.com/tm-578-first-portfolio

-->
</head>

<body>

    <section class="preloader">
        <div class="spinner">
            <span class="spinner-rotate"></span>
        </div>
    </section>

    <nav class="navbar navbar-expand-lg">
        <div class="container">

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <a href="index.html" class="navbar-brand mx-auto mx-lg-0">Borneologi</a>

            <div class="d-flex align-items-center d-lg-none">
                <i class="navbar-icon bi-telephone-plus me-3"></i>
                <a class="custom-btn btn" href="#section_5">
                    120-240-9600
                </a>
            </div>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-lg-5">
                    <li class="nav-item">
                        <a class="nav-link click-scroll" href="#section_1">Home</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link click-scroll" href="#section_2">Map</a>
                    </li>

                    <!-- <li class="nav-item">
                        <a class="nav-link click-scroll" href="#section_3">Services</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link click-scroll" href="#section_4">Projects</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link click-scroll" href="#section_5">Contact</a>
                    </li> -->
                </ul>

                <div class="d-lg-flex align-items-center d-none ms-auto">
                    <i class="navbar-icon bi-envelope me-3"></i>
                    <a class="custom-btn btn" href="#section_5">
                        info@borneoinstitute.org
                    </a>
                </div>
            </div>

        </div>
    </nav>

    <main>

        <section class="hero d-flex justify-content-center align-items-center" id="section_1">
            <div class="container">
                <div class="row">

                    <div class="col-lg-7 col-12">
                        <div class="hero-text">
                            <div class="hero-title-wrap d-flex align-items-center mb-4">
                                <img src="images/dayak1.png" class="avatar-image avatar-image-large img-fluid" alt="">

                                <h1 class="hero-title ms-3 mb-0">Hello teman Borneologi!</h1>
                            </div>

                            <h2 class="mb-4">Borneologi menyajikan peta lahan interaktif.</h2>
                            <p class="mb-4"><a class="custom-btn btn custom-link" href="#section_2">Lihat Peta</a></p>
                        </div>
                    </div>

                    <div class="col-lg-5 col-12 position-relative">
                        <div class="hero-image-wrap"></div>
                        <img src="images/dayak4.png" class="hero-image img-fluid" alt="">
                    </div>

                </div>
            </div>

            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320">
                <path fill="#535da1" fill-opacity="1"
                    d="M0,160L24,160C48,160,96,160,144,138.7C192,117,240,75,288,64C336,53,384,75,432,106.7C480,139,528,181,576,208C624,235,672,245,720,240C768,235,816,213,864,186.7C912,160,960,128,1008,133.3C1056,139,1104,181,1152,202.7C1200,224,1248,224,1296,197.3C1344,171,1392,117,1416,90.7L1440,64L1440,0L1416,0C1392,0,1344,0,1296,0C1248,0,1200,0,1152,0C1104,0,1056,0,1008,0C960,0,912,0,864,0C816,0,768,0,720,0C672,0,624,0,576,0C528,0,480,0,432,0C384,0,336,0,288,0C240,0,192,0,144,0C96,0,48,0,24,0L0,0Z">
                </path>
            </svg>
        </section>


        <section class="about mt-4" id="section_2">
            <div class="container">
                <div class="row">

                    <div class="col-lg-12 col-12">
                        <h3 class="pt-2 mb-3">Peta Sebaran</h3>
                        <div class="map-wrapper position-relative">
                            <!-- INFO BOX -->
                            <div class="map-stats">
                                <div class="stat-box">
                                    <span>Total</span>
                                    <span>Petani</span>
                                    <h5 id="totalPetani">20</h5>
                                </div>
                                <div class="stat-box">
                                    <span>Petani</span>
                                    <span>Laki-laki</span>
                                    <h5 id="totalLaki">12</h5>
                                </div>
                                <div class="stat-box">
                                    <span>Petani</span>
                                    <span>Perempuan</span>
                                    <h5 id="totalPerempuan">8</h5>
                                </div>
                            </div>
                            <div id="map" class="about-image img-fluid" alt=""></div>
                        </div>

                        <!-- Modal Informasi Hutan Adat -->
                        <div class="modal fade" id="hutanAdatModal" tabindex="0">
                            <div class="modal-dialog modal-xl modal-dialog-scrollable">
                                <div class="modal-content">

                                    <div class="modal-header">
                                        <h5 class="modal-title1"></h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>

                                    <div class="modal-body">
                                        <ul class="nav nav-tabs custom-tabs" id="myTabHutanAdat" role="tablist">
                                            <li class="nav-item" role="presentation">
                                                <button class="nav-link active" id="tab1hutanAdat-tab"
                                                    data-bs-toggle="tab" data-bs-target="#tab1hutanAdat" type="button"
                                                    role="tab" aria-controls="tab1hutanAdat" aria-selected="false">Hutan
                                                    Adat</button>
                                            </li>
                                            <li class="nav-item" role="presentation">
                                                <button class="nav-link" id="tab2kelompok-tab" data-bs-toggle="tab"
                                                    data-bs-target="#tab2kelompok" type="button" role="tab"
                                                    aria-controls="tab2kelompok" aria-selected="true">Masyarakat Hukum
                                                    Adat</button>
                                            </li>
                                            <li class="nav-item" role="presentation">
                                                <button class="nav-link" id="tab3tanah-tab" data-bs-toggle="tab"
                                                    data-bs-target="#tab3tanah" type="button" role="tab"
                                                    aria-controls="tab3tanah" aria-selected="false">Tanah</button>
                                            </li>
                                            <li class="nav-item" role="presentation">
                                                <button class="nav-link" id="tab4kaleka-tab" data-bs-toggle="tab"
                                                    data-bs-target="#tab4kaleka" type="button" role="tab"
                                                    aria-controls="tab4kaleka" aria-selected="false">Kaleka</button>
                                            </li>
                                            <li class="nav-item" role="presentation">
                                                <button class="nav-link" id="tab5benih-tab" data-bs-toggle="tab"
                                                    data-bs-target="#tab5benih" type="button" role="tab"
                                                    aria-controls="tab5benih" aria-selected="false">Benih</button>
                                            </li>
                                        </ul>

                                        <div class="tab-content pt-3" id="myTabContent">
                                            <div class="tab-pane fade show active" id="tab1hutanAdat" role="tabpanel"
                                                aria-labelledby="tab1hutanAdat-tab">
                                                <div class="row">
                                                    <div class="col-lg-3 col-md-6 col-12 mb-4">
                                                        <strong class="site-footer-title d-block mb-3">Nama
                                                            Hutan Adat</strong>
                                                        <p class="mb-0" id="namaHutanAdat"></p>
                                                    </div>
                                                    <div class="col-lg-3 col-md-6 col-12 mb-4">
                                                        <strong class="site-footer-title d-block mb-3">Nomor SK</strong>
                                                        <p class="mb-0" id="nomorSk"></p>
                                                    </div>
                                                    <div class="col-lg-3 col-md-6 col-12 mb-4">
                                                        <strong class="site-footer-title d-block mb-3">Tanggal
                                                            SK</strong>
                                                        <p class="mb-0" id="tanggalSk"></p>
                                                    </div>
                                                    <div class="col-lg-3 col-md-6 col-12 mb-4">
                                                        <strong class="site-footer-title d-block mb-3">Status
                                                            Kawasan</strong>
                                                        <p class="mb-0" id="statusKawasan"></p>
                                                    </div>
                                                    <div class="col-lg-3 col-md-6 col-12 mb-4">
                                                        <strong class="site-footer-title d-block mb-3">Nama
                                                            Desa</strong>
                                                        <p class="mb-0" id="namaDesaHutanAdat"></p>
                                                    </div>
                                                    <div class="col-lg-3 col-md-6 col-12 mb-4">
                                                        <strong class="site-footer-title d-block mb-3">Nama
                                                            Kecamatan</strong>
                                                        <p class="mb-0" id="namaKecamatanHutanAdat"></p>
                                                    </div>
                                                    <div class="col-lg-3 col-md-6 col-12 mb-4">
                                                        <strong class="site-footer-title d-block mb-3">Nama
                                                            Kabupaten</strong>
                                                        <p class="mb-0" id="namaKabupatenHutanAdat"></p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="tab-pane fade" id="tab2kelompok" role="tabpanel"
                                                aria-labelledby="tab2kelompok-tab">
                                                <div class="row">
                                                    <div class="col-lg-3 col-md-6 col-12 mb-4">
                                                        <strong class="site-footer-title d-block mb-3">Nama Masyarakat
                                                            Hukum Adat
                                                        </strong>
                                                        <p class="mb-0" id="namaKelompokTani"></p>
                                                    </div>
                                                    <div class="col-lg-3 col-md-6 col-12 mb-4">
                                                        <strong class="site-footer-title d-block mb-3">Kategori
                                                            Masyarakat Hukum Adat
                                                        </strong>
                                                        <p class="mb-0" id="kategoriKelompok"></p>
                                                    </div>
                                                    <div class="col-lg-3 col-md-6 col-12 mb-4">
                                                        <strong class="site-footer-title d-block mb-3">Tahun Bentuk
                                                        </strong>
                                                        <p class="mb-0" id="tahunBentuk"></p>
                                                    </div>
                                                    <div class="col-lg-3 col-md-6 col-12 mb-4">
                                                        <strong class="site-footer-title d-block mb-3">Status Masyarakat
                                                            Hukum Adat
                                                        </strong>
                                                        <p class="mb-0" id="statusKelompok"></p>
                                                    </div>
                                                    <div class="col-lg-3 col-md-6 col-12 mb-4">
                                                        <strong class="site-footer-title d-block mb-3">Total Anggota
                                                        </strong>
                                                        <p class="mb-0" id="totalAnggota"></p>
                                                    </div>
                                                    <div class="col-lg-3 col-md-6 col-12 mb-4">
                                                        <strong class="site-footer-title d-block mb-3">Alamat Masyarakat
                                                            Hukum Adat
                                                        </strong>
                                                        <p class="mb-0" id="alamatKelompokTani"></p>
                                                    </div>
                                                    <div class="col-lg-6 col-md-6 col-12 mb-4">
                                                        <strong class="site-footer-title d-block mb-3">Deskripsi
                                                        </strong>
                                                        <p class="mb-0" id="deskripsiKelompokTani"></p>
                                                    </div>

                                                    <div class="col-lg-12 col-md-12 col-12">
                                                        <strong class="site-footer-title d-block mb-3">Pengurus
                                                            Masyarakat Hukum Adat
                                                        </strong>
                                                        <table class="profile-thumb">
                                                            <tr>
                                                                <th>Nama Lengkap</th>
                                                                <th>Nama Panggilan</th>
                                                                <th>Jenis Kelamin</th>
                                                                <th>Umur</th>
                                                                <th>Status Petani</th>
                                                                <th>Alamat</th>
                                                                <th>Foto</th>
                                                            </tr>
                                                            <tbody id="petaniKelompokTableBody">
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="tab-pane fade" id="tab3tanah" role="tabpanel"
                                                aria-labelledby="tab3tanah-tab">
                                                <div class="row">
                                                    <div class="col-lg-3 col-md-6 col-12 mb-4">
                                                        <strong class="site-footer-title d-block mb-3">Nama
                                                            Lahan</strong>
                                                        <p class="mb-0" id="namaLahanHutanAdat"></p>
                                                    </div>
                                                    <div class="col-lg-3 col-md-6 col-12 mb-4">
                                                        <strong class="site-footer-title d-block mb-3">Legalitas
                                                        </strong>
                                                        <p class="mb-0" id="legalitasLahanHutanAdat"></p>
                                                    </div>
                                                    <div class="col-lg-3 col-md-6 col-12 mb-4">
                                                        <strong class="site-footer-title d-block mb-3">Luas
                                                        </strong>
                                                        <p class="mb-0" id="luasHaHutanAdat"></p>
                                                    </div>
                                                    <div class="col-lg-3 col-md-6 col-12 mb-4">
                                                        <strong class="site-footer-title d-block mb-3">Sejarah
                                                        </strong>
                                                        <p class="mb-0" id="sejarahHutanAdat"></p>
                                                    </div>
                                                    <div class="col-lg-6 col-md-6 col-12 mb-4">
                                                        <strong class="site-footer-title d-block mb-3">Alamat Lokasi
                                                        </strong>
                                                        <p class="mb-0" id="alamatLokasiHutanAdat"></p>
                                                    </div>
                                                    <div class="col-lg-6 col-md-6 col-12 mb-4">
                                                        <strong class="site-footer-title d-block mb-3">Keterangan
                                                        </strong>
                                                        <p class="mb-0" id="keteranganHutanAdat"></p>
                                                    </div>

                                                    <div class="col-lg-3 col-md-6 col-12 mb-4">
                                                        <strong class="site-footer-title d-block mb-3">Sudah
                                                            Validasi</strong>
                                                        <p class="mb-0" id="sudahValidasiHutanAdat"></p>
                                                    </div>
                                                    <div class="col-lg-3 col-md-6 col-12 mb-4">
                                                        <strong class="site-footer-title d-block mb-3">Tanggal
                                                            Validasi</strong>
                                                        <p class="mb-0" id="tanggalValidasiHutanAdat"></p>
                                                    </div>
                                                    <div class="col-lg-3 col-md-6 col-12 mb-4">
                                                        <strong class="site-footer-title d-block mb-3">Latitude</strong>
                                                        <p class="mb-0" id="centroidLatHutanAdat"></p>
                                                    </div>
                                                    <div class="col-lg-3 col-md-6 col-12 mb-4">
                                                        <strong class="site-footer-title d-block mb-3">Longitude
                                                        </strong>
                                                        <p class="mb-0" id="centroidLngHutanAdat"></p>
                                                    </div>

                                                    <div class="col-lg-12 col-md-12 col-12">
                                                        <strong class="site-footer-title d-block mb-3">Polygon
                                                        </strong>
                                                        <table class="profile-thumb">
                                                            <tbody id="polygonHutanAdatTableBody"></tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="tab-pane fade" id="tab4kaleka" role="tabpanel"
                                                aria-labelledby="tab4kaleka-tab">
                                                <div class="row">
                                                    <div class="col-lg-12 col-md-12 col-12">
                                                        <strong class="site-footer-title d-block mb-3">List Kaleka
                                                        </strong>
                                                        <table class="profile-thumb">
                                                            <tr>
                                                                <th>Nama Kaleka</th>
                                                                <th>Nama Desa</th>
                                                                <th>Nama Kecamatan</th>
                                                                <th>Nama Kabupaten</th>
                                                                <th>Luas ha</th>
                                                                <th>Alamat Lokasi</th>
                                                            </tr>
                                                            <tbody id="polygonHutanAdatKalekaTableBody">
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="tab-pane fade" id="tab5benih" role="tabpanel"
                                                aria-labelledby="tab5benih-tab">
                                                <div class="row">
                                                    <div class="col-lg-12 col-md-12 col-12">
                                                        <strong class="site-footer-title d-block mb-3">List Benih
                                                        </strong>
                                                        <table class="profile-thumb">
                                                            <tr>
                                                                <th>Nama Lokal</th>
                                                                <th>Nama Ilmiah</th>
                                                                <th>Luas ha</th>
                                                                <th>Alamat Lokasi</th>
                                                            </tr>
                                                            <tbody id="polygonHutanAdatBenihTableBody">
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Modal Informasi Kaleka -->
                        <div class="modal fade" id="kalekaModal" tabindex="0">
                            <div class="modal-dialog modal-xl modal-dialog-scrollable">
                                <div class="modal-content">

                                    <div class="modal-header">
                                        <h5 class="modal-title2"></h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>

                                    <div class="modal-body">
                                        <!-- Nav tabs -->
                                        <ul class="nav nav-tabs custom-tabs" id="myTabKaleka" role="tablist">
                                            <li class="nav-item" role="presentation">
                                                <button class="nav-link active" id="tab1-tab" data-bs-toggle="tab"
                                                    data-bs-target="#tab1" type="button" role="tab" aria-controls="tab1"
                                                    aria-selected="true">Petani</button>
                                            </li>
                                            <li class="nav-item" role="presentation">
                                                <button class="nav-link" id="tab2-tab" data-bs-toggle="tab"
                                                    data-bs-target="#tab2" type="button" role="tab" aria-controls="tab2"
                                                    aria-selected="false">Tanah</button>
                                            </li>
                                            <li class="nav-item" role="presentation">
                                                <button class="nav-link" id="tab3-tab" data-bs-toggle="tab"
                                                    data-bs-target="#tab3" type="button" role="tab" aria-controls="tab3"
                                                    aria-selected="false">Obervasi</button>
                                            </li>
                                            <li class="nav-item" role="presentation">
                                                <button class="nav-link" id="tab4-tab" data-bs-toggle="tab"
                                                    data-bs-target="#tab4" type="button" role="tab" aria-controls="tab4"
                                                    aria-selected="false">Hasil</button>
                                            </li>
                                        </ul>

                                        <!-- Tab content -->
                                        <div class="tab-content pt-3" id="myTabContent">
                                            <div class="tab-pane fade show active" id="tab1" role="tabpanel"
                                                aria-labelledby="tab1-tab">
                                                <div class="row">
                                                    <div class="col-lg-3 col-md-6 col-12">
                                                        <div class="projects-thumb">
                                                            <div class="projects-info">
                                                                <small class="projects-tag" id="namaPanggilan"></small>

                                                                <h3 class="projects-title" id="namaLengkap"></h3>
                                                            </div>

                                                            <a href="" id="petaniFotoLink" class="popup-image">
                                                                <img id="petaniFoto" class="projects-image img-fluid"
                                                                    alt="">
                                                            </a>
                                                        </div>
                                                    </div>

                                                    <div class="col-lg-4 col-md-6 col-12">
                                                        <strong class="site-footer-title d-block mb-3">Data
                                                            Pribadi</strong>
                                                        <ul class="footer-menu">
                                                            <li class="footer-menu-item"><a class="footer-menu-link"
                                                                    id="jenisKelamin"></a>
                                                            </li>
                                                            <li class="footer-menu-item"><a class="footer-menu-link"
                                                                    id="umur"></a>
                                                            </li>
                                                            <li class="footer-menu-item"><a class="footer-menu-link"
                                                                    id="statusPetani"></a>
                                                            </li>
                                                        </ul>

                                                        <strong class="site-footer-title d-block mt-4 mb-3">Alamat
                                                        </strong>
                                                        <p class="mb-0" id="alamat"></p>
                                                        <ul class="footer-menu mt-2 mb-3">
                                                            <li class="footer-menu-item"><a class="footer-menu-link"
                                                                    id="namaDesa"></a>
                                                            </li>
                                                            <li class="footer-menu-item"><a class="footer-menu-link"
                                                                    id="namaKecamatan"></a>
                                                            </li>
                                                            <li class="footer-menu-item"><a class="footer-menu-link"
                                                                    id="namaKabupaten"></a>
                                                            </li>
                                                        </ul>
                                                    </div>

                                                    <div class="col-lg-5 col-md-6 col-12">
                                                        <strong class="site-footer-title d-block mb-3">Kelompok Petani
                                                        </strong>
                                                        <table class="profile-thumb">
                                                            <tr>
                                                                <th>Kelompok Tani</th>
                                                                <th>Kategori Kelompok</th>
                                                                <th>Tahun Gabung</th>
                                                            </tr>
                                                            <tbody id="kelompokTableBody">
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="tab-pane fade" id="tab2" role="tabpanel"
                                                aria-labelledby="tab2-tab">
                                                <div class="row">
                                                    <div class="col-lg-3 col-md-6 col-12 mb-4">
                                                        <strong class="site-footer-title d-block mb-3">Nama
                                                            Kaleka</strong>
                                                        <p class="mb-0" id="namaKaleka"></p>
                                                    </div>
                                                    <div class="col-lg-3 col-md-6 col-12 mb-4">
                                                        <strong class="site-footer-title d-block mb-3">Nama
                                                            Lahan</strong>
                                                        <p class="mb-0" id="namaLahan"></p>
                                                    </div>
                                                    <div class="col-lg-3 col-md-6 col-12 mb-4">
                                                        <strong class="site-footer-title d-block mb-3">Legalitas
                                                        </strong>
                                                        <p class="mb-0" id="legalitasLahan"></p>
                                                    </div>
                                                    <div class="col-lg-3 col-md-6 col-12 mb-4">
                                                        <strong class="site-footer-title d-block mb-3">Luas
                                                        </strong>
                                                        <p class="mb-0" id="luasHa"></p>
                                                    </div>
                                                    <div class="col-lg-3 col-md-6 col-12 mb-4">
                                                        <strong class="site-footer-title d-block mb-3">Sejarah
                                                        </strong>
                                                        <p class="mb-0" id="sejarah"></p>
                                                    </div>
                                                    <div class="col-lg-3 col-md-6 col-12 mb-4">
                                                        <strong class="site-footer-title d-block mb-3">Alamat Lokasi
                                                        </strong>
                                                        <p class="mb-0" id="alamatLokasi"></p>
                                                    </div>
                                                    <div class="col-lg-6 col-md-6 col-12 mb-4">
                                                        <strong class="site-footer-title d-block mb-3">Keterangan
                                                        </strong>
                                                        <p class="mb-0" id="keterangan"></p>
                                                    </div>

                                                    <div class="col-lg-3 col-md-6 col-12 mb-4">
                                                        <strong class="site-footer-title d-block mb-3">Sudah
                                                            Validasi</strong>
                                                        <p class="mb-0" id="sudahValidasi"></p>
                                                    </div>
                                                    <div class="col-lg-3 col-md-6 col-12 mb-4">
                                                        <strong class="site-footer-title d-block mb-3">Tanggal
                                                            Validasi</strong>
                                                        <p class="mb-0" id="tanggalValidasi"></p>
                                                    </div>
                                                    <div class="col-lg-3 col-md-6 col-12 mb-4">
                                                        <strong class="site-footer-title d-block mb-3">Latitude</strong>
                                                        <p class="mb-0" id="centroidLat"></p>
                                                    </div>
                                                    <div class="col-lg-3 col-md-6 col-12 mb-4">
                                                        <strong class="site-footer-title d-block mb-3">Longitude
                                                        </strong>
                                                        <p class="mb-0" id="centroidLng"></p>
                                                    </div>

                                                    <div class="col-lg-12 col-md-12 col-12">
                                                        <strong class="site-footer-title d-block mb-3">Polygon
                                                        </strong>
                                                        <table class="profile-thumb">
                                                            <tbody id="polygonTableBody"></tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="tab-pane fade" id="tab3" role="tabpanel"
                                                aria-labelledby="tab3-tab">
                                                <div class="row">
                                                    <div class="modal-body">
                                                        <ul class="nav nav-tabs" id="myTabKaleka" role="tablist">
                                                            <li class="nav-item" role="presentation">
                                                                <button class="nav-link active" id="tab31-tab"
                                                                    data-bs-toggle="tab" data-bs-target="#tab31"
                                                                    type="button" role="tab">
                                                                    Perairan Observasi
                                                                </button>
                                                            </li>

                                                            <li class="nav-item" role="presentation">
                                                                <button class="nav-link" id="tab32-tab"
                                                                    data-bs-toggle="tab" data-bs-target="#tab32"
                                                                    type="button" role="tab">
                                                                    Infrastruktur Observasi
                                                                </button>
                                                            </li>

                                                            <li class="nav-item" role="presentation">
                                                                <button class="nav-link" id="tab33-tab"
                                                                    data-bs-toggle="tab" data-bs-target="#tab33"
                                                                    type="button" role="tab">
                                                                    Land Cover Observasi
                                                                </button>
                                                            </li>

                                                            <li class="nav-item" role="presentation">
                                                                <button class="nav-link" id="tab34-tab"
                                                                    data-bs-toggle="tab" data-bs-target="#tab34"
                                                                    type="button" role="tab">
                                                                    Topografi Observasi
                                                                </button>
                                                            </li>

                                                            <li class="nav-item" role="presentation">
                                                                <button class="nav-link" id="tab35-tab"
                                                                    data-bs-toggle="tab" data-bs-target="#tab35"
                                                                    type="button" role="tab">
                                                                    Pohon Observasi
                                                                </button>
                                                            </li>
                                                        </ul>
                                                    </div>

                                                    <!-- Tab content -->
                                                    <div class="col-md-12">
                                                        <div class="tab-content" id="myTabContent2">
                                                            <div class="tab-pane fade show active" id="tab31"
                                                                role="tabpanel">
                                                                <div class="row">
                                                                    <div class="col-lg-3 col-md-6 col-12 mb-4">
                                                                        <strong
                                                                            class="site-footer-title d-block mb-3">Periode
                                                                            Pengecekan
                                                                        </strong>
                                                                        <p class="mb-0" id="perairanPeriodePengecekan">
                                                                        </p>
                                                                    </div>
                                                                    <div class="col-lg-3 col-md-6 col-12 mb-4">
                                                                        <strong
                                                                            class="site-footer-title d-block mb-3">Warna
                                                                            Air
                                                                        </strong>
                                                                        <p class="mb-0" id="perairanWarnaAir"></p>
                                                                    </div>
                                                                    <div class="col-lg-3 col-md-6 col-12 mb-4">
                                                                        <strong
                                                                            class="site-footer-title d-block mb-3">Jenis
                                                                            Palung
                                                                        </strong>
                                                                        <p class="mb-0" id="perairanJenisPalung"></p>
                                                                    </div>
                                                                    <div class="col-lg-4 col-md-6 col-12 mb-4">
                                                                        <strong
                                                                            class="site-footer-title d-block mb-3">Kecepatan
                                                                            Aliran
                                                                        </strong>
                                                                        <p class="mb-0" id="perairanKecepatanAliran">
                                                                        </p>
                                                                    </div>
                                                                    <div class="col-lg-4 col-md-6 col-12 mb-4">
                                                                        <strong
                                                                            class="site-footer-title d-block mb-3">Kedalaman
                                                                            Air
                                                                        </strong>
                                                                        <p class="mb-0" id="perairanKedalaman"></p>
                                                                    </div>
                                                                    <div class="col-lg-4 col-md-6 col-12 mb-4">
                                                                        <strong
                                                                            class="site-footer-title d-block mb-3">Lebar
                                                                        </strong>
                                                                        <p class="mb-0" id="perairanLebar"></p>
                                                                    </div>
                                                                    <div class="col-lg-4 col-md-6 col-12 mb-4">
                                                                        <strong
                                                                            class="site-footer-title d-block mb-3">Debit
                                                                            Air
                                                                        </strong>
                                                                        <p class="mb-0" id="perairanDebit"></p>
                                                                    </div>
                                                                    <div class="col-lg-4 col-md-6 col-12 mb-4">
                                                                        <strong
                                                                            class="site-footer-title d-block mb-3">pH
                                                                        </strong>
                                                                        <p class="mb-0" id="perairanPh"></p>
                                                                    </div>
                                                                    <div class="col-lg-4 col-md-6 col-12 mb-4">
                                                                        <strong
                                                                            class="site-footer-title d-block mb-3">Kekeruhan
                                                                        </strong>
                                                                        <p class="mb-0" id="perairanKekeruhan"></p>
                                                                    </div>
                                                                    <div class="col-lg-12 col-md-6 col-12 mb-4">
                                                                        <strong
                                                                            class="site-footer-title d-block mb-3">Catatan
                                                                        </strong>
                                                                        <p class="mb-0" id="perairanCatatan"></p>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="tab-pane fade" id="tab32" role="tabpanel">
                                                                <div class="row">
                                                                    <div class="col-lg-4 col-md-6 col-12 mb-4">
                                                                        <strong
                                                                            class="site-footer-title d-block mb-3">Periode
                                                                            Pengecekan
                                                                        </strong>
                                                                        <p class="mb-0"
                                                                            id="infrastrukturPeriodePengecekan"></p>
                                                                    </div>
                                                                    <div class="col-lg-4 col-md-6 col-12 mb-4">
                                                                        <strong
                                                                            class="site-footer-title d-block mb-3">Akses
                                                                            Perjalanan
                                                                        </strong>
                                                                        <p class="mb-0"
                                                                            id="infrastrukturAksesPerjalanan"></p>
                                                                    </div>
                                                                    <div class="col-lg-4 col-md-6 col-12 mb-4">
                                                                        <strong
                                                                            class="site-footer-title d-block mb-3">Kondisi
                                                                            Jalan
                                                                        </strong>
                                                                        <p class="mb-0" id="infrastrukturKondisiJalan">
                                                                        </p>
                                                                    </div>
                                                                    <div class="col-lg-4 col-md-6 col-12 mb-4">
                                                                        <strong
                                                                            class="site-footer-title d-block mb-3">Jarak
                                                                            ke Jalan
                                                                        </strong>
                                                                        <p class="mb-0" id="infrastrukturJarakKeJalan">
                                                                        </p>
                                                                    </div>
                                                                    <div class="col-lg-4 col-md-6 col-12 mb-4">
                                                                        <strong
                                                                            class="site-footer-title d-block mb-3">Ada
                                                                            Jembatan
                                                                        </strong>
                                                                        <p class="mb-0" id="infrastrukturAdaJembatan">
                                                                        </p>
                                                                    </div>
                                                                    <div class="col-lg-4 col-md-6 col-12 mb-4">
                                                                        <strong
                                                                            class="site-footer-title d-block mb-3">Ada
                                                                            Listrik
                                                                        </strong>
                                                                        <p class="mb-0" id="infrastrukturAdaListrik">
                                                                        </p>
                                                                    </div>
                                                                    <div class="col-lg-4 col-md-6 col-12 mb-4">
                                                                        <strong
                                                                            class="site-footer-title d-block mb-3">Ada
                                                                            Internet
                                                                        </strong>
                                                                        <p class="mb-0" id="infrastrukturAdaInternet">
                                                                        </p>
                                                                    </div>
                                                                    <div class="col-lg-4 col-md-6 col-12 mb-4">
                                                                        <strong
                                                                            class="site-footer-title d-block mb-3">Sinyal
                                                                            Seluler
                                                                        </strong>
                                                                        <p class="mb-0" id="infrastrukturSinyalSeluler">
                                                                        </p>
                                                                    </div>
                                                                    <div class="col-lg-12 col-md-6 col-12 mb-4">
                                                                        <strong
                                                                            class="site-footer-title d-block mb-3">Catatan
                                                                        </strong>
                                                                        <p class="mb-0" id="infrastrukturCatatan"></p>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="tab-pane fade" id="tab33" role="tabpanel">
                                                                <div class="row">
                                                                    <div class="col-lg-4 col-md-6 col-12 mb-4">
                                                                        <strong
                                                                            class="site-footer-title d-block mb-3">Periode
                                                                            Pengecekan
                                                                        </strong>
                                                                        <p class="mb-0" id="landCoverPeriodePengecekan">
                                                                        </p>
                                                                    </div>
                                                                    <div class="col-lg-4 col-md-6 col-12 mb-4">
                                                                        <strong
                                                                            class="site-footer-title d-block mb-3">Kategori
                                                                            Area
                                                                        </strong>
                                                                        <p class="mb-0" id="landCoverKategoriArea"></p>
                                                                    </div>
                                                                    <div class="col-lg-4 col-md-6 col-12 mb-4">
                                                                        <strong
                                                                            class="site-footer-title d-block mb-3">Penggunaan
                                                                            Pertanian
                                                                        </strong>
                                                                        <p class="mb-0"
                                                                            id="landCoverPenggunaanPertanian"></p>
                                                                    </div>
                                                                    <div class="col-lg-4 col-md-6 col-12 mb-4">
                                                                        <strong
                                                                            class="site-footer-title d-block mb-3">Penggunaan
                                                                            Lainnya
                                                                        </strong>
                                                                        <p class="mb-0" id="landCoverPenggunaanLainnya">
                                                                        </p>
                                                                    </div>
                                                                    <div class="col-lg-4 col-md-6 col-12 mb-4">
                                                                        <strong
                                                                            class="site-footer-title d-block mb-3">Persentase
                                                                            Tutupan
                                                                        </strong>
                                                                        <p class="mb-0" id="landCoverPersentaseTutupan">
                                                                        </p>
                                                                    </div>
                                                                    <div class="col-lg-12 col-md-6 col-12 mb-4">
                                                                        <strong
                                                                            class="site-footer-title d-block mb-3">Catatan
                                                                        </strong>
                                                                        <p class="mb-0" id="landCoverCatatan"></p>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="tab-pane fade" id="tab34" role="tabpanel">
                                                                <div class="row">
                                                                    <div class="col-lg-4 col-md-6 col-12 mb-4">
                                                                        <strong
                                                                            class="site-footer-title d-block mb-3">Periode
                                                                            Pengecekan
                                                                        </strong>
                                                                        <p class="mb-0" id="topografiPeriodePengecekan">
                                                                        </p>
                                                                    </div>
                                                                    <div class="col-lg-4 col-md-6 col-12 mb-4">
                                                                        <strong
                                                                            class="site-footer-title d-block mb-3">Lanskap
                                                                        </strong>
                                                                        <p class="mb-0" id="topografiLanskap"></p>
                                                                    </div>
                                                                    <div class="col-lg-4 col-md-6 col-12 mb-4">
                                                                        <strong
                                                                            class="site-footer-title d-block mb-3">Fitur
                                                                            Tambahan
                                                                        </strong>
                                                                        <p class="mb-0" id="topografiFiturTambahan"></p>
                                                                    </div>
                                                                    <div class="col-lg-4 col-md-6 col-12 mb-4">
                                                                        <strong
                                                                            class="site-footer-title d-block mb-3">Elevasi
                                                                            mdpl
                                                                        </strong>
                                                                        <p class="mb-0" id="topografiElevasiMdpl"></p>
                                                                    </div>
                                                                    <div class="col-lg-4 col-md-6 col-12 mb-4">
                                                                        <strong
                                                                            class="site-footer-title d-block mb-3">Kemiringan
                                                                            Derajat
                                                                        </strong>
                                                                        <p class="mb-0" id="topografiKemiringanDerajat">
                                                                        </p>
                                                                    </div>
                                                                    <div class="col-lg-4 col-md-6 col-12 mb-4">
                                                                        <strong
                                                                            class="site-footer-title d-block mb-3">Rawan
                                                                            Erosi
                                                                        </strong>
                                                                        <p class="mb-0" id="topografiRawanErosi"></p>
                                                                    </div>
                                                                    <div class="col-lg-4 col-md-6 col-12 mb-4">
                                                                        <strong
                                                                            class="site-footer-title d-block mb-3">Arah
                                                                            Lereng
                                                                        </strong>
                                                                        <p class="mb-0" id="topografiArahLereng"></p>
                                                                    </div>
                                                                    <div class="col-lg-12 col-md-6 col-12 mb-4">
                                                                        <strong
                                                                            class="site-footer-title d-block mb-3">Catatan
                                                                        </strong>
                                                                        <p class="mb-0" id="topografiCatatan"></p>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="tab-pane fade" id="tab35" role="tabpanel">
                                                                <div class="row">
                                                                    <div class="col-lg-4 col-md-6 col-12 mb-4">
                                                                        <strong
                                                                            class="site-footer-title d-block mb-3">Periode
                                                                            Pengecekan
                                                                        </strong>
                                                                        <p class="mb-0" id="pohonPeriodePengecekan"></p>
                                                                    </div>
                                                                    <div class="col-lg-4 col-md-6 col-12 mb-4">
                                                                        <strong
                                                                            class="site-footer-title d-block mb-3">Nama
                                                                            Pohon
                                                                        </strong>
                                                                        <p class="mb-0" id="pohonNamaPohon"></p>
                                                                    </div>
                                                                    <div class="col-lg-4 col-md-6 col-12 mb-4">
                                                                        <strong
                                                                            class="site-footer-title d-block mb-3">Nama
                                                                            Latin
                                                                        </strong>
                                                                        <p class="mb-0" id="pohonNamaLatin"></p>
                                                                    </div>
                                                                    <div class="col-lg-4 col-md-6 col-12 mb-4">
                                                                        <strong
                                                                            class="site-footer-title d-block mb-3">Fungsi
                                                                            Pohon
                                                                        </strong>
                                                                        <p class="mb-0" id="pohonFungsiPohon"></p>
                                                                    </div>
                                                                    <div class="col-lg-4 col-md-6 col-12 mb-4">
                                                                        <strong
                                                                            class="site-footer-title d-block mb-3">Jumlah
                                                                            Pohon
                                                                        </strong>
                                                                        <p class="mb-0" id="pohonJumlahPohon"></p>
                                                                    </div>
                                                                    <div class="col-lg-4 col-md-6 col-12 mb-4">
                                                                        <strong
                                                                            class="site-footer-title d-block mb-3">Diameter
                                                                            Rata-rata (cm)
                                                                        </strong>
                                                                        <p class="mb-0" id="pohonDiameterRata"></p>
                                                                    </div>
                                                                    <div class="col-lg-4 col-md-6 col-12 mb-4">
                                                                        <strong
                                                                            class="site-footer-title d-block mb-3">Tinggi
                                                                            Rata-rata (m)
                                                                        </strong>
                                                                        <p class="mb-0" id="pohonTinggiRata"></p>
                                                                    </div>
                                                                    <div class="col-lg-4 col-md-6 col-12 mb-4">
                                                                        <strong
                                                                            class="site-footer-title d-block mb-3">Kondisi
                                                                        </strong>
                                                                        <p class="mb-0" id="pohonKondisi"></p>
                                                                    </div>
                                                                    <div class="col-lg-12 col-md-6 col-12 mb-4">
                                                                        <strong
                                                                            class="site-footer-title d-block mb-3">Catatan
                                                                        </strong>
                                                                        <p class="mb-0" id="pohonCatatan"></p>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="tab-pane fade" id="tab4" role="tabpanel"
                                                aria-labelledby="tab4-tab">
                                                <div class="row">
                                                    <div class="col-lg-12 col-md-12 col-12 mb-4">
                                                        <div class="col">
                                                            <strong class="site-footer-title d-block mb-3">Grafik
                                                                Tingkat Kematian dan Kelangsungan Hidup Benih
                                                            </strong>
                                                        </div>
                                                        <div class="col">
                                                            <div style="height:210px;">
                                                                <canvas id="survivalChart"></canvas>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6 col-md-6 col-12 mb-4">
                                                        <div class="col">
                                                            <strong class="site-footer-title d-block mb-3">Grafik Stok
                                                                Benih & Jumlah Ditanam Benih
                                                            </strong>
                                                        </div>
                                                        <div class="col">
                                                            <div style="height:185px;">
                                                                <canvas id="stokChart"></canvas>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6 col-md-6 col-12 mb-4">
                                                        <div class="col">
                                                            <strong class="site-footer-title d-block mb-3">Tingkat
                                                                kelayakan atau daya hidup benih (viabilitas)
                                                            </strong>
                                                        </div>
                                                        <div class="col">
                                                            <div style="height:185px;">
                                                                <canvas id="viabilitasChart"></canvas>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6 col-md-6 col-12 mb-4">
                                                        <div class="col">
                                                            <strong class="site-footer-title d-block mb-3">Grafik Kadar
                                                                Air
                                                                Benih
                                                            </strong>
                                                        </div>
                                                        <div class="col">
                                                            <div style="height:185px;">
                                                                <canvas id="kadarAirChart"></canvas>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6 col-md-6 col-12 mb-4">
                                                        <div class="col">
                                                            <strong class="site-footer-title d-block mb-3">Grafik
                                                                Tingkat Kematian dan Kelangsungan Hidup Benih
                                                            </strong>
                                                        </div>
                                                        <div class="col">
                                                            <div style="height:210px;">
                                                                <canvas id="penyimpananChart2"></canvas>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!-- <div class="col-lg-6 col-md-6 col-12 mb-4">
                                                        <div class="col">
                                                            <strong class="site-footer-title d-block mb-3">Grafik
                                                                Penyimpanan Benih Berdasarkan Tipe Penyimpanan
                                                            </strong>
                                                        </div>
                                                        <div class="col">
                                                            <div style="height:185px;">
                                                                <canvas id="penyimpananChart"></canvas>
                                                            </div>
                                                        </div>
                                                    </div> -->
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Modal Informasi Benih -->
                        <div class="modal fade" id="benihModal" tabindex="0">
                            <div class="modal-dialog modal-xl modal-dialog-scrollable">
                                <div class="modal-content">

                                    <div class="modal-header">
                                        <h5 class="modal-title3"></h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>

                                    <div class="modal-body">
                                        <ul class="nav nav-tabs custom-tabs" id="myTabHutanAdat" role="tablist">
                                            <li class="nav-item" role="presentation">
                                                <button class="nav-link active" id="tab1benih-tab" data-bs-toggle="tab"
                                                    data-bs-target="#tab1benih" type="button" role="tab"
                                                    aria-controls="tab1benih" aria-selected="false">Benih</button>
                                            </li>
                                            <li class="nav-item" role="presentation">
                                                <button class="nav-link" id="tab2monitoring-tab" data-bs-toggle="tab"
                                                    data-bs-target="#tab2monitoring" type="button" role="tab"
                                                    aria-controls="tab2monitoring"
                                                    aria-selected="false">Monitoring</button>
                                            </li>
                                            <li class="nav-item" role="presentation">
                                                <button class="nav-link" id="tab3tanahBenih-tab" data-bs-toggle="tab"
                                                    data-bs-target="#tab3tanahBenih" type="button" role="tab"
                                                    aria-controls="tab3tanahBenih" aria-selected="false">Tanah</button>
                                            </li>
                                        </ul>

                                        <div class="tab-content pt-3" id="myTabContent">
                                            <div class="tab-pane fade show active" id="tab1benih" role="tabpanel"
                                                aria-labelledby="tab1benih-tab">
                                                <div class="row">
                                                    <div class="col-lg-3 col-md-6 col-12">
                                                        <div class="projects-thumb">
                                                            <div class="projects-info">
                                                                <small class="projects-tag" id="namaLokal"></small>

                                                                <h3 class="projects-title" id="namaIlmiah"></h3>
                                                            </div>

                                                            <a href="" id="gambarBenihLink" class="popup-image">
                                                                <img id="gambarBenih" class="projects-image img-fluid"
                                                                    alt="">
                                                            </a>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-3 col-md-6 col-12 mb-4 mt-4">
                                                        <strong class="site-footer-title d-block mb-3">Nomor
                                                            Aksesi</strong>
                                                        <p class="mb-0" id="nomorAksesi"></p>
                                                        <strong
                                                            class="site-footer-title d-block mb-3 mt-4">Provenance</strong>
                                                        <p class="mb-0" id="provenance"></p>
                                                        <strong class="site-footer-title d-block mb-3 mt-4">Jumlah
                                                            Stok</strong>
                                                        <p class="mb-0" id="jumlahStok"></p>
                                                        <strong class="site-footer-title d-block mb-3 mt-4">Viabilitas
                                                            Persen</strong>
                                                        <p class="mb-0" id="viabilitasPersen"></p>
                                                    </div>
                                                    <div class="col-lg-3 col-md-6 col-12 mb-4 mt-4">
                                                        <strong class="site-footer-title d-block mb-3">Nama
                                                            Negara</strong>
                                                        <p class="mb-0" id="namaNegara"></p>
                                                        <strong class="site-footer-title d-block mb-3 mt-4">Tipe
                                                            Penyimpanan Benih</strong>
                                                        <p class="mb-0" id="tipePenyimpananBenih"></p>
                                                        <strong class="site-footer-title d-block mb-3 mt-4">Satuan
                                                            Stok</strong>
                                                        <p class="mb-0" id="satuanStok"></p>
                                                        <strong class="site-footer-title d-block mb-3 mt-4">Ketinggian
                                                            mdpl</strong>
                                                        <p class="mb-0" id="ketinggianMdpl"></p>
                                                    </div>
                                                    <div class="col-lg-3 col-md-6 col-12 mb-4 mt-4">
                                                        <strong class="site-footer-title d-block mb-3">Famili
                                                            Tanaman</strong>
                                                        <p class="mb-0" id="familiTanaman"></p>
                                                        <strong class="site-footer-title d-block mb-3 mt-4">Tanggal
                                                            Masuk</strong>
                                                        <p class="mb-0" id="tanggalMasuk"></p>
                                                        <strong class="site-footer-title d-block mb-3 mt-4">Kadar Air
                                                            Persen</strong>
                                                        <p class="mb-0" id="kadarAirPersen"></p>
                                                        <strong class="site-footer-title d-block mb-3 mt-4">Masa Berlaku
                                                            Sampai</strong>
                                                        <p class="mb-0" id="masaBerlakuSampai"></p>
                                                    </div>
                                                    <div class="col-lg-3 col-md-6 col-12 mb-4">
                                                        <strong class="site-footer-title d-block mb-3">Lokasi
                                                            Penyimpanan</strong>
                                                        <p class="mb-0" id="lokasiPenyimpanan"></p>
                                                    </div>
                                                    <div class="col-lg-3 col-md-6 col-12 mb-4">
                                                        <strong class="site-footer-title d-block mb-3">Titik Koleksi
                                                            Lat</strong>
                                                        <p class="mb-0" id="titikKoleksiLat"></p>
                                                    </div>
                                                    <div class="col-lg-3 col-md-6 col-12 mb-4">
                                                        <strong class="site-footer-title d-block mb-3">Titik Koleksi
                                                            Lng</strong>
                                                        <p class="mb-0" id="titikKoleksiLng"></p>
                                                    </div>
                                                    <div class="col-lg-3 col-md-6 col-12 mb-4">
                                                        <strong class="site-footer-title d-block mb-3">Jumlah
                                                            Ditanam</strong>
                                                        <p class="mb-0" id="jumlahDitanam"></p>
                                                    </div>
                                                    <div class="col-lg-3 col-md-6 col-12 mb-4">
                                                        <strong class="site-footer-title d-block mb-3">Satuan</strong>
                                                        <p class="mb-0" id="satuanBenih"></p>
                                                    </div>
                                                    <div class="col-lg-3 col-md-6 col-12 mb-4">
                                                        <strong class="site-footer-title d-block mb-3">Jumlah
                                                            Hidup</strong>
                                                        <p class="mb-0" id="jumlahHidup"></p>
                                                    </div>
                                                    <div class="col-lg-3 col-md-6 col-12 mb-4">
                                                        <strong class="site-footer-title d-block mb-3">Jumlah
                                                            Mati</strong>
                                                        <p class="mb-0" id="jumlahMati"></p>
                                                    </div>
                                                    <div class="col-lg-3 col-md-6 col-12 mb-4">
                                                        <strong class="site-footer-title d-block mb-3">Tinggi Rata-rata
                                                            (cm)</strong>
                                                        <p class="mb-0" id="tinggiRata2Cm"></p>
                                                    </div>
                                                    <div class="col-lg-3 col-md-6 col-12 mb-4">
                                                        <strong class="site-footer-title d-block mb-3">Diameter
                                                            Rata-rata (cm)</strong>
                                                        <p class="mb-0" id="diameterRata2Cm"></p>
                                                    </div>
                                                    <div class="col-lg-9 col-md-6 col-12 mb-4">
                                                        <strong class="site-footer-title d-block mb-3">Catatan</strong>
                                                        <p class="mb-0" id="catatanBenih"></p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="tab-pane fade show active" id="tab2monitoring" role="tabpanel"
                                                aria-labelledby="tab2monitoring-tab">
                                                <div class="row">
                                                    <div class="col-lg-12 col-md-12 col-12">
                                                        <strong class="site-footer-title d-block mb-3">Monitoring
                                                            Penanaman Benih
                                                        </strong>
                                                        <table class="profile-thumb">
                                                            <tr>
                                                                <th>Nama Tipe Penanaman</th>
                                                                <th>Progress Status Monitoring</th>
                                                                <th>Periode Pengecekan</th>
                                                                <th>Tanggal Tanam</th>
                                                                <th>Tanggal Monitoring</th>
                                                                <th>Luas Tanam ha</th>
                                                                <th>Survival Rate Persen</th>
                                                                <th>Catatan</th>
                                                            </tr>
                                                            <tbody id="benihMonitoringTableBody">
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="tab-pane fade" id="tab3tanahBenih" role="tabpanel"
                                                aria-labelledby="tab3tanahBenih-tab">
                                                <div class="row">
                                                    <div class="col-lg-3 col-md-6 col-12 mb-4">
                                                        <strong class="site-footer-title d-block mb-3">Nama
                                                            Lahan</strong>
                                                        <p class="mb-0" id="namaLahanBenih"></p>
                                                    </div>
                                                    <div class="col-lg-3 col-md-6 col-12 mb-4">
                                                        <strong class="site-footer-title d-block mb-3">Legalitas
                                                        </strong>
                                                        <p class="mb-0" id="legalitasLahanBenih"></p>
                                                    </div>
                                                    <div class="col-lg-3 col-md-6 col-12 mb-4">
                                                        <strong class="site-footer-title d-block mb-3">Luas
                                                        </strong>
                                                        <p class="mb-0" id="luasHaBenih"></p>
                                                    </div>
                                                    <div class="col-lg-3 col-md-6 col-12 mb-4">
                                                        <strong class="site-footer-title d-block mb-3">Sejarah
                                                        </strong>
                                                        <p class="mb-0" id="sejarahBenih"></p>
                                                    </div>
                                                    <div class="col-lg-6 col-md-6 col-12 mb-4">
                                                        <strong class="site-footer-title d-block mb-3">Alamat Lokasi
                                                        </strong>
                                                        <p class="mb-0" id="alamatLokasiBenih"></p>
                                                    </div>
                                                    <div class="col-lg-6 col-md-6 col-12 mb-4">
                                                        <strong class="site-footer-title d-block mb-3">Keterangan
                                                        </strong>
                                                        <p class="mb-0" id="keteranganBenih"></p>
                                                    </div>

                                                    <div class="col-lg-3 col-md-6 col-12 mb-4">
                                                        <strong class="site-footer-title d-block mb-3">Sudah
                                                            Validasi</strong>
                                                        <p class="mb-0" id="sudahValidasiBenih"></p>
                                                    </div>
                                                    <div class="col-lg-3 col-md-6 col-12 mb-4">
                                                        <strong class="site-footer-title d-block mb-3">Tanggal
                                                            Validasi</strong>
                                                        <p class="mb-0" id="tanggalValidasiBenih"></p>
                                                    </div>
                                                    <div class="col-lg-3 col-md-6 col-12 mb-4">
                                                        <strong class="site-footer-title d-block mb-3">Latitude</strong>
                                                        <p class="mb-0" id="centroidLatBenih"></p>
                                                    </div>
                                                    <div class="col-lg-3 col-md-6 col-12 mb-4">
                                                        <strong class="site-footer-title d-block mb-3">Longitude
                                                        </strong>
                                                        <p class="mb-0" id="centroidLngBenih"></p>
                                                    </div>

                                                    <div class="col-lg-12 col-md-12 col-12">
                                                        <strong class="site-footer-title d-block mb-3">Polygon
                                                        </strong>
                                                        <table class="profile-thumb">
                                                            <tbody id="polygonBenihTableBody"></tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- <div class="tab-pane fade" id="tab3obervasi" role="tabpanel"
                                                aria-labelledby="tab3obervasi-tab">
                                                <div class="row">
                                                    <div class="modal-body">
                                                        <ul class="nav nav-tabs" id="myTabHutanAdat" role="tablist">
                                                            <li class="nav-item" role="presentation">
                                                                <button class="nav-link active" id="tab31-tab"
                                                                    data-bs-toggle="tab" data-bs-target="#tab31"
                                                                    type="button" role="tab">
                                                                    Perairan Observasi
                                                                </button>
                                                            </li>

                                                            <li class="nav-item" role="presentation">
                                                                <button class="nav-link" id="tab32-tab"
                                                                    data-bs-toggle="tab" data-bs-target="#tab32"
                                                                    type="button" role="tab">
                                                                    Infrastruktur Observasi
                                                                </button>
                                                            </li>

                                                            <li class="nav-item" role="presentation">
                                                                <button class="nav-link" id="tab33-tab"
                                                                    data-bs-toggle="tab" data-bs-target="#tab33"
                                                                    type="button" role="tab">
                                                                    Land Cover Observasi
                                                                </button>
                                                            </li>

                                                            <li class="nav-item" role="presentation">
                                                                <button class="nav-link" id="tab34-tab"
                                                                    data-bs-toggle="tab" data-bs-target="#tab34"
                                                                    type="button" role="tab">
                                                                    Topografi Observasi
                                                                </button>
                                                            </li>

                                                            <li class="nav-item" role="presentation">
                                                                <button class="nav-link" id="tab35-tab"
                                                                    data-bs-toggle="tab" data-bs-target="#tab35"
                                                                    type="button" role="tab">
                                                                    Pohon Observasi
                                                                </button>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="tab-pane fade" id="tab4hasil" role="tabpanel"
                                                aria-labelledby="tab4hasil-tab">
                                                <div class="row">
                                                    <div class="col-lg-6 col-md-6 col-12 mb-4">
                                                        <div class="col">
                                                            <strong class="site-footer-title d-block mb-3">Grafik Stok
                                                                Benih & Jumlah Ditanam Benih
                                                            </strong>
                                                        </div>
                                                        <div class="col">
                                                            <div style="height:185px;">
                                                                <canvas id="stokChart"></canvas>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-lg-6 col-md-6 col-12 mb-4">
                                                        <div class="col">
                                                            <strong class="site-footer-title d-block mb-3">Tingkat
                                                                kelayakan atau daya hidup benih (viabilitas)
                                                            </strong>
                                                        </div>
                                                        <div class="col">
                                                            <div style="height:185px;">
                                                                <canvas id="viabilitasChart"></canvas>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-lg-6 col-md-6 col-12 mb-4">
                                                        <div class="col">
                                                            <strong class="site-footer-title d-block mb-3">Grafik Kadar
                                                                Air
                                                                Benih
                                                            </strong>
                                                        </div>
                                                        <div class="col">
                                                            <div style="height:185px;">
                                                                <canvas id="kadarAirChart"></canvas>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-lg-6 col-md-6 col-12 mb-4">
                                                        <div class="col">
                                                            <strong class="site-footer-title d-block mb-3">Grafik
                                                                Tingkat Kematian dan Kelangsungan Hidup Benih
                                                            </strong>
                                                        </div>
                                                        <div class="col">
                                                            <div style="height:185px;">
                                                                <canvas id="survivalChart"></canvas>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6 col-md-6 col-12 mb-4">
                                                        <div class="col">
                                                            <strong class="site-footer-title d-block mb-3">Grafik
                                                                Penyimpanan Benih Berdasarkan Tipe Penyimpanan
                                                            </strong>
                                                        </div>
                                                        <div class="col">
                                                            <div style="height:185px;">
                                                                <canvas id="penyimpananChart"></canvas>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div> -->
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
        </section>

        <section class="featured section-padding">
            <div class="container">
                <div class="row">

                    <div class="col-lg-6 col-12">
                        <div class="profile-thumb">
                            <div class="profile-title">
                                <h4 class="mb-0">Information</h4>
                            </div>

                            <div class="profile-body">
                                <p>
                                    <span class="profile-small-title">Name</span>
                                    <span>Joshua Morgan</span>
                                </p>

                                <p>
                                    <span class="profile-small-title">Birthday</span>
                                    <span>Aug 12, 1986</span>
                                </p>

                                <p>
                                    <span class="profile-small-title">Phone</span>
                                    <span><a href="tel: 305-240-9671">120-240-9600</a></span>
                                </p>

                                <p>
                                    <span class="profile-small-title">Email</span>
                                    <span><a href="mailto:hello@josh.design">hello@josh.design</a></span>
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-6 col-12 mt-5 mt-lg-0">
                        <div class="about-thumb">
                            <div class="row">
                                <div class="col-lg-6 col-6 featured-border-bottom py-2">
                                    <strong class="featured-numbers">20+</strong>

                                    <p class="featured-text">Years of Experiences</p>
                                </div>

                                <div class="col-lg-6 col-6 featured-border-start featured-border-bottom ps-5 py-2">
                                    <strong class="featured-numbers">245</strong>

                                    <p class="featured-text">Happy Customers</p>
                                </div>

                                <div class="col-lg-6 col-6 pt-4">
                                    <strong class="featured-numbers">640</strong>

                                    <p class="featured-text">Project Finished</p>
                                </div>

                                <div class="col-lg-6 col-6 featured-border-start ps-5 pt-4">
                                    <strong class="featured-numbers">72+</strong>

                                    <p class="featured-text">Digital Awards</p>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </section>


        <section class="clients section-padding">
            <div class="container">
                <div class="row align-items-center">

                    <div class="col-lg-12 col-12">
                        <h3 class="text-center mb-5">Companies I've had worked</h3>
                    </div>

                    <div class="col-lg-2 col-4 ms-auto clients-item-height">
                        <img src="images/clients/cachet.svg" class="clients-image img-fluid" alt="">
                    </div>

                    <div class="col-lg-2 col-4 clients-item-height">
                        <img src="images/clients/guitar-center.svg" class="clients-image img-fluid" alt="">
                    </div>

                    <div class="col-lg-2 col-4 clients-item-height">
                        <img src="images/clients/tokico.svg" class="clients-image img-fluid" alt="">
                    </div>

                    <div class="col-lg-2 col-4 clients-item-height">
                        <img src="images/clients/shopify.svg" class="clients-image img-fluid" alt="">
                    </div>

                    <div class="col-lg-2 col-4 me-auto clients-item-height">
                        <img src="images/clients/profil-rejser.svg" class="clients-image img-fluid" alt="">
                    </div>

                </div>
            </div>
        </section>


        <section class="services section-padding" id="section_3">
            <div class="container">
                <div class="row">

                    <div class="col-lg-10 col-12 mx-auto">
                        <div class="section-title-wrap d-flex justify-content-center align-items-center mb-5">
                            <img src="images/handshake-man-woman-after-signing-business-contract-closeup.jpg"
                                class="avatar-image img-fluid" alt="">

                            <h2 class="text-white ms-4 mb-0">Services</h2>
                        </div>

                        <div class="row pt-lg-5">
                            <div class="col-lg-6 col-12">
                                <div class="services-thumb">
                                    <div class="d-flex flex-wrap align-items-center border-bottom mb-4 pb-3">
                                        <h3 class="mb-0">Websites</h3>

                                        <div class="services-price-wrap ms-auto">
                                            <p class="services-price-text mb-0">$2,400</p>
                                            <div class="services-price-overlay"></div>
                                        </div>
                                    </div>

                                    <p>You may want to explore Too CSS for great collection of free HTML CSS templates.
                                    </p>

                                    <a href="#" class="custom-btn custom-border-btn btn mt-3">Discover More</a>

                                    <div class="services-icon-wrap d-flex justify-content-center align-items-center">
                                        <i class="services-icon bi-globe"></i>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-6 col-12">
                                <div class="services-thumb services-thumb-up">
                                    <div class="d-flex flex-wrap align-items-center border-bottom mb-4 pb-3">
                                        <h3 class="mb-0">Branding</h3>

                                        <div class="services-price-wrap ms-auto">
                                            <p class="services-price-text mb-0">$1,200</p>
                                            <div class="services-price-overlay"></div>
                                        </div>
                                    </div>

                                    <p>You can explore more CSS templates on TemplateMo website by browsing through
                                        different tags.</p>

                                    <a href="#" class="custom-btn custom-border-btn btn mt-3">Discover More</a>

                                    <div class="services-icon-wrap d-flex justify-content-center align-items-center">
                                        <i class="services-icon bi-lightbulb"></i>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-6 col-12">
                                <div class="services-thumb">
                                    <div class="d-flex flex-wrap align-items-center border-bottom mb-4 pb-3">
                                        <h3 class="mb-0">Ecommerce</h3>

                                        <div class="services-price-wrap ms-auto">
                                            <p class="services-price-text mb-0">$3,600</p>
                                            <div class="services-price-overlay"></div>
                                        </div>
                                    </div>

                                    <p>If you need a customized ecommerce website for your business, feel free to
                                        discuss with me.</p>

                                    <a href="#" class="custom-btn custom-border-btn btn mt-3">Discover More</a>

                                    <div class="services-icon-wrap d-flex justify-content-center align-items-center">
                                        <i class="services-icon bi-phone"></i>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-6 col-12">
                                <div class="services-thumb services-thumb-up">
                                    <div class="d-flex flex-wrap align-items-center border-bottom mb-4 pb-3">
                                        <h3 class="mb-0">SEO</h3>

                                        <div class="services-price-wrap ms-auto">
                                            <p class="services-price-text mb-0">$1,450</p>
                                            <div class="services-price-overlay"></div>
                                        </div>
                                    </div>

                                    <p>To list your website first on any search engine, we will work together. First
                                        Portfolio is one-page CSS Template for free download.</p>

                                    <a href="#" class="custom-btn custom-border-btn btn mt-3">Discover More</a>

                                    <div class="services-icon-wrap d-flex justify-content-center align-items-center">
                                        <i class="services-icon bi-google"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>


        <section class="projects section-padding" id="section_4">
            <div class="container">
                <div class="row">

                    <div class="col-lg-8 col-md-8 col-12 ms-auto">
                        <div class="section-title-wrap d-flex justify-content-center align-items-center mb-4">
                            <img src="images/white-desk-work-study-aesthetics.jpg" class="avatar-image img-fluid"
                                alt="">

                            <h2 class="text-white ms-4 mb-0">Projects</h2>
                        </div>
                    </div>

                    <div class="clearfix"></div>

                    <div class="col-lg-4 col-md-6 col-12">
                        <div class="projects-thumb">
                            <div class="projects-info">
                                <small class="projects-tag">Branding</small>

                                <h3 class="projects-title">Zoik agency</h3>
                            </div>

                            <a href="images/projects/nikhil-KO4io-eCAXA-unsplash.jpg" class="popup-image">
                                <img src="images/projects/nikhil-KO4io-eCAXA-unsplash.jpg"
                                    class="projects-image img-fluid" alt="">
                            </a>
                        </div>
                    </div>

                    <div class="col-lg-4 col-md-6 col-12">
                        <div class="projects-thumb">
                            <div class="projects-info">
                                <small class="projects-tag">Photography</small>

                                <h3 class="projects-title">The Watch</h3>
                            </div>

                            <a href="images/projects/the-5th-IQYR7N67dhM-unsplash.jpg" class="popup-image">
                                <img src="images/projects/the-5th-IQYR7N67dhM-unsplash.jpg"
                                    class="projects-image img-fluid" alt="">
                            </a>
                        </div>
                    </div>

                    <div class="col-lg-4 col-md-6 col-12">
                        <div class="projects-thumb">
                            <div class="projects-info">
                                <small class="projects-tag">Website</small>

                                <h3 class="projects-title">Polo</h3>
                            </div>

                            <a href="images/projects/true-agency-9Bjog5FZ-oc-unsplash.jpg" class="popup-image">
                                <img src="images/projects/true-agency-9Bjog5FZ-oc-unsplash.jpg"
                                    class="projects-image img-fluid" alt="">
                            </a>
                        </div>
                    </div>

                </div>
            </div>
        </section>

        <section class="contact section-padding" id="section_5">
            <div class="container">
                <div class="row">

                    <div class="col-lg-6 col-md-8 col-12">
                        <div class="section-title-wrap d-flex justify-content-center align-items-center mb-5">
                            <img src="images/aerial-view-man-using-computer-laptop-wooden-table.jpg"
                                class="avatar-image img-fluid" alt="">

                            <h2 class="text-white ms-4 mb-0">Say Hi</h2>
                        </div>
                    </div>

                    <div class="clearfix"></div>

                    <div class="col-lg-3 col-md-6 col-12 pe-lg-0">
                        <div class="contact-info contact-info-border-start d-flex flex-column">
                            <strong class="site-footer-title d-block mb-3">Services</strong>

                            <ul class="footer-menu">
                                <li class="footer-menu-item"><a href="#" class="footer-menu-link">Websites</a></li>

                                <li class="footer-menu-item"><a href="#" class="footer-menu-link">Branding</a></li>

                                <li class="footer-menu-item"><a href="#" class="footer-menu-link">Ecommerce</a></li>

                                <li class="footer-menu-item"><a href="#" class="footer-menu-link">SEO</a></li>
                            </ul>

                            <strong class="site-footer-title d-block mt-4 mb-3">Stay connected</strong>

                            <ul class="social-icon">
                                <li class="social-icon-item"><a href="https://twitter.com/minthu"
                                        class="social-icon-link bi-twitter"></a></li>

                                <li class="social-icon-item"><a href="#" class="social-icon-link bi-instagram"></a></li>

                                <li class="social-icon-item"><a href="#" class="social-icon-link bi-pinterest"></a></li>

                                <li class="social-icon-item"><a href="https://www.youtube.com/templatemo"
                                        class="social-icon-link bi-youtube"></a></li>
                            </ul>

                            <strong class="site-footer-title d-block mt-4 mb-3">Start a project</strong>

                            <p class="mb-0">I’m available for freelance projects</p>
                        </div>
                    </div>

                    <div class="col-lg-3 col-md-6 col-12 ps-lg-0">
                        <div class="contact-info d-flex flex-column">
                            <strong class="site-footer-title d-block mb-3">About</strong>

                            <p class="mb-2">
                                Joshua is a professional web developer. Feel free to get in touch with me.
                            </p>

                            <strong class="site-footer-title d-block mt-4 mb-3">Email</strong>

                            <p>
                                <a href="mailto:hello@josh.design">
                                    hello@josh.design
                                </a>
                            </p>

                            <strong class="site-footer-title d-block mt-4 mb-3">Call</strong>

                            <p class="mb-0">
                                <a href="tel: 120-240-9600">
                                    120-240-9600
                                </a>
                            </p>
                        </div>
                    </div>

                    <div class="col-lg-6 col-12 mt-5 mt-lg-0">
                        <form action="#" method="get" class="custom-form contact-form" role="form">
                            <div class="row">
                                <div class="col-lg-6 col-md-6 col-12">
                                    <div class="form-floating">
                                        <input type="text" name="name" id="name" class="form-control" placeholder="Name"
                                            required="">

                                        <label for="floatingInput">Name</label>
                                    </div>
                                </div>

                                <div class="col-lg-6 col-md-6 col-12">
                                    <div class="form-floating">
                                        <input type="email" name="email" id="email" pattern="[^ @]*@[^ @]*"
                                            class="form-control" placeholder="Email address" required="">

                                        <label for="floatingInput">Email address</label>
                                    </div>
                                </div>

                                <div class="col-lg-3 col-md-6 col-6">
                                    <div class="form-check form-check-inline">
                                        <input name="website" type="checkbox" class="form-check-input"
                                            id="inlineCheckbox1" value="1">

                                        <label class="form-check-label" for="inlineCheckbox1">
                                            <i class="bi-globe form-check-icon"></i>
                                            <span class="form-check-label-text">Websites</span>
                                        </label>
                                    </div>
                                </div>

                                <div class="col-lg-3 col-md-6 col-6">
                                    <div class="form-check form-check-inline">
                                        <input name="branding" type="checkbox" class="form-check-input"
                                            id="inlineCheckbox2" value="1">

                                        <label class="form-check-label" for="inlineCheckbox2">
                                            <i class="bi-lightbulb form-check-icon"></i>
                                            <span class="form-check-label-text">Branding</span>
                                        </label>
                                    </div>
                                </div>

                                <div class="col-lg-3 col-md-6 col-6">
                                    <div class="form-check form-check-inline">
                                        <input name="ecommerce" type="checkbox" class="form-check-input"
                                            id="inlineCheckbox3" value="1">

                                        <label class="form-check-label" for="inlineCheckbox3">
                                            <i class="bi-phone form-check-icon"></i>
                                            <span class="form-check-label-text">Ecommerce</span>
                                        </label>
                                    </div>
                                </div>

                                <div class="col-lg-3 col-md-6 col-6">
                                    <div class="form-check form-check-inline me-0">
                                        <input name="seo" type="checkbox" class="form-check-input" id="inlineCheckbox4"
                                            value="1">

                                        <label class="form-check-label" for="inlineCheckbox4">
                                            <i class="bi-google form-check-icon"></i>
                                            <span class="form-check-label-text">SEO</span>
                                        </label>
                                    </div>
                                </div>

                                <div class="col-lg-12 col-12">
                                    <div class="form-floating">
                                        <textarea class="form-control" id="message" name="message"
                                            placeholder="Tell me about the project"></textarea>

                                        <label for="floatingTextarea">Tell me about the project</label>
                                    </div>
                                </div>

                                <div class="col-lg-3 col-12 ms-auto">
                                    <button type="submit" class="form-control">Send</button>
                                </div>

                            </div>
                        </form>
                    </div>

                </div>
            </div>
            </div>
        </section>

    </main>

    <footer class="site-footer">
        <div class="container">
            <div class="row">

                <div class="col-lg-12 col-12">
                    <div class="copyright-text-wrap">
                        <p class="mb-0">
                            <span class="copyright-text">Copyright © 2026 <a href="#">Borneologi</a>. All
                                rights reserved.</span>
                            Design:
                            <a rel="sponsored" href="https://templatemo.com" target="_blank">TemplateMo</a>
                        </p>
                    </div>
                </div>

            </div>
        </div>
    </footer>

    <!-- JAVASCRIPT FILES -->
    <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/jquery.sticky.js"></script>
    <script src="js/click-scroll.js"></script>
    <script src="js/jquery.magnific-popup.min.js"></script>
    <script src="js/magnific-popup-options.js"></script>
    <script src="js/custom.js"></script>

    <script>
        const map = L.map('map').setView([-1.5, 114.5], 6.5);

        L.control.fullscreen({
            position: 'topleft',
            title: 'Full Screen',
            titleCancel: 'Exit Full Screen'
        }).addTo(map);

        const allPolygons = [];
        const allMarkers = [];

        map.createPane('paneHutanAdat');
        map.getPane('paneHutanAdat').style.zIndex = 400;

        map.createPane('paneKaleka');
        map.getPane('paneKaleka').style.zIndex = 500;

        map.createPane('paneBenih');
        map.getPane('paneBenih').style.zIndex = 500;

        const satellite = L.tileLayer(
            'https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
            attribution: 'Tiles © Esri'
        }
        );

        const street = L.tileLayer(
            'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap'
        }
        );

        const topo = L.tileLayer(
            'https://{s}.tile.opentopomap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenTopoMap'
        }
        );

        const light = L.tileLayer(
            'https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png', {
            attribution: '© CartoDB'
        }
        );

        const dark = L.tileLayer(
            'https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png', {
            attribution: '© CartoDB'
        }
        );

        const labels = L.tileLayer(
            'https://services.arcgisonline.com/ArcGIS/rest/services/Reference/World_Boundaries_and_Places/MapServer/tile/{z}/{y}/{x}', {
            attribution: '© Esri'
        }
        );

        const osmLabels = L.tileLayer(
            'https://{s}.basemaps.cartocdn.com/light_only_labels/{z}/{x}/{y}{r}.png', {
            attribution: '© OpenStreetMap © CartoDB',
            pane: 'labels'
        }
        );

        map.createPane('labels');
        map.getPane('labels').style.zIndex = 650;
        map.getPane('labels').style.pointerEvents = 'none';

        const hybrid = L.layerGroup([satellite, osmLabels]);

        hybrid.addTo(map);

        L.control.layers({
            "Hybrid": hybrid,
            // "Satellite": satellite,
            "Street": street,
            "Topographic": topo,
            "Light": light,
            "Dark": dark,
        }).addTo(map);


        map.on('click', function (e) {
            console.log('LatLng:', e.latlng.lat, e.latlng.lng);
        });

        let allKaleka = [];
        let allBenih = [];

        fetch('data.php')
            .then(res => res.json())
            .then(data => {

                allKaleka = data.kaleka; // simpan global
                allBenih = data.benih; // simpan global

                data.hutan_adat.forEach(item => {
                    const hutanAdat = L.polygon(
                        item.tanah.geom_area, {
                        pane: 'paneHutanAdat',
                        color: '#FF4400',
                        fillOpacity: 0.35
                    }
                    ).addTo(map);

                    allPolygons.push(hutanAdat);

                    hutanAdat.on('click', (e) => {
                        showHutanAdatModal(item, e.latlng);
                    });
                    const center = getCentroid(item.tanah.geom_area);
                    L.marker(center).addTo(map);
                });

                data.kaleka.forEach(item => {
                    const kalekaPolygon = L.polygon(item.tanah.geom_area, {
                        pane: 'paneKaleka',
                        color: '#4DFFBE',
                        fillOpacity: 0.55
                    }).addTo(map);

                    kalekaPolygon.on('click', (e) => {
                        showKalekaModal(item, e.latlng);
                    });
                    const center = getCentroid(item.tanah.geom_area);
                    L.marker(center).addTo(map);
                });

                data.benih.forEach(item => {
                    const benihPolygon = L.polygon(item.bank_benih.tanah.geom_area, {
                        pane: 'paneBenih',
                        color: '#FFD45A',
                        fillOpacity: 0.55
                    }).addTo(map);

                    benihPolygon.on('click', (e) => {
                        showBenihModal(item, e.latlng);
                    });
                    const center = getCentroid(item.bank_benih.tanah.geom_area);
                    L.marker(center).addTo(map);
                });

                // const total =
                //     (data.hutan_adat?.length || 0) +
                //     (data.kaleka?.length || 0);

                const totalHutanAdat = Array.isArray(data.hutan_adat) ? data.hutan_adat.length : 0;
                const totalKaleka = Array.isArray(data.kaleka) ? data.kaleka.length : 0;
                const totalBenih = Array.isArray(data.benih) ? data.benih.length : 0;
                const total = totalHutanAdat + totalKaleka + totalBenih;

                // const total =
                //     Array.isArray(data.hutan_adat) ? data.hutan_adat.length : 0 +
                //         Array.isArray(data.kaleka) ? data.kaleka.length : 0;

                createTotalMarker(total);

                // Legend Control
                const legend = L.control({
                    position: 'topright'
                });

                legend.onAdd = function (map) {
                    const div = L.DomUtil.create('div', 'info legend');
                    div.innerHTML = `
                        <div style="
                            background:white;
                            padding:10px;
                            border-radius:8px;
                            box-shadow:0 3px 8px rgba(0,0,0,0.2);
                            font-size:14px;
                        ">
                            <strong>Keterangan</strong><br><br>

                            <div style="display:flex; align-items:center; margin-bottom:6px;">
                                <span style="
                                    width:18px;
                                    height:18px;
                                    background:#FF4400;
                                    display:inline-block;
                                    margin-right:8px;
                                "></span>
                                Hutan Adat (${totalHutanAdat})
                            </div>

                            <div style="display:flex; align-items:center; margin-bottom:6px;">
                                <span style="
                                    width:18px;
                                    height:18px;
                                    background:#4DFFBE;
                                    display:inline-block;
                                    margin-right:8px;
                                "></span>
                                Kaleka (${totalKaleka})
                            </div>

                            <div style="display:flex; align-items:center;">
                                <span style="
                                    width:18px;
                                    height:18px;
                                    background:#FFD45A;
                                    display:inline-block;
                                    margin-right:8px;
                                "></span>
                                Benih (${totalBenih})
                            </div>
                        </div>
                    `;
                    return div;
                };
                legend.addTo(map);
            });

        let totalMarker;

        function createTotalMarker(total) {

            const group = L.featureGroup(allPolygons);
            const center = group.getBounds().getCenter();

            totalMarker = L.marker(center, {
                icon: L.divIcon({
                    className: 'total-marker',
                    html: `
                        <div style="
                            background:#0d6efd;
                            color:white;
                            width:24px;
                            height:24px;
                            border-radius:50%;
                            display:flex;
                            align-items:center;
                            justify-content:center;
                            font-size:16px;
                            font-weight:700;
                            box-shadow:0 3px 8px rgba(0,0,0,.35);
                        ">
                            ${total}
                        </div>
                        `
                })
            }).addTo(map);

            totalMarker.on('click', () => {
                map.fitBounds(group.getBounds());
            });

            toggleLayers();
        }

        function toggleLayers() {
            const zoom = map.getZoom();

            if (zoom < 14) {
                allPolygons.forEach(p => map.removeLayer(p));
                allMarkers.forEach(m => map.removeLayer(m));
                map.addLayer(totalMarker);
            } else {
                allPolygons.forEach(p => map.addLayer(p));
                allMarkers.forEach(m => map.addLayer(m));
                map.removeLayer(totalMarker);
            }
        }

        function showHutanAdatModal(item, latlng) {
            document.querySelector('.modal-title1').innerText = 'Informasi Hutan Adat';
            document.getElementById('namaHutanAdat').innerText = item.nama_hutan_adat;
            document.getElementById('nomorSk').innerText = item.nomor_sk;
            document.getElementById('tanggalSk').innerText = item.tanggal_sk;
            document.getElementById('statusKawasan').innerText = item.status_kawasan;
            document.getElementById('namaDesaHutanAdat').innerText = item.desa.nama_desa;
            document.getElementById('namaKecamatanHutanAdat').innerText = item.desa.nama_kecamatan;
            document.getElementById('namaKabupatenHutanAdat').innerText = item.desa.nama_kabupaten;
            document.getElementById('namaKelompokTani').innerText = item.kelompok_tani.nama_kelompok_tani;
            document.getElementById('kategoriKelompok').innerText = item.kelompok_tani.kategori_kelompok;
            document.getElementById('deskripsiKelompokTani').innerText = item.kelompok_tani.deskripsi;
            document.getElementById('alamatKelompokTani').innerText = item.kelompok_tani.alamat;
            document.getElementById('tahunBentuk').innerText = item.kelompok_tani.tahun_bentuk;
            document.getElementById('statusKelompok').innerText = item.kelompok_tani.status_kelompok;
            document.getElementById('totalAnggota').innerText = item.kelompok_tani.total_anggota;

            document.getElementById('namaLahanHutanAdat').innerText = item.tanah.nama_lahan;
            document.getElementById('legalitasLahanHutanAdat').innerText = item.tanah.legalitas_lahan;
            document.getElementById('luasHaHutanAdat').innerText = item.tanah.luas_ha;
            document.getElementById('alamatLokasiHutanAdat').innerText = item.tanah.alamat_lokasi;
            document.getElementById('keteranganHutanAdat').innerText = item.tanah.keterangan;
            document.getElementById('sudahValidasiHutanAdat').innerText = item.tanah.sudah_validasi;
            document.getElementById('tanggalValidasiHutanAdat').innerText = item.tanah.tanggal_validasi;
            document.getElementById('sejarahHutanAdat').innerText = item.tanah.sejarah;
            document.getElementById('centroidLatHutanAdat').innerText = item.tanah.centroid_lat;
            document.getElementById('centroidLngHutanAdat').innerText = item.tanah.centroid_lng;

            let tableBodyPetaniKelompok = document.getElementById('petaniKelompokTableBody');
            tableBodyPetaniKelompok.innerHTML = ""; // reset dulu

            if (item.kelompok_tani.petani_kelompok && item.kelompok_tani.petani_kelompok.length > 0) {
                item.kelompok_tani.petani_kelompok.forEach((data, index) => {
                    let row = `
                    <tr>
                        <td>${data.petani.nama_lengkap}</td>
                        <td>${data.petani.nama_panggilan}</td>
                        <td>${data.petani.jenis_kelamin}</td>
                        <td>${data.petani.umur}</td>
                        <td>${data.petani.status_petani}</td>
                        <td>${data.petani.alamat}</td>
                        <td><img src="${data.petani.foto}" alt="Foto Petani" width="50" height="50" class="img-fluid rounded-circle"></td>
                    </tr>
                `;
                    tableBodyPetaniKelompok.innerHTML += row;
                });
            } else {
                tableBodyPetaniKelompok.innerHTML = `
                    <tr>
                        <td colspan="5" style="text-align:center;">Tidak ada data petani</td>
                    </tr>
                `;
            }

            let tableBodyPolygon = document.getElementById('polygonHutanAdatTableBody');
            tableBodyPolygon.innerHTML = "";

            if (item.tanah.geom_area && item.tanah.geom_area.length > 0) {
                let rowLat = `<tr><th>Latitude</th>`;
                let rowLng = `<tr><th>Longitude</th>`;

                item.tanah.geom_area.forEach((point, index) => {
                    rowLat += `<td>${point[0]}</td>`;
                    rowLng += `<td>${point[1]}</td>`;
                });

                rowLat += `</tr>`;
                rowLng += `</tr>`;

                tableBodyPolygon.innerHTML = rowLat + rowLng;

            } else {
                tableBodyPolygon.innerHTML = `
                    <tr>
                        <td colspan="10" style="text-align:center;">
                            Tidak ada data koordinat
                        </td>
                    </tr>
                `;
            }


            let tableBodyPolygonHutanAdatKaleka = document.getElementById('polygonHutanAdatKalekaTableBody');
            tableBodyPolygonHutanAdatKaleka.innerHTML = "";

            if (item.kaleka_info && item.kaleka_info.length > 0) {
                item.kaleka_info.forEach((data, index) => {
                    let row = document.createElement("tr");

                    row.innerHTML = `
                        <td>${data.nama_kaleka}</td>
                        <td>${data.desa.nama_desa}</td>
                        <td>${data.desa.nama_kecamatan}</td>
                        <td>${data.desa.nama_kabupaten}</td>
                        <td>${data.tanah.luas_ha}</td>
                        <td>${data.tanah.alamat_lokasi}</td>
                    `;

                    row.style.cursor = "pointer";

                    row.addEventListener("click", function () {
                        let id = data.id_kaleka;

                        let kalekaLengkap = allKaleka.find(b =>
                            b.id_kaleka == id
                        );

                        if (kalekaLengkap) {
                            showKalekaModal(kalekaLengkap);
                        }
                    });
                    tableBodyPolygonHutanAdatKaleka.appendChild(row);
                //     let row = `
                //     <tr>
                //         <td>${data.nama_kaleka}</td>
                //         <td>${data.desa.nama_desa}</td>
                //         <td>${data.desa.nama_kecamatan}</td>
                //         <td>${data.desa.nama_kabupaten}</td>
                //         <td>${data.tanah.luas_ha}</td>
                //         <td>${data.tanah.alamat_lokasi}</td>
                //     </tr>
                // `;
                //     tableBodyPolygonHutanAdatKaleka.innerHTML += row;
                });
            } else {
                tableBodyPolygonHutanAdatKaleka.innerHTML = `
                    <tr>
                        <td colspan="5" style="text-align:center;">Tidak ada data Kaleka</td>
                    </tr>
                `;
            }


            let tableBodyPolygonHutanAdatBenih = document.getElementById('polygonHutanAdatBenihTableBody');
            tableBodyPolygonHutanAdatBenih.innerHTML = "";

            if (item.benih_info && item.benih_info.length > 0) {
                item.benih_info.forEach((data, index) => {
                    let row = document.createElement("tr");

                    row.innerHTML = `
                        <td>${data.bank_benih.nama_lokal}</td>
                        <td>${data.bank_benih.nama_ilmiah}</td>
                        <td>${data.bank_benih.tanah.luas_ha}</td>
                        <td>${data.bank_benih.tanah.alamat_lokasi}</td>
                    `;

                    row.style.cursor = "pointer";

                    row.addEventListener("click", function () {
                        // showBenihModal(data);
                        let id = data.bank_benih.id_bank_benih;

                        let benihLengkap = allBenih.find(b =>
                            b.bank_benih.id_bank_benih == id
                        );

                        if (benihLengkap) {
                            showBenihModal(benihLengkap);
                        }
                    });

                    tableBodyPolygonHutanAdatBenih.appendChild(row);
                });
            } else {
                tableBodyPolygonHutanAdatBenih.innerHTML = `
                    <tr>
                        <td colspan="5" style="text-align:center;">Tidak ada data Benih</td>
                    </tr>
                `;
            }

            new bootstrap.Modal(document.getElementById('hutanAdatModal')).show();

            // fillLahan(item.lahan, latlng);
        }

        function showKalekaModal(item, latlng) {
            document.querySelector('.modal-title2').innerText = 'Informasi Kaleka';

            document.getElementById('namaLengkap').innerText = item.petani.nama_lengkap;
            document.getElementById('namaPanggilan').innerText = item.petani.nama_panggilan;
            // document.getElementById('nik').innerText = item.petani.nik;
            // document.getElementById('noKk').innerText = item.petani.no_kk;
            document.getElementById('jenisKelamin').innerText = item.petani.jenis_kelamin;
            // document.getElementById('tanggalLahir').innerText = item.petani.tanggal_lahir;
            document.getElementById('umur').innerText = item.petani.umur;
            // document.getElementById('nomorHp').innerText = item.petani.nomor_hp;
            document.getElementById('alamat').innerText = item.petani.alamat;
            document.getElementById('statusPetani').innerText = item.petani.status_petani;
            document.getElementById('petaniFoto').src = item.petani.foto;
            document.getElementById('petaniFotoLink').href = item.petani.foto;
            document.getElementById('namaDesa').innerText = item.desa.nama_desa;
            // document.getElementById('tipeWilayah').innerText = item.desa.tipe_wilayah;
            document.getElementById('namaKecamatan').innerText = item.desa.nama_kecamatan;
            document.getElementById('namaKabupaten').innerText = item.desa.nama_kabupaten;
            // document.getElementById('tipe').innerText = item.desa.tipe;
            // document.getElementById('namaProvinsi').innerText = item.desa.nama_provinsi;
            // document.getElementById('kodePos').innerText = item.desa.kode_pos;

            document.getElementById('perairanPeriodePengecekan').innerText = item.perairan_observasi.periode_pengecekan;
            document.getElementById('perairanWarnaAir').innerText = item.perairan_observasi.warna_air;
            document.getElementById('perairanJenisPalung').innerText = item.perairan_observasi.jenis_palung;
            document.getElementById('perairanKecepatanAliran').innerText = item.perairan_observasi.kecepatan_aliran;
            document.getElementById('perairanKedalaman').innerText = item.perairan_observasi.kedalaman_cm;
            document.getElementById('perairanLebar').innerText = item.perairan_observasi.lebar_m;
            document.getElementById('perairanDebit').innerText = item.perairan_observasi.debit_lps;
            document.getElementById('perairanPh').innerText = item.perairan_observasi.ph;
            document.getElementById('perairanKekeruhan').innerText = item.perairan_observasi.kekeruhan_ntu;
            document.getElementById('perairanCatatan').innerText = item.perairan_observasi.catatan;

            document.getElementById('infrastrukturPeriodePengecekan').innerText = item.infrastruktur_observasi.periode_pengecekan;
            document.getElementById('infrastrukturAksesPerjalanan').innerText = item.infrastruktur_observasi.akses_perjalanan;
            document.getElementById('infrastrukturKondisiJalan').innerText = item.infrastruktur_observasi.kondisi_jalan;
            document.getElementById('infrastrukturJarakKeJalan').innerText = item.infrastruktur_observasi.jarak_ke_jalan_km;
            document.getElementById('infrastrukturAdaJembatan').innerText = item.infrastruktur_observasi.ada_jembatan;
            document.getElementById('infrastrukturAdaListrik').innerText = item.infrastruktur_observasi.ada_listrik;
            document.getElementById('infrastrukturAdaInternet').innerText = item.infrastruktur_observasi.ada_internet;
            document.getElementById('infrastrukturSinyalSeluler').innerText = item.infrastruktur_observasi.sinyal_seluler;
            document.getElementById('infrastrukturCatatan').innerText = item.infrastruktur_observasi.catatan;

            document.getElementById('landCoverPeriodePengecekan').innerText = item.land_cover_observasi.periode_pengecekan;
            document.getElementById('landCoverKategoriArea').innerText = item.land_cover_observasi.kategori_area;
            document.getElementById('landCoverPenggunaanPertanian').innerText = item.land_cover_observasi.penggunaan_pertanian;
            document.getElementById('landCoverPenggunaanLainnya').innerText = item.land_cover_observasi.penggunaan_lainnya;
            document.getElementById('landCoverPersentaseTutupan').innerText = item.land_cover_observasi.persentase_tutupan;
            document.getElementById('landCoverCatatan').innerText = item.land_cover_observasi.catatan;

            document.getElementById('topografiPeriodePengecekan').innerText = item.topografi_observasi.periode_pengecekan;
            document.getElementById('topografiLanskap').innerText = item.topografi_observasi.lanskap;
            document.getElementById('topografiFiturTambahan').innerText = item.topografi_observasi.fitur_tambahan;
            document.getElementById('topografiElevasiMdpl').innerText = item.topografi_observasi.elevasi_mdpl;
            document.getElementById('topografiKemiringanDerajat').innerText = item.topografi_observasi.kemiringan_derajat;
            document.getElementById('topografiRawanErosi').innerText = item.topografi_observasi.rawan_erosi;
            document.getElementById('topografiArahLereng').innerText = item.topografi_observasi.arah_lereng;
            document.getElementById('topografiCatatan').innerText = item.topografi_observasi.catatan;

            document.getElementById('pohonPeriodePengecekan').innerText = item.pohon_observasi.periode_pengecekan;
            document.getElementById('pohonNamaPohon').innerText = item.pohon_observasi.nama_pohon;
            document.getElementById('pohonNamaLatin').innerText = item.pohon_observasi.nama_latin;
            document.getElementById('pohonFungsiPohon').innerText = item.pohon_observasi.fungsi_pohon;
            document.getElementById('pohonJumlahPohon').innerText = item.pohon_observasi.jumlah_pohon;
            document.getElementById('pohonDiameterRata').innerText = item.pohon_observasi.diameter_rata2_cm;
            document.getElementById('pohonTinggiRata').innerText = item.pohon_observasi.tinggi_rata2_m;
            document.getElementById('pohonKondisi').innerText = item.pohon_observasi.kondisi;
            document.getElementById('pohonCatatan').innerText = item.pohon_observasi.catatan;

            document.getElementById('namaKaleka').innerText = item.nama_kaleka;
            // document.getElementById('pohonGambar').src = item.pohon_observasi.gambar_benih;
            // document.getElementById('pohonGambarLink').href = item.pohon_observasi.gambar_benih;

            let tableBodyKelompok = document.getElementById('kelompokTableBody');
            tableBodyKelompok.innerHTML = ""; // reset dulu

            if (item.petani.petani_kelompok && item.petani.petani_kelompok.length > 0) {
                item.petani.petani_kelompok.forEach((kelompok, index) => {
                    let row = `
                <tr>
                    <td>${kelompok.nama_kelompok_tani}</td>
                    <td>${kelompok.kategori_kelompok}</td>
                    <td>${kelompok.tahun_gabung}</td>
                </tr>
            `;
                    tableBodyKelompok.innerHTML += row;
                });
            } else {
                tableBodyKelompok.innerHTML = `
                    <tr>
                        <td colspan="5" style="text-align:center;">Tidak ada data kelompok</td>
                    </tr>
                `;
            }

            let tableBodyPolygon = document.getElementById('polygonTableBody');
            tableBodyPolygon.innerHTML = "";

            if (item.tanah.geom_area && item.tanah.geom_area.length > 0) {
                let rowLat = `<tr><th>Latitude</th>`;
                let rowLng = `<tr><th>Longitude</th>`;

                item.tanah.geom_area.forEach((point, index) => {
                    rowLat += `<td>${point[0]}</td>`;
                    rowLng += `<td>${point[1]}</td>`;
                });

                rowLat += `</tr>`;
                rowLng += `</tr>`;

                tableBodyPolygon.innerHTML = rowLat + rowLng;

            } else {
                tableBodyPolygon.innerHTML = `
                    <tr>
                        <td colspan="10" style="text-align:center;">
                            Tidak ada data koordinat
                        </td>
                    </tr>
                `;
            }

            // let tableBodyMonitoringPenanamanBenih = document.getElementById('monitoringPenanamanBenihTableBody');
            // tableBodyMonitoringPenanamanBenih.innerHTML = ""; // reset dulu

            // if (item.monitoring_penanaman_benih && item.monitoring_penanaman_benih.length > 0) {
            //     item.monitoring_penanaman_benih.forEach((data, index) => {
            //         let row = `
            //     <tr>
            //         <td>${index + 1}</td>
            //         <td>${data.aksesi_nomor}</td>
            //         <td>${data.nama_koleksi}</td>
            //         <td>${data.nama_latin}</td>
            //         <td>${data.nama_inggris}</td>
            //         <td>${data.tipe_penyimpanan}</td>
            //         <td>${data.tipe_penanaman}</td>
            //         <td>${data.kode_negara}</td>
            //         <td>${data.ketinggian}</td>
            //         <td>${data.periode_benih_disimpan}</td>
            //         <td>${data.tipe_geography}</td>
            //         <td>${data.koordinat}</td>
            //         <td>${data.periode_waktu_tanam}</td>
            //         <td>${data.progress_status_monitoring_tanaman}</td>
            //         <td>${data.periode_benih_dipanen}</td>
            //     </tr>
            // `;
            //         tableBodyMonitoringPenanamanBenih.innerHTML += row;
            //     });
            // } else {
            //     tableBodyMonitoringPenanamanBenih.innerHTML = `
            //         <tr>
            //             <td colspan="5" style="text-align:center;">Tidak ada data kelompok</td>
            //         </tr>
            //     `;
            // }

            fillLahan(item.tanah, latlng);

            // ==============================
            // Ambil bank_benih hanya dari kaleka yang diklik
            // ==============================

            let bankBenih = [];

            if (item.bank_benih && item.bank_benih) {
                bankBenih = item.bank_benih;
            }

            // Reset chart jika sudah ada
            if (window.stokChartInstance) window.stokChartInstance.destroy();
            if (window.jumlahDitanamChartInstance) window.jumlahDitanamChartInstance.destroy();
            if (window.viabilitasChartInstance) window.viabilitasChartInstance.destroy();
            if (window.survivalChartInstance) window.survivalChartInstance.destroy();
            if (window.kadarAirChartInstance) window.kadarAirChartInstance.destroy();
            if (window.penyimpananChartInstance) window.penyimpananChartInstance.destroy();
            if (window.penyimpananChart2Instance) window.penyimpananChart2Instance.destroy();

            let labelsData = [];
            let stokData = [];
            let tanamData = [];
            let viabilitasData = [];
            let kadarAirData = [];
            let totalHidup = [];
            let totalMati = [];
            // let totalHidup = 0;
            // let totalMati = 0;
            let tipeCounts = {};

            bankBenih.forEach(b => {
                labelsData.push(b.nama_lokal);
                stokData.push(parseInt(b.jumlah_stok));
                tanamData.push(parseInt(b.jumlah_ditanam));
                viabilitasData.push(parseInt(b.viabilitas_persen));
                kadarAirData.push(parseInt(b.kadar_air_persen));
                totalHidup.push(parseInt(b.jumlah_hidup));
                totalMati.push(parseInt(b.jumlah_mati));
                // totalHidup += parseInt(b.jumlah_hidup);
                // totalMati += parseInt(b.jumlah_mati);
                let tipe = b.tipe_penyimpanan_benih;
                if (tipeCounts[tipe]) {
                    tipeCounts[tipe] += 1;
                } else {
                    tipeCounts[tipe] = 1;
                }
            });

            // Ambil tipe unik sesuai urutan kemunculan
            let tipeUnik = [];
            bankBenih.forEach(b => {
                if (!tipeUnik.includes(b.tipe_penyimpanan_benih)) {
                    tipeUnik.push(b.tipe_penyimpanan_benih);
                }
            });

            // 🎨 Warna tetap berdasarkan URUTAN
            let warnaUrutanBackgroundColor = [
                '#0fb8882e', // urutan 1
                '#FFAA002e', // urutan 2
                '#FF44002e', // urutan 3
                '#3D45AA2e', // urutan 4
                '#6E50342e', // urutan 5
            ];
            let warnaUrutanBorderColor = [
                '#0fb888', // urutan 1
                '#FFAA00', // urutan 2
                '#FF4400', // urutan 3
                '#3D45AA', // urutan 4
                '#6E5034', // urutan 5
            ];

            // Buat dataset
            let datasetsData = tipeUnik.map((tipe, index) => {

                return {
                    label: tipe,
                    data: labelsData.map(labelNama => {

                        let filtered = bankBenih.filter(b =>
                            b.nama_lokal === labelNama &&
                            b.tipe_penyimpanan_benih === tipe
                        );

                        // otomatis jumlahkan kalau ada lebih dari 1
                        return filtered.reduce((sum, item) => {
                            return sum + parseInt(item.jumlah_stok);
                        }, 0);

                    }),
                    backgroundColor: warnaUrutanBackgroundColor[index % warnaUrutanBackgroundColor.length],
                    borderColor: warnaUrutanBorderColor[index % warnaUrutanBorderColor.length],
                    borderWidth: 1,
                };
            });

            let tipeLabels = Object.keys(tipeCounts);
            let tipeData = Object.values(tipeCounts);

            let satuanStokLabel = bankBenih.length > 0 ? bankBenih[0].satuan_stok : '';
            let satuanTanamLabel = bankBenih.length > 0 ? bankBenih[0].satuan : '';

            // Buat chart baru
            window.stokChartInstance = new Chart(document.getElementById('stokChart'), {
                type: 'bar',
                data: {
                    labels: labelsData,
                    datasets: [{
                        label: 'Jumlah Stok (' + satuanStokLabel + ')',
                        data: stokData,
                        backgroundColor: '#0fb8882e',
                        borderColor: '#0fb888',
                        borderWidth: 1,
                    },
                    {
                        label: 'Jumlah Ditanam (' + satuanTanamLabel + ')',
                        data: tanamData,
                        backgroundColor: '#2372272e',
                        borderColor: '#237227',
                        borderWidth: 1,
                    }
                    ]
                },
                options: {
                    plugins: {
                        legend: {
                            display: true,
                            position: 'top'
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: 'Satuan (' + satuanStokLabel + ')' // Pindah ke kiri
                            },
                            ticks: {
                                callback: function (value) {
                                    return value + ' ' + satuanStokLabel;
                                }
                            }
                        }
                    },
                    interaction: {
                        mode: 'index',
                        intersect: false
                    },
                    // responsive: true,
                    maintainAspectRatio: false
                }
            });

            let satuanLabel = bankBenih.length > 0 ? bankBenih[0].satuan : '';

            window.viabilitasChartInstance = new Chart(document.getElementById('viabilitasChart'), {
                type: 'line',
                data: {
                    labels: labelsData,
                    datasets: [{
                        label: 'Viabilitas (%)',
                        data: viabilitasData,
                        borderColor: '#0fb888',
                        backgroundColor: '#0fb888',
                        tension: 0.4,
                        fill: false,
                        pointRadius: 5,
                        borderWidth: 3
                    }]
                },
                options: {
                    plugins: {
                        legend: {
                            display: false // Hilangkan legend atas
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: 'Persentase (%)' // Pindah ke kiri
                            },
                            ticks: {
                                callback: function (value) {
                                    return value + '%'; // Tambah simbol %
                                }
                            }
                        }
                    },
                    interaction: {
                        mode: 'index',
                        intersect: false
                    },
                    // responsive: true,
                    maintainAspectRatio: false
                }
            });

            // window.survivalChartInstance = new Chart(document.getElementById('survivalChart'), {
            //     type: 'doughnut',
            //     data: {
            //         labels: ['Hidup', 'Mati'],
            //         datasets: [{
            //             label: 'Jumlah (' + satuanStokLabel + ')',
            //             data: [totalHidup, totalMati],
            //             backgroundColor: ['#0fb888', '#b80f0f']
            //         }],
            //     },
            //     options: {
            //         interaction: {
            //             mode: 'index',
            //             intersect: false
            //         },
            //         // responsive: true,
            //         maintainAspectRatio: false
            //     }
            // });

            window.kadarAirChartInstance = new Chart(document.getElementById('kadarAirChart'), {
                type: 'line',
                data: {
                    labels: labelsData,
                    datasets: [{
                        label: 'Kadar Air (%)',
                        data: kadarAirData,
                        borderColor: '#0fb888',
                        backgroundColor: '#0fb888',
                        tension: 0.4,
                        fill: false,
                        pointRadius: 5,
                        borderWidth: 3
                    }]
                },
                options: {
                    plugins: {
                        legend: {
                            display: false // Hilangkan legend atas
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: 'Persentase (%)' // Pindah ke kiri
                            },
                            ticks: {
                                callback: function (value) {
                                    return value + '%'; // Tambah simbol %
                                }
                            }
                        }
                    },
                    interaction: {
                        mode: 'index',
                        intersect: false
                    },
                    // responsive: true,
                    maintainAspectRatio: false
                }
            });

            window.survivalChartInstance = new Chart(document.getElementById('survivalChart'), {
                type: 'bar',
                data: {
                    labels: labelsData,
                    datasets: [{
                        label: 'Benih Hidup (' + satuanStokLabel + ')',
                        data: totalHidup,
                        backgroundColor: '#0fb8882e',
                        borderColor: '#0fb888',
                        borderWidth: 1,
                    },
                    {
                        label: 'Benih Mati (' + satuanTanamLabel + ')',
                        data: totalMati,
                        backgroundColor: '#7223232e',
                        borderColor: '#722323',
                        borderWidth: 1,
                    }
                    ]
                },
                options: {
                    plugins: {
                        legend: {
                            display: true,
                            position: 'top'
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: 'Satuan (' + satuanStokLabel + ')' // Pindah ke kiri
                            },
                            ticks: {
                                callback: function (value) {
                                    return value + ' ' + satuanStokLabel;
                                }
                            }
                        }
                    },
                    interaction: {
                        mode: 'index',
                        intersect: false
                    },
                    // responsive: true,
                    maintainAspectRatio: false
                }
            });

            window.penyimpananChartInstance = new Chart(document.getElementById('penyimpananChart'), {
                type: 'doughnut',
                data: {
                    labels: tipeLabels,
                    datasets: [{
                        data: tipeData,
                        backgroundColor: [
                            '#0fb888',
                            '#237227',
                            '#4db6ac',
                            '#81c784',
                            '#66bb6a'
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    plugins: {
                        legend: {
                            position: 'top'
                        },
                        tooltip: {
                            callbacks: {
                                label: function (context) {
                                    let total = context.dataset.data.reduce((a, b) => a + b, 0);
                                    let value = context.raw;
                                    let percentage = ((value / total) * 100).toFixed(1);
                                    return context.label + ': ' + value + ' (' + percentage + '%)';
                                }
                            }
                        }
                    },
                    maintainAspectRatio: false
                }
            });

            window.penyimpananChart2Instance = new Chart(
                document.getElementById('penyimpananChart2'), {
                type: 'bar',
                data: {
                    labels: labelsData,
                    datasets: datasetsData
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: true,
                            position: 'top'
                        }
                    },
                    interaction: {
                        mode: 'index',
                        intersect: false
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: 'Jumlah Stok (' + satuanStokLabel + ')'
                            }
                        }
                    }
                }
            }
            );
        }

        function fillLahan(tanah, latlng) {
            document.getElementById('namaLahan').innerText = tanah.nama_lahan;
            document.getElementById('legalitasLahan').innerText = tanah.legalitas_lahan;
            // document.getElementById('statusKawasan').innerText = tanah.status_kawasan;
            document.getElementById('luasHa').innerText = tanah.luas_ha;
            document.getElementById('alamatLokasi').innerText = tanah.alamat_lokasi;
            document.getElementById('keterangan').innerText = tanah.keterangan;
            document.getElementById('sudahValidasi').innerText = tanah.sudah_validasi;
            document.getElementById('tanggalValidasi').innerText = tanah.tanggal_validasi;
            document.getElementById('sejarah').innerText = tanah.sejarah;
            document.getElementById('centroidLat').innerText = tanah.centroid_lat;
            document.getElementById('centroidLng').innerText = tanah.centroid_lng;
            console.log(latlng);

            new bootstrap.Modal(document.getElementById('kalekaModal')).show();
        }

        function getCentroid(coords) {
            let lat = 0,
                lng = 0;

            coords.forEach(c => {
                lat += c[0];
                lng += c[1];
            });

            return [lat / coords.length, lng / coords.length];
        }

        function showBenihModal(item, latlng) {
            document.querySelector('.modal-title3').innerText = 'Informasi Benih';
            document.getElementById('nomorAksesi').innerText = item.bank_benih.nomor_aksesi;
            document.getElementById('namaNegara').innerText = item.bank_benih.nama_negara;
            document.getElementById('namaLokal').innerText = item.bank_benih.nama_lokal;
            document.getElementById('namaIlmiah').innerText = item.bank_benih.nama_ilmiah;
            document.getElementById('familiTanaman').innerText = item.bank_benih.famili_tanaman;
            document.getElementById('provenance').innerText = item.bank_benih.provenance;
            document.getElementById('tipePenyimpananBenih').innerText = item.bank_benih.tipe_penyimpanan_benih;
            document.getElementById('tanggalMasuk').innerText = item.bank_benih.tanggal_masuk;
            document.getElementById('jumlahStok').innerText = item.bank_benih.jumlah_stok;
            document.getElementById('satuanStok').innerText = item.bank_benih.satuan_stok;
            document.getElementById('kadarAirPersen').innerText = item.bank_benih.kadar_air_persen;
            document.getElementById('viabilitasPersen').innerText = item.bank_benih.viabilitas_persen;
            document.getElementById('ketinggianMdpl').innerText = item.bank_benih.ketinggian_mdpl;
            document.getElementById('masaBerlakuSampai').innerText = item.bank_benih.masa_berlaku_sampai;
            document.getElementById('lokasiPenyimpanan').innerText = item.bank_benih.lokasi_penyimpanan;
            document.getElementById('titikKoleksiLat').innerText = item.bank_benih.titik_koleksi_lat;
            document.getElementById('titikKoleksiLng').innerText = item.bank_benih.titik_koleksi_lng;
            document.getElementById('catatanBenih').innerText = item.bank_benih.catatan;
            document.getElementById('jumlahDitanam').innerText = item.bank_benih.jumlah_ditanam;
            document.getElementById('satuanBenih').innerText = item.bank_benih.satuan;
            document.getElementById('jumlahHidup').innerText = item.bank_benih.jumlah_hidup;
            document.getElementById('jumlahMati').innerText = item.bank_benih.jumlah_mati;
            document.getElementById('tinggiRata2Cm').innerText = item.bank_benih.tinggi_rata2_cm;
            document.getElementById('diameterRata2Cm').innerText = item.bank_benih.diameter_rata2_cm;
            document.getElementById('gambarBenih').src = item.bank_benih.gambar_benih;
            document.getElementById('gambarBenihLink').href = item.bank_benih.gambar_benih;
            // document.getElementById('progressStatusMonitoringBenih').innerText = item.monitoring_penanaman.progress_status_monitoring;
            // document.getElementById('periodePengecekanBenih').innerText = item.monitoring_penanaman.periode_pengecekan;
            // document.getElementById('tanggalTanamBenih').innerText = item.monitoring_penanaman.tanggal_tanam;
            // document.getElementById('tanggalMonitoringBenih').innerText = item.monitoring_penanaman.desa.tanggal_monitoring;
            // document.getElementById('luasTanamHaBenih').innerText = item.monitoring_penanaman.desa.luas_tanam_ha;
            // document.getElementById('survivalRatePersenBenih').innerText = item.monitoring_penanaman.desa.survival_rate_persen;
            // document.getElementById('catatanBenih').innerText = item.monitoring_penanaman.kelompok_tani.catatan;

            document.getElementById('namaLahanBenih').innerText = item.bank_benih.tanah.nama_lahan;
            document.getElementById('legalitasLahanBenih').innerText = item.bank_benih.tanah.legalitas_lahan;
            document.getElementById('luasHaBenih').innerText = item.bank_benih.tanah.luas_ha;
            document.getElementById('centroidLatBenih').innerText = item.bank_benih.tanah.centroid_lat;
            document.getElementById('centroidLngBenih').innerText = item.bank_benih.tanah.centroid_lng;
            document.getElementById('alamatLokasiBenih').innerText = item.bank_benih.tanah.alamat_lokasi;
            document.getElementById('keteranganBenih').innerText = item.bank_benih.tanah.keterangan;
            document.getElementById('sudahValidasiBenih').innerText = item.bank_benih.tanah.sudah_validasi;
            document.getElementById('tanggalValidasiBenih').innerText = item.bank_benih.tanah.tanggal_validasi;
            document.getElementById('sejarahBenih').innerText = item.bank_benih.tanah.sejarah;

            let tableBodyMonitoring = document.getElementById('benihMonitoringTableBody');
            tableBodyMonitoring.innerHTML = ""; // reset dulu

            if (item.bank_benih.monitoring_penanaman && item.bank_benih.monitoring_penanaman.length > 0) {
                item.bank_benih.monitoring_penanaman.forEach((data, index) => {
                    let row = `
                    <tr>
                        <td>${data.nama_tipe_penanaman}</td>
                        <td>${data.progress_status_monitoring}</td>
                        <td>${data.periode_pengecekan}</td>
                        <td>${data.tanggal_tanam}</td>
                        <td>${data.tanggal_monitoring}</td>
                        <td>${data.luas_tanam_ha}</td>
                        <td>${data.survival_rate_persen}</td>
                        <td>${data.catatan}</td>
                    </tr>
                `;
                    tableBodyMonitoring.innerHTML += row;
                });
            } else {
                tableBodyMonitoring.innerHTML = `
                    <tr>
                        <td colspan="5" style="text-align:center;">Tidak ada data monitoring</td>
                    </tr>
                `;
            }

            // let tableBodyPetaniKelompok = document.getElementById('petaniKelompokTableBody');
            // tableBodyPetaniKelompok.innerHTML = ""; // reset dulu

            // if (item.kelompok_tani.petani_kelompok && item.kelompok_tani.petani_kelompok.length > 0) {
            //     item.kelompok_tani.petani_kelompok.forEach((data, index) => {
            //         let row = `
            //         <tr>
            //             <td>${data.petani.nama_lengkap}</td>
            //             <td>${data.petani.nama_panggilan}</td>
            //             <td>${data.petani.jenis_kelamin}</td>
            //             <td>${data.petani.umur}</td>
            //             <td>${data.petani.status_petani}</td>
            //             <td>${data.petani.alamat}</td>
            //             <td><img src="${data.petani.foto}" alt="Foto Petani" width="50" height="50" class="img-fluid rounded-circle"></td>
            //         </tr>
            //     `;
            //         tableBodyPetaniKelompok.innerHTML += row;
            //     });
            // } else {
            //     tableBodyPetaniKelompok.innerHTML = `
            //         <tr>
            //             <td colspan="5" style="text-align:center;">Tidak ada data petani</td>
            //         </tr>
            //     `;
            // }

            let tableBodyPolygon = document.getElementById('polygonBenihTableBody');
            tableBodyPolygon.innerHTML = "";

            if (item.bank_benih.tanah.geom_area && item.bank_benih.tanah.geom_area.length > 0) {
                let rowLat = `<tr><th>Latitude</th>`;
                let rowLng = `<tr><th>Longitude</th>`;

                item.bank_benih.tanah.geom_area.forEach((point, index) => {
                    rowLat += `<td>${point[0]}</td>`;
                    rowLng += `<td>${point[1]}</td>`;
                });

                rowLat += `</tr>`;
                rowLng += `</tr>`;

                tableBodyPolygon.innerHTML = rowLat + rowLng;

            } else {
                tableBodyPolygon.innerHTML = `
                    <tr>
                        <td colspan="10" style="text-align:center;">
                            Tidak ada data koordinat
                        </td>
                    </tr>
                `;
            }

            new bootstrap.Modal(document.getElementById('benihModal')).show();

            // fillLahan(item.lahan, latlng);
        }

        map.on('zoomend', toggleLayers);
    </script>
</body>

</html>