<?php

require_once '../../app/config/database.php';
require_once '../../app/controllers/BankBenihController.php';
require_once '../../app/core/session.php';
require_once '../../app/core/csrf.php';
require_once '../../app/core/auth.php';
require_once '../../app/helpers/escape.php';
require_once '../../app/core/permission.php';

secureSessionStart();
checkAuth("non_dashboard");

Permission::authorize(
    $pdo,
    'Bank Benih',
    'view'
);


$controller =
    new BankBenihController($pdo);


$id =
    isset($_GET['id'])
        ? (int)$_GET['id']
        : 0;


if ($id <= 0) {

    header(
        "Location: index.php?error=invalid_id"
    );

    exit;
}


$bankBenih =
    $controller->find($id);


if (!$bankBenih) {

    header(
        "Location: index.php?error=not_found"
    );

    exit;
}

/*
|--------------------------------------------------------------------------
| STATUS MASA BERLAKU
|--------------------------------------------------------------------------
*/

$statusMasaBerlaku =
    'Tidak ditentukan';

$statusClass =
    'secondary';

$hariTersisa =
    null;


if (
    !empty(
        $bankBenih['masa_berlaku_sampai']
    )
) {

    try {

        $today =
            new DateTime(
                date('Y-m-d')
            );

        $masaBerlaku =
            new DateTime(
                $bankBenih[
                    'masa_berlaku_sampai'
                ]
            );


        $hariTersisa =
            (int)$today
            ->diff($masaBerlaku)
            ->format('%r%a');


        if ($hariTersisa < 0) {

            $statusMasaBerlaku =
                'Kedaluwarsa';

            $statusClass =
                'danger';

        } elseif (
            $hariTersisa <= 30
        ) {

            $statusMasaBerlaku =
                'Segera Kedaluwarsa';

            $statusClass =
                'warning';

        } else {

            $statusMasaBerlaku =
                'Masih Berlaku';

            $statusClass =
                'success';

        }

    } catch (Throwable $e) {

        $statusMasaBerlaku =
            'Tanggal tidak valid';

        $statusClass =
            'danger';

    }

}


/*
|--------------------------------------------------------------------------
| STOK
|--------------------------------------------------------------------------
*/

$stok =
    (float)(
        $bankBenih['jumlah_stok']
        ?? 0
    );


if ($stok <= 0) {

    $stokClass =
        'danger';

    $stokStatus =
        'Stok Habis';

} else {

    $stokClass =
        'success';

    $stokStatus =
        'Tersedia';

}


/*
|--------------------------------------------------------------------------
| GOOGLE MAP
|--------------------------------------------------------------------------
*/

$mapUrl = null;

if (
    $bankBenih['titik_koleksi_lat'] !== null &&
    $bankBenih['titik_koleksi_lat'] !== '' &&
    $bankBenih['titik_koleksi_lng'] !== null &&
    $bankBenih['titik_koleksi_lng'] !== ''
) {

    $lat =
        (float)$bankBenih[
            'titik_koleksi_lat'
        ];

    $lng =
        (float)$bankBenih[
            'titik_koleksi_lng'
        ];


    $mapUrl =
        'https://www.google.com/maps?q='
        . rawurlencode(
            $lat . ',' . $lng
        );
}

?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="utf-8">

    <meta name="viewport"
        content="width=device-width, initial-scale=1">

    <title>
        Detail <?= htmlspecialchars(
            $bankBenih['nama_lokal']
        ) ?>
        - Bank Benih
    </title>


    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700">

    <link rel="stylesheet"
        href="../assets/adminlte/plugins/fontawesome-free/css/all.min.css">

    <link rel="stylesheet"
        href="../assets/adminlte/dist/css/adminlte.min.css">


    <style>

        .hero-card {
            border-radius: 8px;
        }

        .seed-photo {
            width: 100%;
            max-height: 350px;
            object-fit: cover;
            border-radius: 8px;
            border: 1px solid #ddd;
        }

        .photo-placeholder {
            width: 100%;
            height: 280px;
            border-radius: 8px;
            background: #f4f6f9;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #6c757d;
            font-size: 60px;
        }

        .info-label {
            font-size: 12px;
            color: #6c757d;
            margin-bottom: 3px;
        }

        .info-value {
            font-weight: 600;
            font-size: 15px;
            margin-bottom: 18px;
        }

        .stat-box {
            border-radius: 8px;
            padding: 18px;
            background: #f8f9fa;
            height: 100%;
        }

        .stat-label {
            font-size: 12px;
            color: #6c757d;
        }

        .stat-value {
            font-size: 24px;
            font-weight: 700;
        }

        .section-header {
            font-weight: 600;
        }

        .coordinate-box {
            font-family: monospace;
            background: #f8f9fa;
            padding: 10px;
            border-radius: 5px;
        }

    </style>

