<?php

class LandCoverObservasi
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function getAll()
    {
        $sql = "SELECT
                lc.id,
                lc.id_tanah,
                lc.periode_pengecekan,
                lc.id_kategori_area,
                lc.id_penggunaan_pertanian,
                lc.id_penggunaan_lainnya,
                lc.persentase_tutupan,
                lc.catatan,
                ta.nama_lahan,
                ka.nama as nama_kategori_area,
                pp.nama as nama_penggunaan_pertanian,
                pl.nama as nama_penggunaan_lainnya
            FROM  t_land_cover_observasi lc
            LEFT JOIN t_tanah ta ON ta.id = lc.id_tanah
            LEFT JOIN m_kategori_area ka ON ka.id = lc.id_kategori_area
            LEFT JOIN m_penggunaan_pertanian pp ON pp.id = lc.id_penggunaan_pertanian
            LEFT JOIN m_penggunaan_lainnya pl ON pl.id = lc.id_penggunaan_lainnya
            WHERE lc.deleted_at IS NULL
            ORDER BY lc.id ASC";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function findById($id)
    {
        $sql = "SELECT *
            FROM  t_land_cover_observasi
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

        $sql = "INSERT INTO  t_land_cover_observasi
            (id_tanah,periode_pengecekan,id_kategori_area,id_penggunaan_pertanian,id_penggunaan_lainnya,persentase_tutupan,catatan,created_by)
            VALUES
            (:id_tanah,:periode_pengecekan,:id_kategori_area,:id_penggunaan_pertanian,:id_penggunaan_lainnya,:persentase_tutupan,:catatan,:created_by)";

        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute($data);

    }

    public function update($id, $data)
    {
        $sql = "UPDATE  t_land_cover_observasi
            SET
            id_tanah=:id_tanah,
            periode_pengecekan=:periode_pengecekan,
            id_kategori_area=:id_kategori_area,
            id_penggunaan_pertanian=:id_penggunaan_pertanian,
            id_penggunaan_lainnya=:id_penggunaan_lainnya,
            persentase_tutupan=:persentase_tutupan,
            catatan=:catatan,
            updated_by=:updated_by
            WHERE id=:id";

        $stmt = $this->pdo->prepare($sql);

        $data["id"] = $id;

        return $stmt->execute($data);
    }

    public function softDelete($id, $user_id)
    {
        $sql = "UPDATE  t_land_cover_observasi
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

    public function getKategoriArea()
    {
        $sql = "SELECT id,nama FROM m_kategori_area
                WHERE deleted_at IS NULL
                ORDER BY id";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function getPenggunaanPertanian()
    {
        $sql = "SELECT id,nama FROM m_penggunaan_pertanian
                WHERE deleted_at IS NULL
                ORDER BY id";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function getPenggunaanLainnya()
    {
        $sql = "SELECT id,nama FROM m_penggunaan_lainnya
                WHERE deleted_at IS NULL
                ORDER BY id";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll();
    }

}