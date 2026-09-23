<?php

class MutasiStokBenih
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * Ambil satu data bank benih.
     */
    public function getBankBenih($idBankBenih)
    {
        $sql = "SELECT
                    id,
                    nomor_aksesi,
                    nama_lokal,
                    nama_ilmiah,
                    jumlah_stok,
                    satuan_stok,
                    is_active,
                    deleted_at
                FROM t_bank_benih
                WHERE id = :id
                LIMIT 1";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            'id' => $idBankBenih
        ]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Ambil stok saat ini dari t_bank_benih.
     */
    public function getStokSaatIni($idBankBenih)
    {
        $sql = "SELECT
                    jumlah_stok,
                    satuan_stok
                FROM t_bank_benih
                WHERE id = :id
                  AND deleted_at IS NULL
                LIMIT 1";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            'id' => $idBankBenih
        ]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Ambil riwayat mutasi satu bank benih.
     */
    public function getRiwayatByBankBenih($idBankBenih)
    {
        $sql = "SELECT
                    msb.*,
                    bb.nomor_aksesi,
                    bb.nama_lokal
                FROM t_mutasi_stok_benih msb
                INNER JOIN t_bank_benih bb
                    ON bb.id = msb.id_bank_benih
                WHERE msb.id_bank_benih = :id_bank_benih
                  AND msb.deleted_at IS NULL
                ORDER BY
                    msb.tanggal_mutasi DESC,
                    msb.id DESC";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            'id_bank_benih' => $idBankBenih
        ]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Ambil seluruh riwayat mutasi.
     */
    public function getAllRiwayat()
    {
        $sql = "SELECT
                    msb.*,
                    bb.nomor_aksesi,
                    bb.nama_lokal
                FROM t_mutasi_stok_benih msb
                INNER JOIN t_bank_benih bb
                    ON bb.id = msb.id_bank_benih
                WHERE msb.deleted_at IS NULL
                ORDER BY
                    msb.tanggal_mutasi DESC,
                    msb.id DESC";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Membuat mutasi stok sekaligus memperbarui saldo t_bank_benih.
     *
     * Semua proses dilakukan dalam transaction.
     */
    public function createMutation(array $data)
    {
        $this->pdo->beginTransaction();

        try {
            /*
             * Lock row bank benih selama transaction.
             *
             * Tujuannya mencegah dua user mengubah stok
             * secara bersamaan dan menghasilkan saldo yang salah.
             */
            $sql = "SELECT
                        id,
                        jumlah_stok,
                        satuan_stok,
                        deleted_at
                    FROM t_bank_benih
                    WHERE id = :id
                    LIMIT 1
                    FOR UPDATE";

            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                'id' => $data['id_bank_benih']
            ]);

            $bankBenih = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$bankBenih) {
                throw new Exception('Data bank benih tidak ditemukan.');
            }

            if ($bankBenih['deleted_at'] !== null) {
                throw new Exception('Data bank benih sudah dihapus.');
            }

            $stokSebelum = (float) $bankBenih['jumlah_stok'];
            $jumlah = (float) $data['jumlah'];

            if ($jumlah <= 0) {
                throw new Exception('Jumlah mutasi harus lebih besar dari 0.');
            }

            /*
             * Validasi satuan.
             *
             * Satuan mutasi harus sama dengan satuan stok
             * pada Bank Benih.
             */
            if ($data['satuan'] !== $bankBenih['satuan_stok']) {
                throw new Exception(
                    'Satuan mutasi (' . $data['satuan'] .
                    ') tidak sesuai dengan satuan stok Bank Benih (' .
                    $bankBenih['satuan_stok'] . ').'
                );
            }

            /*
             * Tentukan stok sesudah transaksi.
             */
            switch ($data['tipe_mutasi']) {

                case 'STOK_AWAL':
                case 'MASUK':

                    $stokSesudah = $stokSebelum + $jumlah;

                    break;

                case 'KELUAR':

                    if ($jumlah > $stokSebelum) {
                        throw new Exception(
                            'Stok tidak mencukupi. ' .
                            'Stok tersedia: ' . $stokSebelum . ' ' .
                            $bankBenih['satuan_stok'] . '.'
                        );
                    }

                    $stokSesudah = $stokSebelum - $jumlah;

                    break;

                case 'KOREKSI':

                    if (!in_array(
                        $data['arah_koreksi'],
                        ['TAMBAH', 'KURANG'],
                        true
                    )) {
                        throw new Exception(
                            'Arah koreksi harus TAMBAH atau KURANG.'
                        );
                    }

                    if ($data['arah_koreksi'] === 'TAMBAH') {

                        $stokSesudah = $stokSebelum + $jumlah;

                    } else {

                        if ($jumlah > $stokSebelum) {
                            throw new Exception(
                                'Jumlah koreksi melebihi stok yang tersedia.'
                            );
                        }

                        $stokSesudah = $stokSebelum - $jumlah;
                    }

                    break;

                default:

                    throw new Exception(
                        'Tipe mutasi stok tidak valid.'
                    );
            }

            /*
             * Pastikan stok tidak pernah negatif.
             */
            if ($stokSesudah < 0) {
                throw new Exception(
                    'Stok sesudah transaksi tidak boleh negatif.'
                );
            }

            /*
             * Masukkan histori mutasi.
             */
            $sql = "INSERT INTO t_mutasi_stok_benih (
                        id_bank_benih,
                        tipe_mutasi,
                        arah_koreksi,
                        jumlah,
                        stok_sebelum,
                        stok_sesudah,
                        satuan,
                        sumber,
                        id_referensi,
                        alasan,
                        tanggal_mutasi,
                        created_by
                    ) VALUES (
                        :id_bank_benih,
                        :tipe_mutasi,
                        :arah_koreksi,
                        :jumlah,
                        :stok_sebelum,
                        :stok_sesudah,
                        :satuan,
                        :sumber,
                        :id_referensi,
                        :alasan,
                        :tanggal_mutasi,
                        :created_by
                    )";

            $stmt = $this->pdo->prepare($sql);

            $stmt->execute([
                'id_bank_benih' => $data['id_bank_benih'],
                'tipe_mutasi' => $data['tipe_mutasi'],
                'arah_koreksi' => $data['arah_koreksi'],
                'jumlah' => $jumlah,
                'stok_sebelum' => $stokSebelum,
                'stok_sesudah' => $stokSesudah,
                'satuan' => $data['satuan'],
                'sumber' => $data['sumber'],
                'id_referensi' => $data['id_referensi'],
                'alasan' => $data['alasan'],
                'tanggal_mutasi' => $data['tanggal_mutasi'],
                'created_by' => $data['created_by']
            ]);

            $idMutasi = $this->pdo->lastInsertId();

            /*
             * Update saldo stok Bank Benih.
             */
            $sql = "UPDATE t_bank_benih
                    SET
                        jumlah_stok = :jumlah_stok,
                        updated_by = :updated_by
                    WHERE id = :id
                      AND deleted_at IS NULL";

            $stmt = $this->pdo->prepare($sql);

            $stmt->execute([
                'jumlah_stok' => $stokSesudah,
                'updated_by' => $data['created_by'],
                'id' => $data['id_bank_benih']
            ]);

            if ($stmt->rowCount() !== 1) {
                throw new Exception(
                    'Saldo stok gagal diperbarui.'
                );
            }

            /*
             * Commit.
             */
            $this->pdo->commit();

            return [
                'success' => true,
                'id_mutasi' => $idMutasi,
                'stok_sebelum' => $stokSebelum,
                'stok_sesudah' => $stokSesudah
            ];

        } catch (Throwable $e) {

            if ($this->pdo->inTransaction()) {
                $this->pdo->rollBack();
            }

            throw $e;
        }
    }

    /**
     * Soft delete mutasi.
     *
     * Untuk keamanan, fungsi ini TIDAK mengembalikan stok.
     *
     * Mutasi yang sudah masuk histori sebaiknya tidak dihapus
     * sembarangan.
     */
    public function softDelete($idMutasi, $userId)
    {
        $sql = "UPDATE t_mutasi_stok_benih
                SET
                    deleted_at = NOW(),
                    deleted_by = :deleted_by
                WHERE id = :id
                  AND deleted_at IS NULL";

        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([
            'id' => $idMutasi,
            'deleted_by' => $userId
        ]);
    }

    /**
     * Cari mutasi berdasarkan ID.
     */
    public function findById($id)
    {
        $sql = "SELECT
                    msb.*,
                    bb.nomor_aksesi,
                    bb.nama_lokal,
                    bb.nama_ilmiah
                FROM t_mutasi_stok_benih msb
                INNER JOIN t_bank_benih bb
                    ON bb.id = msb.id_bank_benih
                WHERE msb.id = :id
                LIMIT 1";

        $stmt = $this->pdo->prepare($sql);

        $stmt->execute([
            'id' => $id
        ]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}