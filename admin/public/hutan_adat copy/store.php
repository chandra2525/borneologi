<?php
require "../../app/core/session.php";
require "../../app/core/csrf.php";

require "../../app/config/database.php";
require "../../app/models/HutanAdat.php";

require '../../../vendor/autoload.php';

use Shapefile\ShapefileReader;

secureSessionStart();
verifyCsrfToken();

$hutanAdat = new HutanAdat($pdo);
$geomWKT = null;

// function cleanZM($wkt) {
//     // hapus ZM
//     $wkt = str_replace(['ZM(', 'Z(', 'M('], '(', $wkt);

//     // hapus nilai Z dan M → ambil hanya lon lat
//     $wkt = preg_replace_callback('/(-?\d+\.\d+)\s(-?\d+\.\d+)\s-?\d+(\.\d+)?\s-?\d+(\.\d+)?/', function ($matches) {
//         return $matches[1] . ' ' . $matches[2];
//     }, $wkt);

//     return $wkt;
// }

if (!empty($_FILES['shp_file']['tmp_name'])) {

    $zipPath = $_FILES['shp_file']['tmp_name'];
    $extractPath = "../../uploads/shp_" . time();

    mkdir($extractPath, 0777, true);

    $zip = new ZipArchive;
    if ($zip->open($zipPath) === TRUE) {
        $zip->extractTo($extractPath);
        $zip->close();

        // cari file .shp
        $files = scandir($extractPath);
        $shpFile = null;

        foreach ($files as $file) {
            if (pathinfo($file, PATHINFO_EXTENSION) === 'shp') {
                $shpFile = $extractPath . "/" . $file;
                break;
            }
        }

        // var_dump(scandir($extractPath));
        // die;

        if ($shpFile) {
            $reader = new ShapefileReader($shpFile);

            $polygons = [];

            while ($record = $reader->fetchRecord()) {

                if ($record->isDeleted())
                    continue;

                $wkt = $record->getWKT();

                // jika polygon → ubah ke multipolygon
                if (stripos($wkt, 'POLYGON') === 0) {
                    $wkt = preg_replace('/^POLYGON/i', 'MULTIPOLYGON', $wkt);
                    $wkt = str_replace('(', '((', $wkt);
                    $wkt = str_replace(')', '))', $wkt);
                }

                $wkt = preg_replace('/^MULTIPOLYGON\s*/i', '', $wkt);

                $polygons[] = trim($wkt);
            }

            if (!empty($polygons)) {
                $geomWKT = "MULTIPOLYGON(" . implode(',', $polygons) . ")";
            }
        }
        // var_dump($shpFile);
        // die;
    }
}

function normalizeWKT($wkt) {
    // hilangkan kurung berlebih
    $wkt = preg_replace('/MULTIPOLYGON\s*\(\(\(\(\(/', 'MULTIPOLYGON(((', $wkt);
    $wkt = preg_replace('/\)\)\)\)\)/', ')))', $wkt);

    return $wkt;
}

$geomWKT = normalizeWKT($geomWKT);

// $geomWKT = cleanZM($geomWKT);

// var_dump($geomWKT);
// die;

$data = [
    "kode_hutan_adat" => $_POST["kode_hutan_adat"],
    "nama_hutan_adat" => $_POST["nama_hutan_adat"],
    "id_masyarakat_hukum_adat" => $_POST["id_masyarakat_hukum_adat"],
    "id_desa" => $_POST["id_desa"],
    "nomor_sk" => $_POST["nomor_sk"],
    "tanggal_sk" => $_POST["tanggal_sk"],
    "id_status_kawasan" => $_POST["id_status_kawasan"],
    "luas_ha" => $_POST["luas_ha"],
    "geom_area" => $geomWKT,
    "keterangan" => $_POST["keterangan"],
    "is_active" => $_POST["is_active"],
    "created_by" => $_SESSION["user_id"]
];

$hutanAdat->create($data);

header("Location: index.php?success=created");