</head>


<body class="hold-transition sidebar-mini">


<div class="wrapper">


<?php include "../feature/navbar.php"; ?>


<?php

$menu = "bank_benih";

include "../feature/sidebar.php";

?>


<div class="content-wrapper">


<!-- ===================================================== -->
<!-- HEADER -->
<!-- ===================================================== -->

<section class="content-header">

<div class="container-fluid">

<div class="row mb-2">

<div class="col-sm-6">

<h1>

<i class="fas fa-seedling"></i>

Detail Data Benih

</h1>

</div>


<div class="col-sm-6">

<ol class="breadcrumb float-sm-right">

<li class="breadcrumb-item">

<a href="index.php">
Data Benih
</a>

</li>

<li class="breadcrumb-item active">
Detail
</li>

</ol>

</div>

</div>

</div>

</section>



<section class="content">

<div class="container-fluid">


<!-- ===================================================== -->
<!-- HERO -->
<!-- ===================================================== -->

<div class="card card-primary hero-card">


<div class="card-body">


<div class="row align-items-center">


<!-- FOTO -->

<div class="col-lg-3 col-md-4 mb-3 mb-md-0">


<?php if (
    !empty(
        $bankBenih['foto_benih']
    )
): ?>

<img
    src="../../uploads/bank_benih/<?= htmlspecialchars(
        $bankBenih['foto_benih']
    ) ?>"
    alt="Foto Benih"
    class="seed-photo">

<?php else: ?>

<div class="photo-placeholder">

<i class="fas fa-seedling"></i>

</div>

<?php endif; ?>


</div>


<!-- IDENTITAS -->

<div class="col-lg-6 col-md-5">


<div class="mb-2">

<span class="badge badge-info">

<?= htmlspecialchars(
    $bankBenih['nomor_aksesi']
    ?: 'Tanpa Nomor Aksesi'
) ?>

</span>

</div>


<h2 class="mb-1">

<?= htmlspecialchars(
    $bankBenih['nama_lokal']
) ?>

</h2>


<?php if (
    !empty(
        $bankBenih['nama_ilmiah']
    )
): ?>

<p class="text-muted mb-2">

<i>

<?= htmlspecialchars(
    $bankBenih['nama_ilmiah']
) ?>

</i>

</p>

<?php endif; ?>


<p class="mb-1">

<strong>
Famili:
</strong>

<?= htmlspecialchars(
    $bankBenih['famili_tanaman']
) ?>

</p>


<p class="mb-1">

<strong>
Provenance:
</strong>

<?= htmlspecialchars(
    $bankBenih['provenance']
) ?>

</p>


<p class="mb-0">

<strong>
Tanah:
</strong>

<?= htmlspecialchars(
    $bankBenih['nama_lahan']
) ?>

</p>


</div>


<!-- ACTION -->

<div class="col-lg-3 col-md-3 text-md-right mt-3 mt-md-0">


<?php

$canUpdate =
    Permission::can(
        $pdo,
        'Bank Benih',
        'update'
    );

$canDelete =
    Permission::can(
        $pdo,
        'Bank Benih',
        'delete'
    );

?>


<?php if ($canUpdate): ?>

<a
    href="edit.php?id=<?= (int)$bankBenih['id'] ?>"
    class="btn btn-info btn-block mb-2">

<i class="fas fa-edit"></i>

Edit Data

</a>

<?php endif; ?>


<a
    href="index.php"
    class="btn btn-secondary btn-block">

<i class="fas fa-arrow-left"></i>

Kembali

</a>


</div>


</div>


</div>

</div>



<!-- ===================================================== -->
<!-- STATISTIC -->
<!-- ===================================================== -->

<div class="row">


<!-- STOK -->

<div class="col-lg-3 col-md-6 mb-3">

<div class="stat-box">

<div class="stat-label">
STOK
</div>

<div class="stat-value text-<?= $stokClass ?>">

<?= number_format(
    $stok,
    2
) ?>

</div>

<div>

<?= htmlspecialchars(
    $bankBenih['satuan_stok']
) ?>

<span
    class="badge badge-<?= $stokClass ?> ml-1">

<?= $stokStatus ?>

</span>

</div>

</div>

</div>


<!-- VIABILITAS -->

<div class="col-lg-3 col-md-6 mb-3">

