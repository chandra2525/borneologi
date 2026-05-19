<?php

class BankBenih
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function getAll()
    {
        $sql = "SELECT
                bb.id,
                bb.nomor_aksesi,
                bb.id_tanah,
                bb.id_negara,
                bb.nama_lokal,
                bb.nama_ilmiah,
                bb.famili_tanaman,
                bb.provenance,
                bb.id_tipe_penyimpanan_benih,
                bb.tanggal_masuk,
                bb.jumlah_stok,
                bb.satuan_stok,
                bb.kadar_air_persen,
                bb.viabilitas_persen,
                bb.ketinggian_mdpl,
                bb.masa_berlaku_sampai,
                bb.lokasi_penyimpanan,
                bb.titik_koleksi_lat,
                bb.titik_koleksi_lng,
                bb.catatan,
                bb.is_active,
                ta.nama_lahan,
                ne.nama as nama_negara,
                tp.nama as nama_tipe_penyimpanan_benih
            FROM t_bank_benih bb
            LEFT JOIN t_tanah ta ON ta.id=bb.id_tanah
            LEFT JOIN m_negara ne ON ne.id=bb.id_negara
            LEFT JOIN m_tipe_penyimpanan_benih tp ON tp.id=bb.id_tipe_penyimpanan_benih
            WHERE bb.deleted_at IS NULL
            ORDER BY bb.id ASC";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function findById($id)
    {
        $sql = "SELECT *
            FROM t_bank_benih
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

        $sql = "INSERT INTO t_bank_benih
            (nomor_aksesi,id_tanah,id_negara,nama_lokal,nama_ilmiah,famili_tanaman,provenance,id_tipe_penyimpanan_benih,tanggal_masuk,jumlah_stok,satuan_stok,kadar_air_persen,viabilitas_persen,ketinggian_mdpl,masa_berlaku_sampai,lokasi_penyimpanan,titik_koleksi_lat,titik_koleksi_lng,catatan,is_active,created_by)
            VALUES
            (:nomor_aksesi,:id_tanah,:id_negara,:nama_lokal,:nama_ilmiah,:famili_tanaman,:provenance,:id_tipe_penyimpanan_benih,:tanggal_masuk,:jumlah_stok,:satuan_stok,:kadar_air_persen,:viabilitas_persen,:ketinggian_mdpl,:masa_berlaku_sampai,:lokasi_penyimpanan,:titik_koleksi_lat,:titik_koleksi_lng,:catatan,:is_active,:created_by)";

        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute($data);

    }

    public function update($id, $data)
    {
        $sql = "UPDATE t_bank_benih
            SET
            nomor_aksesi=:nomor_aksesi,
            id_tanah=:id_tanah,
            id_negara=:id_negara,
            nama_lokal=:nama_lokal,
            nama_ilmiah=:nama_ilmiah,
            famili_tanaman=:famili_tanaman,
            provenance=:provenance,
            id_tipe_penyimpanan_benih=:id_tipe_penyimpanan_benih,
            tanggal_masuk=:tanggal_masuk,
            jumlah_stok=:jumlah_stok,
            satuan_stok=:satuan_stok,
            kadar_air_persen=:kadar_air_persen,
            viabilitas_persen=:viabilitas_persen,
            ketinggian_mdpl=:ketinggian_mdpl,
            masa_berlaku_sampai=:masa_berlaku_sampai,
            lokasi_penyimpanan=:lokasi_penyimpanan,
            titik_koleksi_lat=:titik_koleksi_lat,
            titik_koleksi_lng=:titik_koleksi_lng,
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
        $sql = "UPDATE t_bank_benih
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

    public function getNegara()
    {
        $sql = "SELECT id,nama FROM m_negara
                WHERE deleted_at IS NULL
                ORDER BY id";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function getTipePenyimpananBenih()
    {
        $sql = "SELECT id,nama FROM m_tipe_penyimpanan_benih
                WHERE deleted_at IS NULL
                ORDER BY id";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll();
    }

}