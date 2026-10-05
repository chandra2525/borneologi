<?php

class MonitoringPenanaman
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * Ambil semua monitoring.
     *
     * Jika id_bank_benih diberikan, hanya monitoring
     * yang pernah menggunakan bank benih tersebut
     * melalui t_detail_monitoring_penanaman yang ditampilkan.
     */
    public function getAll($id_bank_benih = null)
    {
        $params = [];

        $sql = "
            SELECT
                mp.id,
                mp.kode_monitoring,
                mp.id_tanah,
                mp.id_tipe_penanaman,
                mp.periode_pengecekan,
                mp.tanggal_tanam,
                mp.tanggal_monitoring,
                mp.luas_tanam_ha,
                mp.id_progress_status_monitoring,
                mp.catatan,

                tt.nama_lahan,

                mtp.nama,

                mps.nama AS nama_status_monitoring,

                COUNT(DISTINCT dm.id) AS jumlah_detail

            FROM t_monitoring_penanaman mp

            LEFT JOIN t_tanah tt
                ON tt.id = mp.id_tanah

            LEFT JOIN m_tipe_penanaman mtp
                ON mtp.id = mp.id_tipe_penanaman

            LEFT JOIN m_progress_status_monitoring mps
                ON mps.id = mp.id_progress_status_monitoring

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
                mp.id_tanah,
                mp.id_tipe_penanaman,
                mp.periode_pengecekan,
                mp.tanggal_tanam,
                mp.tanggal_monitoring,
                mp.luas_tanam_ha,
                mp.id_progress_status_monitoring,
                mp.catatan,
                tt.nama_lahan,
                mtp.nama,
                mps.nama

            ORDER BY mp.id DESC
        ";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Ambil satu monitoring.
     */
    public function findById($id)
    {
        $sql = "
            SELECT
                mp.*,

                tt.nama_lahan,

                mtp.nama,

                mps.nama AS nama_status_monitoring

            FROM t_monitoring_penanaman mp

            LEFT JOIN t_tanah tt
                ON tt.id = mp.id_tanah

            LEFT JOIN m_tipe_penanaman mtp
                ON mtp.id = mp.id_tipe_penanaman

            LEFT JOIN m_progress_status_monitoring mps
                ON mps.id = mp.id_progress_status_monitoring

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
     * Generate kode KOM001, KOM002, dst.
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
     * Tambah monitoring.
     */
    public function create($data)
    {
        $sql = "
            INSERT INTO t_monitoring_penanaman
            (
                kode_monitoring,
                id_tanah,
                id_tipe_penanaman,
                periode_pengecekan,
                tanggal_tanam,
                tanggal_monitoring,
                luas_tanam_ha,
                id_progress_status_monitoring,
                catatan,
                created_by
            )
            VALUES
            (
                :kode_monitoring,
                :id_tanah,
                :id_tipe_penanaman,
                :periode_pengecekan,
                :tanggal_tanam,
                :tanggal_monitoring,
                :luas_tanam_ha,
                :id_progress_status_monitoring,
                :catatan,
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
     * Update monitoring.
     */
    public function update($id, $data)
    {
        $sql = "
            UPDATE t_monitoring_penanaman

            SET
                id_tanah = :id_tanah,
                id_tipe_penanaman = :id_tipe_penanaman,
                periode_pengecekan = :periode_pengecekan,
                tanggal_tanam = :tanggal_tanam,
                tanggal_monitoring = :tanggal_monitoring,
                luas_tanam_ha = :luas_tanam_ha,
                id_progress_status_monitoring = :id_progress_status_monitoring,
                catatan = :catatan,
                updated_by = :updated_by

            WHERE id = :id
            AND deleted_at IS NULL
        ";

        $data['id'] = $id;

        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute($data);
    }

    /**
     * Soft delete.
     */
    public function softDelete($id, $user_id)
    {
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
     * Tipe penanaman.
     */
    public function getTipePenanaman()
    {
        $sql = "
            SELECT
                id,
                nama
            FROM m_tipe_penanaman
            WHERE is_active = 1
            ORDER BY nama ASC
        ";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Status monitoring.
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

    /**
     * Data Bank Benih sebagai konteks halaman.
     */
    public function getBankBenih($id)
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

            WHERE id = :id
            AND deleted_at IS NULL

            LIMIT 1
        ";

        $stmt = $this->pdo->prepare($sql);

        $stmt->execute([
            'id' => $id
        ]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}