<div class="stat-box">

<div class="stat-label">
VIABILITAS
</div>

<div class="stat-value">

<?= $bankBenih[
    'viabilitas_persen'
] !== null &&
$bankBenih[
    'viabilitas_persen'
] !== ''
    ? number_format(
        (float)$bankBenih[
            'viabilitas_persen'
        ],
        2
    ) . '%'
    : '-'
?>

</div>

</div>

</div>


<!-- KADAR AIR -->

<div class="col-lg-3 col-md-6 mb-3">

<div class="stat-box">

<div class="stat-label">
KADAR AIR
</div>

<div class="stat-value">

<?= $bankBenih[
    'kadar_air_persen'
] !== null &&
$bankBenih[
    'kadar_air_persen'
] !== ''
    ? number_format(
        (float)$bankBenih[
            'kadar_air_persen'
        ],
        2
    ) . '%'
    : '-'
?>

</div>

</div>

</div>


<!-- STATUS -->

<div class="col-lg-3 col-md-6 mb-3">

<div class="stat-box">

<div class="stat-label">
STATUS DATA
</div>

<div class="stat-value">

<?php if (
    (int)$bankBenih['is_active'] === 1
): ?>

<span class="badge badge-success">
Aktif
</span>

<?php else: ?>

<span class="badge badge-secondary">
Nonaktif
</span>

<?php endif; ?>

</div>

</div>

</div>


</div>



<!-- ===================================================== -->
<!-- INFORMASI DETAIL -->
<!-- ===================================================== -->

<div class="card">


<div class="card-header">

<h3 class="card-title section-header">

<i class="fas fa-info-circle"></i>

Informasi Lengkap

</h3>

</div>


<div class="card-body">


<div class="row">


<!-- KOLOM 1 -->

<div class="col-md-4">


<div class="info-label">
Nomor Aksesi
</div>

<div class="info-value">
<?= htmlspecialchars(
    $bankBenih['nomor_aksesi']
) ?>
</div>


<div class="info-label">
Nama Lokal
</div>

<div class="info-value">
<?= htmlspecialchars(
    $bankBenih['nama_lokal']
) ?>
</div>


<div class="info-label">
Nama Ilmiah
</div>

<div class="info-value">

<?php if (
    !empty(
        $bankBenih['nama_ilmiah']
    )
): ?>

<i>
<?= htmlspecialchars(
    $bankBenih['nama_ilmiah']
) ?>
</i>

<?php else: ?>

-

<?php endif; ?>

</div>


<div class="info-label">
Famili Tanaman
</div>

<div class="info-value">
<?= htmlspecialchars(
    $bankBenih['famili_tanaman']
) ?>
</div>


</div>


<!-- KOLOM 2 -->

<div class="col-md-4">


<div class="info-label">
Provenance
</div>

<div class="info-value">
<?= htmlspecialchars(
    $bankBenih['provenance']
) ?>
</div>


<div class="info-label">
Negara
</div>

<div class="info-value">
<?= htmlspecialchars(
    $bankBenih['nama_negara']
) ?>
</div>


<div class="info-label">
Tanah / Lahan
</div>

<div class="info-value">
<?= htmlspecialchars(
    $bankBenih['nama_lahan']
) ?>
</div>


<div class="info-label">
Ketinggian
</div>

<div class="info-value">

<?= $bankBenih[
    'ketinggian_mdpl'
] !== null &&
$bankBenih[
    'ketinggian_mdpl'
] !== ''
    ? number_format(
        (float)$bankBenih[
            'ketinggian_mdpl'
        ],
        2
    ) . ' MDPL'
    : '-'
?>

</div>


</div>


<!-- KOLOM 3 -->

<div class="col-md-4">


<div class="info-label">
Tipe Penyimpanan
</div>

<div class="info-value">
<?= htmlspecialchars(
    $bankBenih[
        'nama_tipe_penyimpanan_benih'
    ]
) ?>
</div>


<div class="info-label">
Lokasi Penyimpanan
</div>

<div class="info-value">
<?= htmlspecialchars(
    $bankBenih[
        'lokasi_penyimpanan'
    ]
) ?>
</div>


<div class="info-label">
Tanggal Masuk
</div>

<div class="info-value">

<?= !empty(
    $bankBenih['tanggal_masuk']
)
    ? date(
        'd/m/Y',
        strtotime(
            $bankBenih[
                'tanggal_masuk'
            ]
        )
    )
    : '-'
?>

</div>


<div class="info-label">
Masa Berlaku
</div>

