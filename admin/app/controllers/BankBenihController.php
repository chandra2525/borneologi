<?php

require_once __DIR__ . '/../models/BankBenih.php';
require_once __DIR__ . '/../core/csrf.php';

class BankBenihController
{
    private $model;

    public function __construct($pdo)
    {
        $this->model = new BankBenih($pdo);
    }

    /*
    =========================
    LIST BANK BENIH
    =========================
    */
    public function index()
    {
        return $this->model->getAll();
    }

    /*
    =========================
    GET BANK BENIH BY ID
    =========================
    */
    public function find($id)
    {
        return $this->model->findById($id);
    }

    /*
    =========================
    CREATE BANK BENIH
    =========================
    */
    public function store($data, $user_id)
    {
        if (!verifyCsrfToken()) {
            die("Invalid CSRF Token");
        }

        $nomor_aksesi = trim($data['nomor_aksesi']);
        $id_tanah = trim($data['id_tanah']);
        $id_negara = trim($data['id_negara']);
        $nama_lokal = trim($data['nama_lokal']);
        $nama_ilmiah = trim($data['nama_ilmiah']);
        $famili_tanaman = trim($data['famili_tanaman']);
        $provenance = trim($data['provenance']);
        $id_tipe_penyimpanan_benih = trim($data['id_tipe_penyimpanan_benih']);
        $tanggal_masuk = trim($data['tanggal_masuk']);
        $jumlah_stok = trim($data['jumlah_stok']);
        $satuan_stok = trim($data['satuan_stok']);
        $kadar_air_persen = trim($data['kadar_air_persen']);
        $viabilitas_persen = trim($data['viabilitas_persen']);
        $ketinggian_mdpl = trim($data['ketinggian_mdpl']);
        $masa_berlaku_sampai = trim($data['masa_berlaku_sampai']);
        $lokasi_penyimpanan = trim($data['lokasi_penyimpanan']);
        $titik_koleksi_lat = trim($data['titik_koleksi_lat']);
        $titik_koleksi_lng = trim($data['titik_koleksi_lng']);
        $foto_benih = trim($data['foto_benih']);
        $catatan = trim($data['catatan']);
        $is_active = isset($data['is_active']) ? 1 : 0;

        return $this->model->create([
            'nomor_aksesi' => $nomor_aksesi,
            'id_tanah' => $id_tanah,
            'id_negara' => $id_negara,
            'nama_lokal' => $nama_lokal,
            'nama_ilmiah' => $nama_ilmiah,
            'famili_tanaman' => $famili_tanaman,
            'provenance' => $provenance,
            'id_tipe_penyimpanan_benih' => $id_tipe_penyimpanan_benih,
            'tanggal_masuk' => $tanggal_masuk,
            'jumlah_stok' => $jumlah_stok,
            'satuan_stok' => $satuan_stok,
            'kadar_air_persen' => $kadar_air_persen,
            'viabilitas_persen' => $viabilitas_persen,
            'ketinggian_mdpl' => $ketinggian_mdpl,
            'masa_berlaku_sampai' => $masa_berlaku_sampai,
            'lokasi_penyimpanan' => $lokasi_penyimpanan,
            'titik_koleksi_lat' => $titik_koleksi_lat,
            'titik_koleksi_lng' => $titik_koleksi_lng,
            'foto_benih' => $foto_benih,
            'catatan' => $catatan,
            'is_active' => $is_active,
            'created_by' => $user_id
        ]);
    }

    /*
    =========================
    UPDATE BANK BENIH
    =========================
    */
    public function update($id, $data, $user_id)
    {
        if (!verifyCsrfToken()) {
            die("Invalid CSRF Token");
        }

        $nomor_aksesi = trim($data['nomor_aksesi']);
        $id_tanah = trim($data['id_tanah']);
        $id_negara = trim($data['id_negara']);
        $nama_lokal = trim($data['nama_lokal']);
        $nama_ilmiah = trim($data['nama_ilmiah']);
        $famili_tanaman = trim($data['famili_tanaman']);
        $provenance = trim($data['provenance']);
        $id_tipe_penyimpanan_benih = trim($data['id_tipe_penyimpanan_benih']);
        $tanggal_masuk = trim($data['tanggal_masuk']);
        $jumlah_stok = trim($data['jumlah_stok']);
        $satuan_stok = trim($data['satuan_stok']);
        $kadar_air_persen = trim($data['kadar_air_persen']);
        $viabilitas_persen = trim($data['viabilitas_persen']);
        $ketinggian_mdpl = trim($data['ketinggian_mdpl']);
        $masa_berlaku_sampai = trim($data['masa_berlaku_sampai']);
        $lokasi_penyimpanan = trim($data['lokasi_penyimpanan']);
        $titik_koleksi_lat = trim($data['titik_koleksi_lat']);
        $titik_koleksi_lng = trim($data['titik_koleksi_lng']);
        $foto_benih = trim($data['foto_benih']);
        $catatan = trim($data['catatan']);
        $is_active = isset($data['is_active']) ? 1 : 0;

        return $this->model->update($id, [
            'nomor_aksesi' => $nomor_aksesi,
            'nama_lokal' => $nama_lokal,
            'id_tanah' => $id_tanah,
            'id_negara' => $id_negara,
            'nama_ilmiah' => $nama_ilmiah,
            'famili_tanaman' => $famili_tanaman,
            'provenance' => $provenance,
            'id_tipe_penyimpanan_benih' => $id_tipe_penyimpanan_benih,
            'tanggal_masuk' => $tanggal_masuk,
            'jumlah_stok' => $jumlah_stok,
            'satuan_stok' => $satuan_stok,
            'kadar_air_persen' => $kadar_air_persen,
            'viabilitas_persen' => $viabilitas_persen,
            'ketinggian_mdpl' => $ketinggian_mdpl,
            'masa_berlaku_sampai' => $masa_berlaku_sampai,
            'lokasi_penyimpanan' => $lokasi_penyimpanan,
            'titik_koleksi_lat' => $titik_koleksi_lat,
            'titik_koleksi_lng' => $titik_koleksi_lng,
            'foto_benih' => $foto_benih,
            'catatan' => $catatan,
            'is_active' => $is_active,
            'updated_by' => $user_id
        ]);
    }

    /*
    =========================
    DELETE BANK BENIH (SOFT DELETE)
    =========================
    */
    public function delete($id, $user_id)
    {
        if (!verifyCsrfToken()) {
            die("Invalid CSRF Token");
        }

        return $this->model->softDelete($id, $user_id);
    }

    public function getTanah()
    {
        return $this->model->getTanah();
    }

    public function getNegara()
    {
        return $this->model->getNegara();
    }

    public function getTipePenyimpananBenih()
    {
        return $this->model->getTipePenyimpananBenih();
    }
}