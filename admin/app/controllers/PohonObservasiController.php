<?php

require_once __DIR__ . '/../models/PohonObservasi.php';
require_once __DIR__ . '/../core/csrf.php';

class PohonObservasiController
{
    private $model;

    public function __construct($pdo)
    {
        $this->model = new PohonObservasi($pdo);
    }

    /*
    =========================
    LIST POHON OBSERVASI
    =========================
    */
    public function index()
    {
        return $this->model->getAll();
    }

    /*
    =========================
    GET POHON OBSERVASI BY ID
    =========================
    */
    public function find($id)
    {
        return $this->model->findById($id);
    }

    /*
    =========================
    CREATE POHON OBSERVASI
    =========================
    */
    public function store($data, $user_id)
    {
        if (!verifyCsrfToken()) {
            die("Invalid CSRF Token");
        }

        $id_tanah = trim($data['id_tanah']);
        $periode_pengecekan = trim($data['periode_pengecekan']);
        $id_jenis_pohon = trim($data['id_jenis_pohon']);
        $id_fungsi_pohon = trim($data['id_fungsi_pohon']);
        $jumlah_pohon = trim($data['jumlah_pohon']);
        $diameter_rata2_cm = trim($data['diameter_rata2_cm']);
        $tinggi_rata2_m = trim($data['tinggi_rata2_m']);
        $kondisi = trim($data['kondisi']);
        $catatan = trim($data['catatan']);

        return $this->model->create([
            'id_tanah' => $id_tanah,
            'periode_pengecekan' => $periode_pengecekan,
            'id_jenis_pohon' => $id_jenis_pohon,
            'id_fungsi_pohon' => $id_fungsi_pohon,
            'jumlah_pohon' => $jumlah_pohon,
            'diameter_rata2_cm' => $diameter_rata2_cm,
            'tinggi_rata2_m' => $tinggi_rata2_m,
            'kondisi' => $kondisi,
            'catatan' => $catatan,
            'created_by' => $user_id
        ]);
    }

    /*
    =========================
    UPDATE POHON OBSERVASI
    =========================
    */
    public function update($id, $data, $user_id)
    {
        if (!verifyCsrfToken()) {
            die("Invalid CSRF Token");
        }

        $id_tanah = trim($data['id_tanah']);
        $periode_pengecekan = trim($data['periode_pengecekan']);
        $id_jenis_pohon = trim($data['id_jenis_pohon']);
        $id_fungsi_pohon = trim($data['id_fungsi_pohon']);
        $jumlah_pohon = trim($data['jumlah_pohon']);
        $diameter_rata2_cm = trim($data['diameter_rata2_cm']);
        $tinggi_rata2_m = trim($data['tinggi_rata2_m']);
        $kondisi = trim($data['kondisi']);
        $catatan = trim($data['catatan']);

        return $this->model->update($id, [
            'id_tanah' => $id_tanah,
            'periode_pengecekan' => $periode_pengecekan,
            'id_jenis_pohon' => $id_jenis_pohon,
            'id_fungsi_pohon' => $id_fungsi_pohon,
            'jumlah_pohon' => $jumlah_pohon,
            'diameter_rata2_cm' => $diameter_rata2_cm,
            'tinggi_rata2_m' => $tinggi_rata2_m,
            'kondisi' => $kondisi,
            'catatan' => $catatan,
            'updated_by' => $user_id
        ]);
    }

    /*
    =========================
    DELETE POHON OBSERVASI (SOFT DELETE)
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

    public function getJenisPohon()
    {
        return $this->model->getJenisPohon();
    }

    public function getFungsiPohon()
    {
        return $this->model->getFungsiPohon();
    }
}