<?php

class DetailMonitoringPenanaman
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * List Detail Monitoring.
     *
     * Jika $id_monitoring diberikan,
     * hanya histori id_monitoring penanaman tersebut.
     */
    public function getByMonitoring($id_monitoring)
    {
        $sql = "
        SELECT
            dm.id,
            dm.id_monitoring,
            dm.id_bank_benih,
            dm.id_tipe_penanaman,
            dm.jumlah_ditanam,
            dm.satuan,
            dm.jumlah_hidup,
            dm.jumlah_mati,
            dm.survival_rate_persen,
            dm.tinggi_rata2_cm,
            dm.diameter_rata2_cm,
            dm.luas_tanam_ha,
            dm.catatan,
            bb.nomor_aksesi,
            bb.nama_lokal,
            bb.nama_ilmiah,
            bb.jumlah_stok,
            bb.satuan_stok,
            mtp.nama AS nama_tipe_penanaman
            FROM t_detail_monitoring_penanaman dm
            INNER JOIN t_bank_benih bb
                ON bb.id = dm.id_bank_benih
            LEFT JOIN m_tipe_penanaman mtp
                ON mtp.id = dm.id_tipe_penanaman
            WHERE dm.id_monitoring = :id_monitoring
            AND dm.deleted_at IS NULL
            AND bb.deleted_at IS NULL
            ORDER BY dm.id DESC
        ";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            'id_monitoring' => $id_monitoring
        ]);
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
                bb.stok_terpakai,
                mtp.nama AS nama_tipe_penanaman,
                mp.id AS id_monitoring,
                mp.kode_monitoring,
                mp.periode_pengecekan,
                mp.tanggal_tanam,
                mp.tanggal_monitoring
            FROM t_detail_monitoring_penanaman dm
            INNER JOIN t_bank_benih bb
                ON bb.id = dm.id_bank_benih
            INNER JOIN t_monitoring_penanaman mp
                ON mp.id = dm.id_monitoring
            LEFT JOIN m_tipe_penanaman mtp
                ON mtp.id = dm.id_tipe_penanaman
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
                jumlah_stok,
                stok_terpakai
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
     * Ambil Monitoring Penanaman.
     */
    public function getMonitoringPenanaman($id = null)
    {
        $sql = "
            SELECT
                id,
                kode_monitoring,
                id_turunan,
                id_tanah,
                periode_pengecekan,
                tanggal_tanam,
                tanggal_monitoring,
                id_progress_status_monitoring
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
     * Tipe Penanaman.
     */
    public function getTipePenanaman()
    {
        $sql = "
            SELECT
                id,
                nama
            FROM m_tipe_penanaman
            WHERE is_active = 1 AND deleted_at IS NULL
            ORDER BY nama ASC
        ";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Cek pasangan Monitoring + Bank Benih.
     *
     * Satu Bank Benih hanya boleh muncul satu kali
     * dalam satu Monitoring.
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
     * CREATE DETAIL + PENGURANGAN STOK.
     */
    public function createWithStock($data, $user_id)
    {
        $this->pdo->beginTransaction();
        try {
            $id_monitoring = (int) $data['id_monitoring'];
            $id_bank_benih = (int) $data['id_bank_benih'];
            $id_tipe_penanaman = (int) $data['id_tipe_penanaman'];
            $jumlah_ditanam = (float) $data['jumlah_ditanam'];
            $satuan = trim($data['satuan']);
            $luas_tanam_ha = (float) $data['luas_tanam_ha'];
            if ($jumlah_ditanam <= 0) {
                throw new Exception(
                    'Jumlah ditanam harus lebih dari 0.'
                );
            }
            if ($luas_tanam_ha <= 0) {
                throw new Exception(
                    'Luas tanam harus lebih dari 0.'
                );
            }
            /**
             * Pastikan Monitoring aktif.
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
             * Cek duplicate.
             */
            if (
                $this->existsPair(
                    $id_monitoring,
                    $id_bank_benih
                )
            ) {
                throw new Exception(
                    'Bank Benih tersebut sudah memiliki Detail Monitoring pada monitoring ini.'
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
                    stok_terpakai,
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
             * Satuan harus sama.
             */
            if ($bank['satuan_stok'] !== $satuan) {
                throw new Exception(
                    'Satuan Detail Monitoring harus sama dengan satuan stok Bank Benih.'
                );
            }
            $stok_sebelum = (float) $bank['jumlah_stok'];
            if ($jumlah_ditanam > $stok_sebelum) {
                throw new Exception(
                    'Stok tidak mencukupi. Stok tersedia: '
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
            $stok_terpakai_sesudah =
                (float) $bank['stok_terpakai']
                + $jumlah_ditanam;

            /**
             * Hitung survival rate otomatis.
             */
            $jumlah_hidup =
                $data['jumlah_hidup'] !== ''
                ? (float) $data['jumlah_hidup']
                : null;
            $jumlah_mati =
                $data['jumlah_mati'] !== ''
                ? (float) $data['jumlah_mati']
                : null;
            $survival_rate = null;
            if ($jumlah_hidup !== null) {
                $survival_rate =
                    ($jumlah_hidup / $jumlah_ditanam) * 100;
                $survival_rate =
                    min(100, max(0, $survival_rate));
            }

            /**
             * Insert Detail.
             */
            $stmt = $this->pdo->prepare("
                INSERT INTO t_detail_monitoring_penanaman
                (
                    id_monitoring,
                    id_bank_benih,
                    id_tipe_penanaman,
                    jumlah_ditanam,
                    satuan,
                    jumlah_hidup,
                    jumlah_mati,
                    survival_rate_persen,
                    tinggi_rata2_cm,
                    diameter_rata2_cm,
                    luas_tanam_ha,
                    catatan,
                    created_by
                )
                VALUES
                (
                    :id_monitoring,
                    :id_bank_benih,
                    :id_tipe_penanaman,
                    :jumlah_ditanam,
                    :satuan,
                    :jumlah_hidup,
                    :jumlah_mati,
                    :survival_rate_persen,
                    :tinggi_rata2_cm,
                    :diameter_rata2_cm,
                    :luas_tanam_ha,
                    :catatan,
                    :created_by
                )
            ");

            $stmt->execute([
                'id_monitoring' => $id_monitoring,
                'id_bank_benih' => $id_bank_benih,
                'id_tipe_penanaman' => $id_tipe_penanaman,
                'jumlah_ditanam' => $jumlah_ditanam,
                'satuan' => $satuan,
                'jumlah_hidup' => $jumlah_hidup,
                'jumlah_mati' => $jumlah_mati,
                'survival_rate_persen' => $survival_rate,
                'tinggi_rata2_cm' =>
                    $data['tinggi_rata2_cm'] !== ''
                    ? $data['tinggi_rata2_cm']
                    : null,
                'diameter_rata2_cm' =>
                    $data['diameter_rata2_cm'] !== ''
                    ? $data['diameter_rata2_cm']
                    : null,
                'luas_tanam_ha' => $luas_tanam_ha,
                'catatan' =>
                    $data['catatan'] !== ''
                    ? $data['catatan']
                    : null,
                'created_by' => $user_id
            ]);
            $detail_id =
                $this->pdo->lastInsertId();

            /**
             * Update stok.
             */
            $stmt = $this->pdo->prepare("
                UPDATE t_bank_benih
                SET
                    jumlah_stok = :jumlah_stok,
                    stok_terpakai = :stok_terpakai,
                    updated_by = :updated_by
                WHERE id = :id
            ");
            $stmt->execute([
                'jumlah_stok' => $stok_sesudah,
                'stok_terpakai' => $stok_terpakai_sesudah,
                'updated_by' => $user_id,
                'id' => $id_bank_benih
            ]);

            /**
             * Catat mutasi.
             */
            $mutation_id = $this->insertMutation([
                'id_bank_benih' => $id_bank_benih,
                'tipe_mutasi' => 'KELUAR',
                'arah_koreksi' => null,
                'jumlah' => $jumlah_ditanam,
                'stok_sebelum' => $stok_sebelum,
                'stok_sesudah' => $stok_sesudah,
                'satuan' => $satuan,
                'sumber' => 'DETAIL_MONITORING',
                'id_referensi' => $detail_id,
                'alasan' =>
                    'Penanaman melalui Detail Monitoring Penanaman.',
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
     * UPDATE DETAIL + KOREKSI STOK.
     *
     * Bank Benih dan Monitoring TIDAK BOLEH DIGANTI.
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

            /**
             * Monitoring tidak boleh dipindahkan.
             */
            if (
                isset($data['id_monitoring'])
                && (int) $data['id_monitoring']
                !== (int) $old['id_monitoring']
            ) {
                throw new Exception(
                    'Monitoring Penanaman tidak dapat diganti pada Edit Detail Monitoring.'
                );
            }

            /**
             * Bank Benih tidak boleh dipindahkan.
             */
            if (
                (int) $data['id_bank_benih']
                !== (int) $old['id_bank_benih']
            ) {
                throw new Exception(
                    'Bank Benih tidak dapat diganti pada Edit Detail Monitoring. Silakan buat Detail Monitoring baru.'
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
            $luas_tanam_ha =
                (float) $data['luas_tanam_ha'];
            if ($luas_tanam_ha <= 0) {
                throw new Exception(
                    'Luas tanam harus lebih dari 0.'
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
                    stok_terpakai,
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

            /**
             * Validasi jumlah hidup/mati.
             */
            $jumlah_hidup =
                $data['jumlah_hidup'] !== ''
                ? (float) $data['jumlah_hidup']
                : null;
            $jumlah_mati =
                $data['jumlah_mati'] !== ''
                ? (float) $data['jumlah_mati']
                : null;
            if (
                $jumlah_hidup !== null
                && $jumlah_hidup > $jumlah_baru
            ) {
                throw new Exception(
                    'Jumlah hidup tidak boleh lebih besar dari jumlah ditanam.'
                );
            }
            if (
                $jumlah_hidup !== null
                && $jumlah_mati !== null
                && ($jumlah_hidup + $jumlah_mati)
                > $jumlah_baru
            ) {
                throw new Exception(
                    'Jumlah hidup + jumlah mati tidak boleh lebih besar dari jumlah ditanam.'
                );
            }

            /**
             * Hitung survival rate.
             */
            $survival_rate = null;
            if ($jumlah_hidup !== null) {
                $survival_rate =
                    ($jumlah_hidup / $jumlah_baru) * 100;
                $survival_rate =
                    min(100, max(0, $survival_rate));
            }

            /**
             * Delta stok.
             *
             * jumlah baru > jumlah lama
             * = stok keluar
             *
             * jumlah baru < jumlah lama
             * = stok kembali
             */
            $delta =
                $jumlah_baru - $jumlah_lama;
            $stok_sebelum =
                (float) $bank['jumlah_stok'];
            $stok_sesudah =
                $stok_sebelum;
            $stok_terpakai_sebelum =
                (float) $bank['stok_terpakai'];
            $stok_terpakai_sesudah =
                $stok_terpakai_sebelum;
            if ($delta > 0) {
                if ($delta > $stok_sebelum) {
                    throw new Exception(
                        'Stok tidak mencukupi untuk penambahan jumlah tanam.'
                    );
                }
                $stok_sesudah =
                    $stok_sebelum - $delta;
                $stok_terpakai_sesudah =
                    $stok_terpakai_sebelum + $delta;
            } elseif ($delta < 0) {
                $stok_sesudah =
                    $stok_sebelum + abs($delta);
                $stok_terpakai_sesudah =
                    max(
                        0,
                        $stok_terpakai_sebelum
                        - abs($delta)
                    );
            }

            /**
             * Update detail.
             */
            $stmt = $this->pdo->prepare("
                UPDATE t_detail_monitoring_penanaman
                SET
                    id_tipe_penanaman = :id_tipe_penanaman,
                    jumlah_ditanam = :jumlah_ditanam,
                    satuan = :satuan,
                    jumlah_hidup = :jumlah_hidup,
                    jumlah_mati = :jumlah_mati,
                    survival_rate_persen = :survival_rate_persen,
                    tinggi_rata2_cm = :tinggi_rata2_cm,
                    diameter_rata2_cm = :diameter_rata2_cm,
                    luas_tanam_ha = :luas_tanam_ha,
                    catatan = :catatan,
                    updated_by = :updated_by
                WHERE id = :id
                AND deleted_at IS NULL
            ");

            $stmt->execute([
                'id_tipe_penanaman' =>
                    $data['id_tipe_penanaman'],
                'jumlah_ditanam' =>
                    $jumlah_baru,
                'satuan' =>
                    $data['satuan'],
                'jumlah_hidup' =>
                    $jumlah_hidup,
                'jumlah_mati' =>
                    $jumlah_mati,
                'survival_rate_persen' =>
                    $survival_rate,
                'tinggi_rata2_cm' =>
                    $data['tinggi_rata2_cm'] !== ''
                    ? $data['tinggi_rata2_cm']
                    : null,
                'diameter_rata2_cm' =>
                    $data['diameter_rata2_cm'] !== ''
                    ? $data['diameter_rata2_cm']
                    : null,
                'luas_tanam_ha' =>
                    $luas_tanam_ha,
                'catatan' =>
                    $data['catatan'] !== ''
                    ? $data['catatan']
                    : null,
                'updated_by' =>
                    $user_id,
                'id' =>
                    $id
            ]);

            /**
             * Update stok jika jumlah berubah.
             */
            if ($delta != 0) {
                $stmt = $this->pdo->prepare("
                    UPDATE t_bank_benih
                    SET
                        jumlah_stok = :jumlah_stok,
                        stok_terpakai = :stok_terpakai,
                        updated_by = :updated_by

                    WHERE id = :id
                ");
                $stmt->execute([
                    'jumlah_stok' =>
                        $stok_sesudah,
                    'stok_terpakai' =>
                        $stok_terpakai_sesudah,
                    'updated_by' =>
                        $user_id,
                    'id' =>
                        $old['id_bank_benih']
                ]);
                if ($delta > 0) {
                    /**
                     * Penambahan jumlah tanam
                     * = stok keluar.
                     */
                    $this->insertMutation([
                        'id_bank_benih' =>
                            $old['id_bank_benih'],
                        'tipe_mutasi' =>
                            'KELUAR',
                        'arah_koreksi' =>
                            null,
                        'jumlah' =>
                            abs($delta),
                        'stok_sebelum' =>
                            $stok_sebelum,
                        'stok_sesudah' =>
                            $stok_sesudah,
                        'satuan' =>
                            $bank['satuan_stok'],
                        'sumber' =>
                            'DETAIL_MONITORING_EDIT',
                        'id_referensi' =>
                            $id,
                        'alasan' =>
                            'Penambahan jumlah ditanam pada edit Detail Monitoring.',
                        'created_by' =>
                            $user_id
                    ]);
                } else {
                    /**
                     * Pengurangan jumlah tanam
                     * = stok kembali.
                     */
                    $this->insertMutation([
                        'id_bank_benih' =>
                            $old['id_bank_benih'],
                        'tipe_mutasi' =>
                            'KOREKSI',
                        'arah_koreksi' =>
                            'TAMBAH',
                        'jumlah' =>
                            abs($delta),
                        'stok_sebelum' =>
                            $stok_sebelum,
                        'stok_sesudah' =>
                            $stok_sesudah,
                        'satuan' =>
                            $bank['satuan_stok'],
                        'sumber' =>
                            'DETAIL_MONITORING_EDIT',
                        'id_referensi' =>
                            $id,
                        'alasan' =>
                            'Pengembalian stok karena pengurangan jumlah ditanam pada edit Detail Monitoring.',
                        'created_by' =>
                            $user_id
                    ]);
                }
            }

            $this->pdo->commit();
            return [
                'stok_sebelum' =>
                    $stok_sebelum,
                'stok_sesudah' =>
                    $stok_sesudah,
                'delta' =>
                    $delta
            ];
        } catch (Throwable $e) {
            if ($this->pdo->inTransaction()) {
                $this->pdo->rollBack();
            }
            throw $e;
        }
    }

    /**
     * DELETE DETAIL + KEMBALIKAN STOK.
     */
    public function deleteWithStock(
        $id,
        $user_id
    ) {
        $this->pdo->beginTransaction();
        try {
            /**
             * Lock Detail.
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
             * Lock Bank.
             */
            $stmt = $this->pdo->prepare("
                SELECT
                    id,
                    satuan_stok,
                    jumlah_stok,
                    stok_terpakai
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
            $stok_terpakai_sesudah =
                max(
                    0,
                    (float) $bank['stok_terpakai']
                    - $jumlah
                );

            /**
             * Kembalikan stok.
             */
            $stmt = $this->pdo->prepare("
                UPDATE t_bank_benih
                SET
                    jumlah_stok = :jumlah_stok,
                    stok_terpakai = :stok_terpakai,
                    updated_by = :updated_by
                WHERE id = :id
            ");

            $stmt->execute([
                'jumlah_stok' =>
                    $stok_sesudah,
                'stok_terpakai' =>
                    $stok_terpakai_sesudah,
                'updated_by' =>
                    $user_id,
                'id' =>
                    $detail['id_bank_benih']
            ]);

            /**
             * Catat mutasi.
             */
            $this->insertMutation([
                'id_bank_benih' =>
                    $detail['id_bank_benih'],
                'tipe_mutasi' =>
                    'KOREKSI',
                'arah_koreksi' =>
                    'TAMBAH',
                'jumlah' =>
                    $jumlah,
                'stok_sebelum' =>
                    $stok_sebelum,
                'stok_sesudah' =>
                    $stok_sesudah,
                'satuan' =>
                    $detail['satuan'],
                'sumber' =>
                    'DETAIL_MONITORING_DELETE',
                'id_referensi' =>
                    $id,
                'alasan' =>
                    'Pengembalian stok karena Detail Monitoring dihapus.',
                'created_by' =>
                    $user_id
            ]);

            /**
             * Soft delete Detail.
             */
            $stmt = $this->pdo->prepare("
                UPDATE t_detail_monitoring_penanaman
                SET
                    deleted_at = NOW(),
                    deleted_by = :deleted_by
                WHERE id = :id
                AND deleted_at IS NULL
            ");

            $stmt->execute([
                'deleted_by' =>
                    $user_id,
                'id' =>
                    $id
            ]);
            $this->pdo->commit();
            return [
                'stok_sebelum' =>
                    $stok_sebelum,
                'stok_sesudah' =>
                    $stok_sesudah
            ];
        } catch (Throwable $e) {
            if ($this->pdo->inTransaction()) {
                $this->pdo->rollBack();
            }
            throw $e;
        }
    }


    /**
     * Cek apakah Monitoring merupakan Monitoring Turunan.
     *
     * Monitoring Turunan memiliki id_turunan yang tidak NULL.
     */
    public function isMonitoringTurunan($id_monitoring)
    {
        $sql = "
        SELECT id_turunan
        FROM t_monitoring_penanaman
        WHERE id = :id
        AND deleted_at IS NULL
        LIMIT 1
    ";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            'id' => $id_monitoring
        ]);

        $id_turunan = $stmt->fetchColumn();
        return $id_turunan !== false
            && $id_turunan !== null
            && (int) $id_turunan > 0;
    }

    /**
     * UPDATE DETAIL MONITORING TURUNAN
     *
     * Monitoring Turunan bukan transaksi pengambilan benih baru.
     *
     * Field yang TIDAK BOLEH berubah:
     * - id_monitoring
     * - id_bank_benih
     * - id_tipe_penanaman
     * - jumlah_ditanam
     * - satuan
     * - luas_tanam_ha
     * - catatan
     *
     * Field yang BOLEH berubah:
     * - jumlah_hidup
     * - jumlah_mati
     * - tinggi_rata2_cm
     * - diameter_rata2_cm
     *
     * Tidak ada perubahan stok Bank Benih.
     */
    public function updateTurunan(
        $id,
        $data,
        $user_id
    ) {
        $this->pdo->beginTransaction();
        try {
            /**
             * Lock Detail.
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

            /**
             * Pastikan Monitoring Detail masih sama.
             */
            $id_monitoring_lama =
                (int) $old['id_monitoring'];

            /**
             * Pastikan Monitoring merupakan Turunan.
             */
            $stmt = $this->pdo->prepare("
                SELECT
                    id,
                    id_turunan
                FROM t_monitoring_penanaman
                WHERE id = :id
                AND deleted_at IS NULL
                LIMIT 1
            ");

            $stmt->execute([
                'id' => $id_monitoring_lama
            ]);
            $monitoring = $stmt->fetch(PDO::FETCH_ASSOC);
            if (!$monitoring) {
                throw new Exception(
                    'Monitoring Penanaman tidak ditemukan.'
                );
            }
            if (
                $monitoring['id_turunan'] === null
                || (int) $monitoring['id_turunan'] <= 0
            ) {
                throw new Exception(
                    'Detail ini bukan merupakan Detail Monitoring Turunan.'
                );
            }

            /**
             * Jangan pernah menerima perubahan
             * field yang seharusnya immutable.
             *
             * Kita menggunakan nilai lama dari database.
             */
            $jumlah_ditanam =
                (float) $old['jumlah_ditanam'];

            /**
             * Ambil nilai baru yang memang boleh diubah.
             */
            $jumlah_hidup =
                isset($data['jumlah_hidup'])
                && $data['jumlah_hidup'] !== ''
                ? (float) $data['jumlah_hidup']
                : null;
            $jumlah_mati =
                isset($data['jumlah_mati'])
                && $data['jumlah_mati'] !== ''
                ? (float) $data['jumlah_mati']
                : null;
            $tinggi_rata2_cm =
                isset($data['tinggi_rata2_cm'])
                && $data['tinggi_rata2_cm'] !== ''
                ? $data['tinggi_rata2_cm']
                : null;
            $diameter_rata2_cm =
                isset($data['diameter_rata2_cm'])
                && $data['diameter_rata2_cm'] !== ''
                ? $data['diameter_rata2_cm']
                : null;

            /**
             * Validasi jumlah hidup.
             */
            if (
                $jumlah_hidup !== null
                && $jumlah_hidup > $jumlah_ditanam
            ) {
                throw new Exception(
                    'Jumlah hidup tidak boleh lebih besar dari jumlah ditanam.'
                );
            }

            /**
             * Validasi jumlah mati.
             */
            if (
                $jumlah_mati !== null
                && $jumlah_mati > $jumlah_ditanam
            ) {
                throw new Exception(
                    'Jumlah mati tidak boleh lebih besar dari jumlah ditanam.'
                );
            }

            /**
             * Validasi hidup + mati.
             */
            if (
                $jumlah_hidup !== null
                && $jumlah_mati !== null
                && (
                    $jumlah_hidup
                    + $jumlah_mati
                    > $jumlah_ditanam
                )
            ) {
                throw new Exception(
                    'Jumlah hidup + jumlah mati tidak boleh lebih besar dari jumlah ditanam.'
                );
            }

            /**
             * Hitung ulang Survival Rate.
             */
            $survival_rate = null;
            if ($jumlah_hidup !== null) {
                $survival_rate =
                    ($jumlah_hidup / $jumlah_ditanam) * 100;
                $survival_rate =
                    min(
                        100,
                        max(
                            0,
                            $survival_rate
                        )
                    );
            }

            /**
             * UPDATE HANYA FIELD YANG DIPERBOLEHKAN.
             *
             * Field seperti:
             * id_bank_benih
             * id_tipe_penanaman
             * jumlah_ditanam
             * satuan
             * luas_tanam_ha
             * catatan
             *
             * sengaja TIDAK dimasukkan.
             */
            $stmt = $this->pdo->prepare("
            UPDATE t_detail_monitoring_penanaman
            SET
                jumlah_hidup = :jumlah_hidup,
                jumlah_mati = :jumlah_mati,
                survival_rate_persen = :survival_rate_persen,
                tinggi_rata2_cm = :tinggi_rata2_cm,
                diameter_rata2_cm = :diameter_rata2_cm,
                updated_by = :updated_by
            WHERE id = :id
            AND deleted_at IS NULL
        ");

            $stmt->execute([
                'jumlah_hidup' =>
                    $jumlah_hidup,
                'jumlah_mati' =>
                    $jumlah_mati,
                'survival_rate_persen' =>
                    $survival_rate,
                'tinggi_rata2_cm' =>
                    $tinggi_rata2_cm,
                'diameter_rata2_cm' =>
                    $diameter_rata2_cm,
                'updated_by' =>
                    $user_id,
                'id' =>
                    $id
            ]);
            $this->pdo->commit();

            return [
                'id' => $id,
                'id_monitoring' =>
                    $id_monitoring_lama,
                'jumlah_ditanam' =>
                    $jumlah_ditanam,
                'jumlah_hidup' =>
                    $jumlah_hidup,
                'jumlah_mati' =>
                    $jumlah_mati,
                'survival_rate_persen' =>
                    $survival_rate,
                'tinggi_rata2_cm' =>
                    $tinggi_rata2_cm,
                'diameter_rata2_cm' =>
                    $diameter_rata2_cm
            ];
        } catch (Throwable $e) {
            if ($this->pdo->inTransaction()) {
                $this->pdo->rollBack();
            }
            throw $e;
        }
    }
}
?>