<?php

require_once __DIR__ . '/../models/MutasiStokBenih.php';

class MutasiStokBenihController
{
    private $model;

    public function __construct($pdo)
    {
        $this->model = new MutasiStokBenih($pdo);
    }

    /**
     * Ambil data bank benih.
     */
    public function getBankBenih($idBankBenih)
    {
        return $this->model->getBankBenih($idBankBenih);
    }

    /**
     * Ambil stok saat ini.
     */
    public function getStokSaatIni($idBankBenih)
    {
        return $this->model->getStokSaatIni($idBankBenih);
    }

    /**
     * Ambil seluruh riwayat mutasi.
     */
    public function getAllRiwayat()
    {
        return $this->model->getAllRiwayat();
    }

    /**
     * Ambil riwayat berdasarkan bank benih.
     */
    public function getRiwayatByBankBenih($idBankBenih)
    {
        return $this->model->getRiwayatByBankBenih($idBankBenih);
    }

    /**
     * Cari mutasi berdasarkan ID.
     */
    public function find($id)
    {
        return $this->model->findById($id);
    }

    /**
     * Membuat mutasi stok.
     */
    public function store(array $data, $userId)
    {
        /*
         * CSRF ditangani di halaman endpoint
         * menggunakan verifyCsrfToken().
         *
         * Controller fokus pada validasi bisnis
         * dan proses data.
         */

        $data = $this->sanitize($data);

        $errors = $this->validate($data);

        if (!empty($errors)) {
            throw new InvalidArgumentException(
                implode(' ', $errors)
            );
        }

        $data['created_by'] = (int) $userId;

        return $this->model->createMutation($data);
    }

    /**
     * Soft delete mutasi.
     */
    public function delete($id, $userId)
    {
        $id = filter_var(
            $id,
            FILTER_VALIDATE_INT,
            [
                'options' => [
                    'min_range' => 1
                ]
            ]
        );

        if (!$id) {
            throw new InvalidArgumentException(
                'ID mutasi tidak valid.'
            );
        }

        return $this->model->softDelete(
            $id,
            (int) $userId
        );
    }

    /**
     * Sanitasi data.
     */
    private function sanitize(array $data)
    {
        $result = [];

        $result['id_bank_benih'] =
            isset($data['id_bank_benih'])
            ? (int) $data['id_bank_benih']
            : 0;

        $result['tipe_mutasi'] =
            strtoupper(
                trim(
                    (string) (
                        $data['tipe_mutasi'] ?? ''
                    )
                )
            );

        $result['arah_koreksi'] =
            strtoupper(
                trim(
                    (string) (
                        $data['arah_koreksi'] ?? ''
                    )
                )
            );

        /*
         * Gunakan string untuk jumlah terlebih dahulu
         * agar tidak terjadi masalah pada angka desimal.
         */
        $result['jumlah'] =
            trim(
                (string) (
                    $data['jumlah'] ?? ''
                )
            );

        $result['satuan'] =
            trim(
                (string) (
                    $data['satuan'] ?? ''
                )
            );

        $result['sumber'] =
            trim(
                (string) (
                    $data['sumber'] ?? ''
                )
            );

        $result['id_referensi'] =
            isset($data['id_referensi']) &&
            $data['id_referensi'] !== ''
            ? (int) $data['id_referensi']
            : null;

        $result['alasan'] =
            trim(
                (string) (
                    $data['alasan'] ?? ''
                )
            );

        $result['tanggal_mutasi'] =
            trim(
                (string) (
                    $data['tanggal_mutasi'] ??
                    date('Y-m-d H:i:s')
                )
            );

        return $result;
    }

    /**
     * Validasi bisnis.
     */
    private function validate(array $data)
    {
        $errors = [];

        /*
         * ID Bank Benih
         */
        if ($data['id_bank_benih'] <= 0) {
            $errors[] =
                'Bank Benih wajib dipilih.';
        }

        /*
         * Tipe mutasi
         */
        $allowedTypes = [
            'STOK_AWAL',
            'MASUK',
            'KELUAR',
            'KOREKSI'
        ];

        if (
            !in_array(
                $data['tipe_mutasi'],
                $allowedTypes,
                true
            )
        ) {
            $errors[] =
                'Tipe mutasi tidak valid.';
        }

        /*
         * Koreksi harus memiliki arah.
         */
        if (
            $data['tipe_mutasi'] === 'KOREKSI' &&
            !in_array(
                $data['arah_koreksi'],
                ['TAMBAH', 'KURANG'],
                true
            )
        ) {
            $errors[] = 'Arah koreksi wajib dipilih.';
        }

        /*
         * Jumlah harus berupa angka.
         */
        if (
            $data['jumlah'] === '' ||
            !is_numeric($data['jumlah'])
        ) {
            $errors[] =
                'Jumlah stok harus berupa angka.';
        } else {

            $jumlah = (float) $data['jumlah'];

            if ($jumlah <= 0) {
                $errors[] =
                    'Jumlah stok harus lebih besar dari 0.';
            }
        }

        /*
         * Satuan.
         */
        $allowedUnits = [
            'butir',
            'gram',
            'kg',
            'paket',
            'bibit'
        ];

        if (
            !in_array(
                $data['satuan'],
                $allowedUnits,
                true
            )
        ) {
            $errors[] =
                'Satuan stok tidak valid.';
        }

        /*
         * Sumber maksimal 50 karakter.
         */
        if (strlen($data['sumber']) > 50) {
            $errors[] =
                'Sumber mutasi maksimal 50 karakter.';
        }

        /*
         * Tanggal mutasi.
         */
        $date = DateTime::createFromFormat(
            'Y-m-d H:i:s',
            $data['tanggal_mutasi']
        );

        if (
            !$date ||
            $date->format('Y-m-d H:i:s')
            !== $data['tanggal_mutasi']
        ) {
            $errors[] =
                'Format tanggal mutasi tidak valid.';
        }

        /*
         * Alasan.
         */
        if (
            $data['tipe_mutasi'] === 'KOREKSI' &&
            $data['alasan'] === ''
        ) {
            $errors[] =
                'Alasan koreksi wajib diisi.';
        }

        return $errors;
    }
}