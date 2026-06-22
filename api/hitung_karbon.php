<?php

$client_id = "fbf31463-0717-4349-b859-dbd30c625b9d";
$client_secret = "Kr8eG8PX5mYxfRikMFNbTyn2T7isez6j";

/*
|--------------------------------------------------------------------------
| Ambil Token
|--------------------------------------------------------------------------
*/

$ch = curl_init();

curl_setopt_array($ch, [
    CURLOPT_URL => 'https://services.sentinel-hub.com/auth/realms/main/protocol/openid-connect/token',
    CURLOPT_POST => true,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POSTFIELDS => http_build_query([
        'grant_type' => 'client_credentials',
        'client_id' => $client_id,
        'client_secret' => $client_secret
    ])
]);

$response = curl_exec($ch);
curl_close($ch);

$token = json_decode($response, true);

$access_token = $token['access_token'];

/*
|--------------------------------------------------------------------------
| Bounding Box Kota Palangka Raya
|--------------------------------------------------------------------------
*/

$bbox = [
    113.70,
    -2.50,
    114.20,
    -2.00
];

/*
|--------------------------------------------------------------------------
| Request NDVI
|--------------------------------------------------------------------------
*/

$payload = [
    "input" => [
        "bounds" => [
            "bbox" => $bbox
        ],
        "data" => [
            [
                "type" => "sentinel-2-l2a",
                "dataFilter" => [
                    "maxCloudCoverage" => 20,
                    "timeRange" => [
                        "from" => date('Y-m-d', strtotime('-30 days')) . 'T00:00:00Z',
                        "to" => date('Y-m-d') . 'T23:59:59Z'
                    ]
                ]
            ]
        ]
    ],
    "output" => [
        "width" => 256,
        "height" => 256,
        "responses" => [
            [
                "identifier" => "default",
                "format" => [
                    "type" => "image/png"
                ]
            ]
        ]
    ],
    "evalscript" => '
    
    //VERSION=3

    function setup() {
        return {
            input:["B04","B08"],
            output:{bands:3}
        };
    }

    function evaluatePixel(sample)
    {
        let ndvi =
            (sample.B08 - sample.B04) /
            (sample.B08 + sample.B04);

        if(ndvi < 0.2)
            return [255,255,0];

        if(ndvi < 0.5)
            return [255,165,0];

        if(ndvi < 0.7)
            return [144,238,144];

        return [0,100,0];
    }
    '
];

$ch = curl_init();

curl_setopt_array($ch, [
    CURLOPT_URL => 'https://services.sentinel-hub.com/api/v1/process',
    CURLOPT_POST => true,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_HTTPHEADER => [
        'Authorization: Bearer ' . $access_token,
        'Content-Type: application/json'
    ],
    CURLOPT_POSTFIELDS => json_encode($payload)
]);

$result = curl_exec($ch);

curl_close($ch);

// echo $result;
file_put_contents(
    'cache/ndvi_palangkaraya.png',
    $result
);