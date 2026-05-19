<?php

require_once __DIR__ . '/../models/TopografiObservasi.php';
require_once __DIR__ . '/../core/csrf.php';

class TopografiObservasiController
{
    private $model;

    public function __construct($pdo)
    {
        $this->model = new TopografiObservasi($pdo);
    }

    /*
    =========================
    LIST TOPOGRAFI OBSERVASI
    =========================
    */
    public function index()
    {
        return $this->model->getAll();
    }

    /*
    =========================
    GET TOPOGRAFI OBSERVASI BY ID
    =========================
    */
    public function find($id)
    {
        return $this->model->findById($id);
    }

    /*
    =========================
    CREATE TOPOGRAFI OBSERVASI
    =========================
    */
    public function store($data, $user_id)
    {
        if (!verifyCsrfToken()) {
            die("Invalid CSRF Token");
        }

        $id_tanah = trim($data['id_tanah']);
        $periode_pengecekan = trim($data['periode_pengecekan']);
        $id_lanskap = trim($data['id_lanskap']);
        $id_fitur_tambahan = trim($data['id_fitur_tambahan']);
        $elevasi_mdpl = trim($data['elevasi_mdpl']);
        $kemiringan_derajat = trim($data['kemiringan_derajat']);
        $rawan_erosi = trim($data['rawan_erosi']);
        $arah_lereng = trim($data['arah_lereng']);
        $catatan = trim($data['catatan']);

        return $this->model->create([
            'id_tanah' => $id_tanah,
            'periode_pengecekan' => $periode_pengecekan,
            'id_lanskap' => $id_lanskap,
            'id_fitur_tambahan' => $id_fitur_tambahan,
            'elevasi_mdpl' => $elevasi_mdpl,
            'kemiringan_derajat' => $kemiringan_derajat,
            'rawan_erosi' => $rawan_erosi,
            'arah_lereng' => $arah_lereng,
            'catatan' => $catatan,
            'created_by' => $user_id
        ]);
    }

    /*
    =========================
    UPDATE TOPOGRAFI OBSERVASI
    =========================
    */
    public function update($id, $data, $user_id)
    {
        if (!verifyCsrfToken()) {
            die("Invalid CSRF Token");
        }

        $id_tanah = trim($data['id_tanah']);
        $periode_pengecekan = trim($data['periode_pengecekan']);
        $id_lanskap = trim($data['id_lanskap']);
        $id_fitur_tambahan = trim($data['id_fitur_tambahan']);
        $elevasi_mdpl = trim($data['elevasi_mdpl']);
        $kemiringan_derajat = trim($data['kemiringan_derajat']);
        $rawan_erosi = trim($data['rawan_erosi']);
        $arah_lereng = trim($data['arah_lereng']);
        $catatan = trim($data['catatan']);

        return $this->model->update($id, [
            'id_tanah' => $id_tanah,
            'periode_pengecekan' => $periode_pengecekan,
            'id_lanskap' => $id_lanskap,
            'id_fitur_tambahan' => $id_fitur_tambahan,
            'elevasi_mdpl' => $elevasi_mdpl,
            'kemiringan_derajat' => $kemiringan_derajat,
            'rawan_erosi' => $rawan_erosi,
            'arah_lereng' => $arah_lereng,
            'catatan' => $catatan,
            'updated_by' => $user_id
        ]);
    }

    /*
    =========================
    DELETE TOPOGRAFI OBSERVASI (SOFT DELETE)
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

    public function getLanskap()
    {
        return $this->model->getLanskap();
    }

    public function getFiturTambahan()
    {
        return $this->model->getFiturTambahan();
    }
}