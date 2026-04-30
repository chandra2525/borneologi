<?php
require "../../app/core/session.php";
require "../../app/core/csrf.php";

require "../../app/config/database.php";
require "../../app/models/Polygon.php";

require '../../../vendor/autoload.php';

use Shapefile\ShapefileReader;

secureSessionStart();
verifyCsrfToken();

$polygon = new Polygon($pdo);
$geomWKT = null;

if (!isset($_FILES['shp_file']) || $_FILES['shp_file']['error'] !== 0) {
    die("File shapefile wajib diupload!");
}

$fileType = mime_content_type($_FILES['shp_file']['tmp_name']);

if (!in_array($fileType, ['application/zip', 'application/x-zip-compressed'])) {
    die("File harus berupa ZIP!");
}


// function cleanZM($wkt)
// {
//     // hapus ZM
//     $wkt = preg_replace('/ZM/i', '', $wkt);

//     // hapus nilai Z & M (ambil hanya lng lat)
//     $wkt = preg_replace('/(-?\d+\.\d+)\s+(-?\d+\.\d+)\s+[-\d\.]+\s+[-\d\.]+/', '$1 $2', $wkt);

//     return $wkt;
// }
function cleanZM($wkt)
{
    // 1. ubah MULTIPOLYGONZM -> MULTIPOLYGON
    $wkt = preg_replace('/MULTIPOLYGONZM/i', 'MULTIPOLYGON', $wkt);
    $wkt = preg_replace('/POLYGONZM/i', 'POLYGON', $wkt);

    // 2. hapus Z / M kalau ada spasi
    $wkt = preg_replace('/\s+Z(M)?/i', '', $wkt);

    // 3. convert scientific notation ke decimal
    $wkt = preg_replace_callback(
        '/-?\d+(\.\d+)?E[-+]?\d+/i',
        function ($matches) {
            return sprintf('%.12f', (float) $matches[0]);
        },
        $wkt
    );

    // 4. ambil hanya X Y (buang Z/M value)
    $wkt = preg_replace(
        '/(-?\d+(\.\d+)?)\s+(-?\d+(\.\d+)?)(\s+-?\d+(\.\d+)?)+/',
        '$1 $3',
        $wkt
    );

    return $wkt;
}

function splitMultiPolygon($wkt)
{
    $wkt = cleanZM($wkt);

    // ambil isi dalam MULTIPOLYGON(...)
    preg_match('/MULTIPOLYGON\s*\((.*)\)/i', $wkt, $matches);

    if (!isset($matches[1]))
        return [];

    $inner = $matches[1];

    // split polygon berdasarkan ')),(('
    $parts = preg_split('/\)\)\s*,\s*\(\(/', $inner);

    $result = [];

    foreach ($parts as $part) {
        $part = trim($part, '()');

        $result[] = "MULTIPOLYGON(((" . $part . ")))";
    }

    return $result;
}

function forceMultiPolygon($wkt)
{
    if (stripos($wkt, 'MULTIPOLYGON') !== false) {
        return $wkt;
    }

    if (stripos($wkt, 'POLYGON') !== false) {
        // ubah POLYGON jadi MULTIPOLYGON
        $wkt = preg_replace('/^POLYGON\s*\(\(/i', 'MULTIPOLYGON(((', $wkt);
        $wkt = preg_replace('/\)\)$/', ')))', $wkt);
    }

    return $wkt;
}

// function getLastKodePolygon($pdo)
// {
//     $stmt = $pdo->query("SELECT kode_polygon FROM t_polygon ORDER BY id DESC LIMIT 1");
//     $row = $stmt->fetch(PDO::FETCH_ASSOC);

//     if (!$row) return 0;

//     // ambil angka dari PLY050 → 50
//     return (int) preg_replace('/[^0-9]/', '', $row['kode_polygon']);
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

        $required = ['shp', 'shx', 'dbf'];

        $found = [];

        foreach ($files as $file) {
            $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
            if (in_array($ext, $required)) {
                $found[] = $ext;
            }
        }

        foreach ($required as $req) {
            if (!in_array($req, $found)) {
                die("ZIP tidak lengkap! Harus ada file .$req");
            }
        }

        // var_dump(scandir($extractPath));
        // die;

        if ($shpFile) {
            $reader = new ShapefileReader($shpFile);

            $no = 1;
            // $lastNumber = getLastKodePolygon($pdo);
            $lastNumber = (new Polygon($pdo))->generateKode();

            while ($record = $reader->fetchRecord()) {

                if ($record->isDeleted())
                    continue;

                $wkt = cleanZM($record->getWKT());

                // var_dump($wkt);
                // die;
                // var_dump(substr($wkt, 0, 50));
                // die;

                // split jadi polygon kecil
                $polygons = splitMultiPolygon($wkt);

                // kalau ternyata bukan multipolygon
                if (empty($polygons)) {
                    $polygons = [$wkt];
                }

                foreach ($polygons as $poly) {

                    $poly = forceMultiPolygon($poly);

                    // if (empty($poly))
                    //     continue;

                    if (
                        stripos($poly, 'POLYGON') === false &&
                        stripos($poly, 'MULTIPOLYGON') === false
                    ) {
                        continue; // skip geometry aneh
                    }

                    $kodeBaru = str_pad($lastNumber, 3, '0', STR_PAD_LEFT);

                    $data = [
                        "kode_polygon" => $kodeBaru,
                        "nama_polygon" => $_POST["nama_polygon"] . " - " . $no,
                        "geom_area" => $poly,
                        "is_active" => $_POST["is_active"],
                        "created_by" => $_SESSION["user_id"]
                    ];

                    $lastNumber++;

                    $polygon->create($data);
                    $no++;
                }
            }
        }
        // var_dump($shpFile);
        // die;
    }
}

function normalizeWKT($wkt)
{
    // hilangkan kurung berlebih
    $wkt = preg_replace('/MULTIPOLYGON\s*\(\(\(\(\(/', 'MULTIPOLYGON(((', $wkt);
    $wkt = preg_replace('/\)\)\)\)\)/', ')))', $wkt);

    return $wkt;
}

$geomWKT = normalizeWKT($geomWKT);

// $geomWKT = cleanZM($geomWKT);

// var_dump($geomWKT);
// die;

// $data = [
//     "kode_polygon" => $_POST["kode_polygon"],
//     "nama_polygon" => $_POST["nama_polygon"],
//     "geom_area" => $geomWKT,
//     "is_active" => $_POST["is_active"],
//     "created_by" => $_SESSION["user_id"]
// ];

// $polygon->create($data);

header("Location: index.php?success=created");