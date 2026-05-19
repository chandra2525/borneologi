<?php

class TopografiObservasi
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function getAll()
    {
        $sql = "SELECT
                tp.id,
                tp.id_tanah,
                tp.periode_pengecekan,
                tp.id_lanskap,
                tp.id_fitur_tambahan,
                tp.elevasi_mdpl,
                tp.kemiringan_derajat,
                tp.rawan_erosi,
                tp.arah_lereng,
                tp.catatan,
                ta.nama_lahan,
                la.nama as lanskap,
                ft.nama as fitur_tambahan
            FROM t_topografi_observasi tp
            LEFT JOIN t_tanah ta ON ta.id = tp.id_tanah
            LEFT JOIN m_lanskap la ON la.id = tp.id_lanskap
            LEFT JOIN m_fitur_tambahan ft ON ft.id = tp.id_fitur_tambahan
            WHERE tp.deleted_at IS NULL
            ORDER BY tp.id ASC";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function findById($id)
    {
        $sql = "SELECT *
            FROM t_topografi_observasi
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

        $sql = "INSERT INTO t_topografi_observasi
            (id_tanah,periode_pengecekan,id_lanskap,id_fitur_tambahan,elevasi_mdpl,kemiringan_derajat,rawan_erosi,arah_lereng,catatan,created_by)
            VALUES
            (:id_tanah,:periode_pengecekan,:id_lanskap,:id_fitur_tambahan,:elevasi_mdpl,:kemiringan_derajat,:rawan_erosi,:arah_lereng,:catatan,:created_by)";

        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute($data);

    }

    public function update($id, $data)
    {
        $sql = "UPDATE t_topografi_observasi
            SET
            id_tanah=:id_tanah,
            periode_pengecekan=:periode_pengecekan,
            id_lanskap=:id_lanskap,
            id_fitur_tambahan=:id_fitur_tambahan,
            elevasi_mdpl=:elevasi_mdpl,
            kemiringan_derajat=:kemiringan_derajat,
            rawan_erosi=:rawan_erosi,
            arah_lereng=:arah_lereng,
            catatan=:catatan,
            updated_by=:updated_by
            WHERE id=:id";

        $stmt = $this->pdo->prepare($sql);

        $data["id"] = $id;

        return $stmt->execute($data);
    }

    public function softDelete($id, $user_id)
    {
        $sql = "UPDATE t_topografi_observasi
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

    public function getLanskap()
    {
        $sql = "SELECT id,nama FROM m_lanskap
                WHERE deleted_at IS NULL
                ORDER BY id";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function getFiturTambahan()
    {
        $sql = "SELECT id,nama FROM m_fitur_tambahan
                WHERE deleted_at IS NULL
                ORDER BY id";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll();
    }

}