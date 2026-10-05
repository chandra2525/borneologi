<?php

class DetailMonitoringPenanaman
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * List detail monitoring.
     *
     * Jika $id_bank_benih diberikan,
     * hanya detail untuk Bank Benih tersebut.
     */
    public function getAll($id_bank_benih = null)
    {
        $params = [];

        $sql = "
            SELECT
                dm.id,
                dm.id_monitoring,
                dm.id_bank_benih,
                dm.jumlah_ditanam,
                dm.satuan,
                dm.jumlah_hidup,
                dm.jumlah_mati,
                dm.tinggi_rata2_cm,
                dm.diameter_rata2_cm,
                dm.catatan,

                bb.nomor_aksesi,
                bb.nama_lokal,
                bb.nama_ilmiah,

                mp.kode_monitoring,
                mp.periode_pengecekan,
                mp.tanggal_tanam,
                mp.tanggal_monitoring

            FROM t_detail_monitoring_penanaman dm

            INNER JOIN t_bank_benih bb
                ON bb.id = dm.id_bank_benih

            INNER JOIN t_monitoring_penanaman mp
                ON mp.id = dm.id_monitoring

            WHERE dm.deleted_at IS NULL
            AND bb.deleted_at IS NULL
            AND mp.deleted_at IS NULL
        ";

        if ($id_bank_benih !== null) {

            $sql .= "
                AND dm.id_bank_benih = :id_bank_benih
            ";

            $params['id_bank_benih'] = $id_bank_benih;
        }

        $sql .= "
            ORDER BY dm.id DESC
        ";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Detail berdasarkan ID.
     */
    public function findById($id)
    {
        $sql = "
            SELECT
                dm.*,

                bb.nomor_aksesi,
                bb.nama_lokal,
                bb.nama_ilmiah,
                bb.satuan_stok,
                bb.jumlah_stok,

                mp.kode_monitoring,
                mp.periode_pengecekan,
                mp.tanggal_tanam,
                mp.tanggal_monitoring

            FROM t_detail_monitoring_penanaman dm

            INNER JOIN t_bank_benih bb
                ON bb.id = dm.id_bank_benih

            INNER JOIN t_monitoring_penanaman mp
                ON mp.id = dm.id_monitoring

            WHERE dm.id = :id
            AND dm.deleted_at IS NULL

            LIMIT 1
        ";

        $stmt = $this->pdo->prepare($sql);

        $stmt->execute([
            'id' => $id
        ]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Ambil Bank Benih.
     */
    public function getBankBenih($id = null)
    {
        $sql = "
            SELECT
                id,
                nomor_aksesi,
                nama_lokal,
                nama_ilmiah,
                satuan_stok,
                jumlah_stok

            FROM t_bank_benih

            WHERE deleted_at IS NULL
            AND is_active = 1
        ";

        $params = [];

        if ($id !== null) {

            $sql .= "
                AND id = :id
            ";

            $params['id'] = $id;
        }

        $sql .= "
            ORDER BY nama_lokal ASC
        ";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);

        if ($id !== null) {
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Ambil Monitoring Penanaman.
     */
    public function getMonitoringPenanaman($id = null)
    {
        $sql = "
            SELECT
                id,
                kode_monitoring,
                periode_pengecekan,
                tanggal_tanam,
                tanggal_monitoring

            FROM t_monitoring_penanaman

            WHERE deleted_at IS NULL
        ";

        $params = [];

        if ($id !== null) {

            $sql .= "
                AND id = :id
            ";

            $params['id'] = $id;
        }

        $sql .= "
            ORDER BY id DESC
        ";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);

        if ($id !== null) {
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Cek apakah pasangan Monitoring + Bank Benih
     * sudah digunakan oleh detail aktif.
     */
    public function existsPair(
        $id_monitoring,
        $id_bank_benih,
        $exclude_id = null
    ) {
        $sql = "
            SELECT id
            FROM t_detail_monitoring_penanaman

            WHERE id_monitoring = :id_monitoring
            AND id_bank_benih = :id_bank_benih
            AND deleted_at IS NULL
        ";

        $params = [
            'id_monitoring' => $id_monitoring,
            'id_bank_benih' => $id_bank_benih
        ];

        if ($exclude_id !== null) {

            $sql .= "
                AND id != :exclude_id
            ";

            $params['exclude_id'] = $exclude_id;
        }

        $sql .= "
            LIMIT 1
        ";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);

        return (bool) $stmt->fetchColumn();
    }

    /**
     * Insert mutasi stok.
     */
    private function insertMutation($data)
    {
        $sql = "
            INSERT INTO t_mutasi_stok_benih
            (
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
            )
            VALUES
            (
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
                NOW(),
                :created_by
            )
        ";

        $stmt = $this->pdo->prepare($sql);

        $stmt->execute($data);

        return $this->pdo->lastInsertId();
    }

    /**
     * CREATE + pengurangan stok.
     */
    public function createWithStock($data, $user_id)
    {
        $this->pdo->beginTransaction();

        try {

            $id_monitoring =
                (int) $data['id_monitoring'];

            $id_bank_benih =
                (int) $data['id_bank_benih'];

            $jumlah_ditanam =
                (float) $data['jumlah_ditanam'];

            $satuan =
                trim($data['satuan']);

            if ($jumlah_ditanam <= 0) {
                throw new Exception(
                    'Jumlah ditanam harus lebih dari 0.'
                );
            }

            /**
             * Pastikan Monitoring masih aktif.
             */
            $stmt = $this->pdo->prepare("
                SELECT id
                FROM t_monitoring_penanaman
                WHERE id = :id
                AND deleted_at IS NULL
                LIMIT 1
            ");

            $stmt->execute([
                'id' => $id_monitoring
            ]);

            if (!$stmt->fetchColumn()) {
                throw new Exception(
                    'Monitoring Penanaman tidak ditemukan.'
                );
            }

            /**
             * Jangan boleh duplicate pasangan
             * monitoring + bank benih.
             */
            if (
                $this->existsPair(
                    $id_monitoring,
                    $id_bank_benih
                )
            ) {
                throw new Exception(
                    'Bank Benih tersebut sudah memiliki '
                    . 'Detail Monitoring pada monitoring ini.'
                );
            }

            /**
             * Lock Bank Benih.
             */
            $stmt = $this->pdo->prepare("
                SELECT
                    id,
                    satuan_stok,
                    jumlah_stok,
                    is_active

                FROM t_bank_benih

                WHERE id = :id
                AND deleted_at IS NULL

                LIMIT 1

                FOR UPDATE
            ");

            $stmt->execute([
                'id' => $id_bank_benih
            ]);

            $bank = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$bank) {
                throw new Exception(
                    'Bank Benih tidak ditemukan.'
                );
            }

            if ((int) $bank['is_active'] !== 1) {
                throw new Exception(
                    'Bank Benih sedang tidak aktif.'
                );
            }

            /**
             * Unit harus sama dengan satuan stok.
             */
            if ($bank['satuan_stok'] !== $satuan) {
                throw new Exception(
                    'Satuan Detail Monitoring harus sama '
                    . 'dengan satuan stok Bank Benih.'
                );
            }

            $stok_sebelum =
                (float) $bank['jumlah_stok'];

            if ($jumlah_ditanam > $stok_sebelum) {
                throw new Exception(
                    'Stok tidak mencukupi. '
                    . 'Stok tersedia: '
                    . number_format(
                        $stok_sebelum,
                        2,
                        ',',
                        '.'
                    )
                    . ' '
                    . $bank['satuan_stok']
                );
            }

            $stok_sesudah =
                $stok_sebelum - $jumlah_ditanam;

            /**
             * Insert detail.
             */
            $stmt = $this->pdo->prepare("
                INSERT INTO t_detail_monitoring_penanaman
                (
                    id_monitoring,
                    id_bank_benih,
                    jumlah_ditanam,
                    satuan,
                    jumlah_hidup,
                    jumlah_mati,
                    tinggi_rata2_cm,
                    diameter_rata2_cm,
                    catatan,
                    created_by
                )
                VALUES
                (
                    :id_monitoring,
                    :id_bank_benih,
                    :jumlah_ditanam,
                    :satuan,
                    :jumlah_hidup,
                    :jumlah_mati,
                    :tinggi_rata2_cm,
                    :diameter_rata2_cm,
                    :catatan,
                    :created_by
                )
            ");

            $stmt->execute([
                'id_monitoring' => $id_monitoring,
                'id_bank_benih' => $id_bank_benih,
                'jumlah_ditanam' => $jumlah_ditanam,
                'satuan' => $satuan,
                'jumlah_hidup' =>
                    $data['jumlah_hidup'] !== ''
                        ? $data['jumlah_hidup']
                        : null,
                'jumlah_mati' =>
                    $data['jumlah_mati'] !== ''
                        ? $data['jumlah_mati']
                        : null,
                'tinggi_rata2_cm' =>
                    $data['tinggi_rata2_cm'] !== ''
                        ? $data['tinggi_rata2_cm']
                        : null,
                'diameter_rata2_cm' =>
                    $data['diameter_rata2_cm'] !== ''
                        ? $data['diameter_rata2_cm']
                        : null,
                'catatan' =>
                    $data['catatan'] !== ''
                        ? $data['catatan']
                        : null,
                'created_by' => $user_id
            ]);

            $detail_id =
                $this->pdo->lastInsertId();

            /**
             * Kurangi stok.
             */
            $stmt = $this->pdo->prepare("
                UPDATE t_bank_benih

                SET
                    jumlah_stok = :jumlah_stok,
                    updated_by = :updated_by

                WHERE id = :id
            ");

            $stmt->execute([
                'jumlah_stok' => $stok_sesudah,
                'updated_by' => $user_id,
                'id' => $id_bank_benih
            ]);

            /**
             * Catat mutasi.
             */
            $mutation_id =
                $this->insertMutation([
                    'id_bank_benih' => $id_bank_benih,
                    'tipe_mutasi' => 'KELUAR',
                    'arah_koreksi' => null,
                    'jumlah' => $jumlah_ditanam,
                    'stok_sebelum' => $stok_sebelum,
                    'stok_sesudah' => $stok_sesudah,
                    'satuan' => $satuan,
                    'sumber' => 'DETAIL_MONITORING',
                    'id_referensi' => $detail_id,
                    'alasan' => 'Penanaman melalui Detail Monitoring Penanaman.',
                    'created_by' => $user_id
                ]);

            $this->pdo->commit();

            return [
                'detail_id' => $detail_id,
                'mutation_id' => $mutation_id,
                'stok_sebelum' => $stok_sebelum,
                'stok_sesudah' => $stok_sesudah
            ];

        } catch (Throwable $e) {

            if ($this->pdo->inTransaction()) {
                $this->pdo->rollBack();
            }

            throw $e;
        }
    }

    /**
     * UPDATE Detail Monitoring.
     *
     * Bank Benih sengaja TIDAK boleh diganti.
     *
     * Alasannya:
     * detail ini sudah memiliki histori pengeluaran stok.
     */
    public function updateWithStock(
        $id,
        $data,
        $user_id
    ) {
        $this->pdo->beginTransaction();

        try {

            /**
             * Lock detail.
             */
            $stmt = $this->pdo->prepare("
                SELECT *
                FROM t_detail_monitoring_penanaman

                WHERE id = :id
                AND deleted_at IS NULL

                LIMIT 1

                FOR UPDATE
            ");

            $stmt->execute([
                'id' => $id
            ]);

            $old = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$old) {
                throw new Exception(
                    'Detail Monitoring tidak ditemukan.'
                );
            }

            $new_bank =
                (int) $data['id_bank_benih'];

            /**
             * Bank Benih tidak boleh diganti.
             */
            if (
                $new_bank
                !== (int) $old['id_bank_benih']
            ) {
                throw new Exception(
                    'Bank Benih tidak dapat diganti pada Edit Detail Monitoring. '
                    . 'Silakan buat Detail Monitoring baru.'
                );
            }

            $jumlah_lama =
                (float) $old['jumlah_ditanam'];

            $jumlah_baru =
                (float) $data['jumlah_ditanam'];

            if ($jumlah_baru <= 0) {
                throw new Exception(
                    'Jumlah ditanam harus lebih dari 0.'
                );
            }

            /**
             * Cek duplicate monitoring + bank.
             */
            if (
                $this->existsPair(
                    (int) $old['id_monitoring'],
                    (int) $old['id_bank_benih'],
                    $id
                )
            ) {
                throw new Exception(
                    'Kombinasi Monitoring dan Bank Benih sudah digunakan.'
                );
            }

            /**
             * Lock Bank Benih.
             */
            $stmt = $this->pdo->prepare("
                SELECT
                    id,
                    satuan_stok,
                    jumlah_stok,
                    is_active

                FROM t_bank_benih

                WHERE id = :id
                AND deleted_at IS NULL

                LIMIT 1

                FOR UPDATE
            ");

            $stmt->execute([
                'id' => $old['id_bank_benih']
            ]);

            $bank = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$bank) {
                throw new Exception(
                    'Bank Benih tidak ditemukan.'
                );
            }

            if ((int) $bank['is_active'] !== 1) {
                throw new Exception(
                    'Bank Benih sedang tidak aktif.'
                );
            }

            if (
                $bank['satuan_stok']
                !== $old['satuan']
            ) {
                throw new Exception(
                    'Satuan stok Bank Benih tidak sesuai dengan Detail Monitoring.'
                );
            }

            if (
                $data['satuan']
                !== $bank['satuan_stok']
            ) {
                throw new Exception(
                    'Satuan Detail Monitoring harus sama dengan satuan stok Bank Benih.'
                );
            }

            $stok_sebelum =
                (float) $bank['jumlah_stok'];

            $delta =
                $jumlah_baru - $jumlah_lama;

            $stok_sesudah =
                $stok_sebelum;

            /**
             * Jumlah naik:
             * stok berkurang.
             */
            if ($delta > 0) {

                if ($delta > $stok_sebelum) {

                    throw new Exception(
                        'Stok tidak mencukupi untuk penambahan jumlah tanam.'
                    );
                }

                $stok_sesudah =
                    $stok_sebelum - $delta;

            /**
             * Jumlah turun:
             * stok dikembalikan.
             */
            } elseif ($delta < 0) {

                $stok_sesudah =
                    $stok_sebelum + abs($delta);
            }

            /**
             * Update detail.
             */
            $stmt = $this->pdo->prepare("
                UPDATE t_detail_monitoring_penanaman

                SET
                    jumlah_ditanam = :jumlah_ditanam,
                    satuan = :satuan,
                    jumlah_hidup = :jumlah_hidup,
                    jumlah_mati = :jumlah_mati,
                    tinggi_rata2_cm = :tinggi_rata2_cm,
                    diameter_rata2_cm = :diameter_rata2_cm,
                    catatan = :catatan,
                    updated_by = :updated_by

                WHERE id = :id
            ");

            $stmt->execute([
                'jumlah_ditanam' => $jumlah_baru,
                'satuan' => $data['satuan'],
                'jumlah_hidup' =>
                    $data['jumlah_hidup'] !== ''
                        ? $data['jumlah_hidup']
                        : null,
                'jumlah_mati' =>
                    $data['jumlah_mati'] !== ''
                        ? $data['jumlah_mati']
                        : null,
                'tinggi_rata2_cm' =>
                    $data['tinggi_rata2_cm'] !== ''
                        ? $data['tinggi_rata2_cm']
                        : null,
                'diameter_rata2_cm' =>
                    $data['diameter_rata2_cm'] !== ''
                        ? $data['diameter_rata2_cm']
                        : null,
                'catatan' =>
                    $data['catatan'] !== ''
                        ? $data['catatan']
                        : null,
                'updated_by' => $user_id,
                'id' => $id
            ]);

            /**
             * Jika jumlah berubah,
             * update stok + mutasi.
             */
            if ($delta != 0) {

                $stmt = $this->pdo->prepare("
                    UPDATE t_bank_benih

                    SET
                        jumlah_stok = :jumlah_stok,
                        updated_by = :updated_by

                    WHERE id = :id
                ");

                $stmt->execute([
                    'jumlah_stok' => $stok_sesudah,
                    'updated_by' => $user_id,
                    'id' => $old['id_bank_benih']
                ]);

                if ($delta > 0) {

                    /**
                     * Tambahan penanaman =
                     * stok keluar.
                     */
                    $this->insertMutation([
                        'id_bank_benih' =>
                            $old['id_bank_benih'],
                        'tipe_mutasi' => 'KELUAR',
                        'arah_koreksi' => null,
                        'jumlah' => abs($delta),
                        'stok_sebelum' => $stok_sebelum,
                        'stok_sesudah' => $stok_sesudah,
                        'satuan' => $bank['satuan_stok'],
                        'sumber' =>
                            'DETAIL_MONITORING_EDIT',
                        'id_referensi' => $id,
                        'alasan' =>
                            'Penambahan jumlah ditanam pada edit Detail Monitoring.',
                        'created_by' => $user_id
                    ]);

                } else {

                    /**
                     * Pengurangan penanaman =
                     * stok kembali.
                     */
                    $this->insertMutation([
                        'id_bank_benih' =>
                            $old['id_bank_benih'],
                        'tipe_mutasi' => 'KOREKSI',
                        'arah_koreksi' => 'TAMBAH',
                        'jumlah' => abs($delta),
                        'stok_sebelum' => $stok_sebelum,
                        'stok_sesudah' => $stok_sesudah,
                        'satuan' => $bank['satuan_stok'],
                        'sumber' =>
                            'DETAIL_MONITORING_EDIT',
                        'id_referensi' => $id,
                        'alasan' =>
                            'Pengembalian stok karena pengurangan jumlah ditanam pada edit Detail Monitoring.',
                        'created_by' => $user_id
                    ]);
                }
            }

            $this->pdo->commit();

            return [
                'stok_sebelum' => $stok_sebelum,
                'stok_sesudah' => $stok_sesudah,
                'delta' => $delta
            ];

        } catch (Throwable $e) {

            if ($this->pdo->inTransaction()) {
                $this->pdo->rollBack();
            }

            throw $e;
        }
    }

    /**
     * DELETE detail + kembalikan stok.
     */
    public function deleteWithStock(
        $id,
        $user_id
    ) {
        $this->pdo->beginTransaction();

        try {

            /**
             * Lock detail.
             */
            $stmt = $this->pdo->prepare("
                SELECT *
                FROM t_detail_monitoring_penanaman

                WHERE id = :id
                AND deleted_at IS NULL

                LIMIT 1

                FOR UPDATE
            ");

            $stmt->execute([
                'id' => $id
            ]);

            $detail = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$detail) {
                throw new Exception(
                    'Detail Monitoring tidak ditemukan.'
                );
            }

            /**
             * Lock bank.
             */
            $stmt = $this->pdo->prepare("
                SELECT
                    id,
                    satuan_stok,
                    jumlah_stok

                FROM t_bank_benih

                WHERE id = :id
                AND deleted_at IS NULL

                LIMIT 1

                FOR UPDATE
            ");

            $stmt->execute([
                'id' => $detail['id_bank_benih']
            ]);

            $bank = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$bank) {
                throw new Exception(
                    'Bank Benih tidak ditemukan.'
                );
            }

            if (
                $bank['satuan_stok']
                !== $detail['satuan']
            ) {
                throw new Exception(
                    'Satuan stok Bank Benih tidak sesuai.'
                );
            }

            $jumlah =
                (float) $detail['jumlah_ditanam'];

            $stok_sebelum =
                (float) $bank['jumlah_stok'];

            $stok_sesudah =
                $stok_sebelum + $jumlah;

            /**
             * Kembalikan stok.
             */
            $stmt = $this->pdo->prepare("
                UPDATE t_bank_benih

                SET
                    jumlah_stok = :jumlah_stok,
                    updated_by = :updated_by

                WHERE id = :id
            ");

            $stmt->execute([
                'jumlah_stok' => $stok_sesudah,
                'updated_by' => $user_id,
                'id' => $detail['id_bank_benih']
            ]);

            /**
             * Catat mutasi.
             */
            $this->insertMutation([
                'id_bank_benih' =>
                    $detail['id_bank_benih'],
                'tipe_mutasi' => 'KOREKSI',
                'arah_koreksi' => 'TAMBAH',
                'jumlah' => $jumlah,
                'stok_sebelum' => $stok_sebelum,
                'stok_sesudah' => $stok_sesudah,
                'satuan' => $detail['satuan'],
                'sumber' =>
                    'DETAIL_MONITORING_DELETE',
                'id_referensi' => $id,
                'alasan' =>
                    'Pengembalian stok karena Detail Monitoring dihapus.',
                'created_by' => $user_id
            ]);

            /**
             * Soft delete detail.
             */
            $stmt = $this->pdo->prepare("
                UPDATE t_detail_monitoring_penanaman

                SET
                    deleted_at = NOW(),
                    deleted_by = :deleted_by

                WHERE id = :id
            ");

            $stmt->execute([
                'deleted_by' => $user_id,
                'id' => $id
            ]);

            $this->pdo->commit();

            return [
                'stok_sebelum' => $stok_sebelum,
                'stok_sesudah' => $stok_sesudah
            ];

        } catch (Throwable $e) {

            if ($this->pdo->inTransaction()) {
                $this->pdo->rollBack();
            }

            throw $e;
        }
    }
}