<?php

require_once __DIR__ . '/../models/MonitoringPenanaman.php';
require_once __DIR__ . '/../core/csrf.php';

class MonitoringPenanamanController
{
    private $model;

    public function __construct($pdo)
    {
        $this->model = new MonitoringPenanaman($pdo);
    }

    /*
    =========================
    LIST MONITORING PENANAMAN
    =========================
    */
    public function index()
    {
        return $this->model->getAll();
    }

    /*
    =========================
    GET MONITORING PENANAMAN BY ID
    =========================
    */
    public function find($id)
    {
        return $this->model->findById($id);
    }

    /*
    =========================
    CREATE MONITORING PENANAMAN
    =========================
    */
    public function store($data, $user_id)
    {
        if (!verifyCsrfToken()) {
            die("Invalid CSRF Token");
        }

        $kode_monitoring = trim($data['kode_monitoring']);
        $id_tanah = trim($data['id_tanah']);
        $id_tipe_penanaman = trim($data['id_tipe_penanaman']);
        $id_progress_status_monitoring = trim($data['id_progress_status_monitoring']);
        $periode_pengecekan = trim($data['periode_pengecekan']);
        $tanggal_tanam = trim($data['tanggal_tanam']);
        $tanggal_monitoring = trim($data['tanggal_monitoring']);
        $luas_tanam_ha = trim($data['luas_tanam_ha']);
        $survival_rate_persen = trim($data['survival_rate_persen']);
        $catatan = trim($data['catatan']);
        $is_active = isset($data['is_active']) ? 1 : 0;

        return $this->model->create([
            'kode_monitoring' => $kode_monitoring,
            'id_tanah' => $id_tanah,
            'id_tipe_penanaman' => $id_tipe_penanaman,
            'id_progress_status_monitoring' => $id_progress_status_monitoring,
            'periode_pengecekan' => $periode_pengecekan,
            'tanggal_tanam' => $tanggal_tanam,
            'tanggal_monitoring' => $tanggal_monitoring,
            'luas_tanam_ha' => $luas_tanam_ha,
            'survival_rate_persen' => $survival_rate_persen,
            'catatan' => $catatan,
            'is_active' => $is_active,
            'created_by' => $user_id
        ]);
    }

    /*
    =========================
    UPDATE MONITORING PENANAMAN
    =========================
    */
    public function update($id, $data, $user_id)
    {
        if (!verifyCsrfToken()) {
            die("Invalid CSRF Token");
        }

        $kode_monitoring = trim($data['kode_monitoring']);
        $id_tanah = trim($data['id_tanah']);
        $id_tipe_penanaman = trim($data['id_tipe_penanaman']);
        $id_progress_status_monitoring = trim($data['id_progress_status_monitoring']);
        $periode_pengecekan = trim($data['periode_pengecekan']);
        $tanggal_tanam = trim($data['tanggal_tanam']);
        $tanggal_monitoring = trim($data['tanggal_monitoring']);
        $luas_tanam_ha = trim($data['luas_tanam_ha']);
        $survival_rate_persen = trim($data['survival_rate_persen']);
        $catatan = trim($data['catatan']);
        $is_active = isset($data['is_active']) ? 1 : 0;

        return $this->model->update($id, [
            'kode_monitoring' => $kode_monitoring,
            'id_progress_status_monitoring' => $id_progress_status_monitoring,
            'id_tanah' => $id_tanah,
            'id_tipe_penanaman' => $id_tipe_penanaman,
            'periode_pengecekan' => $periode_pengecekan,
            'tanggal_tanam' => $tanggal_tanam,
            'tanggal_monitoring' => $tanggal_monitoring,
            'luas_tanam_ha' => $luas_tanam_ha,
            'survival_rate_persen' => $survival_rate_persen,
            'catatan' => $catatan,
            'is_active' => $is_active,
            'updated_by' => $user_id
        ]);
    }

    /*
    =========================
    DELETE MONITORING PENANAMAN (SOFT DELETE)
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

    public function getProgressStatusMonitoring()
    {
        return $this->model->getProgressStatusMonitoring();
    }

    public function getTipePenanaman()
    {
        return $this->model->getTipePenanaman();
    }
}