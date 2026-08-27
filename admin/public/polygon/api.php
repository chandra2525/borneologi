<?php

require_once '../../app/config/database.php';
require_once '../../app/controllers/PolygonController.php';
require_once '../../app/core/session.php';
require_once '../../app/core/auth.php';

secureSessionStart();
checkAuth("non_dashboard");

header('Content-Type: application/json; charset=utf-8');

try {

    $controller = new PolygonController($pdo);


    /*
    |--------------------------------------------------------------------------
    | DataTables parameters
    |--------------------------------------------------------------------------
    */

    $draw = isset($_GET['draw'])
        ? (int) $_GET['draw']
        : 0;

    $start = isset($_GET['start'])
        ? (int) $_GET['start']
        : 0;

    $length = isset($_GET['length'])
        ? (int) $_GET['length']
        : 10;

    $search = $_GET['search']['value'] ?? '';

    $orderColumn = isset($_GET['order'][0]['column'])
        ? (int) $_GET['order'][0]['column']
        : 0;

    $orderDir = $_GET['order'][0]['dir'] ?? 'asc';


    /*
    |--------------------------------------------------------------------------
    | Get Data
    |--------------------------------------------------------------------------
    */

    $result = $controller->getDataTables(
        $start,
        $length,
        $search,
        $orderColumn,
        $orderDir
    );


    /*
    |--------------------------------------------------------------------------
    | Response
    |--------------------------------------------------------------------------
    */

    echo json_encode([
        'draw' => $draw,
        'recordsTotal' => $result['recordsTotal'],
        'recordsFiltered' => $result['recordsFiltered'],
        'data' => $result['data']
    ], JSON_UNESCAPED_UNICODE);


} catch (Throwable $e) {

    http_response_code(500);

    echo json_encode([
        'draw' => isset($_GET['draw'])
            ? (int) $_GET['draw']
            : 0,

        'recordsTotal' => 0,

        'recordsFiltered' => 0,

        'data' => [],

        'error' => $e->getMessage()
    ], JSON_UNESCAPED_UNICODE);
}