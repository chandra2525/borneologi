<?php

$json = file_get_contents('lawangkanjiJSON.json');
$data = json_decode($json, true);

foreach ($data['features'] as &$feature) {
    $coords = &$feature['geometry']['coordinates'];

    foreach ($coords as &$polygon) {
        foreach ($polygon as &$point) {
            // swap longitude dan latitude
            $point = [$point[1], $point[0]];
        }
    }
}

file_put_contents('lawangkanjiJSON_fixed.json', json_encode($data, JSON_PRETTY_PRINT));

echo "Selesai dibalik!";