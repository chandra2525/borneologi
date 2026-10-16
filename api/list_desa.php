<?php

require_once __DIR__ . '/api_helper.php';

try {
    $idKecamatan = null;

    // Jika parameter diberikan, validasi dan filter berdasarkan kecamatan.
    if (isset($_GET['id_kecamatan'])) {
        $idKecamatan = getPositiveId($_GET['id_kecamatan']);

        if ($idKecamatan === null) {
            apiError(400, 'ID kecamatan tidak valid');
        }

        // Pastikan kecamatan induk tersedia dan aktif.
        $kecamatan = $controller->getKecamatanById($idKecamatan);

        if (!$kecamatan) {
            apiError(404, 'Data kecamatan tidak ditemukan');
        }
    }

    $data = $controller->getAllDesa($idKecamatan);

    echo json_encode([
        'status' => true,
        'message' => 'Data desa berhasil diambil',
        'total' => count($data),
        'data' => $data
    ], JSON_UNESCAPED_UNICODE);

} catch (Throwable $e) {
    error_log($e->getMessage());

    apiError(500, 'Terjadi kesalahan pada server');
}