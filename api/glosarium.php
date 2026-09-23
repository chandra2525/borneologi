<?php

header('Content-Type: application/json; charset=utf-8');

require_once '../admin/app/config/database.php';
require_once '../admin/app/models/Glosarium.php';

try {

    $model = new Glosarium($pdo);

    /*
    ==========================================
    GET DETAIL
    /api/glosarium.php?id=1
    ==========================================
    */

    if (isset($_GET['id'])) {

        $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

        if (!$id || $id <= 0) {
            http_response_code(400);

            echo json_encode([
                'success' => false,
                'message' => 'ID glosarium tidak valid.'
            ], JSON_UNESCAPED_UNICODE);

            exit;
        }

        $data = $model->findPublicById($id);

        if (!$data) {
            http_response_code(404);

            echo json_encode([
                'success' => false,
                'message' => 'Data glosarium tidak ditemukan.'
            ], JSON_UNESCAPED_UNICODE);

            exit;
        }

        echo json_encode([
            'success' => true,
            'data' => $data
        ], JSON_UNESCAPED_UNICODE);

        exit;
    }


    /*
    ==========================================
    GET SEMUA DATA
    /api/glosarium.php
    ==========================================
    */

    $data = $model->getPublicAll();

    echo json_encode([
        'success' => true,
        'total' => count($data),
        'data' => $data
    ], JSON_UNESCAPED_UNICODE);

} catch (Throwable $e) {

    http_response_code(500);

    echo json_encode([
        'success' => false,
        'message' => 'Terjadi kesalahan pada server.'
    ], JSON_UNESCAPED_UNICODE);
}