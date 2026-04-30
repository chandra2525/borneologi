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

                            <div id="mapLoading" style="
                                position:absolute;
                                top:10px;
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
                                ⏳ Memuat data...
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
                                                        <p class="mb-0" id="namaMasyarakatHukumAdat"></p>
                                                    </div>
                                                    <div class="col-lg-3 col-md-6 col-12 mb-4">
                                                        <strong class="site-footer-title d-block mb-3">Kategori
                                                            Masyarakat Hukum Adat
                                                        </strong>
                                                        <p class="mb-0" id="namaKategoriKelompok"></p>
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
                                                        <p class="mb-0" id="statusMasyarakatHukumAdat"></p>
                                                    </div>
                                                    <div class="col-lg-3 col-md-6 col-12 mb-4">
                                                        <strong class="site-footer-title d-block mb-3">Total Anggota
                                                        </strong>
                                                        <p class="mb-0" id="totalAnggotaMasyarakatHukumAdat"></p>
                                                    </div>
                                                    <div class="col-lg-3 col-md-6 col-12 mb-4">
                                                        <strong class="site-footer-title d-block mb-3">Alamat Masyarakat
                                                            Hukum Adat
                                                        </strong>
                                                        <p class="mb-0" id="alamatMasyarakatHukumAdat"></p>
                                                    </div>
                                                    <div class="col-lg-6 col-md-6 col-12 mb-4">
                                                        <strong class="site-footer-title d-block mb-3">Deskripsi
                                                        </strong>
                                                        <p class="mb-0" id="deskripsiMasyarakatHukumAdat"></p>
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
                                                        <p class="mb-0" id="namaLahanHA"></p>
                                                    </div>
                                                    <div class="col-lg-3 col-md-6 col-12 mb-4">
                                                        <strong class="site-footer-title d-block mb-3">Legalitas
                                                        </strong>
                                                        <p class="mb-0" id="legalitasLahanHA"></p>
                                                    </div>
                                                    <div class="col-lg-3 col-md-6 col-12 mb-4">
                                                        <strong class="site-footer-title d-block mb-3">Luas
                                                        </strong>
                                                        <p class="mb-0" id="luasHaHA"></p>
                                                    </div>
                                                    <div class="col-lg-3 col-md-6 col-12 mb-4">
                                                        <strong class="site-footer-title d-block mb-3">Sejarah
                                                        </strong>
                                                        <p class="mb-0" id="sejarahHA"></p>
                                                    </div>
                                                    <div class="col-lg-6 col-md-6 col-12 mb-4">
                                                        <strong class="site-footer-title d-block mb-3">Alamat Lokasi
                                                        </strong>
                                                        <p class="mb-0" id="alamatLokasiHA"></p>
                                                    </div>
                                                    <div class="col-lg-6 col-md-6 col-12 mb-4">
                                                        <strong class="site-footer-title d-block mb-3">Keterangan
                                                        </strong>
                                                        <p class="mb-0" id="keteranganHA"></p>
                                                    </div>

                                                    <div class="col-lg-3 col-md-6 col-12 mb-4">
                                                        <strong class="site-footer-title d-block mb-3">Sudah
                                                            Validasi</strong>
                                                        <p class="mb-0" id="sudahValidasiHA"></p>
                                                    </div>
                                                    <div class="col-lg-3 col-md-6 col-12 mb-4">
                                                        <strong class="site-footer-title d-block mb-3">Tanggal
                                                            Validasi</strong>
                                                        <p class="mb-0" id="tanggalValidasiHA"></p>
                                                    </div>
                                                    <!-- <div class="col-lg-3 col-md-6 col-12 mb-4">
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
                            <div class="modal-dialog modal-xl modal-dialog-scrollable">
                                <div class="modal-content">

                                    <div class="modal-header">
                                        <h5 class="modal-title1"></h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>

                                    <div class="modal-body">
                                        <ul class="nav nav-tabs custom-tabs" id="myTabProvinsi" role="tablist">
                                            <li class="nav-item" role="presentation">
                                                <button class="nav-link active" id="tab1Provinsi-tab"
                                                    data-bs-toggle="tab" data-bs-target="#tab1Provinsi" type="button"
                                                    role="tab" aria-controls="tab1Provinsi" aria-selected="false">Data
                                                    Provinsi</button>
                                            </li>
                                            <li class="nav-item" role="presentation">
                                                <button class="nav-link" id="tab2ProvinsiKabupaten-tab"
                                                    data-bs-toggle="tab" data-bs-target="#tab2ProvinsiKabupaten"
                                                    type="button" role="tab" aria-controls="tab2ProvinsiKabupaten"
                                                    aria-selected="true">Data Kabupaten</button>
                                            </li>
                                            <li class="nav-item" role="presentation">
                                                <button class="nav-link" id="tab3ProvinsiKecamatan-tab"
                                                    data-bs-toggle="tab" data-bs-target="#tab3ProvinsiKecamatan"
                                                    type="button" role="tab" aria-controls="tab3ProvinsiKecamatan"
                                                    aria-selected="false">Data Kecamatan</button>
                                            </li>
                                        </ul>

                                        <div class="tab-content pt-3" id="myTabContent">
                                            <div class="tab-pane fade show active" id="tab1Provinsi" role="tabpanel"
                                                aria-labelledby="tab1Provinsi-tab">
                                                <div class="row">
                                                    <div class="col-lg-3 col-md-6 col-12 mb-4">
                                                        <strong class="site-footer-title d-block mb-3">Nama
                                                            Provinsi</strong>
                                                        <p class="mb-0" id="namaProvinsi"></p>
                                                    </div>
                                                    <div class="col-lg-3 col-md-6 col-12 mb-4">
                                                        <strong class="site-footer-title d-block mb-3">Luas
                                                            Provinsi</strong>
                                                        <p class="mb-0" id="luasProvinsi"></p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="tab-pane fade" id="tab2ProvinsiKabupaten" role="tabpanel"
                                                aria-labelledby="tab2ProvinsiKabupaten-tab">
                                                <div class="row">
                                                    <div class="col-lg-12 col-md-12 col-12">
                                                        <strong class="site-footer-title d-block mb-3">List Kabupaten
                                                        </strong>
                                                        <table class="profile-thumb">
                                                            <tr>
                                                                <th>Nama Kabupaten</th>
                                                                <th>Kode Kabupaten</th>
                                                                <th>Luas Kabupaten</th>
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
                                                        <strong class="site-footer-title d-block mb-3">List Kecamatan
                                                        </strong>
                                                        <table class="profile-thumb">
                                                            <tr>
                                                                <th>Nama Kecamatan</th>
                                                                <th>Kode Kecamatan</th>
                                                                <th>Luas Kecamatan</th>
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
                            <div class="modal-dialog modal-xl modal-dialog-scrollable">
                                <div class="modal-content">

                                    <div class="modal-header">
                                        <h5 class="modal-title1"></h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>

                                    <div class="modal-body">
                                        <ul class="nav nav-tabs custom-tabs" id="myTabKabupaten" role="tablist">
                                            <li class="nav-item" role="presentation">
                                                <button class="nav-link active" id="tab1Kabupaten-tab"
                                                    data-bs-toggle="tab" data-bs-target="#tab1Kabupaten" type="button"
                                                    role="tab" aria-controls="tab1Kabupaten" aria-selected="false">Data
                                                    Kabupaten</button>
                                            </li>
                                            <li class="nav-item" role="presentation">
                                                <button class="nav-link" id="tab2KabupatenKecamatan-tab"
                                                    data-bs-toggle="tab" data-bs-target="#tab2KabupatenKecamatan"
                                                    type="button" role="tab" aria-controls="tab2KabupatenKecamatan"
                                                    aria-selected="true">Data Kecamatan</button>
                                            </li>
                                        </ul>

                                        <div class="tab-content pt-3" id="myTabContent">
                                            <div class="tab-pane fade show active" id="tab1Kabupaten" role="tabpanel"
                                                aria-labelledby="tab1Kabupaten-tab">
                                                <div class="row">
                                                    <div class="col-lg-3 col-md-6 col-12 mb-4">
                                                        <strong class="site-footer-title d-block mb-3">Nama
                                                            Kabupaten</strong>
                                                        <p class="mb-0" id="namaKabupaten"></p>
                                                    </div>
                                                    <div class="col-lg-3 col-md-6 col-12 mb-4">
                                                        <strong class="site-footer-title d-block mb-3">Luas
                                                            Kabupaten</strong>
                                                        <p class="mb-0" id="luasKabupaten"></p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="tab-pane fade" id="tab2KabupatenKecamatan" role="tabpanel"
                                                aria-labelledby="tab2KabupatenKecamatan-tab">
                                                <div class="row">
                                                    <div class="col-lg-12 col-md-12 col-12">
                                                        <strong class="site-footer-title d-block mb-3">List Kecamatan
                                                        </strong>
                                                        <table class="profile-thumb">
                                                            <tr>
                                                                <th>Nama Kecamatan</th>
                                                                <th>Kode Kecamatan</th>
                                                                <th>Luas Kecamatan</th>
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
                            <div class="modal-dialog modal-xl modal-dialog-scrollable">
                                <div class="modal-content">

                                    <div class="modal-header">
                                        <h5 class="modal-title1"></h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>

                                    <div class="modal-body">
                                        <ul class="nav nav-tabs custom-tabs" id="myTabKecamatan" role="tablist">
                                            <li class="nav-item" role="presentation">
                                                <button class="nav-link active" id="tab1Kecamatan-tab"
                                                    data-bs-toggle="tab" data-bs-target="#tab1Kecamatan" type="button"
                                                    role="tab" aria-controls="tab1Kecamatan" aria-selected="false">Data
                                                    Kecamatan</button>
                                            </li>
                                        </ul>

                                        <div class="tab-content pt-3" id="myTabContent">
                                            <div class="tab-pane fade show active" id="tab1Kecamatan" role="tabpanel"
                                                aria-labelledby="tab1Kecamatan-tab">
                                                <div class="row">
                                                    <div class="col-lg-3 col-md-6 col-12 mb-4">
                                                        <strong class="site-footer-title d-block mb-3">Nama
                                                            Kecamatan</strong>
                                                        <p class="mb-0" id="namaKecamatan"></p>
                                                    </div>
                                                    <div class="col-lg-3 col-md-6 col-12 mb-4">
                                                        <strong class="site-footer-title d-block mb-3">Luas
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

                        <div id="loadingDetail" style="display:none; text-align:center; padding:20px;">
                            <div class="spinner-border text-primary" role="status"></div>
                            <p class="mt-2">Memuat data...</p>
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