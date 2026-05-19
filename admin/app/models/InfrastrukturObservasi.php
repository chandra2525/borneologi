<?php

class InfrastrukturObservasi
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function getAll()
    {
        $sql = "SELECT
                io.id,
                io.id_tanah,
                io.periode_pengecekan,
                io.id_akses_perjalanan,
                io.id_kondisi_jalan,
                io.jarak_ke_jalan_km,
                io.ada_jembatan,
                io.ada_listrik,
                io.ada_internet,
                io.sinyal_seluler,
                io.catatan,
                ta.nama_lahan,
                ap.nama as nama_akses_perjalanan,
                kj.nama as nama_kondisi_jalan
            FROM t_infrastruktur_observasi io
            LEFT JOIN t_tanah ta ON ta.id = io.id_tanah
            LEFT JOIN m_akses_perjalanan ap ON ap.id = io.id_akses_perjalanan
            LEFT JOIN m_kondisi_jalan kj ON kj.id = io.id_kondisi_jalan
            WHERE io.deleted_at IS NULL
            ORDER BY io.id ASC";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function findById($id)
    {
        $sql = "SELECT *
            FROM t_infrastruktur_observasi
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

        $sql = "INSERT INTO t_infrastruktur_observasi
            (id_tanah,periode_pengecekan,id_akses_perjalanan,id_kondisi_jalan,jarak_ke_jalan_km,ada_jembatan,ada_listrik,ada_internet,sinyal_seluler,catatan,created_by)
            VALUES
            (:id_tanah,:periode_pengecekan,:id_akses_perjalanan,:id_kondisi_jalan,:jarak_ke_jalan_km,:ada_jembatan,:ada_listrik,:ada_internet,:sinyal_seluler,:catatan,:created_by)";

        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute($data);

    }

    public function update($id, $data)
    {
        $sql = "UPDATE t_infrastruktur_observasi
            SET
            id_tanah=:id_tanah,
            periode_pengecekan=:periode_pengecekan,
            id_akses_perjalanan=:id_akses_perjalanan,
            id_kondisi_jalan=:id_kondisi_jalan,
            jarak_ke_jalan_km=:jarak_ke_jalan_km,
            ada_jembatan=:ada_jembatan,
            ada_listrik=:ada_listrik,
            ada_internet=:ada_internet,
            sinyal_seluler=:sinyal_seluler,
            catatan=:catatan,
            updated_by=:updated_by
            WHERE id=:id";

        $stmt = $this->pdo->prepare($sql);

        $data["id"] = $id;

        return $stmt->execute($data);
    }

    public function softDelete($id, $user_id)
    {
        $sql = "UPDATE t_infrastruktur_observasi
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

    public function getAksesPerjalanan()
    {
        $sql = "SELECT id,nama FROM m_akses_perjalanan
                WHERE deleted_at IS NULL
                ORDER BY id";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function getKondisiJalan()
    {
        $sql = "SELECT id,nama FROM m_kondisi_jalan
                WHERE deleted_at IS NULL
                ORDER BY id";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll();
    }

}