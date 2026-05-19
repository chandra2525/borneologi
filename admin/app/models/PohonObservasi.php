<?php

class PohonObservasi
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function getAll()
    {
        $sql = "SELECT
                po.id,
                po.id_tanah,
                po.periode_pengecekan,
                po.id_jenis_pohon,
                po.id_fungsi_pohon,
                po.jumlah_pohon,
                po.diameter_rata2_cm,
                po.tinggi_rata2_m,
                po.kondisi,
                po.catatan,
                ta.nama_lahan,
                jp.nama as jenis_pohon,
                fp.nama as fungsi_pohon
            FROM t_pohon_observasi po
            LEFT JOIN t_tanah ta ON ta.id = po.id_tanah
            LEFT JOIN m_jenis_pohon jp ON jp.id = po.id_jenis_pohon
            LEFT JOIN m_fungsi_pohon fp ON fp.id = po.id_fungsi_pohon
            WHERE po.deleted_at IS NULL
            ORDER BY po.id ASC";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function findById($id)
    {
        $sql = "SELECT *
            FROM t_pohon_observasi
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

        $sql = "INSERT INTO t_pohon_observasi
            (id_tanah,periode_pengecekan,id_jenis_pohon,id_fungsi_pohon,jumlah_pohon,diameter_rata2_cm,tinggi_rata2_m,kondisi,catatan,created_by)
            VALUES
            (:id_tanah,:periode_pengecekan,:id_jenis_pohon,:id_fungsi_pohon,:jumlah_pohon,:diameter_rata2_cm,:tinggi_rata2_m,:kondisi,:catatan,:created_by)";

        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute($data);

    }

    public function update($id, $data)
    {
        $sql = "UPDATE t_pohon_observasi
            SET
            id_tanah=:id_tanah,
            periode_pengecekan=:periode_pengecekan,
            id_jenis_pohon=:id_jenis_pohon,
            id_fungsi_pohon=:id_fungsi_pohon,
            jumlah_pohon=:jumlah_pohon,
            diameter_rata2_cm=:diameter_rata2_cm,
            tinggi_rata2_m=:tinggi_rata2_m,
            kondisi=:kondisi,
            catatan=:catatan,
            updated_by=:updated_by
            WHERE id=:id";

        $stmt = $this->pdo->prepare($sql);

        $data["id"] = $id;

        return $stmt->execute($data);
    }

    public function softDelete($id, $user_id)
    {
        $sql = "UPDATE t_pohon_observasi
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

    public function getJenisPohon()
    {
        $sql = "SELECT id,nama FROM m_jenis_pohon
                WHERE deleted_at IS NULL
                ORDER BY id";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function getFungsiPohon()
    {
        $sql = "SELECT id,nama FROM m_fungsi_pohon
                WHERE deleted_at IS NULL
                ORDER BY id";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll();
    }

}