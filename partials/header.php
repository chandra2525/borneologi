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

        .legend input[type="checkbox"] {
            width: 18px;
            height: 18px;
            cursor: pointer;
        }
    </style>

    <!--

TemplateMo 578 First Portfolio

https://templatemo.com/tm-578-first-portfolio

-->
</head>