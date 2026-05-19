<?php

class DetailMonitoringPenanaman
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function getAll()
    {
        $sql = "SELECT
                dm.id,
                dm.id_monitoring,
                dm.id_bank_benih,
                dm.jumlah_ditanam,
                dm.satuan,
                dm.jumlah_hidup,
                dm.jumlah_mati,
                dm.tinggi_rata2_cm,
                dm.diameter_rata2_cm,
                dm.catatan,
                bb.nama_lokal AS nama_bank_benih,
                mp.kode_monitoring
            FROM t_detail_monitoring_penanaman dm
            LEFT JOIN t_bank_benih bb ON bb.id = dm.id_bank_benih
            LEFT JOIN t_monitoring_penanaman mp ON mp.id = dm.id_monitoring
            WHERE dm.deleted_at IS NULL
            ORDER BY dm.id ASC";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function findById($id)
    {
        $sql = "SELECT *
            FROM t_detail_monitoring_penanaman
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

        $sql = "INSERT INTO t_detail_monitoring_penanaman
            (id_monitoring,id_bank_benih,jumlah_ditanam,satuan,jumlah_hidup,jumlah_mati,tinggi_rata2_cm,diameter_rata2_cm,catatan,created_by)
            VALUES
            (:id_monitoring,:id_bank_benih,:jumlah_ditanam,:satuan,:jumlah_hidup,:jumlah_mati,:tinggi_rata2_cm,:diameter_rata2_cm,:catatan,:created_by)";

        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute($data);

    }

    public function update($id, $data)
    {
        $sql = "UPDATE t_detail_monitoring_penanaman
            SET
            id_monitoring=:id_monitoring,
            id_bank_benih=:id_bank_benih,
            jumlah_ditanam=:jumlah_ditanam,
            satuan=:satuan,
            jumlah_hidup=:jumlah_hidup,
            jumlah_mati=:jumlah_mati,
            tinggi_rata2_cm=:tinggi_rata2_cm,
            diameter_rata2_cm=:diameter_rata2_cm,
            catatan=:catatan,
            updated_by=:updated_by
            WHERE id=:id";

        $stmt = $this->pdo->prepare($sql);

        $data["id"] = $id;

        return $stmt->execute($data);
    }

    public function softDelete($id, $user_id)
    {
        $sql = "UPDATE t_detail_monitoring_penanaman
            SET deleted_at = NOW(),
            deleted_by = :user
            WHERE id = :id";

        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([
            "id" => $id,
            "user" => $user_id
        ]);
    }

    public function getBankBenih()
    {
        $sql = "SELECT id,nama_lokal FROM t_bank_benih
                WHERE deleted_at IS NULL
                ORDER BY id";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function getMonitoringPenanaman()
    {
        $sql = "SELECT id,kode_monitoring FROM t_monitoring_penanaman
                WHERE deleted_at IS NULL
                ORDER BY id";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll();
    }

}