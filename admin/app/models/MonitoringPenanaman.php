<?php

class MonitoringPenanaman
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * Ambil semua Monitoring Penanaman.
     *
     * Monitoring sekarang hanya merupakan HEADER.
     * Data Bank Benih, Tipe Penanaman, Luas dan Survival Rate
     * berada di t_detail_monitoring_penanaman.
     *
     * Jika $id_bank_benih diberikan, hanya monitoring
     * yang pernah menggunakan Bank Benih tersebut yang ditampilkan.
     */
    public function getAll($id_bank_benih = null)
    {
        $params = [];

        $sql = "
            SELECT
                mp.id,
                mp.kode_monitoring,
                mp.id_tanah,
                mp.id_progress_status_monitoring,
                mp.periode_pengecekan,
                mp.tanggal_tanam,
                mp.tanggal_monitoring,
                mp.catatan,
                mp.is_active,

                tt.nama_lahan,

                mps.nama AS nama_status_monitoring,
                mp_awal.kode_monitoring AS kode_monitoring_awal,

                COUNT(DISTINCT dm.id) AS jumlah_detail,

                COALESCE(
                    SUM(dm.luas_tanam_ha),
                    0
                ) AS total_luas_tanam_ha

            FROM t_monitoring_penanaman mp

            LEFT JOIN t_tanah tt
                ON tt.id = mp.id_tanah

            LEFT JOIN m_progress_status_monitoring mps
                ON mps.id = mp.id_progress_status_monitoring

            LEFT JOIN t_monitoring_penanaman mp_awal
                ON mp_awal.id = mp.id_turunan
                AND mp_awal.deleted_at IS NULL

            LEFT JOIN t_detail_monitoring_penanaman dm
                ON dm.id_monitoring = mp.id
                AND dm.deleted_at IS NULL

            WHERE mp.deleted_at IS NULL
        ";

        if ($id_bank_benih !== null) {
            $sql .= "
                AND EXISTS (
                    SELECT 1
                    FROM t_detail_monitoring_penanaman dm_filter
                    WHERE dm_filter.id_monitoring = mp.id
                    AND dm_filter.id_bank_benih = :id_bank_benih
                    AND dm_filter.deleted_at IS NULL
                )
            ";

            $params['id_bank_benih'] = $id_bank_benih;
        }

        $sql .= "
            GROUP BY
                mp.id,
                mp.kode_monitoring,
                mp.id_turunan,
                mp.id_tanah,
                mp.id_progress_status_monitoring,
                mp.periode_pengecekan,
                mp.tanggal_tanam,
                mp.tanggal_monitoring,
                mp.catatan,
                tt.nama_lahan,
                mps.nama,
                mp_awal.kode_monitoring

            ORDER BY mp.id DESC
        ";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Ambil satu Monitoring Penanaman.
     */
    public function findById($id)
    {
        $sql = "
            SELECT
                mp.id,
                mp.kode_monitoring,
                mp.id_turunan,
                mp.id_tanah,
                mp.id_progress_status_monitoring,
                mp.periode_pengecekan,
                mp.tanggal_tanam,
                mp.tanggal_monitoring,
                mp.catatan,
                mp.is_active,
                tt.nama_lahan,
                mps.nama AS nama_status_monitoring,
                mp_awal.kode_monitoring AS kode_monitoring_awal
            FROM t_monitoring_penanaman mp
            LEFT JOIN t_tanah tt
                ON tt.id = mp.id_tanah
            LEFT JOIN m_progress_status_monitoring mps
                ON mps.id = mp.id_progress_status_monitoring
            LEFT JOIN t_monitoring_penanaman mp_awal
                ON mp_awal.id = mp.id_turunan
                AND mp_awal.deleted_at IS NULL
            WHERE mp.id = :id
            AND mp.deleted_at IS NULL
            LIMIT 1
        ";

        $stmt = $this->pdo->prepare($sql);

        $stmt->execute([
            'id' => $id
        ]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Generate kode:
     * KOM001
     * KOM002
     * KOM003
     */
    public function generateKodeMonitoring()
    {
        $sql = "
            SELECT kode_monitoring
            FROM t_monitoring_penanaman
            WHERE kode_monitoring REGEXP '^KOM[0-9]+$'
            ORDER BY id DESC
            LIMIT 1
        ";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();

        $last = $stmt->fetchColumn();

        if (!$last) {
            return 'KOM001';
        }

        $number = (int) substr($last, 3);

        return 'KOM' . str_pad(
            $number + 1,
            3,
            '0',
            STR_PAD_LEFT
        );
    }

    /**
     * Tambah Monitoring Penanaman.
     *
     * Hanya menyimpan data HEADER.
     */
    public function create($data)
    {
        $sql = "
            INSERT INTO t_monitoring_penanaman
            (
                kode_monitoring,
                id_turunan,
                id_tanah,
                id_progress_status_monitoring,
                periode_pengecekan,
                tanggal_tanam,
                tanggal_monitoring,
                catatan,
                is_active,
                created_by
            )
            VALUES
            (
                :kode_monitoring,
                :id_turunan,
                :id_tanah,
                :id_progress_status_monitoring,
                :periode_pengecekan,
                :tanggal_tanam,
                :tanggal_monitoring,
                :catatan,
                :is_active,
                :created_by
            )
        ";

        $stmt = $this->pdo->prepare($sql);

        if ($stmt->execute($data)) {
            return $this->pdo->lastInsertId();
        }

        return false;
    }

    /**
     * Update Monitoring Penanaman.
     *
     * Hanya field HEADER yang diubah.
     */
    public function update($id, $data)
    {
        $sql = "
            UPDATE t_monitoring_penanaman
            SET
                kode_monitoring = :kode_monitoring,
                id_tanah = :id_tanah,
                periode_pengecekan = :periode_pengecekan,
                tanggal_tanam = :tanggal_tanam,
                tanggal_monitoring = :tanggal_monitoring,
                id_progress_status_monitoring = :id_progress_status_monitoring,
                catatan = :catatan,
                id_turunan = :id_turunan,
                is_active = :is_active,
                updated_by = :updated_by

            WHERE id = :id
            AND deleted_at IS NULL
        ";

        $data['id'] = $id;

        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute($data);
    }

    /**
     * Cek apakah Monitoring masih mempunyai Detail aktif.
     */
    public function hasActiveDetail($id)
    {
        $sql = "
            SELECT id
            FROM t_detail_monitoring_penanaman
            WHERE id_monitoring = :id
            AND deleted_at IS NULL
            LIMIT 1
        ";

        $stmt = $this->pdo->prepare($sql);

        $stmt->execute([
            'id' => $id
        ]);

        return (bool) $stmt->fetchColumn();
    }

    /**
     * Soft delete Monitoring.
     *
     * Monitoring tidak boleh dihapus jika masih
     * mempunyai Detail Monitoring aktif.
     */
    public function softDelete($id, $user_id)
    {
        if ($this->hasActiveDetail($id)) {
            throw new Exception(
                'Monitoring Penanaman tidak dapat dihapus karena masih memiliki Detail Monitoring Penanaman aktif.'
            );
        }

        $sql = "
            UPDATE t_monitoring_penanaman
            SET
                deleted_at = NOW(),
                deleted_by = :deleted_by

            WHERE id = :id
            AND deleted_at IS NULL
        ";

        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([
            'id' => $id,
            'deleted_by' => $user_id
        ]);
    }

    /**
     * Data tanah.
     */
    public function getTanah()
    {
        $sql = "
            SELECT
                id,
                nama_lahan
            FROM t_tanah
            WHERE deleted_at IS NULL
            ORDER BY nama_lahan ASC
        ";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Status Monitoring.
     */
    public function getStatusMonitoring()
    {
        $sql = "
            SELECT
                id,
                nama
            FROM m_progress_status_monitoring
            WHERE is_active = 1
            ORDER BY id ASC
        ";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getMonitoringAwal()
    {
        $sql = "
        SELECT
            mp.id,
            mp.kode_monitoring,
            mp.id_tanah,
            mp.tanggal_tanam,
            tt.nama_lahan

        FROM t_monitoring_penanaman mp

        LEFT JOIN t_tanah tt
            ON tt.id = mp.id_tanah

        INNER JOIN m_progress_status_monitoring mps
            ON mps.id = mp.id_progress_status_monitoring

        WHERE mp.deleted_at IS NULL
        AND mp.is_active = 1
        AND mps.nama = 'Baru Ditanam'

        ORDER BY mp.id DESC
    ";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findMonitoringAwal($id)
    {
        $sql = "
        SELECT
            mp.id,
            mp.kode_monitoring,
            mp.id_tanah,
            mp.tanggal_tanam,
            mp.id_progress_status_monitoring,
            mps.nama AS nama_status_monitoring

        FROM t_monitoring_penanaman mp

        INNER JOIN m_progress_status_monitoring mps
            ON mps.id = mp.id_progress_status_monitoring

        WHERE mp.id = :id
        AND mp.deleted_at IS NULL
        AND mp.is_active = 1
        AND mps.nama = 'Baru Ditanam'

        LIMIT 1
    ";

        $stmt = $this->pdo->prepare($sql);

        $stmt->execute([
            'id' => $id
        ]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function createWithInheritedDetails(
        $data,
        $userId
    ) {
        try {

            $this->pdo->beginTransaction();

            /*
             * 1. Buat Monitoring Penanaman
             */
            $monitoringSql = "
            INSERT INTO t_monitoring_penanaman
            (
                kode_monitoring,
                id_turunan,
                id_tanah,
                id_progress_status_monitoring,
                periode_pengecekan,
                tanggal_tanam,
                tanggal_monitoring,
                catatan,
                is_active,
                created_by
            )
            VALUES
            (
                :kode_monitoring,
                :id_turunan,
                :id_tanah,
                :id_progress_status_monitoring,
                :periode_pengecekan,
                :tanggal_tanam,
                :tanggal_monitoring,
                :catatan,
                :is_active,
                :created_by
            )
        ";

            $stmt = $this->pdo->prepare($monitoringSql);

            $stmt->execute([
                'kode_monitoring' =>
                    $data['kode_monitoring'],

                'id_turunan' =>
                    $data['id_turunan'],

                'id_tanah' =>
                    $data['id_tanah'],

                'id_progress_status_monitoring' =>
                    $data['id_progress_status_monitoring'],

                'periode_pengecekan' =>
                    $data['periode_pengecekan'],

                'tanggal_tanam' =>
                    $data['tanggal_tanam'],

                'tanggal_monitoring' =>
                    $data['tanggal_monitoring'],

                'catatan' =>
                    $data['catatan'],

                'is_active' =>
                    $data['is_active'],

                'created_by' =>
                    $userId
            ]);

            $newMonitoringId =
                $this->pdo->lastInsertId();


            /*
             * 2. Jika mempunyai Monitoring Awal,
             *    copy seluruh detail.
             */
            if (
                !empty($data['id_turunan'])
                && (int) $data['id_turunan'] > 0
            ) {

                $sourceMonitoringId =
                    (int) $data['id_turunan'];


                /*
                 * Ambil detail Monitoring Awal
                 */
                $detailSql = "
                SELECT
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
                    catatan

                FROM t_detail_monitoring_penanaman

                WHERE id_monitoring = :id_monitoring

                AND deleted_at IS NULL

                ORDER BY id ASC
            ";

                $detailStmt =
                    $this->pdo->prepare($detailSql);

                $detailStmt->execute([
                    'id_monitoring' =>
                        $sourceMonitoringId
                ]);

                $details =
                    $detailStmt->fetchAll(
                        PDO::FETCH_ASSOC
                    );


                /*
                 * 3. Insert detail hasil copy
                 */
                if ($details) {

                    $insertDetailSql = "
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
                ";

                    $insertDetailStmt =
                        $this->pdo->prepare(
                            $insertDetailSql
                        );


                    foreach ($details as $detail) {

                        $insertDetailStmt->execute([

                            'id_monitoring' =>
                                $newMonitoringId,

                            'id_bank_benih' =>
                                $detail['id_bank_benih'],

                            'id_tipe_penanaman' =>
                                $detail['id_tipe_penanaman'],

                            'jumlah_ditanam' =>
                                $detail['jumlah_ditanam'],

                            'satuan' =>
                                $detail['satuan'],

                            'jumlah_hidup' =>
                                $detail['jumlah_hidup'],

                            'jumlah_mati' =>
                                $detail['jumlah_mati'],

                            'survival_rate_persen' =>
                                $detail['survival_rate_persen'],

                            'tinggi_rata2_cm' =>
                                $detail['tinggi_rata2_cm'],

                            'diameter_rata2_cm' =>
                                $detail['diameter_rata2_cm'],

                            'luas_tanam_ha' =>
                                $detail['luas_tanam_ha'],

                            'catatan' =>
                                $detail['catatan'],

                            'created_by' =>
                                $userId
                        ]);
                    }
                }
            }


            /*
             * 4. Commit
             */
            $this->pdo->commit();

            return $newMonitoringId;

        } catch (Exception $e) {

            /*
             * Jika gagal, rollback semuanya.
             */
            if ($this->pdo->inTransaction()) {
                $this->pdo->rollBack();
            }

            throw $e;
        }
    }

    /**
     * Data Bank Benih sebagai konteks.
     *
     * Digunakan ketika Monitoring dibuka dari halaman
     * Detail Bank Benih.
     */
    // public function getBankBenih($id)
    // {
    //     $sql = "
    //         SELECT
    //             id,
    //             nomor_aksesi,
    //             nama_lokal,
    //             nama_ilmiah,
    //             satuan_stok,
    //             jumlah_stok,
    //             stok_terpakai

    //         FROM t_bank_benih

    //         WHERE id = :id
    //         AND deleted_at IS NULL

    //         LIMIT 1
    //     ";

    //     $stmt = $this->pdo->prepare($sql);

    //     $stmt->execute([
    //         'id' => $id
    //     ]);

    //     return $stmt->fetch(PDO::FETCH_ASSOC);
    // }
}
?>