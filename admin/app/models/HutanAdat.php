<?php

class HutanAdat
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function getAll()
    {
        $sql = "SELECT
                ha.id,
                ha.kode_hutan_adat,
                ha.nama_hutan_adat,
                ha.id_masyarakat_hukum_adat,
                ha.id_desa,
                ha.nomor_sk,
                ha.tanggal_sk,
                ha.id_status_kawasan,
                ha.luas_ha,
                ha.keterangan,
                ha.is_active,
                d.nama_desa as nama_desa,
                kt.nama_kelompok as nama_masyarakat_hukum_adat,
                sk.nama as nama_status_kawasan
            FROM t_hutan_adat ha
            LEFT JOIN m_desa d ON d.id=ha.id_desa
            LEFT JOIN t_kelompok_tani kt ON kt.id=ha.id_masyarakat_hukum_adat
            LEFT JOIN m_status_kawasan sk ON sk.id=ha.id_status_kawasan
            WHERE ha.deleted_at IS NULL
            ORDER BY ha.id ASC";
        
        // $sql = "SELECT
        //     ha.id,
        //     ha.kode_hutan_adat,
        //     ha.nama_hutan_adat,
        //     ha.id_masyarakat_hukum_adat,
        //     ha.id_desa,
        //     ha.nomor_sk,
        //     ha.tanggal_sk,
        //     ha.id_status_kawasan,
        //     ha.luas_ha,

        //     p.id AS id_polygon,
        //     p.nama_polygon,
        //     ST_AsText(p.geom_area) AS geom_area,

        //     ha.keterangan,
        //     ha.is_active,

        //     d.nama_desa,
        //     kt.nama_kelompok AS nama_masyarakat_hukum_adat,
        //     sk.nama AS nama_status_kawasan

        // FROM t_hutan_adat ha

        // LEFT JOIN m_desa d 
        //     ON d.id = ha.id_desa

        // LEFT JOIN t_kelompok_tani kt 
        //     ON kt.id = ha.id_masyarakat_hukum_adat

        // LEFT JOIN m_status_kawasan sk 
        //     ON sk.id = ha.id_status_kawasan

        // -- 🔥 Relasi polymorphic
        // LEFT JOIN t_polygon_relasi r 
        //     ON r.relasi_id = ha.id
        //     AND r.relasi_tipe = 'hutan_adat'

        // LEFT JOIN t_polygon p 
        //     ON p.id = r.id_polygon

        // WHERE ha.deleted_at IS NULL

        // ORDER BY ha.id ASC";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function findById($id)
    {
        $sql = "SELECT *
            FROM t_hutan_adat
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

        $sql = "INSERT INTO t_hutan_adat
            (kode_hutan_adat,nama_hutan_adat,id_masyarakat_hukum_adat,id_desa,nomor_sk,tanggal_sk,id_status_kawasan,luas_ha,keterangan,is_active,created_by)
            VALUES
            (:kode_hutan_adat,:nama_hutan_adat,:id_masyarakat_hukum_adat,:id_desa,:nomor_sk,:tanggal_sk,:id_status_kawasan,:luas_ha,:keterangan,:is_active,:created_by)";

        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute($data);

    }

    public function update($id, $data)
    {
        $sql = "UPDATE t_hutan_adat
            SET
            kode_hutan_adat=:kode_hutan_adat,
            nama_hutan_adat=:nama_hutan_adat,
            id_masyarakat_hukum_adat=:id_masyarakat_hukum_adat,
            id_desa=:id_desa,
            nomor_sk=:nomor_sk,
            tanggal_sk=:tanggal_sk,
            id_status_kawasan=:id_status_kawasan,
            luas_ha=:luas_ha,
            keterangan=:keterangan,
            is_active=:is_active,
            updated_by=:updated_by
            WHERE id=:id";

        $stmt = $this->pdo->prepare($sql);

        $data["id"] = $id;

        return $stmt->execute($data);
    }

    public function softDelete($id, $user_id)
    {
        $sql = "UPDATE t_hutan_adat
            SET deleted_at = NOW(),
            deleted_by = :user
            WHERE id = :id";

        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([
            "id" => $id,
            "user" => $user_id
        ]);
    }

    public function getKelompokTani()
    {
        $sql = "SELECT 
                    t.id,
                    t.nama_kelompok
                FROM t_kelompok_tani t
                JOIN m_kategori_kelompok k 
                    ON t.id_kategori_kelompok = k.id
                WHERE 
                    t.deleted_at IS NULL
                    AND k.is_masyarakat_hukum_adat = 1
                ORDER BY t.id;";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function getDesa()
    {
        $sql = "SELECT id,nama_desa FROM m_desa
                WHERE deleted_at IS NULL
                ORDER BY id";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function getStatusKawasan()
    {
        $sql = "SELECT id,nama FROM m_status_kawasan
                WHERE deleted_at IS NULL
                ORDER BY id";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function getPolygon()
    {
        $sql = "SELECT id,nama_polygon FROM t_polygon
                WHERE deleted_at IS NULL
                ORDER BY id";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function generateKode()
    {
        $sql = "SELECT kode_hutan_adat 
            FROM t_hutan_adat 
            -- WHERE deleted_at IS NULL
            ORDER BY id DESC 
            LIMIT 1";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        $row = $stmt->fetch();

        if (!$row) {
            return "HUA001";
        }

        $lastKode = $row['kode_hutan_adat'];
        $number = (int) substr($lastKode, 3);
        $number++;

        return "HUA" . str_pad($number, 3, "0", STR_PAD_LEFT);
    }

}