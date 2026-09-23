<?php

secureSessionStart();

require_once '../../../config/database.php';
require_once '../../../app/controllers/MutasiStokBenihController.php';
require_once '../../../app/helpers/csrf.php';
require_once '../../../app/helpers/permission.php';
require_once '../../../app/models/ActivityLog.php';

Permission::authorize(
    $pdo,
    'Koreksi Stok',
    'create'
);

verifyCsrfToken();

$controller =
    new MutasiStokBenihController($pdo);

$userId =
    (int)($_SESSION['user_id'] ?? 0);

$username =
    $_SESSION['username'] ?? '';

$_SESSION['old_koreksi_stok'] =
    $_POST;

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

    $arah =
        strtoupper(
            trim(
                $_POST['arah_koreksi'] ?? ''
            )
        );

    if (!in_array(
        $arah,
        ['TAMBAH', 'KURANG'],
        true
    )) {
        throw new InvalidArgumentException(
            'Jenis koreksi tidak valid.'
        );
    }

    $jumlah =
        trim(
            $_POST['jumlah'] ?? ''
        );

    if (
        $jumlah === '' ||
        !is_numeric($jumlah) ||
        (float)$jumlah <= 0
    ) {
        throw new InvalidArgumentException(
            'Jumlah koreksi harus lebih besar dari 0.'
        );
    }

    $alasan =
        trim(
            $_POST['alasan'] ?? ''
        );

    if ($alasan === '') {
        throw new InvalidArgumentException(
            'Alasan koreksi wajib diisi.'
        );
    }

    $tanggalInput =
        trim(
            $_POST['tanggal_mutasi'] ?? ''
        );

    $date =
        DateTime::createFromFormat(
            'Y-m-d\TH:i',
            $tanggalInput
        );

    if (!$date) {
        throw new InvalidArgumentException(
            'Tanggal koreksi tidak valid.'
        );
    }

    $tanggalMutasi =
        $date->format('Y-m-d H:i:s');

    $bankBenih =
        $controller->getBankBenih(
            $idBankBenih
        );

    if (!$bankBenih) {
        throw new InvalidArgumentException(
            'Data Bank Benih tidak ditemukan.'
        );
    }

    /*
     * Satuan selalu mengambil dari database.
     */
    $satuan =
        $bankBenih['satuan_stok'];

    $stokSebelum =
        (float)$bankBenih['jumlah_stok'];

    if (
        $arah === 'KURANG' &&
        (float)$jumlah > $stokSebelum
    ) {
        throw new InvalidArgumentException(
            'Jumlah koreksi melebihi stok tersedia. ' .
            'Stok saat ini: ' .
            $stokSebelum . ' ' .
            $satuan . '.'
        );
    }

    $data = [

        'id_bank_benih' =>
            $idBankBenih,

        'tipe_mutasi' =>
            'KOREKSI',

        'arah_koreksi' =>
            $arah,

        'jumlah' =>
            $jumlah,

        'satuan' =>
            $satuan,

        'sumber' =>
            'KOREKSI_MANUAL',

        'id_referensi' =>
            null,

        'alasan' =>
            $alasan,

        'tanggal_mutasi' =>
            $tanggalMutasi
    ];

    $result =
        $controller->store(
            $data,
            $userId
        );

    /*
     * Hitung delta hanya untuk informasi log.
     */
    $delta =
        $result['stok_sesudah']
        - $result['stok_sebelum'];

    $activityLog =
        new ActivityLog($pdo);

    $newData = [

        'id_bank_benih' =>
            $idBankBenih,

        'tipe_mutasi' =>
            'KOREKSI',

        'arah_koreksi' =>
            $arah,

        'jumlah' =>
            (float)$jumlah,

        'stok_sebelum' =>
            $result['stok_sebelum'],

        'stok_sesudah' =>
            $result['stok_sesudah'],

        'delta' =>
            $delta,

        'satuan' =>
            $satuan,

        'sumber' =>
            'KOREKSI_MANUAL',

        'alasan' =>
            $alasan
    ];

    $activityLog->create([

        'user_id' =>
            $userId,

        'username' =>
            $username,

        'activity' =>
            'CREATE',

        'menu_name' =>
            'Koreksi Stok',

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
            'Melakukan koreksi stok benih',

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
        $_SESSION['old_koreksi_stok']
    );

    header(
        'Location: index.php?success=created'
    );

    exit;

} catch (Throwable $e) {

    $_SESSION['koreksi_stok_errors'] = [
        $e->getMessage()
    ];

    header(
        'Location: index.php?error=validation'
    );

    exit;
}