<div class="info-value">


<?php if (
    !empty(
        $bankBenih[
            'masa_berlaku_sampai'
        ]
    )
): ?>

<?= date(
    'd/m/Y',
    strtotime(
        $bankBenih[
            'masa_berlaku_sampai'
        ]
    )
) ?>


<br>

<span
    class="badge badge-<?= $statusClass ?>">

<?= $statusMasaBerlaku ?>

<?php if (
    $hariTersisa !== null &&
    $hariTersisa >= 0
): ?>

&nbsp;

(<?= $hariTersisa ?> hari)

<?php endif; ?>

</span>

<?php else: ?>

-

<?php endif; ?>


</div>


</div>


</div>


</div>

</div>



<!-- ===================================================== -->
<!-- LOKASI -->
<!-- ===================================================== -->

<div class="card">


<div class="card-header">

<h3 class="card-title section-header">

<i class="fas fa-map-marker-alt"></i>

Lokasi Koleksi

</h3>

</div>


<div class="card-body">


<div class="row">


<div class="col-md-6">

<div class="info-label">
Latitude
</div>

<div class="coordinate-box mb-3">

<?= htmlspecialchars(
    $bankBenih[
        'titik_koleksi_lat'
    ]
) ?>

</div>

</div>


<div class="col-md-6">

<div class="info-label">
Longitude
</div>

<div class="coordinate-box mb-3">

<?= htmlspecialchars(
    $bankBenih[
        'titik_koleksi_lng'
    ]
) ?>

</div>

</div>


<?php if (
    $mapUrl !== null
): ?>

<div class="col-md-12">

<a
    href="<?= htmlspecialchars($mapUrl) ?>"
    target="_blank"
    rel="noopener noreferrer"
    class="btn btn-outline-primary">

<i class="fas fa-map"></i>

Lihat Lokasi di Peta

</a>

</div>

<?php else: ?>

<div class="col-md-12">

<div class="alert alert-secondary mb-0">

<i class="fas fa-info-circle"></i>

Koordinat lokasi koleksi belum tersedia.

</div>

</div>

<?php endif; ?>


</div>


</div>

</div>



<!-- ===================================================== -->
<!-- CATATAN -->
<!-- ===================================================== -->

<div class="card">


<div class="card-header">

<h3 class="card-title section-header">

<i class="fas fa-sticky-note"></i>

Catatan

</h3>

</div>


<div class="card-body">


<?php if (
    !empty(
        trim(
            $bankBenih['catatan']
            ?? ''
        )
    )
): ?>

<div
    style="white-space: pre-line;">

<?= htmlspecialchars(
    $bankBenih['catatan']
) ?>

</div>

<?php else: ?>

<span class="text-muted">

Tidak ada catatan.

</span>

<?php endif; ?>


</div>

</div>



<!-- ===================================================== -->
<!-- AUDIT -->
<!-- ===================================================== -->

<div class="card">


<div class="card-header">

<h3 class="card-title section-header">

<i class="fas fa-history"></i>

Informasi Sistem

</h3>

</div>


<div class="card-body">


<div class="row">


<div class="col-md-4">

<div class="info-label">
ID Data
</div>

<div class="info-value">

<?= (int)$bankBenih['id'] ?>

</div>

</div>


<div class="col-md-4">

<div class="info-label">
Dibuat
</div>

<div class="info-value">

<?= htmlspecialchars(
    $bankBenih['created_at']
    ?? '-'
) ?>

</div>

</div>


<div class="col-md-4">

<div class="info-label">
Diperbarui
</div>

<div class="info-value">

<?= htmlspecialchars(
    $bankBenih['updated_at']
    ?? '-'
) ?>

</div>

</div>


</div>


</div>

</div>



<!-- ===================================================== -->
<!-- BOTTOM ACTION -->
<!-- ===================================================== -->

<div class="mb-4">


<a
    href="index.php"
    class="btn btn-secondary">

<i class="fas fa-arrow-left"></i>

Kembali ke Data Benih

</a>


<?php if ($canUpdate): ?>

<a
    href="edit.php?id=<?= (int)$bankBenih['id'] ?>"
    class="btn btn-info">

<i class="fas fa-edit"></i>

Edit Data

</a>

<?php endif; ?>


</div>


</div>

</section>


</div>


<?php include "../feature/footer.php"; ?>


</div>


<script src="../assets/adminlte/plugins/jquery/jquery.min.js"></script>

<script src="../assets/adminlte/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>

<script src="../assets/adminlte/dist/js/adminlte.min.js"></script>


</body>

</html>