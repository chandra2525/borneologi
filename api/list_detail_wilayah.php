<?php

require_once __DIR__ . '/api_helper.php';

try {
    $tipe = $_GET['tipe'] ?? null;
    $id = getPositiveId($_GET['id'] ?? null);
    $data = $_GET['data'] ?? null;

    if ($id === null) {
        apiError(400, 'ID wilayah tidak valid');
    }

    if (!in_array($tipe, ['kecamatan', 'desa'], true)) {
        apiError(400, 'Tipe wilayah tidak valid');
    }

    $allowedData = [
        'kecamatan' => [
            'desa',
            'hutan_adat',
            'kaleka',
            'bank_benih',
            'cbo'
        ],
        'desa' => [
            'hutan_adat',
            'kaleka',
            'bank_benih',
            'cbo'
        ]
    ];

    if (
        !is_string($data) ||
        !in_array($data, $allowedData[$tipe], true)
    ) {
        apiError(400, 'Jenis data tidak valid');
    }

    // Validasi wilayah induk.
    if ($tipe === 'kecamatan') {
        $wilayah = $controller->getKecamatanById($id);
    } else {
        $wilayah = $controller->getDesaById($id);
    }

    if (!$wilayah) {
        apiError(404, 'Data wilayah tidak ditemukan');
    }

    $hasil = $controller->getDetailWilayah(
        $tipe,
        $id,
        $data
    );

    echo json_encode([
        'status' => true,
        'message' => 'Data detail wilayah berhasil diambil',
        'tipe_wilayah' => $tipe,
        'id_wilayah' => $id,
        'jenis_data' => $data,
        'total' => count($hasil),
        'data' => $hasil
    ]);

} catch (InvalidArgumentException $e) {
    apiError(400, 'Parameter permintaan tidak valid');

} catch (Throwable $e) {
    error_log($e->getMessage());

    apiError(500, 'Terjadi kesalahan pada server');
}