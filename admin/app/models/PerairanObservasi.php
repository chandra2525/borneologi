<?php

class PerairanObservasi
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
                po.id_warna_air,
                po.id_jenis_palung,
                po.id_kecepatan_aliran,
                po.kedalaman_cm,
                po.lebar_m,
                po.debit_lps,
                po.ph,
                po.kekeruhan_ntu,
                po.catatan,
                ta.nama_lahan,
                wa.nama as warna_air,
                jp.nama as jenis_palung,
                ka.nama as kecepatan_aliran
            FROM  t_perairan_observasi po
            LEFT JOIN t_tanah ta ON ta.id = po.id_tanah
            LEFT JOIN m_warna_air wa ON wa.id = po.id_warna_air
            LEFT JOIN m_jenis_palung jp ON jp.id = po.id_jenis_palung
            LEFT JOIN m_kecepatan_aliran ka ON ka.id = po.id_kecepatan_aliran
            WHERE po.deleted_at IS NULL
            ORDER BY po.id ASC";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function findById($id)
    {
        $sql = "SELECT *
            FROM  t_perairan_observasi
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

        $sql = "INSERT INTO  t_perairan_observasi
            (id_tanah,periode_pengecekan,id_warna_air,id_jenis_palung,id_kecepatan_aliran,kedalaman_cm,lebar_m,debit_lps,ph,kekeruhan_ntu,catatan,created_by)
            VALUES
            (:id_tanah,:periode_pengecekan,:id_warna_air,:id_jenis_palung,:id_kecepatan_aliran,:kedalaman_cm,:lebar_m,:debit_lps,:ph,:kekeruhan_ntu,:catatan,:created_by)";

        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute($data);

    }

    public function update($id, $data)
    {
        $sql = "UPDATE  t_perairan_observasi
            SET
            id_tanah=:id_tanah,
            periode_pengecekan=:periode_pengecekan,
            id_warna_air=:id_warna_air,
            id_jenis_palung=:id_jenis_palung,
            id_kecepatan_aliran=:id_kecepatan_aliran,
            kedalaman_cm=:kedalaman_cm,
            lebar_m=:lebar_m,
            debit_lps=:debit_lps,
            ph=:ph,
            kekeruhan_ntu=:kekeruhan_ntu,
            catatan=:catatan,
            updated_by=:updated_by
            WHERE id=:id";

        $stmt = $this->pdo->prepare($sql);

        $data["id"] = $id;

        return $stmt->execute($data);
    }

    public function softDelete($id, $user_id)
    {
        $sql = "UPDATE  t_perairan_observasi
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

    public function getWarnaAir()
    {
        $sql = "SELECT id,nama FROM m_warna_air
                WHERE deleted_at IS NULL
                ORDER BY id";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function getJenisPalung()
    {
        $sql = "SELECT id,nama FROM m_jenis_palung
                WHERE deleted_at IS NULL
                ORDER BY id";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function getKecepatanAliran()
    {
        $sql = "SELECT id,nama FROM m_kecepatan_aliran
                WHERE deleted_at IS NULL
                ORDER BY id";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll();
    }

}