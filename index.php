<?php
include 'partials/header.php';
?>

<body>

    <section class="preloader">
        <div class="spinner">
            <span class="spinner-rotate"></span>
        </div>
    </section>

    <?php
    include 'partials/navbar.php';
    ?>

    <main>
        <section class="hero d-flex justify-content-center align-items-center" id="section_1">
            <div class="container">
                <div class="row">

                    <div class="col-lg-7 col-12">
                        <div class="hero-text">
                            <div class="hero-title-wrap d-flex align-items-center mb-4">
                                <img src="images/dayak1.jpeg" class="avatar-image avatar-image-large img-fluid" alt="">

                                <h1 class="hero-title ms-3 mb-0" data-lang="hero_title">Hello teman Borneologi!</h1>
                            </div>

                            <h2 class="mb-4" data-lang="hero_subtitle">Borneologi menyajikan peta lahan interaktif.</h2>
                            <p class="mb-4"><a class="custom-btn btn custom-link" href="#section_2"
                                    data-lang="lihat_peta">Lihat Peta</a></p>
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
                        <h3 class="pt-2 mb-3" data-lang="text_peta_sebaran">Peta Sebaran</h3>
                        <div class="map-wrapper position-relative">
                            <!-- INFO BOX -->
                            <div class="map-stats">
                                <div class="stat-box">
                                    <span data-lang="text_total">Total</span>
                                    <span data-lang="text_petani">Farmers</span>
                                    <h5 id="totalPetani"></h5>
                                </div>
                                <div class="stat-box">
                                    <span data-lang="text_petani">Farmers</span>
                                    <span data-lang="text_laki_laki">Male</span>
                                    <h5 id="totalLaki"></h5>
                                </div>
                                <div class="stat-box">
                                    <span data-lang="text_petani">Farmers</span>
                                    <span data-lang="text_perempuan">Female</span>
                                    <h5 id="totalPerempuan"></h5>
                                </div>
                            </div>

                            <div id="mapLoading" style="
                                position:absolute;
                                top:50%;
                                left:50%;
                                transform:translateX(-50%);
                                background:white;
                                padding:8px 14px;
                                border-radius:8px;
                                box-shadow:0 2px 6px rgba(0,0,0,0.2);
                                font-size:13px;
                                z-index:9999;
                                display:none;
                                ">
                                ⏳ Loading...
                            </div>

                            <div id="loadingDetail" style="
                                position:absolute;
                                top:50%;
                                left:50%;
                                transform:translateX(-50%);
                                background:white;
                                padding:8px 14px;
                                border-radius:8px;
                                box-shadow:0 2px 6px rgba(0,0,0,0.2);
                                font-size:13px;
                                z-index:9999;
                                display:none;
                                text-align:center;
                                ">
                                <div class="spinner-border text-primary" role="status"></div>
                                <p class="mt-2">Loading...</p>
                            </div>
                            <div id="map" class="about-image img-fluid" alt=""></div>
                        </div>

                        <!-- Modal Informasi Hutan Adat -->
                        <div class="modal fade" id="hutanAdatModal" tabindex="0">
                            <!-- <div class="modal-dialog modal-xl modal-dialog-scrollable"> -->
                            <div class="modal-dialog modal-xxl-custom modal-dialog-scrollable">
                                <div class="modal-content">

                                    <div class="modal-header">
                                        <h5 id="modal-title-hutan-adat"></h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>

                                    <div class="modal-body">
                                        <ul class="nav nav-tabs custom-tabs" id="myTabHutanAdat" role="tablist">
                                            <li class="nav-item" role="presentation">
                                                <button class="nav-link active" id="tab1hutanAdat-tab"
                                                    data-bs-toggle="tab" data-bs-target="#tab1hutanAdat" type="button"
                                                    role="tab" aria-controls="tab1hutanAdat" aria-selected="false"
                                                    data-lang="text_hutan_adat">Hutan
                                                    Adat</button>
                                            </li>
                                            <li class="nav-item" role="presentation">
                                                <button class="nav-link" id="tab2kelompok-tab" data-bs-toggle="tab"
                                                    data-bs-target="#tab2kelompok" type="button" role="tab"
                                                    aria-controls="tab2kelompok" aria-selected="true"
                                                    data-lang="text_masyarakat_hukum_adat">Masyarakat Hukum
                                                    Adat</button>
                                            </li>
                                            <li class="nav-item" role="presentation">
                                                <button class="nav-link" id="tab3tanah-tab" data-bs-toggle="tab"
                                                    data-bs-target="#tab3tanah" type="button" role="tab"
                                                    aria-controls="tab3tanah" aria-selected="false"
                                                    data-lang="text_tanah">Tanah</button>
                                            </li>
                                        </ul>

                                        <div class="tab-content pt-3" id="myTabContent">
                                            <div class="tab-pane fade show active" id="tab1hutanAdat" role="tabpanel"
                                                aria-labelledby="tab1hutanAdat-tab">
                                                <div class="row">
                                                    <div class="col-lg-3 col-md-6 col-12 mb-4">
                                                        <strong class="site-footer-title d-block mb-3"
                                                            data-lang="text_nama_hutan_adat">Nama
                                                            Hutan Adat</strong>
                                                        <p class="mb-0" id="namaHutanAdat"></p>
                                                    </div>
                                                    <div class="col-lg-3 col-md-6 col-12 mb-4">
                                                        <strong class="site-footer-title d-block mb-3"
                                                            data-lang="text_nomor_sk">Nomor SK</strong>
                                                        <p class="mb-0" id="nomorSk"></p>
                                                    </div>
                                                    <div class="col-lg-3 col-md-6 col-12 mb-4">
                                                        <strong class="site-footer-title d-block mb-3"
                                                            data-lang="text_tanggal_sk">Tanggal
                                                            SK</strong>
                                                        <p class="mb-0" id="tanggalSk"></p>
                                                    </div>
                                                    <div class="col-lg-3 col-md-6 col-12 mb-4">
                                                        <strong class="site-footer-title d-block mb-3"
                                                            data-lang="text_status_kawasan">Status
                                                            Kawasan</strong>
                                                        <p class="mb-0" id="statusKawasan"></p>
                                                    </div>
                                                    <div class="col-lg-3 col-md-6 col-12 mb-4">
                                                        <strong class="site-footer-title d-block mb-3"
                                                            data-lang="text_nama_desa">Nama
                                                            Desa</strong>
                                                        <p class="mb-0" id="namaDesaHutanAdat"></p>
                                                    </div>
                                                    <div class="col-lg-3 col-md-6 col-12 mb-4">
                                                        <strong class="site-footer-title d-block mb-3"
                                                            data-lang="text_nama_kecamatan">Nama
                                                            Kecamatan</strong>
                                                        <p class="mb-0" id="namaKecamatanHutanAdat"></p>
                                                    </div>
                                                    <div class="col-lg-3 col-md-6 col-12 mb-4">
                                                        <strong class="site-footer-title d-block mb-3"
                                                            data-lang="text_nama_kabupaten">Nama
                                                            Kabupaten</strong>
                                                        <p class="mb-0" id="namaKabupatenHutanAdat"></p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="tab-pane fade" id="tab2kelompok" role="tabpanel"
                                                aria-labelledby="tab2kelompok-tab">
                                                <div class="row">
                                                    <div class="col-lg-3 col-md-6 col-12 mb-4">
                                                        <strong class="site-footer-title d-block mb-3"
                                                            data-lang="text_nama_masyarakat_hukum_adat">Nama Masyarakat
                                                            Hukum Adat
                                                        </strong>
                                                        <p class="mb-0" id="namaMasyarakatHukumAdat"></p>
                                                    </div>
                                                    <div class="col-lg-3 col-md-6 col-12 mb-4">
                                                        <strong class="site-footer-title d-block mb-3"
                                                            data-lang="text_kategori_masyarakat_hukum_adat">Kategori
                                                            Masyarakat Hukum Adat
                                                        </strong>
                                                        <p class="mb-0" id="namaKategoriKelompok"></p>
                                                    </div>
                                                    <div class="col-lg-3 col-md-6 col-12 mb-4">
                                                        <strong class="site-footer-title d-block mb-3"
                                                            data-lang="text_tahun_bentuk">Tahun Bentuk
                                                        </strong>
                                                        <p class="mb-0" id="tahunBentuk"></p>
                                                    </div>
                                                    <div class="col-lg-3 col-md-6 col-12 mb-4">
                                                        <strong class="site-footer-title d-block mb-3"
                                                            data-lang="text_status_masyarakat_hukum_adat">Status
                                                            Masyarakat
                                                            Hukum Adat
                                                        </strong>
                                                        <p class="mb-0" id="statusMasyarakatHukumAdat"></p>
                                                    </div>
                                                    <div class="col-lg-3 col-md-6 col-12 mb-4">
                                                        <strong class="site-footer-title d-block mb-3"
                                                            data-lang="text_total_anggota">Total Anggota
                                                        </strong>
                                                        <p class="mb-0" id="totalAnggotaMasyarakatHukumAdat"></p>
                                                    </div>
                                                    <div class="col-lg-3 col-md-6 col-12 mb-4">
                                                        <strong class="site-footer-title d-block mb-3"
                                                            data-lang="text_alamat_masyarakat_hukum_adat">Alamat
                                                            Masyarakat
                                                            Hukum Adat
                                                        </strong>
                                                        <p class="mb-0" id="alamatMasyarakatHukumAdat"></p>
                                                    </div>
                                                    <div class="col-lg-6 col-md-6 col-12 mb-4">
                                                        <strong class="site-footer-title d-block mb-3"
                                                            data-lang="text_deskripsi">Deskripsi
                                                        </strong>
                                                        <p class="mb-0" id="deskripsiMasyarakatHukumAdat"></p>
                                                    </div>

                                                    <div class="col-lg-12 col-md-12 col-12">
                                                        <strong class="site-footer-title d-block mb-3"
                                                            data-lang="text_pengurus_masyarakat_hukum_adat">Pengurus
                                                            Masyarakat Hukum Adat
                                                        </strong>
                                                        <table class="profile-thumb">
                                                            <tr>
                                                                <th data-lang="text_nama_lengkap">Nama Lengkap</th>
                                                                <th data-lang="text_nama_panggilan">Nama Panggilan</th>
                                                                <th data-lang="text_jenis_kelamin">Jenis Kelamin</th>
                                                                <th data-lang="text_umur">Umur</th>
                                                                <th data-lang="text_status_petani">Status Petani</th>
                                                                <th data-lang="text_alamat">Alamat</th>
                                                                <th data-lang="text_foto">Foto</th>
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
                                                        <strong class="site-footer-title d-block mb-3"
                                                            data-lang="text_nama_lahan">Nama
                                                            Lahan</strong>
                                                        <p class="mb-0" id="namaLahanHA"></p>
                                                    </div>
                                                    <div class="col-lg-3 col-md-6 col-12 mb-4">
                                                        <strong class="site-footer-title d-block mb-3"
                                                            data-lang="text_legalitas">Legalitas
                                                        </strong>
                                                        <p class="mb-0" id="legalitasLahanHA"></p>
                                                    </div>
                                                    <div class="col-lg-3 col-md-6 col-12 mb-4">
                                                        <strong class="site-footer-title d-block mb-3"
                                                            data-lang="text_luas_lahan">Luas
                                                        </strong>
                                                        <p class="mb-0" id="luasHaHA"></p>
                                                    </div>
                                                    <div class="col-lg-3 col-md-6 col-12 mb-4">
                                                        <strong class="site-footer-title d-block mb-3"
                                                            data-lang="text_sejarah_lahan">Sejarah
                                                        </strong>
                                                        <p class="mb-0" id="sejarahHA"></p>
                                                    </div>
                                                    <div class="col-lg-6 col-md-6 col-12 mb-4">
                                                        <strong class="site-footer-title d-block mb-3"
                                                            data-lang="text_alamat_lokasi_lahan">Alamat Lokasi
                                                        </strong>
                                                        <p class="mb-0" id="alamatLokasiHA"></p>
                                                    </div>
                                                    <div class="col-lg-6 col-md-6 col-12 mb-4">
                                                        <strong class="site-footer-title d-block mb-3"
                                                            data-lang="text_keterangan_lahan">Keterangan
                                                        </strong>
                                                        <p class="mb-0" id="keteranganHA"></p>
                                                    </div>

                                                    <div class="col-lg-3 col-md-6 col-12 mb-4">
                                                        <strong class="site-footer-title d-block mb-3"
                                                            data-lang="text_sudah_validasi">Sudah
                                                            Validasi</strong>
                                                        <p class="mb-0" id="sudahValidasiHA"></p>
                                                    </div>
                                                    <div class="col-lg-3 col-md-6 col-12 mb-4">
                                                        <strong class="site-footer-title d-block mb-3"
                                                            data-lang="text_tanggal_validasi">Tanggal
                                                            Validasi</strong>
                                                        <p class="mb-0" id="tanggalValidasiHA"></p>
                                                    </div>
                                                    <!-- <div class="col-lg-3 col-md-6 col-12 mb-4">
                                                        <strong class="site-footer-title d-block mb-3">Latitude</strong>
                                                        <p class="mb-0" id="centroidLatHutanAdat"></p>
                                                    </div>
                                                    <div class="col-lg-3 col-md-6 col-12 mb-4">
                                                        <strong class="site-footer-title d-block mb-3" data-lang="text_longitude">Longitude
                                                        </strong>
                                                        <p class="mb-0" id="centroidLngHutanAdat"></p>
                                                    </div>

                                                    <div class="col-lg-12 col-md-12 col-12">
                                                        <strong class="site-footer-title d-block mb-3" data-lang="text_polygon">Polygon
                                                        </strong>
                                                        <table class="profile-thumb">
                                                            <tbody id="polygonHutanAdatTableBody"></tbody>
                                                        </table>
                                                    </div> -->
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Modal Informasi Provinsi -->
                        <div class="modal fade" id="provinsiModal" tabindex="0">
                            <!-- <div class="modal-dialog modal-xl modal-dialog-scrollable"> -->
                            <div class="modal-dialog modal-xxl-custom modal-dialog-scrollable">
                                <div class="modal-content">

                                    <div class="modal-header">
                                        <h5 id="modal-title-provinsi"></h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>

                                    <div class="modal-body">
                                        <ul class="nav nav-tabs custom-tabs" id="myTabProvinsi" role="tablist">
                                            <li class="nav-item" role="presentation">
                                                <button class="nav-link active" id="tab1Provinsi-tab"
                                                    data-bs-toggle="tab" data-bs-target="#tab1Provinsi" type="button"
                                                    role="tab" aria-controls="tab1Provinsi" aria-selected="false"
                                                    data-lang="text_data_provinsi">Data
                                                    Provinsi</button>
                                            </li>
                                            <li class="nav-item" role="presentation">
                                                <button class="nav-link" id="tab2ProvinsiKabupaten-tab"
                                                    data-bs-toggle="tab" data-bs-target="#tab2ProvinsiKabupaten"
                                                    type="button" role="tab" aria-controls="tab2ProvinsiKabupaten"
                                                    aria-selected="true" data-lang="text_data_kabupaten">Data
                                                    Kabupaten</button>
                                            </li>
                                            <li class="nav-item" role="presentation">
                                                <button class="nav-link" id="tab3ProvinsiKecamatan-tab"
                                                    data-bs-toggle="tab" data-bs-target="#tab3ProvinsiKecamatan"
                                                    type="button" role="tab" aria-controls="tab3ProvinsiKecamatan"
                                                    aria-selected="false" data-lang="text_data_kecamatan">Data
                                                    Kecamatan</button>
                                            </li>
                                        </ul>

                                        <div class="tab-content pt-3" id="myTabContent">
                                            <div class="tab-pane fade show active" id="tab1Provinsi" role="tabpanel"
                                                aria-labelledby="tab1Provinsi-tab">
                                                <div class="row">
                                                    <div class="col-lg-3 col-md-6 col-12 mb-4">
                                                        <strong class="site-footer-title d-block mb-3"
                                                            data-lang="text_nama_provinsi">Nama
                                                            Provinsi</strong>
                                                        <p class="mb-0" id="namaProvinsi"></p>
                                                    </div>
                                                    <div class="col-lg-3 col-md-6 col-12 mb-4">
                                                        <strong class="site-footer-title d-block mb-3"
                                                            data-lang="text_luas_provinsi">Luas
                                                            Provinsi</strong>
                                                        <p class="mb-0" id="luasProvinsi"></p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="tab-pane fade" id="tab2ProvinsiKabupaten" role="tabpanel"
                                                aria-labelledby="tab2ProvinsiKabupaten-tab">
                                                <div class="row">
                                                    <div class="col-lg-12 col-md-12 col-12">
                                                        <strong class="site-footer-title d-block mb-3"
                                                            data-lang="text_list_kabupaten">List Kabupaten
                                                        </strong>
                                                        <table class="profile-thumb">
                                                            <tr>
                                                                <th data-lang="text_nama_kabupaten">Nama Kabupaten</th>
                                                                <th data-lang="text_kode_kabupaten">Kode Kabupaten</th>
                                                                <th data-lang="text_luas_kabupaten">Luas Kabupaten</th>
                                                            </tr>
                                                            <tbody id="petaniKelompokTableBody">
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="tab-pane fade" id="tab3ProvinsiKecamatan" role="tabpanel"
                                                aria-labelledby="tab3ProvinsiKecamatan-tab">
                                                <div class="row">
                                                    <div class="col-lg-12 col-md-12 col-12">
                                                        <strong class="site-footer-title d-block mb-3"
                                                            data-lang="text_list_kecamatan">List Kecamatan
                                                        </strong>
                                                        <table class="profile-thumb">
                                                            <tr>
                                                                <th data-lang="text_nama_kecamatan">Nama Kecamatan</th>
                                                                <th data-lang="text_kode_kecamatan">Kode Kecamatan</th>
                                                                <th data-lang="text_luas_kecamatan">Luas Kecamatan</th>
                                                            </tr>
                                                            <tbody id="petaniKelompokTableBody">
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

                        <!-- Modal Informasi Kabupaten -->
                        <div class="modal fade" id="kabupatenModal" tabindex="0">
                            <!-- <div class="modal-dialog modal-xl modal-dialog-scrollable"> -->
                            <div class="modal-dialog modal-xxl-custom modal-dialog-scrollable">
                                <div class="modal-content">

                                    <div class="modal-header">
                                        <h5 id="modal-title-kabupaten"></h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>

                                    <div class="modal-body">
                                        <ul class="nav nav-tabs custom-tabs" id="myTabKabupaten" role="tablist">
                                            <li class="nav-item" role="presentation">
                                                <button class="nav-link active" id="tab1Kabupaten-tab"
                                                    data-bs-toggle="tab" data-bs-target="#tab1Kabupaten" type="button"
                                                    role="tab" aria-controls="tab1Kabupaten" aria-selected="false"
                                                    data-lang="text_data_kabupaten">Data
                                                    Kabupaten</button>
                                            </li>
                                            <li class="nav-item" role="presentation">
                                                <button class="nav-link" id="tab2KabupatenKecamatan-tab"
                                                    data-bs-toggle="tab" data-bs-target="#tab2KabupatenKecamatan"
                                                    type="button" role="tab" aria-controls="tab2KabupatenKecamatan"
                                                    aria-selected="true" data-lang="text_data_kecamatan">Data
                                                    Kecamatan</button>
                                            </li>
                                        </ul>

                                        <div class="tab-content pt-3" id="myTabContent">
                                            <div class="tab-pane fade show active" id="tab1Kabupaten" role="tabpanel"
                                                aria-labelledby="tab1Kabupaten-tab">
                                                <div class="row">
                                                    <div class="col-lg-3 col-md-6 col-12 mb-4">
                                                        <strong class="site-footer-title d-block mb-3"
                                                            data-lang="text_nama_kabupaten">Nama
                                                            Kabupaten</strong>
                                                        <p class="mb-0" id="namaKabupaten"></p>
                                                    </div>
                                                    <div class="col-lg-3 col-md-6 col-12 mb-4">
                                                        <strong class="site-footer-title d-block mb-3"
                                                            data-lang="text_luas_kabupaten">Luas
                                                            Kabupaten</strong>
                                                        <p class="mb-0" id="luasKabupaten"></p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="tab-pane fade" id="tab2KabupatenKecamatan" role="tabpanel"
                                                aria-labelledby="tab2KabupatenKecamatan-tab">
                                                <div class="row">
                                                    <div class="col-lg-12 col-md-12 col-12">
                                                        <strong class="site-footer-title d-block mb-3"
                                                            data-lang="text_list_kecamatan">List Kecamatan
                                                        </strong>
                                                        <table class="profile-thumb">
                                                            <tr>
                                                                <th data-lang="text_nama_kecamatan">Nama Kecamatan</th>
                                                                <th data-lang="text_kode_kecamatan">Kode Kecamatan</th>
                                                                <th data-lang="text_luas_kecamatan">Luas Kecamatan</th>
                                                            </tr>
                                                            <tbody id="petaniKelompokTableBody">
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

                        <!-- Modal Informasi Kecamatan -->
                        <div class="modal fade" id="kecamatanModal" tabindex="0">
                            <!-- <div class="modal-dialog modal-xl modal-dialog-scrollable"> -->
                            <div class="modal-dialog modal-xxl-custom modal-dialog-scrollable">
                                <div class="modal-content">

                                    <div class="modal-header">
                                        <h5 id="modal-title-kecamatan"></h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>

                                    <div class="modal-body">
                                        <ul class="nav nav-tabs custom-tabs" id="myTabKecamatan" role="tablist">
                                            <li class="nav-item" role="presentation">
                                                <button class="nav-link active" id="tab1Kecamatan-tab"
                                                    data-bs-toggle="tab" data-bs-target="#tab1Kecamatan" type="button"
                                                    role="tab" aria-controls="tab1Kecamatan" aria-selected="false"
                                                    data-lang="text_data_kecamatan">Data
                                                    Kecamatan</button>
                                            </li>
                                        </ul>

                                        <div class="tab-content pt-3" id="myTabContent">
                                            <div class="tab-pane fade show active" id="tab1Kecamatan" role="tabpanel"
                                                aria-labelledby="tab1Kecamatan-tab">
                                                <div class="row">
                                                    <div class="col-lg-3 col-md-6 col-12 mb-4">
                                                        <strong class="site-footer-title d-block mb-3"
                                                            data-lang="text_nama_kecamatan">Nama
                                                            Kecamatan</strong>
                                                        <p class="mb-0" id="namaKecamatan"></p>
                                                    </div>
                                                    <div class="col-lg-3 col-md-6 col-12 mb-4">
                                                        <strong class="site-footer-title d-block mb-3"
                                                            data-lang="text_luas_kecamatan">Luas
                                                            Kecamatan</strong>
                                                        <p class="mb-0" id="luasKecamatan"></p>
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
                            <!-- <div class="modal-dialog modal-xl modal-dialog-scrollable"> -->
                            <div class="modal-dialog modal-xxl-custom modal-dialog-scrollable">
                                <div class="modal-content">

                                    <div class="modal-header">
                                        <h5 id="modal-title-kaleka"></h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>

                                    <div class="modal-body">
                                        <!-- Nav tabs -->
                                        <ul class="nav nav-tabs custom-tabs" id="myTabKaleka" role="tablist">
                                            <li class="nav-item" role="presentation">
                                                <button class="nav-link active" id="tab1-tab" data-bs-toggle="tab"
                                                    data-bs-target="#tab1" type="button" role="tab" aria-controls="tab1"
                                                    aria-selected="true" data-lang="text_petani">Petani</button>
                                            </li>
                                            <li class="nav-item" role="presentation">
                                                <button class="nav-link" id="tab2-tab" data-bs-toggle="tab"
                                                    data-bs-target="#tab2" type="button" role="tab" aria-controls="tab2"
                                                    aria-selected="false" data-lang="text_tanah">Tanah</button>
                                            </li>
                                            <li class="nav-item" role="presentation">
                                                <button class="nav-link" id="tab3-tab" data-bs-toggle="tab"
                                                    data-bs-target="#tab3" type="button" role="tab" aria-controls="tab3"
                                                    aria-selected="false" data-lang="text_observasi">Obervasi</button>
                                            </li>
                                            <li class="nav-item" role="presentation">
                                                <button class="nav-link" id="tab4-tab" data-bs-toggle="tab"
                                                    data-bs-target="#tab4" type="button" role="tab" aria-controls="tab4"
                                                    aria-selected="false" data-lang="text_hasil">Hasil</button>
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

                                                                <h4 class="projects-title" id="namaLengkap"></h4>
                                                            </div>

                                                            <a href="" id="petaniFotoLink" class="popup-image">
                                                                <img id="petaniFoto" class="projects-image img-fluid"
                                                                    alt="">
                                                            </a>
                                                        </div>
                                                    </div>

                                                    <div class="col-lg-4 col-md-6 col-12">
                                                        <strong class="site-footer-title d-block mb-3"
                                                            data-lang="text_data_pribadi">Data
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

                                                        <strong class="site-footer-title d-block mt-4 mb-3"
                                                            data-lang="text_alamat">Alamat
                                                        </strong>
                                                        <p class="mb-0" id="alamatPetani"></p>
                                                        <ul class="footer-menu mt-2 mb-3">
                                                            <li class="footer-menu-item"><a class="footer-menu-link"
                                                                    id="desaPetani"></a>
                                                            </li>
                                                            <li class="footer-menu-item"><a class="footer-menu-link"
                                                                    id="kecamatanPetani"></a>
                                                            </li>
                                                            <li class="footer-menu-item"><a class="footer-menu-link"
                                                                    id="kabupatenPetani"></a>
                                                            </li>
                                                        </ul>
                                                    </div>

                                                    <div class="col-lg-5 col-md-12 col-12">
                                                        <strong class="site-footer-title d-block mb-3"
                                                            data-lang="text_kelompok_petani">Kelompok Petani
                                                        </strong>
                                                        <table class="profile-thumb">
                                                            <tr>
                                                                <th data-lang="text_kelompok_tani">Kelompok Tani</th>
                                                                <th data-lang="text_kategori_kelompok">Kategori Kelompok
                                                                </th>
                                                                <th data-lang="text_tahun_gabung">Tahun Gabung</th>
                                                            </tr>
                                                            <tbody id="kelompokPetaniTableBody">
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="tab-pane fade" id="tab2" role="tabpanel"
                                                aria-labelledby="tab2-tab">
                                                <div class="row">
                                                    <div class="col-lg-3 col-md-6 col-12 mb-4">
                                                        <strong class="site-footer-title d-block mb-3"
                                                            data-lang="text_nama_kaleka">Nama
                                                            Kaleka</strong>
                                                        <p class="mb-0" id="namaKaleka"></p>
                                                    </div>
                                                    <div class="col-lg-3 col-md-6 col-12 mb-4">
                                                        <strong class="site-footer-title d-block mb-3"
                                                            data-lang="text_nama_lahan">Nama
                                                            Lahan</strong>
                                                        <p class="mb-0" id="namaLahan"></p>
                                                    </div>
                                                    <div class="col-lg-3 col-md-6 col-12 mb-4">
                                                        <strong class="site-footer-title d-block mb-3"
                                                            data-lang="text_legalitas">Legalitas
                                                        </strong>
                                                        <p class="mb-0" id="legalitasLahan"></p>
                                                    </div>
                                                    <div class="col-lg-3 col-md-6 col-12 mb-4">
                                                        <strong class="site-footer-title d-block mb-3"
                                                            data-lang="text_luas_lahan">Luas
                                                        </strong>
                                                        <p class="mb-0" id="luasHaTanah"></p>
                                                    </div>
                                                    <div class="col-lg-3 col-md-6 col-12 mb-4">
                                                        <strong class="site-footer-title d-block mb-3"
                                                            data-lang="text_sejarah_lahan">Sejarah
                                                        </strong>
                                                        <p class="mb-0" id="sejarahTanah"></p>
                                                    </div>
                                                    <div class="col-lg-3 col-md-6 col-12 mb-4">
                                                        <strong class="site-footer-title d-block mb-3"
                                                            data-lang="text_alamat_lokasi_lahan">Alamat Lokasi
                                                        </strong>
                                                        <p class="mb-0" id="alamatLokasiTanah"></p>
                                                    </div>
                                                    <div class="col-lg-6 col-md-6 col-12 mb-4">
                                                        <strong class="site-footer-title d-block mb-3"
                                                            data-lang="text_keterangan">Keterangan
                                                        </strong>
                                                        <p class="mb-0" id="keteranganTanah"></p>
                                                    </div>

                                                    <div class="col-lg-3 col-md-6 col-12 mb-4">
                                                        <strong class="site-footer-title d-block mb-3"
                                                            data-lang="text_sudah_validasi">Sudah
                                                            Validasi</strong>
                                                        <p class="mb-0" id="sudahValidasiTanah"></p>
                                                    </div>
                                                    <div class="col-lg-3 col-md-6 col-12 mb-4">
                                                        <strong class="site-footer-title d-block mb-3"
                                                            data-lang="text_tanggal_validasi">Tanggal
                                                            Validasi</strong>
                                                        <p class="mb-0" id="tanggalValidasiTanah"></p>
                                                    </div>
                                                    <!-- <div class="col-lg-3 col-md-6 col-12 mb-4">
                                                        <strong class="site-footer-title d-block mb-3" data-lang="text_latitude">Latitude</strong>
                                                        <p class="mb-0" id="centroidLat"></p>
                                                    </div>
                                                    <div class="col-lg-3 col-md-6 col-12 mb-4">
                                                        <strong class="site-footer-title d-block mb-3" data-lang="text_longitude">Longitude
                                                        </strong>
                                                        <p class="mb-0" id="centroidLng"></p>
                                                    </div>

                                                    <div class="col-lg-12 col-md-12 col-12">
                                                        <strong class="site-footer-title d-block mb-3" data-lang="text_polygon">Polygon
                                                        </strong>
                                                        <table class="profile-thumb">
                                                            <tbody id="polygonTableBody"></tbody>
                                                        </table>
                                                    </div> -->
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
                                                                    type="button" role="tab"
                                                                    data-lang="text_perairan_observasi">
                                                                    Perairan Observasi
                                                                </button>
                                                            </li>

                                                            <li class="nav-item" role="presentation">
                                                                <button class="nav-link" id="tab32-tab"
                                                                    data-bs-toggle="tab" data-bs-target="#tab32"
                                                                    type="button" role="tab"
                                                                    data-lang="text_infrastruktur_observasi">
                                                                    Infrastruktur Observasi
                                                                </button>
                                                            </li>

                                                            <li class="nav-item" role="presentation">
                                                                <button class="nav-link" id="tab33-tab"
                                                                    data-bs-toggle="tab" data-bs-target="#tab33"
                                                                    type="button" role="tab"
                                                                    data-lang="text_land_cover_observasi">
                                                                    Land Cover Observasi
                                                                </button>
                                                            </li>

                                                            <li class="nav-item" role="presentation">
                                                                <button class="nav-link" id="tab34-tab"
                                                                    data-bs-toggle="tab" data-bs-target="#tab34"
                                                                    type="button" role="tab"
                                                                    data-lang="text_topografi_observasi">
                                                                    Topografi Observasi
                                                                </button>
                                                            </li>

                                                            <li class="nav-item" role="presentation">
                                                                <button class="nav-link" id="tab35-tab"
                                                                    data-bs-toggle="tab" data-bs-target="#tab35"
                                                                    type="button" role="tab"
                                                                    data-lang="text_pohon_observasi">
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
                                                                    <div class="col-lg-4 col-md-6 col-12 mb-4">
                                                                        <strong class="site-footer-title d-block mb-3"
                                                                            data-lang="text_periode_pengecekan">
                                                                            Periode Pengecekan
                                                                        </strong>
                                                                        <p class="mb-0" id="periodePengecekanPerairan">
                                                                        </p>
                                                                    </div>
                                                                    <div class="col-lg-4 col-md-6 col-12 mb-4">
                                                                        <strong class="site-footer-title d-block mb-3"
                                                                            data-lang="text_warna_air">
                                                                            Warna Air
                                                                        </strong>
                                                                        <p class="mb-0" id="warnaAirPerairan"></p>
                                                                    </div>
                                                                    <div class="col-lg-4 col-md-6 col-12 mb-4">
                                                                        <strong class="site-footer-title d-block mb-3"
                                                                            data-lang="text_jenis_palung">
                                                                            Jenis Palung
                                                                        </strong>
                                                                        <p class="mb-0" id="jenisPalungPerairan"></p>
                                                                    </div>
                                                                    <div class="col-lg-4 col-md-6 col-12 mb-4">
                                                                        <strong class="site-footer-title d-block mb-3"
                                                                            data-lang="text_kecepatan_aliran">
                                                                            Kecepatan Aliran
                                                                        </strong>
                                                                        <p class="mb-0" id="kecepatanAliranPerairan">
                                                                        </p>
                                                                    </div>
                                                                    <div class="col-lg-4 col-md-6 col-12 mb-4">
                                                                        <strong class="site-footer-title d-block mb-3"
                                                                            data-lang="text_kedalaman_air">
                                                                            Kedalaman Air
                                                                        </strong>
                                                                        <p class="mb-0" id="kedalamanPerairan"></p>
                                                                    </div>
                                                                    <div class="col-lg-4 col-md-6 col-12 mb-4">
                                                                        <strong class="site-footer-title d-block mb-3"
                                                                            data-lang="text_lebar">
                                                                            Lebar
                                                                        </strong>
                                                                        <p class="mb-0" id="lebarPerairan"></p>
                                                                    </div>
                                                                    <div class="col-lg-4 col-md-6 col-12 mb-4">
                                                                        <strong class="site-footer-title d-block mb-3"
                                                                            data-lang="text_debit_air">
                                                                            Debit Air
                                                                        </strong>
                                                                        <p class="mb-0" id="debitPerairan"></p>
                                                                    </div>
                                                                    <div class="col-lg-4 col-md-6 col-12 mb-4">
                                                                        <strong class="site-footer-title d-block mb-3"
                                                                            data-lang="text_ph">
                                                                            pH
                                                                        </strong>
                                                                        <p class="mb-0" id="phPerairan"></p>
                                                                    </div>
                                                                    <div class="col-lg-4 col-md-6 col-12 mb-4">
                                                                        <strong class="site-footer-title d-block mb-3"
                                                                            data-lang="text_kekeruhan">
                                                                            Kekeruhan
                                                                        </strong>
                                                                        <p class="mb-0" id="kekeruhanPerairan"></p>
                                                                    </div>
                                                                    <div class="col-lg-12 col-md-6 col-12 mb-4">
                                                                        <strong class="site-footer-title d-block mb-3"
                                                                            data-lang="text_catatan">
                                                                            Catatan
                                                                        </strong>
                                                                        <p class="mb-0" id="catatanPerairan"></p>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="tab-pane fade" id="tab32" role="tabpanel">
                                                                <div class="row">
                                                                    <div class="col-lg-4 col-md-6 col-12 mb-4">
                                                                        <strong class="site-footer-title d-block mb-3"
                                                                            data-lang="text_periode_pengecekan">
                                                                            Periode Pengecekan
                                                                        </strong>
                                                                        <p class="mb-0"
                                                                            id="periodePengecekanInfrastruktur"></p>
                                                                    </div>
                                                                    <div class="col-lg-4 col-md-6 col-12 mb-4">
                                                                        <strong class="site-footer-title d-block mb-3"
                                                                            data-lang="text_akses_perjalanan">
                                                                            Akses Perjalanan
                                                                        </strong>
                                                                        <p class="mb-0"
                                                                            id="aksesPerjalananInfrastruktur"></p>
                                                                    </div>
                                                                    <div class="col-lg-4 col-md-6 col-12 mb-4">
                                                                        <strong class="site-footer-title d-block mb-3"
                                                                            data-lang="text_kondisi_jalan">
                                                                            Kondisi Jalan
                                                                        </strong>
                                                                        <p class="mb-0" id="kondisiJalanInfrastruktur">
                                                                        </p>
                                                                    </div>
                                                                    <div class="col-lg-4 col-md-6 col-12 mb-4">
                                                                        <strong class="site-footer-title d-block mb-3"
                                                                            data-lang="text_jarak_ke_jalan">
                                                                            Jarak ke Jalan
                                                                        </strong>
                                                                        <p class="mb-0" id="jarakKeJalanInfrastruktur">
                                                                        </p>
                                                                    </div>
                                                                    <div class="col-lg-4 col-md-6 col-12 mb-4">
                                                                        <strong class="site-footer-title d-block mb-3"
                                                                            data-lang="text_ada_jembatan">
                                                                            Ada Jembatan
                                                                        </strong>
                                                                        <p class="mb-0" id="adaJembatanInfrastruktur">
                                                                        </p>
                                                                    </div>
                                                                    <div class="col-lg-4 col-md-6 col-12 mb-4">
                                                                        <strong class="site-footer-title d-block mb-3"
                                                                            data-lang="text_ada_listrik">
                                                                            Ada Listrik
                                                                        </strong>
                                                                        <p class="mb-0" id="adaListrikInfrastruktur">
                                                                        </p>
                                                                    </div>
                                                                    <div class="col-lg-4 col-md-6 col-12 mb-4">
                                                                        <strong class="site-footer-title d-block mb-3"
                                                                            data-lang="text_ada_internet">
                                                                            Ada Internet
                                                                        </strong>
                                                                        <p class="mb-0" id="adaInternetInfrastruktur">
                                                                        </p>
                                                                    </div>
                                                                    <div class="col-lg-4 col-md-6 col-12 mb-4">
                                                                        <strong class="site-footer-title d-block mb-3"
                                                                            data-lang="text_sinyal_seluler">
                                                                            Sinyal Seluler
                                                                        </strong>
                                                                        <p class="mb-0" id="sinyalSelulerInfrastruktur">
                                                                        </p>
                                                                    </div>
                                                                    <div class="col-lg-12 col-md-6 col-12 mb-4">
                                                                        <strong class="site-footer-title d-block mb-3"
                                                                            data-lang="text_catatan">
                                                                            Catatan
                                                                        </strong>
                                                                        <p class="mb-0" id="catatanInfrastruktur"></p>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="tab-pane fade" id="tab33" role="tabpanel">
                                                                <div class="row">
                                                                    <div class="col-lg-4 col-md-6 col-12 mb-4">
                                                                        <strong class="site-footer-title d-block mb-3"
                                                                            data-lang="text_periode_pengecekan">
                                                                            Periode Pengecekan
                                                                        </strong>
                                                                        <p class="mb-0" id="periodePengecekanLandCover">
                                                                        </p>
                                                                    </div>
                                                                    <div class="col-lg-4 col-md-6 col-12 mb-4">
                                                                        <strong class="site-footer-title d-block mb-3"
                                                                            data-lang="text_kategori_area">
                                                                            Kategori Area
                                                                        </strong>
                                                                        <p class="mb-0" id="kategoriAreaLandCover"></p>
                                                                    </div>
                                                                    <div class="col-lg-4 col-md-6 col-12 mb-4">
                                                                        <strong class="site-footer-title d-block mb-3"
                                                                            data-lang="text_penggunaan_pertanian">
                                                                            Penggunaan Pertanian
                                                                        </strong>
                                                                        <p class="mb-0"
                                                                            id="penggunaanPertanianLandCover"></p>
                                                                    </div>
                                                                    <div class="col-lg-4 col-md-6 col-12 mb-4">
                                                                        <strong class="site-footer-title d-block mb-3"
                                                                            data-lang="text_penggunaan_lainnya">
                                                                            Penggunaan Lainnya
                                                                        </strong>
                                                                        <p class="mb-0" id="penggunaanLainnyaLandCover">
                                                                        </p>
                                                                    </div>
                                                                    <div class="col-lg-4 col-md-6 col-12 mb-4">
                                                                        <strong class="site-footer-title d-block mb-3"
                                                                            data-lang="text_persentase_tutupan">
                                                                            Persentase Tutupan
                                                                        </strong>
                                                                        <p class="mb-0" id="persentaseTutupanLandCover">
                                                                        </p>
                                                                    </div>
                                                                    <div class="col-lg-12 col-md-6 col-12 mb-4">
                                                                        <strong class="site-footer-title d-block mb-3"
                                                                            data-lang="text_catatan">
                                                                            Catatan
                                                                        </strong>
                                                                        <p class="mb-0" id="catatanLandCover"></p>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="tab-pane fade" id="tab34" role="tabpanel">
                                                                <div class="row">
                                                                    <div class="col-lg-4 col-md-6 col-12 mb-4">
                                                                        <strong class="site-footer-title d-block mb-3"
                                                                            data-lang="text_periode_pengecekan">Periode
                                                                            Pengecekan
                                                                        </strong>
                                                                        <p class="mb-0" id="periodePengecekanTopografi">
                                                                        </p>
                                                                    </div>
                                                                    <div class="col-lg-4 col-md-6 col-12 mb-4">
                                                                        <strong class="site-footer-title d-block mb-3"
                                                                            data-lang="text_lanskap">Lanskap
                                                                        </strong>
                                                                        <p class="mb-0" id="lanskapTopografi"></p>
                                                                    </div>
                                                                    <div class="col-lg-4 col-md-6 col-12 mb-4">
                                                                        <strong class="site-footer-title d-block mb-3"
                                                                            data-lang="text_fitur_tambahan">Fitur
                                                                            Tambahan
                                                                        </strong>
                                                                        <p class="mb-0" id="fiturTambahanTopografi"></p>
                                                                    </div>
                                                                    <div class="col-lg-4 col-md-6 col-12 mb-4">
                                                                        <strong class="site-footer-title d-block mb-3"
                                                                            data-lang="text_elevasi">Elevasi
                                                                            mdpl
                                                                        </strong>
                                                                        <p class="mb-0" id="elevasiTopografi"></p>
                                                                    </div>
                                                                    <div class="col-lg-4 col-md-6 col-12 mb-4">
                                                                        <strong class="site-footer-title d-block mb-3"
                                                                            data-lang="text_kemiringan_derajat">Kemiringan
                                                                            Derajat
                                                                        </strong>
                                                                        <p class="mb-0" id="kemiringanTopografi">
                                                                        </p>
                                                                    </div>
                                                                    <div class="col-lg-4 col-md-6 col-12 mb-4">
                                                                        <strong class="site-footer-title d-block mb-3"
                                                                            data-lang="text_rawan_erosi">Rawan
                                                                            Erosi</strong>
                                                                        <p class="mb-0" id="rawanErosiTopografi"></p>
                                                                    </div>
                                                                    <div class="col-lg-4 col-md-6 col-12 mb-4">
                                                                        <strong class="site-footer-title d-block mb-3"
                                                                            data-lang="text_arah_lereng">Arah Lereng
                                                                        </strong>
                                                                        <p class="mb-0" id="arahLerengTopografi"></p>
                                                                    </div>
                                                                    <div class="col-lg-12 col-md-6 col-12 mb-4">
                                                                        <strong class="site-footer-title d-block mb-3"
                                                                            data-lang="text_catatan">Catatan
                                                                        </strong>
                                                                        <p class="mb-0" id="catatanTopografi"></p>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="tab-pane fade" id="tab35" role="tabpanel">
                                                                <div class="row">
                                                                    <div class="col-lg-4 col-md-6 col-12 mb-4">
                                                                        <strong class="site-footer-title d-block mb-3"
                                                                            data-lang="text_periode_pengecekan">Periode
                                                                            Pengecekan
                                                                        </strong>
                                                                        <p class="mb-0" id="periodePengecekanPohon"></p>
                                                                    </div>
                                                                    <div class="col-lg-4 col-md-6 col-12 mb-4">
                                                                        <strong class="site-footer-title d-block mb-3"
                                                                            data-lang="text_jenis_pohon">Jenis Pohon
                                                                        </strong>
                                                                        <p class="mb-0" id="jenisPohon"></p>
                                                                    </div>
                                                                    <!-- <div class="col-lg-4 col-md-6 col-12 mb-4">
                                                                        <strong
                                                                            class="site-footer-title d-block mb-3" data-lang="text_nama_pohon">Nama
                                                                            Pohon
                                                                        </strong>
                                                                        <p class="mb-0" id="pohonNamaPohon"></p>
                                                                    </div>
                                                                    <div class="col-lg-4 col-md-6 col-12 mb-4">
                                                                        <strong
                                                                            class="site-footer-title d-block mb-3" data-lang="text_nama_latin">Nama
                                                                            Latin
                                                                        </strong>
                                                                        <p class="mb-0" id="pohonNamaLatin"></p>
                                                                    </div> -->
                                                                    <div class="col-lg-4 col-md-6 col-12 mb-4">
                                                                        <strong class="site-footer-title d-block mb-3"
                                                                            data-lang="text_fungsi_pohon">Fungsi
                                                                            Pohon
                                                                        </strong>
                                                                        <p class="mb-0" id="fungsiPohon"></p>
                                                                    </div>
                                                                    <div class="col-lg-4 col-md-6 col-12 mb-4">
                                                                        <strong class="site-footer-title d-block mb-3"
                                                                            data-lang="text_jumlah_pohon">Jumlah
                                                                            Pohon
                                                                        </strong>
                                                                        <p class="mb-0" id="jumlahPohon"></p>
                                                                    </div>
                                                                    <div class="col-lg-4 col-md-6 col-12 mb-4">
                                                                        <strong class="site-footer-title d-block mb-3"
                                                                            data-lang="text_diameter_rata_rata">Diameter
                                                                            Rata-rata (cm)
                                                                        </strong>
                                                                        <p class="mb-0" id="diameterRata2CmPohon"></p>
                                                                    </div>
                                                                    <div class="col-lg-4 col-md-6 col-12 mb-4">
                                                                        <strong class="site-footer-title d-block mb-3"
                                                                            data-lang="text_tinggi_rata_rata">Tinggi
                                                                            Rata-rata (m)
                                                                        </strong>
                                                                        <p class="mb-0" id="tinggiRata2MPohon"></p>
                                                                    </div>
                                                                    <div class="col-lg-4 col-md-6 col-12 mb-4">
                                                                        <strong class="site-footer-title d-block mb-3"
                                                                            data-lang="text_kondisi">Kondisi
                                                                        </strong>
                                                                        <p class="mb-0" id="kondisiPohon"></p>
                                                                    </div>
                                                                    <div class="col-lg-12 col-md-6 col-12 mb-4">
                                                                        <strong class="site-footer-title d-block mb-3"
                                                                            data-lang="text_catatan">Catatan
                                                                        </strong>
                                                                        <p class="mb-0" id="catatanPohon"></p>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- <div class="tab-pane fade" id="tab4" role="tabpanel"
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
                                                </div>
                                            </div> -->
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Modal Informasi Benih -->
                        <div class="modal fade" id="bankBenihModal" tabindex="0">
                            <!-- <div class="modal-dialog modal-xl modal-dialog-scrollable"> -->
                            <div class="modal-dialog modal-xxl-custom modal-dialog-scrollable">
                                <div class="modal-content">

                                    <div class="modal-header">
                                        <h5 id="modal-title-bank-benih"></h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>

                                    <div class="modal-body">
                                        <ul class="nav nav-tabs custom-tabs" id="myTabHutanAdat" role="tablist">
                                            <li class="nav-item" role="presentation">
                                                <button class="nav-link active" id="tab1benih-tab" data-bs-toggle="tab"
                                                    data-bs-target="#tab1benih" type="button" role="tab"
                                                    aria-controls="tab1benih" aria-selected="false" data-lang="text_benih">Benih</button>
                                            </li>
                                            <li class="nav-item" role="presentation">
                                                <button class="nav-link" id="tab2monitoring-tab" data-bs-toggle="tab"
                                                    data-bs-target="#tab2monitoring" type="button" role="tab"
                                                    aria-controls="tab2monitoring"
                                                    aria-selected="false" data-lang="text_monitoring">Monitoring</button>
                                            </li>
                                            <li class="nav-item" role="presentation">
                                                <button class="nav-link" id="tab3tanahBenih-tab" data-bs-toggle="tab"
                                                    data-bs-target="#tab3tanahBenih" type="button" role="tab"
                                                    aria-controls="tab3tanahBenih" aria-selected="false" data-lang="text_tanah">Tanah</button>
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

                                                            <a href="" id="fotoBenihLink" class="popup-image">
                                                                <img id="fotoBenih" class="projects-image img-fluid"
                                                                    alt="">
                                                            </a>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-3 col-md-6 col-12 mb-4 mt-4">
                                                        <strong class="site-footer-title d-block mb-3" data-lang="text_nomor_aksesi">Nomor
                                                            Aksesi</strong>
                                                        <p class="mb-0" id="nomorAksesi"></p>
                                                        <strong class="site-footer-title d-block mb-3 mt-4" data-lang="text_nama_negara">Nama
                                                            Negara</strong>
                                                        <p class="mb-0" id="namaNegara"></p>
                                                        <strong class="site-footer-title d-block mb-3 mt-4" data-lang="text_famili_tanaman">Famili
                                                            Tanaman</strong>
                                                        <p class="mb-0" id="familiTanaman"></p>
                                                        <strong
                                                            class="site-footer-title d-block mb-3 mt-4" data-lang="text_provenance">Provenance</strong>
                                                        <p class="mb-0" id="provenance"></p>
                                                        <strong class="site-footer-title d-block mb-3 mt-4" data-lang="text_tipe_penyimpanan_benih">Tipe
                                                            Penyimpanan Benih</strong>
                                                        <p class="mb-0" id="tipePenyimpananBenih"></p>
                                                    </div>
                                                    <div class="col-lg-3 col-md-6 col-12 mb-4 mt-4">
                                                        <strong class="site-footer-title d-block mb-3" data-lang="text_tanggal_masuk">Tanggal
                                                            Masuk</strong>
                                                        <p class="mb-0" id="tanggalMasuk"></p>
                                                        <strong class="site-footer-title d-block mb-3 mt-4" data-lang="text_jumlah_stok">Jumlah
                                                            Stok</strong>
                                                        <p class="mb-0" id="jumlahStok"></p>
                                                        <strong class="site-footer-title d-block mb-3 mt-4" data-lang="text_satuan_stok">Satuan
                                                            Stok</strong>
                                                        <p class="mb-0" id="satuanStok"></p>
                                                        <strong class="site-footer-title d-block mb-3 mt-4" data-lang="text_kadar_air_persen">Kadar Air
                                                            Persen</strong>
                                                        <p class="mb-0" id="kadarAirPersen"></p>
                                                        <strong class="site-footer-title d-block mb-3 mt-4" data-lang="text_viabilitas_persen">Viabilitas
                                                            Persen</strong>
                                                        <p class="mb-0" id="viabilitasPersen"></p>
                                                    </div>
                                                    <div class="col-lg-3 col-md-6 col-12 mb-4 mt-4">
                                                        <strong class="site-footer-title d-block mb-3" data-lang="text_ketinggian_mdpl">Ketinggian
                                                            mdpl</strong>
                                                        <p class="mb-0" id="ketinggianMdpl"></p>
                                                        <strong class="site-footer-title d-block mb-3 mt-4" data-lang="text_masa_berlaku_sampai">Masa Berlaku
                                                            Sampai</strong>
                                                        <p class="mb-0" id="masaBerlakuSampai"></p>
                                                        <strong class="site-footer-title d-block mb-3 mt-4" data-lang="text_lokasi_penyimpanan">Lokasi
                                                            Penyimpanan</strong>
                                                        <p class="mb-0" id="lokasiPenyimpanan"></p>
                                                        <strong class="site-footer-title d-block mb-3 mt-4" data-lang="text_titik_koleksi_lat">Titik
                                                            Koleksi
                                                            Lat</strong>
                                                        <p class="mb-0" id="titikKoleksiLat"></p>
                                                        <strong class="site-footer-title d-block mb-3 mt-4" data-lang="text_titik_koleksi_lng">Titik
                                                            Koleksi
                                                            Lng</strong>
                                                        <p class="mb-0" id="titikKoleksiLng"></p>
                                                    </div>
                                                    <!-- <div class="col-lg-3 col-md-6 col-12 mb-4">
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
                                                    </div> -->
                                                    <div class="col-lg-9 col-md-6 col-12 mb-4">
                                                        <strong class="site-footer-title d-block mb-3" data-lang="text_catatan">Catatan</strong>
                                                        <p class="mb-0" id="catatanBenih"></p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="tab-pane fade" id="tab2monitoring" role="tabpanel"
                                                aria-labelledby="tab2monitoring-tab">
                                                <div class="row">
                                                    <div class="col-lg-12 col-md-12 col-12">
                                                        <strong class="site-footer-title d-block mb-3">Monitoring
                                                            Penanaman Benih
                                                        </strong>
                                                        <table class="profile-thumb">
                                                            <tr>
                                                                <th data-lang="text_tipe_penanaman">Tipe Penanaman</th>
                                                                <th data-lang="text_progress_status_monitoring">Progress Status Monitoring</th>
                                                                <th data-lang="text_periode_pengecekan">Periode Pengecekan</th>
                                                                <th data-lang="text_tanggal_tanam">Tanggal Tanam</th>
                                                                <th data-lang="text_tanggal_monitoring">Tanggal Monitoring</th>
                                                                <th data-lang="text_luas_tanam">Luas Tanam (ha)</th>
                                                                <th data-lang="text_survival_rate_persen">Survival Rate (%)</th>
                                                                <th data-lang="text_jumlah_ditanam">Jumlah Ditanam</th>
                                                                <th data-lang="text_satuan">Satuan</th>
                                                                <th data-lang="text_jumlah_hidup">Jumlah Hidup</th>
                                                                <th data-lang="text_jumlah_mati">Jumlah Mati</th>
                                                                <th data-lang="text_tinggi_rata2_cm">Tinggi Rata-Rata (cm)</th>
                                                                <th data-lang="text_diameter_rata2_cm">Diameter Rata-Rata (cm)</th>
                                                                <th data-lang="text_catatan">Catatan</th>
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
                                                        <strong class="site-footer-title d-block mb-3" data-lang="text_nama_lahan">Nama
                                                            Lahan</strong>
                                                        <p class="mb-0" id="namaLahanBenih"></p>
                                                    </div>
                                                    <div class="col-lg-3 col-md-6 col-12 mb-4">
                                                        <strong class="site-footer-title d-block mb-3" data-lang="text_legalitas">Legalitas
                                                        </strong>
                                                        <p class="mb-0" id="legalitasLahanBenih"></p>
                                                    </div>
                                                    <div class="col-lg-3 col-md-6 col-12 mb-4">
                                                        <strong class="site-footer-title d-block mb-3" data-lang="text_status_kawasan">Status Kawasan
                                                        </strong>
                                                        <p class="mb-0" id="statusKawasanBenih"></p>
                                                    </div>
                                                    <div class="col-lg-3 col-md-6 col-12 mb-4">
                                                        <strong class="site-footer-title d-block mb-3" data-lang="text_luas_lahan">Luas
                                                        </strong>
                                                        <p class="mb-0" id="luasHaBenih"></p>
                                                    </div>
                                                    <div class="col-lg-3 col-md-6 col-12 mb-4">
                                                        <strong class="site-footer-title d-block mb-3" data-lang="text_sejarah_lahan">Sejarah
                                                        </strong>
                                                        <p class="mb-0" id="sejarahBenih"></p>
                                                    </div>
                                                    <div class="col-lg-3 col-md-6 col-12 mb-4">
                                                        <strong class="site-footer-title d-block mb-3" data-lang="text_sudah_validasi">Sudah
                                                            Validasi</strong>
                                                        <p class="mb-0" id="sudahValidasiTanahBenih"></p>
                                                    </div>
                                                    <div class="col-lg-3 col-md-6 col-12 mb-4">
                                                        <strong class="site-footer-title d-block mb-3" data-lang="text_tanggal_validasi">Tanggal
                                                            Validasi</strong>
                                                        <p class="mb-0" id="tanggalValidasiTanahBenih"></p>
                                                    </div>
                                                    <div class="col-lg-6 col-md-6 col-12 mb-4">
                                                        <strong class="site-footer-title d-block mb-3" data-lang="text_alamat_lokasi_lahan">Alamat Lokasi
                                                        </strong>
                                                        <p class="mb-0" id="alamatLokasiBenih"></p>
                                                    </div>
                                                    <div class="col-lg-6 col-md-6 col-12 mb-4">
                                                        <strong class="site-footer-title d-block mb-3" data-lang="text_keterangan_lahan">Keterangan
                                                        </strong>
                                                        <p class="mb-0" id="keteranganBenih"></p>
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

                    </div>
                </div>
            </div>
        </section>
    </main>

    <?php
    include 'partials/footer.php';
    ?>

</body>

</html>