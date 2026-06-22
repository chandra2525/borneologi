<?php

$client_id = "fbf31463-0717-4349-b859-dbd30c625b9d";
$client_secret = "Kr8eG8PX5mYxfRikMFNbTyn2T7isez6j";

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

if (!isset($token['access_token'])) {
    die("Gagal mendapatkan token");
}

$access_token = $token['access_token'];


$payload = [
    "collections" => [
        "sentinel-2-l2a"
    ],

    "bbox" => [
        112.94403075473384,
        -1.6901902184285975,
        114.11956786410884,
        -0.2896773395098768
    ],

    "datetime" => "2025-05-01T00:00:00Z/2025-06-10T23:59:59Z",

    "limit" => 10
];


$ch = curl_init();

curl_setopt_array($ch, [
    CURLOPT_URL => 'https://services.sentinel-hub.com/api/v1/catalog/search',
    CURLOPT_POST => true,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_HTTPHEADER => [
        'Authorization: Bearer ' . $access_token,
        'Content-Type: application/json'
    ],
    CURLOPT_POSTFIELDS => json_encode($payload)
]);

$result = curl_exec($ch);

$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

curl_close($ch);

echo "<h3>HTTP Code:</h3>";
echo $httpCode;

echo "<h3>Response:</h3>";
echo "<pre>";
echo json_encode(json_decode($result, true), JSON_PRETTY_PRINT);
echo "</pre>";