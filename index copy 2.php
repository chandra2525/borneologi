<?php

include 'partials/header.php';

?>

<body>

```
<!-- PRELOADER -->
<section class="preloader">
    <div class="spinner">
        <span class="spinner-rotate"></span>
    </div>
</section>

<?php
include 'partials/navbar.php';
?>

<main>

    <!-- =========================================
         HERO GLOSARIUM
    ========================================== -->
    <section class="glossary-hero">

        <div class="container">

            <div class="row align-items-center">

                <div class="col-lg-7 col-12">

                    <div class="glossary-hero-content">

                        <div class="glossary-breadcrumb">
                            <a href="index.php">Borneologi</a>
                            <span>/</span>
                            <span>Glosarium</span>
                        </div>

                        <h1 class="glossary-hero-title">
                            Glosarium Borneologi
                        </h1>

                        <p class="glossary-hero-subtitle">
                            Ensiklopedia istilah, pengetahuan, budaya, adat,
                            bahasa, dan kehidupan masyarakat Dayak.
                        </p>

                        <p class="glossary-hero-description">
                            Temukan berbagai istilah dan pengetahuan yang
                            berkaitan dengan masyarakat Dayak di Kalimantan.
                        </p>

                    </div>

                </div>

                <div class="col-lg-5 col-12">

                    <div class="glossary-hero-card">

                        <div class="glossary-hero-icon">
                            <i class="bi bi-book"></i>
                        </div>

                        <h3>Pengetahuan Dayak</h3>

                        <p>
                            Kumpulan istilah dan pengetahuan yang
                            terdokumentasi untuk mendukung pelestarian
                            pengetahuan lokal.
                        </p>

                    </div>

                </div>

            </div>

        </div>

    </section>


    <!-- =========================================
         SEARCH & FILTER
    ========================================== -->
    <section class="glossary-search-section">

        <div class="container">

            <div class="glossary-search-wrapper">

                <div class="glossary-search-box">

                    <i class="bi bi-search"></i>

                    <input
                        type="text"
                        id="glossarySearch"
                        class="glossary-search-input"
                        placeholder="Cari istilah, budaya, adat, bahasa..."
                        autocomplete="off"
                    >

                    <button
                        type="button"
                        class="glossary-search-button"
                        id="glossarySearchButton">
                        Cari
                    </button>

                </div>

                <!-- CATEGORY -->
                <div class="glossary-filter-row">

                    <span class="glossary-filter-label">
                        Kategori:
                    </span>

                    <button
                        type="button"
                        class="glossary-filter active"
                        data-category="all">
                        Semua
                    </button>

                    <button
                        type="button"
                        class="glossary-filter"
                        data-category="bahasa">
                        Bahasa
                    </button>

                    <button
                        type="button"
                        class="glossary-filter"
                        data-category="adat">
                        Adat Istiadat
                    </button>

                    <button
                        type="button"
                        class="glossary-filter"
                        data-category="budaya">
                        Budaya
                    </button>

                    <button
                        type="button"
                        class="glossary-filter"
                        data-category="lingkungan">
                        Lingkungan
                    </button>

                    <button
                        type="button"
                        class="glossary-filter"
                        data-category="kelembagaan">
                        Kelembagaan Adat
                    </button>

                </div>

            </div>

        </div>

    </section>


    <!-- =========================================
         MAIN GLOSARIUM
    ========================================== -->
    <section class="glossary-section">

        <div class="container">

            <div class="row">

                <!-- ==============================
                     CONTENT
                =============================== -->
                <div class="col-lg-8 col-12">

                    <div class="glossary-section-heading">

                        <div>
                            <span class="glossary-section-label">
                                ENSIKLOPEDIA
                            </span>

                            <h2>
                                Istilah Dayak
                            </h2>
                        </div>

                        <span class="glossary-total">
                            10 istilah
                        </span>

                    </div>


                    <!-- ALPHABET -->
                    <div class="glossary-alphabet">

                        <button class="active">Semua</button>
                        <button>A</button>
                        <button>B</button>
                        <button>C</button>
                        <button>D</button>
                        <button>E</button>
                        <button>F</button>
                        <button>G</button>
                        <button>H</button>
                        <button>I</button>
                        <button>J</button>
                        <button>K</button>
                        <button>L</button>
                        <button>M</button>
                        <button>N</button>
                        <button>O</button>
                        <button>P</button>
                        <button>Q</button>
                        <button>R</button>
                        <button>S</button>
                        <button>T</button>
                        <button>U</button>
                        <button>V</button>
                        <button>W</button>
                        <button>Y</button>
                        <button>Z</button>

                    </div>


                    <!-- ==============================
                         TERM LIST
                    =============================== -->
                    <div class="glossary-list">


                        <!-- ITEM -->
                        <article
                            class="glossary-item"
                            data-term="Kaleka"
                            data-category="lingkungan">

                            <div class="glossary-letter">
                                K
                            </div>

                            <div class="glossary-item-content">

                                <div class="glossary-item-meta">

                                    <span class="glossary-category">
                                        Lingkungan
                                    </span>

                                    <span>
                                        Bahasa Ngaju
                                    </span>

                                </div>

                                <h3>
                                    <a href="#">
                                        Kaleka
                                    </a>
                                </h3>

                                <div class="glossary-pronunciation">
                                    ka-le-ka
                                </div>

                                <p>
                                    Kawasan yang memiliki nilai ekologis,
                                    sosial, dan budaya serta berkaitan
                                    dengan pengetahuan tradisional
                                    masyarakat Dayak.
                                </p>

                                <a href="#" class="glossary-read-more">
                                    Baca selengkapnya
                                    <i class="bi bi-arrow-right"></i>
                                </a>

                            </div>

                        </article>


                        <!-- ITEM -->
                        <article
                            class="glossary-item"
                            data-term="Damang"
                            data-category="kelembagaan">

                            <div class="glossary-letter">
                                D
                            </div>

                            <div class="glossary-item-content">

                                <div class="glossary-item-meta">

                                    <span class="glossary-category">
                                        Kelembagaan Adat
                                    </span>

                                    <span>
                                        Kalimantan Tengah
                                    </span>

                                </div>

                                <h3>
                                    <a href="#">
                                        Damang
                                    </a>
                                </h3>

                                <div class="glossary-pronunciation">
                                    da-mang
                                </div>

                                <p>
                                    Sebutan yang digunakan dalam sistem
                                    kelembagaan adat Dayak di Kalimantan
                                    Tengah.
                                </p>

                                <a href="#" class="glossary-read-more">
                                    Baca selengkapnya
                                    <i class="bi bi-arrow-right"></i>
                                </a>

                            </div>

                        </article>


                        <!-- ITEM -->
                        <article
                            class="glossary-item"
                            data-term="Huma Betang"
                            data-category="budaya">

                            <div class="glossary-letter">
                                H
                            </div>

                            <div class="glossary-item-content">

                                <div class="glossary-item-meta">

                                    <span class="glossary-category">
                                        Budaya
                                    </span>

                                    <span>
                                        Dayak Ngaju
                                    </span>

                                </div>

                                <h3>
                                    <a href="#">
                                        Huma Betang
                                    </a>
                                </h3>

                                <div class="glossary-pronunciation">
                                    hu-ma be-tang
                                </div>

                                <p>
                                    Rumah panjang tradisional yang menjadi
                                    salah satu bentuk arsitektur dan
                                    kehidupan bersama masyarakat Dayak.
                                </p>

                                <a href="#" class="glossary-read-more">
                                    Baca selengkapnya
                                    <i class="bi bi-arrow-right"></i>
                                </a>

                            </div>

                        </article>


                        <!-- ITEM -->
                        <article
                            class="glossary-item"
                            data-term="Lewu"
                            data-category="budaya">

                            <div class="glossary-letter">
                                L
                            </div>

                            <div class="glossary-item-content">

                                <div class="glossary-item-meta">

                                    <span class="glossary-category">
                                        Budaya
                                    </span>

                                    <span>
                                        Bahasa Ngaju
                                    </span>

                                </div>

                                <h3>
                                    <a href="#">
                                        Lewu
                                    </a>
                                </h3>

                                <div class="glossary-pronunciation">
                                    le-wu
                                </div>

                                <p>
                                    Istilah yang digunakan dalam sejumlah
                                    konteks masyarakat Dayak Ngaju untuk
                                    menyebut kampung atau permukiman.
                                </p>

                                <a href="#" class="glossary-read-more">
                                    Baca selengkapnya
                                    <i class="bi bi-arrow-right"></i>
                                </a>

                            </div>

                        </article>


                        <!-- ITEM -->
                        <article
                            class="glossary-item"
                            data-term="Tiwah"
                            data-category="adat">

                            <div class="glossary-letter">
                                T
                            </div>

                            <div class="glossary-item-content">

                                <div class="glossary-item-meta">

                                    <span class="glossary-category">
                                        Upacara Adat
                                    </span>

                                    <span>
                                        Dayak Ngaju
                                    </span>

                                </div>

                                <h3>
                                    <a href="#">
                                        Tiwah
                                    </a>
                                </h3>

                                <div class="glossary-pronunciation">
                                    ti-wah
                                </div>

                                <p>
                                    Upacara adat yang berkaitan dengan
                                    penghormatan terhadap orang yang telah
                                    meninggal dalam tradisi masyarakat
                                    Dayak Ngaju.
                                </p>

                                <a href="#" class="glossary-read-more">
                                    Baca selengkapnya
                                    <i class="bi bi-arrow-right"></i>
                                </a>

                            </div>

                        </article>


                        <!-- ITEM -->
                        <article
                            class="glossary-item"
                            data-term="Belom Bahadat"
                            data-category="adat">

                            <div class="glossary-letter">
                                B
                            </div>

                            <div class="glossary-item-content">

                                <div class="glossary-item-meta">

                                    <span class="glossary-category">
                                        Adat Istiadat
                                    </span>

                                    <span>
                                        Kalimantan Tengah
                                    </span>

                                </div>

                                <h3>
                                    <a href="#">
                                        Belom Bahadat
                                    </a>
                                </h3>

                                <div class="glossary-pronunciation">
                                    be-lom ba-ha-dat
                                </div>

                                <p>
                                    Konsep kehidupan masyarakat yang
                                    menekankan perilaku dan kehidupan
                                    sesuai dengan nilai serta norma adat.
                                </p>

                                <a href="#" class="glossary-read-more">
                                    Baca selengkapnya
                                    <i class="bi bi-arrow-right"></i>
                                </a>

                            </div>

                        </article>


                    </div>


                    <!-- NO RESULT -->
                    <div
                        id="glossaryNoResult"
                        class="glossary-no-result"
                        style="display:none;">

                        <i class="bi bi-search"></i>

                        <h3>
                            Istilah tidak ditemukan
                        </h3>

                        <p>
                            Coba gunakan kata pencarian atau kategori
                            lainnya.
                        </p>

                    </div>

                </div>


                <!-- ==============================
                     SIDEBAR
                =============================== -->
                <div class="col-lg-4 col-12">

                    <aside class="glossary-sidebar">


                        <!-- ABOUT -->
                        <div class="glossary-sidebar-card">

                            <div class="glossary-sidebar-icon">
                                <i class="bi bi-info-circle"></i>
                            </div>

                            <h3>
                                Tentang Glosarium
                            </h3>

                            <p>
                                Glosarium Borneologi merupakan ruang
                                dokumentasi istilah dan pengetahuan
                                masyarakat Dayak yang berkaitan dengan
                                budaya, bahasa, adat, lingkungan,
                                dan kehidupan masyarakat.
                            </p>

                        </div>


                        <!-- CATEGORY -->
                        <div class="glossary-sidebar-card">

                            <h3>
                                Kategori
                            </h3>

                            <ul class="glossary-category-list">

                                <li>
                                    <a href="#">
                                        <span>Bahasa & Linguistik</span>
                                        <span>12</span>
                                    </a>
                                </li>

                                <li>
                                    <a href="#">
                                        <span>Adat Istiadat</span>
                                        <span>18</span>
                                    </a>
                                </li>

                                <li>
                                    <a href="#">
                                        <span>Budaya</span>
                                        <span>24</span>
                                    </a>
                                </li>

                                <li>
                                    <a href="#">
                                        <span>Lingkungan</span>
                                        <span>15</span>
                                    </a>
                                </li>

                                <li>
                                    <a href="#">
                                        <span>Kelembagaan Adat</span>
                                        <span>8</span>
                                    </a>
                                </li>

                            </ul>

                        </div>


                        <!-- FEATURED -->
                        <div class="glossary-sidebar-card glossary-featured">

                            <span class="glossary-featured-label">
                                ISTILAH PILIHAN
                            </span>

                            <h3>
                                Kaleka
                            </h3>

                            <p>
                                Pengetahuan lokal masyarakat yang
                                berkaitan dengan kawasan dan pengelolaan
                                lingkungan secara turun-temurun.
                            </p>

                            <a href="#">
                                Baca istilah
                                <i class="bi bi-arrow-right"></i>
                            </a>

                        </div>


                    </aside>

                </div>

            </div>

        </div>

    </section>

</main>


<!-- =========================================
     GLOSARIUM CSS
========================================== -->
<style>

    /* HERO */

    .glossary-hero {
        position: relative;
        padding: 150px 0 90px;
        background:
            linear-gradient(
                135deg,
                rgba(83, 93, 161, 0.97),
                rgba(83, 93, 161, 0.88)
            );
        color: #fff;
        overflow: hidden;
    }

    .glossary-hero::after {
        content: "";
        position: absolute;
        width: 420px;
        height: 420px;
        border-radius: 50%;
        background: rgba(255,255,255,0.05);
        right: -120px;
        top: -140px;
    }

    .glossary-hero-content {
        position: relative;
        z-index: 2;
    }

    .glossary-breadcrumb {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 22px;
        font-size: 14px;
        opacity: .85;
    }

    .glossary-breadcrumb a {
        color: #fff;
        text-decoration: none;
    }

    .glossary-hero-title {
        font-size: 52px;
        font-weight: 700;
        margin-bottom: 20px;
        color: #fff;
    }

    .glossary-hero-subtitle {
        max-width: 650px;
        font-size: 22px;
        line-height: 1.6;
        color: #fff;
        margin-bottom: 15px;
    }

    .glossary-hero-description {
        max-width: 620px;
        color: rgba(255,255,255,.78);
        font-size: 16px;
        line-height: 1.8;
    }

    .glossary-hero-card {
        position: relative;
        z-index: 2;
        background: rgba(255,255,255,.12);
        border: 1px solid rgba(255,255,255,.18);
        border-radius: 20px;
        padding: 35px;
        backdrop-filter: blur(8px);
        -webkit-backdrop-filter: blur(8px);
    }

    .glossary-hero-icon {
        width: 62px;
        height: 62px;
        border-radius: 15px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: rgba(255,255,255,.15);
        font-size: 28px;
        margin-bottom: 22px;
    }

    .glossary-hero-card h3 {
        color: #fff;
        margin-bottom: 12px;
    }

    .glossary-hero-card p {
        color: rgba(255,255,255,.78);
        line-height: 1.7;
        margin-bottom: 0;
    }


    /* SEARCH */

    .glossary-search-section {
        padding: 40px 0 20px;
        background: #fff;
    }

    .glossary-search-wrapper {
        background: #fff;
        border-radius: 18px;
    }

    .glossary-search-box {
        display: flex;
        align-items: center;
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        padding: 5px 5px 5px 18px;
        box-shadow: 0 8px 25px rgba(0,0,0,.05);
    }

    .glossary-search-box > i {
        font-size: 20px;
        color: #777;
        margin-right: 12px;
    }

    .glossary-search-input {
        flex: 1;
        border: 0;
        outline: 0;
        padding: 14px 10px;
        font-size: 16px;
        background: transparent;
    }

    .glossary-search-button {
        border: 0;
        background: #535da1;
        color: #fff;
        padding: 13px 27px;
        border-radius: 9px;
        font-weight: 600;
        cursor: pointer;
        transition: .2s;
    }

    .glossary-search-button:hover {
        opacity: .9;
    }


    /* FILTER */

    .glossary-filter-row {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        gap: 8px;
        margin-top: 20px;
    }

    .glossary-filter-label {
        font-weight: 600;
        color: #555;
        margin-right: 5px;
    }

    .glossary-filter {
        border: 1px solid #e3e5ea;
        background: #fff;
        color: #555;
        border-radius: 30px;
        padding: 8px 15px;
        cursor: pointer;
        font-size: 14px;
        transition: .2s;
    }

    .glossary-filter:hover,
    .glossary-filter.active {
        background: #535da1;
        color: #fff;
        border-color: #535da1;
    }


    /* MAIN */

    .glossary-section {
        padding: 50px 0 100px;
        background: #f8f9fc;
    }

    .glossary-section-heading {
        display: flex;
        justify-content: space-between;
        align-items: end;
        margin-bottom: 22px;
    }

    .glossary-section-label {
        display: block;
        color: #535da1;
        font-size: 12px;
        font-weight: 700;
        letter-spacing: 2px;
        margin-bottom: 7px;
    }

    .glossary-section-heading h2 {
        margin: 0;
        font-size: 30px;
    }

    .glossary-total {
        color: #777;
        font-size: 14px;
    }


    /* ALPHABET */

    .glossary-alphabet {
        display: flex;
        flex-wrap: wrap;
        gap: 5px;
        margin-bottom: 25px;
        padding: 14px;
        background: #fff;
        border: 1px solid #eee;
        border-radius: 12px;
    }

    .glossary-alphabet button {
        width: 34px;
        height: 34px;
        border: 0;
        border-radius: 7px;
        background: #f2f3f7;
        color: #555;
        cursor: pointer;
        font-size: 13px;
        transition: .2s;
    }

    .glossary-alphabet button:first-child {
        width: auto;
        padding: 0 12px;
    }

    .glossary-alphabet button:hover,
    .glossary-alphabet button.active {
        background: #535da1;
        color: #fff;
    }


    /* ITEM */

    .glossary-item {
        display: flex;
        gap: 20px;
        background: #fff;
        border: 1px solid #eceef2;
        border-radius: 14px;
        padding: 24px;
        margin-bottom: 15px;
        transition: .25s;
    }

    .glossary-item:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 30px rgba(0,0,0,.07);
    }

    .glossary-letter {
        min-width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #535da1;
        color: #fff;
        font-size: 19px;
        font-weight: 700;
    }

    .glossary-item-content {
        flex: 1;
    }

    .glossary-item-meta {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        margin-bottom: 7px;
        color: #888;
        font-size: 12px;
    }

    .glossary-category {
        color: #535da1;
        font-weight: 600;
    }

    .glossary-item h3 {
        margin: 0;
        font-size: 24px;
    }

    .glossary-item h3 a {
        color: #222;
        text-decoration: none;
    }

    .glossary-item h3 a:hover {
        color: #535da1;
    }

    .glossary-pronunciation {
        color: #888;
        font-size: 14px;
        font-style: italic;
        margin: 4px 0 10px;
    }

    .glossary-item p {
        color: #666;
        line-height: 1.7;
        margin-bottom: 12px;
    }

    .glossary-read-more {
        color: #535da1;
        font-size: 14px;
        font-weight: 600;
        text-decoration: none;
    }

    .glossary-read-more i {
        margin-left: 5px;
    }


    /* SIDEBAR */

    .glossary-sidebar {
        position: sticky;
        top: 100px;
    }

    .glossary-sidebar-card {
        background: #fff;
        border: 1px solid #eceef2;
        border-radius: 15px;
        padding: 25px;
        margin-bottom: 18px;
    }

    .glossary-sidebar-card h3 {
        font-size: 19px;
        margin-bottom: 15px;
    }

    .glossary-sidebar-card p {
        color: #666;
        line-height: 1.7;
        font-size: 14px;
        margin-bottom: 0;
    }

    .glossary-sidebar-icon {
        width: 42px;
        height: 42px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: rgba(83,93,161,.1);
        color: #535da1;
        font-size: 20px;
        margin-bottom: 15px;
    }


    /* CATEGORY SIDEBAR */

    .glossary-category-list {
        padding: 0;
        margin: 0;
        list-style: none;
    }

    .glossary-category-list li {
        border-bottom: 1px solid #eee;
    }

    .glossary-category-list li:last-child {
        border-bottom: 0;
    }

    .glossary-category-list a {
        display: flex;
        justify-content: space-between;
        padding: 11px 0;
        color: #555;
        text-decoration: none;
        font-size: 14px;
    }

    .glossary-category-list a:hover {
        color: #535da1;
    }

    .glossary-category-list a span:last-child {
        color: #999;
    }


    /* FEATURED */

    .glossary-featured {
        background: #535da1;
        color: #fff;
        border: 0;
    }

    .glossary-featured-label {
        font-size: 11px;
        font-weight: 700;
        letter-spacing: 1.5px;
        opacity: .7;
    }

    .glossary-featured h3 {
        color: #fff;
        font-size: 26px;
        margin-top: 10px;
        margin-bottom: 10px;
    }

    .glossary-featured p {
        color: rgba(255,255,255,.8);
        margin-bottom: 18px;
    }

    .glossary-featured a {
        color: #fff;
        font-weight: 600;
        text-decoration: none;
    }


    /* NO RESULT */

    .glossary-no-result {
        text-align: center;
        padding: 60px 20px;
        background: #fff;
        border-radius: 14px;
        border: 1px solid #eee;
    }

    .glossary-no-result i {
        font-size: 35px;
        color: #aaa;
    }

    .glossary-no-result h3 {
        margin: 15px 0 7px;
    }

    .glossary-no-result p {
        color: #888;
    }


    /* RESPONSIVE */

    @media (max-width: 991px) {

        .glossary-hero {
            padding: 120px 0 70px;
        }

        .glossary-hero-title {
            font-size: 40px;
        }

        .glossary-hero-card {
            margin-top: 35px;
        }

        .glossary-sidebar {
            position: static;
            margin-top: 35px;
        }

    }


    @media (max-width: 575px) {

        .glossary-hero {
            padding: 110px 0 60px;
        }

        .glossary-hero-title {
            font-size: 32px;
        }

        .glossary-hero-subtitle {
            font-size: 18px;
        }

        .glossary-search-box {
            padding-left: 12px;
        }

        .glossary-search-button {
            padding: 11px 16px;
        }

        .glossary-filter-row {
            align-items: flex-start;
        }

        .glossary-item {
            padding: 18px;
        }

        .glossary-letter {
            min-width: 40px;
            height: 40px;
            font-size: 16px;
        }

        .glossary-item h3 {
            font-size: 21px;
        }

        .glossary-section-heading {
            align-items: flex-start;
            flex-direction: column;
            gap: 8px;
        }

    }

</style>


<!-- =========================================
     GLOSARIUM JAVASCRIPT
========================================== -->
<script>

    document.addEventListener('DOMContentLoaded', function () {

        const searchInput =
            document.getElementById('glossarySearch');

        const searchButton =
            document.getElementById('glossarySearchButton');

        const items =
            document.querySelectorAll('.glossary-item');

        const filters =
            document.querySelectorAll('.glossary-filter');

        const noResult =
            document.getElementById('glossaryNoResult');


        function filterGlossary() {

            const keyword =
                searchInput.value.toLowerCase().trim();

            const activeFilter =
                document.querySelector(
                    '.glossary-filter.active'
                );

            const category =
                activeFilter
                    ? activeFilter.dataset.category
                    : 'all';

            let visibleCount = 0;


            items.forEach(function (item) {

                const term =
                    item.dataset.term.toLowerCase();

                const itemCategory =
                    item.dataset.category;

                const text =
                    item.innerText.toLowerCase();


                const matchSearch =
                    keyword === '' ||
                    term.includes(keyword) ||
                    text.includes(keyword);

                const matchCategory =
                    category === 'all' ||
                    itemCategory === category;


                if (matchSearch && matchCategory) {

                    item.style.display = 'flex';

                    visibleCount++;

                } else {

                    item.style.display = 'none';

                }

            });


            if (visibleCount === 0) {

                noResult.style.display = 'block';

            } else {

                noResult.style.display = 'none';

            }

        }


        searchInput.addEventListener(
            'input',
            filterGlossary
        );


        searchButton.addEventListener(
            'click',
            filterGlossary
        );


        filters.forEach(function (filter) {

            filter.addEventListener(
                'click',
                function () {

                    filters.forEach(function (item) {
                        item.classList.remove('active');
                    });

                    this.classList.add('active');

                    filterGlossary();

                }
            );

        });

    });

</script>


<?php

include 'partials/footer.php';

?>
```

</body>

</html>
