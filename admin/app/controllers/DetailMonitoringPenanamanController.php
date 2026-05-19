<?php

require_once __DIR__ . '/../models/DetailMonitoringPenanaman.php';
require_once __DIR__ . '/../core/csrf.php';

class DetailMonitoringPenanamanController
{
    private $model;

    public function __construct($pdo)
    {
        $this->model = new DetailMonitoringPenanaman($pdo);
    }

    /*
    =========================
    LIST DETAIL MONITORING PENANAMAN
    =========================
    */
    public function index()
    {
        return $this->model->getAll();
    }

    /*
    =========================
    GET DETAIL MONITORING PENANAMAN BY ID
    =========================
    */
    public function find($id)
    {
        return $this->model->findById($id);
    }

    /*
    =========================
    CREATE DETAIL MONITORING PENANAMAN
    =========================
    */
    public function store($data, $user_id)
    {
        if (!verifyCsrfToken()) {
            die("Invalid CSRF Token");
        }

        $id_monitoring = trim($data['id_monitoring']);
        $id_bank_benih = trim($data['id_bank_benih']);
        $jumlah_ditanam = trim($data['jumlah_ditanam']);
        $satuan = trim($data['satuan']);
        $jumlah_hidup = trim($data['jumlah_hidup']);
        $jumlah_mati = trim($data['jumlah_mati']);
        $tinggi_rata2_cm = trim($data['tinggi_rata2_cm']);
        $diameter_rata2_cm = trim($data['diameter_rata2_cm']);
        $catatan = trim($data['catatan']);
        $is_active = isset($data['is_active']) ? 1 : 0;

        return $this->model->create([
            'id_monitoring' => $id_monitoring,
            'id_bank_benih' => $id_bank_benih,
            'jumlah_ditanam' => $jumlah_ditanam,
            'satuan' => $satuan,
            'jumlah_hidup' => $jumlah_hidup,
            'jumlah_mati' => $jumlah_mati,
            'tinggi_rata2_cm' => $tinggi_rata2_cm,
            'diameter_rata2_cm' => $diameter_rata2_cm,
            'catatan' => $catatan,
            'is_active' => $is_active,
            'created_by' => $user_id
        ]);
    }

    /*
    =========================
    UPDATE DETAIL MONITORING PENANAMAN
    =========================
    */
    public function update($id, $data, $user_id)
    {
        if (!verifyCsrfToken()) {
            die("Invalid CSRF Token");
        }

        $id_monitoring = trim($data['id_monitoring']);
        $id_bank_benih = trim($data['id_bank_benih']);
        $jumlah_ditanam = trim($data['jumlah_ditanam']);
        $satuan = trim($data['satuan']);
        $jumlah_hidup = trim($data['jumlah_hidup']);
        $jumlah_mati = trim($data['jumlah_mati']);
        $tinggi_rata2_cm = trim($data['tinggi_rata2_cm']);
        $diameter_rata2_cm = trim($data['diameter_rata2_cm']);
        $catatan = trim($data['catatan']);
        $is_active = isset($data['is_active']) ? 1 : 0;

        return $this->model->update($id, [
            'id_monitoring' => $id_monitoring,
            'id_bank_benih' => $id_bank_benih,
            'jumlah_ditanam' => $jumlah_ditanam,
            'satuan' => $satuan,
            'jumlah_hidup' => $jumlah_hidup,
            'jumlah_mati' => $jumlah_mati,
            'tinggi_rata2_cm' => $tinggi_rata2_cm,
            'diameter_rata2_cm' => $diameter_rata2_cm,
            'catatan' => $catatan,
            'is_active' => $is_active,
            'updated_by' => $user_id
        ]);
    }

    /*
    =========================
    DELETE DETAIL MONITORING PENANAMAN (SOFT DELETE)
    =========================
    */
    public function delete($id, $user_id)
    {
        if (!verifyCsrfToken()) {
            die("Invalid CSRF Token");
        }

        return $this->model->softDelete($id, $user_id);
    }

    public function getBankBenih()
    {
        return $this->model->getBankBenih();
    }

    public function getMonitoringPenanaman()
    {
        return $this->model->getMonitoringPenanaman();
    }
}