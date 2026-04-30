<?php

class Tanah
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function getAll()
    {
        $sql = "SELECT
                ta.id,
                ta.kode_tanah,
                ta.id_petani,
                ta.id_relasi,
                ta.tipe_relasi,
                ta.nama_lahan,
                ta.id_legalitas_lahan,
                ta.id_status_kawasan,
                ta.luas_ha,
                ta.centroid_lat,
                ta.centroid_lng,
                ST_AsText(ta.geom_area) AS geom_area,
                ta.sejarah,
                ta.alamat_lokasi,
                ta.keterangan,
                ta.sudah_validasi,
                ta.tanggal_validasi,
                ta.is_active,

                pe.nama_lengkap AS nama_petani,

                -- ambil nama relasi dari kaleka atau hutan adat
                CASE 
                    WHEN ta.tipe_relasi = 'kaleka' THEN ka.nama_kaleka
                    WHEN ta.tipe_relasi = 'hutan_adat' THEN ha.nama_hutan_adat
                    ELSE NULL
                END AS nama_relasi,

                ll.nama AS nama_legalitas_lahan,
                sk.nama AS nama_status_kawasan

            FROM t_tanah ta

            LEFT JOIN t_petani pe 
                ON pe.id = ta.id_petani

            -- join ke kaleka hanya jika tipe = kaleka
            LEFT JOIN t_kaleka ka 
                ON ka.id = ta.id_relasi 
                AND ta.tipe_relasi = 'kaleka'

            -- join ke hutan adat hanya jika tipe = hutan_adat
            LEFT JOIN t_hutan_adat ha 
                ON ha.id = ta.id_relasi 
                AND ta.tipe_relasi = 'hutan_adat'

            LEFT JOIN m_legalitas_lahan ll 
                ON ll.id = ta.id_legalitas_lahan

            LEFT JOIN m_status_kawasan sk 
                ON sk.id = ta.id_status_kawasan

            WHERE ta.deleted_at IS NULL

            ORDER BY ta.id ASC;";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function findById($id)
    {
        $sql = "SELECT *
            FROM t_tanah
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

        $sql = "INSERT INTO t_tanah
            (kode_tanah,id_petani,id_relasi,tipe_relasi,nama_lahan,id_legalitas_lahan,id_status_kawasan,luas_ha,centroid_lat,centroid_lng,geom_area,sejarah,alamat_lokasi,keterangan,sudah_validasi,tanggal_validasi,is_active,created_by)
            VALUES
            (:kode_tanah,:id_petani,:id_relasi,:tipe_relasi,:nama_lahan,:id_legalitas_lahan,:id_status_kawasan,:luas_ha,:centroid_lat,:centroid_lng,ST_GeomFromText(:geom_area, 4326),:sejarah,:alamat_lokasi,:keterangan,:sudah_validasi,:tanggal_validasi,:is_active,:created_by)";

        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute($data);

    }

    public function update($id, $data)
    {
        $sql = "UPDATE t_tanah
            SET
            kode_tanah=:kode_tanah,
            id_petani=:id_petani,
            id_relasi=:id_relasi,
            tipe_relasi=:tipe_relasi,
            nama_lahan=:nama_lahan,
            id_legalitas_lahan=:id_legalitas_lahan,
            id_status_kawasan=:id_status_kawasan,
            luas_ha=:luas_ha,
            centroid_lat=:centroid_lat,
            centroid_lng=:centroid_lng,
            geom_area=ST_GeomFromText(:geom_area, 4326),
            sejarah=:sejarah,
            alamat_lokasi=:alamat_lokasi,
            keterangan=:keterangan,
            sudah_validasi=:sudah_validasi,
            tanggal_validasi=:tanggal_validasi,
            is_active=:is_active,
            updated_by=:updated_by
            WHERE id=:id";

        $stmt = $this->pdo->prepare($sql);

        $data["id"] = $id;

        return $stmt->execute($data);
    }

    public function softDelete($id, $user_id)
    {
        $sql = "UPDATE t_tanah
            SET deleted_at = NOW(),
            deleted_by = :user
            WHERE id = :id";

        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([
            "id" => $id,
            "user" => $user_id
        ]);
    }

    public function getPetani()
    {
        $sql = "SELECT id,nama_lengkap FROM t_petani
                WHERE deleted_at IS NULL
                ORDER BY id";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function getHutanAdat()
    {
        $sql = "SELECT id,nama_hutan_adat FROM t_hutan_adat
                WHERE deleted_at IS NULL
                ORDER BY id";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function getKaleka()
    {
        $sql = "SELECT id,nama_kaleka FROM t_kaleka
                WHERE deleted_at IS NULL
                ORDER BY id";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function getLegalitasLahan()
    {
        $sql = "SELECT id,nama FROM m_legalitas_lahan
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

    public function generateKode()
    {
        $sql = "SELECT kode_tanah 
            FROM t_tanah 
            -- WHERE deleted_at IS NULL
            ORDER BY id DESC 
            LIMIT 1";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        $row = $stmt->fetch();

        if (!$row) {
            return "TAN001";
        }

        $lastKode = $row['kode_tanah'];
        $number = (int) substr($lastKode, 3);
        $number++;

        return "TAN" . str_pad($number, 3, "0", STR_PAD_LEFT);
    }

}