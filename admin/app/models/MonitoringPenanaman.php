<?php

class MonitoringPenanaman
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function getAll()
    {
        $sql = "SELECT
                mp.id,
                mp.kode_monitoring,
                mp.id_tanah,
                mp.id_tipe_penanaman,
                mp.id_progress_status_monitoring,
                mp.periode_pengecekan,
                mp.tanggal_tanam,
                mp.tanggal_monitoring,
                mp.luas_tanam_ha,
                mp.survival_rate_persen,
                mp.catatan,
                mp.is_active,
                ta.nama_lahan,
                tp.nama as nama_tipe_penanaman,
                ps.nama as nama_progress_status_monitoring
            FROM t_monitoring_penanaman mp
            LEFT JOIN t_tanah ta ON ta.id = mp.id_tanah
            LEFT JOIN m_tipe_penanaman tp ON tp.id = mp.id_tipe_penanaman
            LEFT JOIN m_progress_status_monitoring ps ON ps.id = mp.id_progress_status_monitoring
            WHERE mp.deleted_at IS NULL
            ORDER BY mp.id ASC";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function findById($id)
    {
        $sql = "SELECT *
            FROM t_monitoring_penanaman
            WHERE id = :id
            AND deleted_at IS NULL
            LIMIT 1";

        $stmt = $this->pdo->prepare($sql);

        $stmt->execute([
            "id" => $id
        ]);

        return $stmt->fetch();
    }

    public function create($data)
    {

        $sql = "INSERT INTO t_monitoring_penanaman
            (kode_monitoring,id_tanah,id_tipe_penanaman,id_progress_status_monitoring,periode_pengecekan,tanggal_tanam,tanggal_monitoring,luas_tanam_ha,survival_rate_persen,catatan,is_active,created_by)
            VALUES
            (:kode_monitoring,:id_tanah,:id_tipe_penanaman,:id_progress_status_monitoring,:periode_pengecekan,:tanggal_tanam,:tanggal_monitoring,:luas_tanam_ha,:survival_rate_persen,:catatan,:is_active,:created_by)";

        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute($data);

    }

    public function update($id, $data)
    {
        $sql = "UPDATE t_monitoring_penanaman
            SET
            kode_monitoring=:kode_monitoring,
            id_tanah=:id_tanah,
            id_tipe_penanaman=:id_tipe_penanaman,
            id_progress_status_monitoring=:id_progress_status_monitoring,
            periode_pengecekan=:periode_pengecekan,
            tanggal_tanam=:tanggal_tanam,
            tanggal_monitoring=:tanggal_monitoring,
            luas_tanam_ha=:luas_tanam_ha,
            survival_rate_persen=:survival_rate_persen,
            catatan=:catatan,
            is_active=:is_active,
            updated_by=:updated_by
            WHERE id=:id";

        $stmt = $this->pdo->prepare($sql);

        $data["id"] = $id;

        return $stmt->execute($data);
    }

    public function softDelete($id, $user_id)
    {
        $sql = "UPDATE t_monitoring_penanaman
            SET deleted_at = NOW(),
            deleted_by = :user
            WHERE id = :id";

        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([
            "id" => $id,
            "user" => $user_id
        ]);
    }

    public function getTanah()
    {
        $sql = "SELECT id,nama_lahan FROM t_tanah
                WHERE deleted_at IS NULL
                ORDER BY id";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function getProgressStatusMonitoring()
    {
        $sql = "SELECT id,nama FROM m_progress_status_monitoring
                WHERE deleted_at IS NULL
                ORDER BY id";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function getTipePenanaman()
    {
        $sql = "SELECT id,nama FROM m_tipe_penanaman
                WHERE deleted_at IS NULL
                ORDER BY id";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function generateKode()
    {
        $sql = "SELECT kode_monitoring 
            FROM t_monitoring_penanaman 
            -- WHERE deleted_at IS NULL
            ORDER BY id DESC 
            LIMIT 1";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        $row = $stmt->fetch();

        if (!$row) {
            return "KOM001";
        }

        $lastKode = $row['kode_monitoring'];
        $number = (int) substr($lastKode, 3);
        $number++;

        return "KOM" . str_pad($number, 3, "0", STR_PAD_LEFT);
    }

}