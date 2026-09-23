<?php

secureSessionStart();

require_once '../../../config/database.php';
require_once '../../../app/controllers/MutasiStokBenihController.php';
require_once '../../../app/helpers/csrf.php';
require_once '../../../app/helpers/permission.php';
require_once '../../../app/models/ActivityLog.php';

Permission::authorize(
    $pdo,
    'Stok Masuk',
    'create'
);

verifyCsrfToken();

$controller =
    new MutasiStokBenihController($pdo);

$userId =
    (int)($_SESSION['user_id'] ?? 0);

$username =
    $_SESSION['username'] ?? '';

/*
 * Simpan input untuk dikembalikan
 * apabila terjadi validation error.
 */
$_SESSION['old_stok_masuk'] = $_POST;

try {

    $idBankBenih =
        filter_var(
            $_POST['id_bank_benih'] ?? null,
            FILTER_VALIDATE_INT,
            [
                'options' => [
                    'min_range' => 1
                ]
            ]
        );

    if (!$idBankBenih) {
        throw new InvalidArgumentException(
            'Bank Benih wajib dipilih.'
        );
    }

    $tanggalInput =
        trim(
            $_POST['tanggal_mutasi'] ?? ''
        );

    /*
     * datetime-local menghasilkan:
     * 2026-09-18T13:30
     *
     * Database membutuhkan:
     * 2026-09-18 13:30:00
     */
    $date =
        DateTime::createFromFormat(
            'Y-m-d\TH:i',
            $tanggalInput
        );

    if (!$date) {
        throw new InvalidArgumentException(
            'Tanggal mutasi tidak valid.'
        );
    }

    $tanggalMutasi =
        $date->format('Y-m-d H:i:s');

    $data = [

        'id_bank_benih' =>
            $idBankBenih,

        'tipe_mutasi' =>
            'MASUK',

        'arah_koreksi' =>
            null,

        'jumlah' =>
            trim(
                $_POST['jumlah'] ?? ''
            ),

        'satuan' =>
            trim(
                $_POST['satuan'] ?? ''
            ),

        'sumber' =>
            'STOK_MASUK',

        'id_referensi' =>
            null,

        'alasan' =>
            trim(
                $_POST['alasan'] ?? ''
            ),

        'tanggal_mutasi' =>
            $tanggalMutasi
    ];

    /*
     * Satuan sengaja tidak dipercaya dari browser.
     *
     * Ambil satuan asli dari Bank Benih.
     */
    $bankBenih =
        $controller->getBankBenih(
            $idBankBenih
        );

    if (!$bankBenih) {
        throw new InvalidArgumentException(
            'Data Bank Benih tidak ditemukan.'
        );
    }

    $data['satuan'] =
        $bankBenih['satuan_stok'];

    $result =
        $controller->store(
            $data,
            $userId
        );

    /*
     * Activity Log
     */
    $activityLog =
        new ActivityLog($pdo);

    $newData = [

        'id_bank_benih' =>
            $idBankBenih,

        'tipe_mutasi' =>
            'MASUK',

        'jumlah' =>
            $result['stok_sesudah']
                - $result['stok_sebelum'],

        'stok_sebelum' =>
            $result['stok_sebelum'],

        'stok_sesudah' =>
            $result['stok_sesudah'],

        'satuan' =>
            $data['satuan'],

        'sumber' =>
            'STOK_MASUK',

        'alasan' =>
            $data['alasan']
    ];

    $activityLog->create([

        'user_id' =>
            $userId,

        'username' =>
            $username,

        'activity' =>
            'CREATE',

        'menu_name' =>
            'Stok Masuk',

        'target_id' =>
            $result['id_mutasi'],

        'resource' =>
            't_mutasi_stok_benih',

        'old_data' =>
            null,

        'new_data' =>
            json_encode(
                $newData,
                JSON_UNESCAPED_UNICODE
            ),

        'description' =>
            'Menambahkan stok masuk benih',

        'status' =>
            'SUCCESS',

        'ip_address' =>
            $_SERVER['REMOTE_ADDR'] ?? null,

        'user_agent' =>
            $_SERVER['HTTP_USER_AGENT'] ?? null,

        'request_id' =>
            $_SESSION['request_id'] ?? null
    ]);

    unset(
        $_SESSION['old_stok_masuk']
    );

    header(
        'Location: index.php?success=created'
    );

    exit;

} catch (Throwable $e) {

    $_SESSION['stok_masuk_errors'] = [
        $e->getMessage()
    ];

    header(
        'Location: index.php?error=validation'
    );

    exit;
}