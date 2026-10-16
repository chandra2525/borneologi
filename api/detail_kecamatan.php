<?php

require_once __DIR__ . '/api_helper.php';

try {
    $id = getPositiveId($_GET['id'] ?? null);

    if ($id === null) {
        apiError(400, 'ID kecamatan tidak valid');
    }

    $data = $controller->getKecamatanById($id);

    if (!$data) {
        apiError(404, 'Data kecamatan tidak ditemukan');
    }

    echo json_encode([
        'status' => true,
        'message' => 'Detail kecamatan berhasil diambil',
        'data' => $data
    ]);

} catch (Throwable $e) {
    error_log($e->getMessage());

    apiError(500, 'Terjadi kesalahan pada server');
}