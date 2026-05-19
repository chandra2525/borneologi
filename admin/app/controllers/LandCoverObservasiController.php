<?php

require_once __DIR__ . '/../models/LandCoverObservasi.php';
require_once __DIR__ . '/../core/csrf.php';

class LandCoverObservasiController
{
    private $model;

    public function __construct($pdo)
    {
        $this->model = new LandCoverObservasi($pdo);
    }

    /*
    =========================
    LIST LAND COVER OBSERVASI
    =========================
    */
    public function index()
    {
        return $this->model->getAll();
    }

    /*
    =========================
    GET LAND COVER OBSERVASI BY ID
    =========================
    */
    public function find($id)
    {
        return $this->model->findById($id);
    }

    /*
    =========================
    CREATE LAND COVER OBSERVASI
    =========================
    */
    public function store($data, $user_id)
    {
        if (!verifyCsrfToken()) {
            die("Invalid CSRF Token");
        }

        $id_tanah = trim($data['id_tanah']);
        $periode_pengecekan = trim($data['periode_pengecekan']);
        $id_kategori_area = trim($data['id_kategori_area']);
        $id_penggunaan_pertanian = trim($data['id_penggunaan_pertanian']);
        $id_penggunaan_lainnya = trim($data['id_penggunaan_lainnya']);
        $persentase_tutupan = trim($data['persentase_tutupan']);
        $catatan = trim($data['catatan']);

        return $this->model->create([
            'id_tanah' => $id_tanah,
            'periode_pengecekan' => $periode_pengecekan,
            'id_kategori_area' => $id_kategori_area,
            'id_penggunaan_pertanian' => $id_penggunaan_pertanian,
            'id_penggunaan_lainnya' => $id_penggunaan_lainnya,
            'persentase_tutupan' => $persentase_tutupan,
            'catatan' => $catatan,
            'created_by' => $user_id
        ]);
    }

    /*
    =========================
    UPDATE LAND COVER OBSERVASI
    =========================
    */
    public function update($id, $data, $user_id)
    {
        if (!verifyCsrfToken()) {
            die("Invalid CSRF Token");
        }

        $id_tanah = trim($data['id_tanah']);
        $periode_pengecekan = trim($data['periode_pengecekan']);
        $id_kategori_area = trim($data['id_kategori_area']);
        $id_penggunaan_pertanian = trim($data['id_penggunaan_pertanian']);
        $id_penggunaan_lainnya = trim($data['id_penggunaan_lainnya']);
        $persentase_tutupan = trim($data['persentase_tutupan']);
        $catatan = trim($data['catatan']);

        return $this->model->update($id, [
            'id_tanah' => $id_tanah,
            'periode_pengecekan' => $periode_pengecekan,
            'id_kategori_area' => $id_kategori_area,
            'id_penggunaan_pertanian' => $id_penggunaan_pertanian,
            'id_penggunaan_lainnya' => $id_penggunaan_lainnya,
            'persentase_tutupan' => $persentase_tutupan,
            'catatan' => $catatan,
            'updated_by' => $user_id
        ]);
    }

    /*
    =========================
    DELETE LAND COVER OBSERVASI (SOFT DELETE)
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

    public function getKategoriArea()
    {
        return $this->model->getKategoriArea();
    }

    public function getPenggunaanPertanian()
    {
        return $this->model->getPenggunaanPertanian();
    }

    public function getPenggunaanLainnya()
    {
        return $this->model->getPenggunaanLainnya();
    }
}