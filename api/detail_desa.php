<?php

require_once __DIR__ . '/api_helper.php';

try {
    $id = getPositiveId($_GET['id'] ?? null);

    if ($id === null) {
        apiError(400, 'ID desa tidak valid');
    }

    $data = $controller->getDesaById($id);

    if (!$data) {
        apiError(404, 'Data desa tidak ditemukan');
    }

    echo json_encode([
        'status' => true,
        'message' => 'Detail desa berhasil diambil',
        'data' => $data
    ]);

} catch (Throwable $e) {
    error_log($e->getMessage());

    apiError(500, 'Terjadi kesalahan pada server');
}