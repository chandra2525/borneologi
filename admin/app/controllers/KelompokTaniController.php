<?php

require_once __DIR__ . '/../models/KelompokTani.php';
require_once __DIR__ . '/../core/csrf.php';

class KelompokTaniController
{
    private $model;

    public function __construct($pdo)
    {
        $this->model = new KelompokTani($pdo);
    }

    /*
    =========================
    LIST KELOMPOK TANI
    =========================
    */
    public function index()
    {
        return $this->model->getAll();
    }

    /*
    =========================
    GET KELOMPOK TANI BY ID
    =========================
    */
    public function find($id)
    {
        return $this->model->findById($id);
    }

    /*
    =========================
    CREATE KELOMPOK TANI
    =========================
    */
    public function store($data, $user_id)
    {
        if (!verifyCsrfToken()) {
            die("Invalid CSRF Token");
        }

        $kode_kelompok = trim($data['kode_kelompok']);
        $nama_kelompok = trim($data['nama_kelompok']);
        $id_kategori_kelompok = trim($data['id_kategori_kelompok']);
        $id_desa = trim($data['id_desa']);
        $alamat = trim($data['alamat']);
        $id_akses_perjalanan = trim($data['id_akses_perjalanan']);
        $id_kondisi_jalan = trim($data['id_kondisi_jalan']);
        $tahun_bentuk = trim($data['tahun_bentuk']);
        $nomor_sk = trim($data['nomor_sk']);
        $tanggal_sk = trim($data['tanggal_sk']);
        $status_kelompok = trim($data['status_kelompok']);
        $is_active = isset($data['is_active']) ? 1 : 0;

        return $this->model->create([
            'kode_kelompok' => $kode_kelompok,
            'nama_kelompok' => $nama_kelompok,
            'id_kategori_kelompok' => $id_kategori_kelompok,
            'id_desa' => $id_desa,
            'alamat' => $alamat,
            'id_akses_perjalanan' => $id_akses_perjalanan,
            'id_kondisi_jalan' => $id_kondisi_jalan,
            'tahun_bentuk' => $tahun_bentuk,
            'nomor_sk' => $nomor_sk,
            'tanggal_sk' => $tanggal_sk,
            'status_kelompok' => $status_kelompok,
            'is_active' => $is_active,
            'created_by' => $user_id
        ]);
    }

    /*
    =========================
    UPDATE KELOMPOK TANI
    =========================
    */
    public function update($id, $data, $user_id)
    {
        if (!verifyCsrfToken()) {
            die("Invalid CSRF Token");
        }

        $kode_kelompok = trim($data['kode_kelompok']);
        $nama_kelompok = trim($data['nama_kelompok']);
        $id_kategori_kelompok = trim($data['id_kategori_kelompok']);
        $id_desa = trim($data['id_desa']);
        $alamat = trim($data['alamat']);
        $id_akses_perjalanan = trim($data['id_akses_perjalanan']);
        $id_kondisi_jalan = trim($data['id_kondisi_jalan']);
        $tahun_bentuk = trim($data['tahun_bentuk']);
        $nomor_sk = trim($data['nomor_sk']);
        $tanggal_sk = trim($data['tanggal_sk']);
        $status_kelompok = trim($data['status_kelompok']);
        $is_active = isset($data['is_active']) ? 1 : 0;

        return $this->model->update($id, [
            'kode_kelompok' => $kode_kelompok,
            'nama_kelompok' => $nama_kelompok,
            'id_kategori_kelompok' => $id_kategori_kelompok,
            'id_desa' => $id_desa,
            'alamat' => $alamat,
            'id_akses_perjalanan' => $id_akses_perjalanan,
            'id_kondisi_jalan' => $id_kondisi_jalan,
            'tahun_bentuk' => $tahun_bentuk,
            'nomor_sk' => $nomor_sk,
            'tanggal_sk' => $tanggal_sk,
            'status_kelompok' => $status_kelompok,
            'is_active' => $is_active,
            'updated_by' => $user_id
        ]);
    }

    /*
    =========================
    DELETE KELOMPOK TANI (SOFT DELETE)
    =========================
    */
    public function delete($id, $user_id)
    {
        if (!verifyCsrfToken()) {
            die("Invalid CSRF Token");
        }

        return $this->model->softDelete($id, $user_id);
    }

    public function getKategoriKelompok()
    {
        return $this->model->getKategoriKelompok();
    }

    public function getDesa()
    {
        return $this->model->getDesa();
    }

    public function getAksesPerjalanan()
    {
        return $this->model->getAksesPerjalanan();
    }

    public function getKondisiJalan()
    {
        return $this->model->getKondisiJalan();
    }
}