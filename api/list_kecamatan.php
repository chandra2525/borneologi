<?php

require_once __DIR__ . '/api_helper.php';

try {
    $data = $controller->getAllKecamatan();

    echo json_encode([
        'status' => true,
        'message' => 'Data kecamatan berhasil diambil',
        'total' => count($data),
        'data' => $data
    ]);

} catch (Throwable $e) {
    error_log($e->getMessage());

    apiError(500, $e);
}