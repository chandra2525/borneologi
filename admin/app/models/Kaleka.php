<?php

class Kaleka
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function getAll()
    {
        $sql = "SELECT
                ka.id,
                ka.kode_kaleka,
                ka.nama_kaleka,
                ka.id_petani,
                ka.id_desa,
                ka.luas_ha,
                ka.centroid_lat,
                ka.centroid_lng,
                ST_AsText(ka.geom_area) AS geom_area,
                ka.keterangan,
                ka.is_active,
                d.nama_desa as nama_desa,
                pe.nama_lengkap as nama_petani
            FROM t_kaleka ka
            LEFT JOIN t_petani pe ON pe.id=ka.id_petani
            LEFT JOIN m_desa d ON d.id=ka.id_desa
            WHERE ka.deleted_at IS NULL
            ORDER BY ka.id ASC";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function findById($id)
    {
        $sql = "SELECT *
            FROM t_kaleka
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

        $sql = "INSERT INTO t_kaleka
            (kode_kaleka,nama_kaleka,id_petani,id_desa,luas_ha,centroid_lat,centroid_lng,geom_area,keterangan,is_active,created_by)
            VALUES
            (:kode_kaleka,:nama_kaleka,:id_petani,:id_desa,:luas_ha,:centroid_lat,:centroid_lng,ST_GeomFromText(:geom_area, 4326),:keterangan,:is_active,:created_by)";

        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute($data);

    }

    public function update($id, $data)
    {
        $sql = "UPDATE t_kaleka
            SET
            kode_kaleka=:kode_kaleka,
            nama_kaleka=:nama_kaleka,
            id_petani=:id_petani,
            id_desa=:id_desa,
            luas_ha=:luas_ha,
            centroid_lat=:centroid_lat,
            centroid_lng=:centroid_lng,
            geom_area=ST_GeomFromText(:geom_area, 4326),
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
        $sql = "UPDATE t_kaleka
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

    public function getDesa()
    {
        $sql = "SELECT id,nama_desa FROM m_desa
                WHERE deleted_at IS NULL
                ORDER BY id";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function generateKode()
    {
        $sql = "SELECT kode_kaleka 
            FROM t_kaleka 
            -- WHERE deleted_at IS NULL
            ORDER BY id DESC 
            LIMIT 1";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        $row = $stmt->fetch();

        if (!$row) {
            return "KAL001";
        }

        $lastKode = $row['kode_kaleka'];
        $number = (int) substr($lastKode, 3);
        $number++;

        return "KAL" . str_pad($number, 3, "0", STR_PAD_LEFT);
    }

}