<?php

require_once __DIR__ . '/../models/PerairanObservasi.php';
require_once __DIR__ . '/../core/csrf.php';

class PerairanObservasiController
{
    private $model;

    public function __construct($pdo)
    {
        $this->model = new PerairanObservasi($pdo);
    }

    /*
    =========================
    LIST PERAIRAN OBSERVASI
    =========================
    */
    public function index()
    {
        return $this->model->getAll();
    }

    /*
    =========================
    GET PERAIRAN OBSERVASI BY ID
    =========================
    */
    public function find($id)
    {
        return $this->model->findById($id);
    }

    /*
    =========================
    CREATE PERAIRAN OBSERVASI
    =========================
    */
    public function store($data, $user_id)
    {
        if (!verifyCsrfToken()) {
            die("Invalid CSRF Token");
        }

        $id_tanah = trim($data['id_tanah']);
        $periode_pengecekan = trim($data['periode_pengecekan']);
        $id_warna_air = trim($data['id_warna_air']);
        $id_jenis_palung = trim($data['id_jenis_palung']);
        $id_kecepatan_aliran = trim($data['id_kecepatan_aliran']);
        $kedalaman_cm = trim($data['kedalaman_cm']);
        $lebar_m = trim($data['lebar_m']);
        $debit_lps = trim($data['debit_lps']);
        $ph = trim($data['ph']);
        $kekeruhan_ntu = trim($data['kekeruhan_ntu']);
        $catatan = trim($data['catatan']);

        return $this->model->create([
            'id_tanah' => $id_tanah,
            'periode_pengecekan' => $periode_pengecekan,
            'id_warna_air' => $id_warna_air,
            'id_jenis_palung' => $id_jenis_palung,
            'id_kecepatan_aliran' => $id_kecepatan_aliran,
            'kedalaman_cm' => $kedalaman_cm,
            'lebar_m' => $lebar_m,
            'debit_lps' => $debit_lps,
            'ph' => $ph,
            'kekeruhan_ntu' => $kekeruhan_ntu,
            'catatan' => $catatan,
            'created_by' => $user_id
        ]);
    }

    /*
    =========================
    UPDATE PERAIRAN OBSERVASI
    =========================
    */
    public function update($id, $data, $user_id)
    {
        if (!verifyCsrfToken()) {
            die("Invalid CSRF Token");
        }

        $id_tanah = trim($data['id_tanah']);
        $periode_pengecekan = trim($data['periode_pengecekan']);
        $id_warna_air = trim($data['id_warna_air']);
        $id_jenis_palung = trim($data['id_jenis_palung']);
        $id_kecepatan_aliran = trim($data['id_kecepatan_aliran']);
        $kedalaman_cm = trim($data['kedalaman_cm']);
        $lebar_m = trim($data['lebar_m']);
        $debit_lps = trim($data['debit_lps']);
        $ph = trim($data['ph']);
        $kekeruhan_ntu = trim($data['kekeruhan_ntu']);
        $catatan = trim($data['catatan']);

        return $this->model->update($id, [
            'id_tanah' => $id_tanah,
            'periode_pengecekan' => $periode_pengecekan,
            'id_warna_air' => $id_warna_air,
            'id_jenis_palung' => $id_jenis_palung,
            'id_kecepatan_aliran' => $id_kecepatan_aliran,
            'kedalaman_cm' => $kedalaman_cm,
            'lebar_m' => $lebar_m,
            'debit_lps' => $debit_lps,
            'ph' => $ph,
            'kekeruhan_ntu' => $kekeruhan_ntu,
            'catatan' => $catatan,
            'updated_by' => $user_id
        ]);
    }

    /*
    =========================
    DELETE PERAIRAN OBSERVASI (SOFT DELETE)
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

    public function getWarnaAir()
    {
        return $this->model->getWarnaAir();
    }

    public function getJenisPalung()
    {
        return $this->model->getJenisPalung();
    }

    public function getKecepatanAliran()
    {
        return $this->model->getKecepatanAliran();
    }
}