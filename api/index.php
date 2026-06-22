<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">

<title>Sentinel Palangka Raya</title>

<link rel="stylesheet"
href="https://unpkg.com/leaflet/dist/leaflet.css"/>

<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

<style>
html,body{
    margin:0;
    padding:0;
}

#map{
    height:100vh;
}
</style>
</head>
<body>

<button onclick="updateCitra()">
    Update Citra
</button>
<div id="map"></div>

<script>

function updateCitra()
{
    fetch('generate_sentinel.php')
    .then(() => {
        location.reload();
    });
}

var map = L.map('map').setView(
    [-2.2096, 113.9213],
    10
);

L.tileLayer(
    'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',
    {
        attribution:'© OpenStreetMap'
    }
).addTo(map);

/*
|--------------------------------------------------------------------------
| Overlay Citra Sentinel
|--------------------------------------------------------------------------
*/

var ndviBounds = [
    [-2.50, 113.70],
    [-2.00, 114.20]
];

// var ndviLayer = L.imageOverlay(
//     'cache/palangkaraya.png',
//     ndviBounds,
//     {
//         opacity: 0.8
//     }
// );

// ndviLayer.addTo(map);


var rgbLayer = L.imageOverlay(
    'cache/palangkaraya.png',
    ndviBounds,
    {
        opacity: 0.8
    }
);

var ndviLayer = L.imageOverlay(
    'cache/ndvi_palangkaraya.png?v=' + Date.now(),
    ndviBounds,
    {
        opacity: 0.8
    }
);

var baseMaps = {};

var overlayMaps = {
    "Sentinel RGB": rgbLayer,
    "NDVI": ndviLayer
};

L.control.layers(
    baseMaps,
    overlayMaps
).addTo(map);


</script>

</body>
</html>